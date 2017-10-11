<?php

require_once('models/curl.php');
require_once('models/openweathermap.php');
require_once('models/time.php');
require_once('models/client.php');
require_once('models/validators.php');
require_once('models/databaseBuilder.php');
require_once('models/crypt.php');

class IndexController{

    # Connection and Session handler
    private $conn;
    private $db;
    private $session;

    # Weather
    private $curl;
    private $owm;
    private $owmSettings;

    # Notes
    private $notes;
    private $noteSettings;
    private $crypt;

    # Links (bookmarks)
    private $links;
    private $urls;
    private $urlCategories;

    # Additional needed (objects)
    private $client;
    private $validate;
    public static $time;

    # Give me more!
    private $error;
    private $connected;

    public function __construct(Connection $conn=null, Crud $db=null, Session $session){
        # DB and session handling
        $this->conn     = $conn;
        $this->db       = $db;
        $this->session  = $session;

        # Initialize a few objects
        $this->client   = new Client();
        $this->validate = new Validators();
        self::$time     = new Time();

        # Check if we're connected to interwebs
        $this->connected = self::isConnected();

        # Fetch data from DB
        if($this->conn != null){
            #Fetch all links
            $select      = array('*' => 'links');
            $order       = array('category' => 'asc');
            $this->links = $this->db->read($select, null, $order);
            # Extract URLs from $links
            $this->urls = array();
            $this->urlCategories = array();
            self::substractUrls();

            # Fetch all notes from db
            $select      = array('*' => 'notes');
            $order       = array('id' => 'desc');
            $this->notes = $this->db->read($select, null, $order);
            # And notes settings
            $select = array('*' => 'noteSettings');
            $this->noteSettings = $this->db->read($select);
            # Unlock notes if set to
            if($this->noteSettings != null && $this->noteSettings[0]['locked'] == 0)
                self::bulkDecrypt($this->noteSettings[0]['password']);

            # Fetch weather API settings
            $select = array('*' => 'openweathermap');
            $this->owmSettings = $this->db->read($select);
        }

        # Initialize the objects for weather
        if(!empty($this->owmSettings)){
            $this->owm = new OpenWeatherMap($this->owmSettings[0]['apiKey'], $this->owmSettings[0]['city'], new Curl());
            $this->owm->usingWeatherIcons();
            $this->owm->storeJson('media/');
            if($this->isConnected())
                $this->owm->apiCall();
        }

        # Initializer encrypt/decrypt class
        if(!empty($this->noteSettings))
            self::initCrypt();

        # Check for errors
        $this->error = null;
        self::checkErrors();
    }

    /**
     * Returns the date in a nice little way
     * @return string DOW DOM Month and Year
     */
    public function getDate(){
        return date('l jS \of F Y');
    }

    /**
     * Redirects searches to duckduckgo
     * @return      Navigates to ddg!
     */
    public function ddg(){
        if(!empty($_POST['search'])){
            $words       = preg_split("/ /", $_POST['search']);
            $queryString = implode("+", $words);
            $queryString = "?q=".$queryString;
            $url         = "https://duckduckgo.com".$queryString;
            header("location:".$url);
        }else{
            $this->session->set('error', 'Empty search value');
            header("location:".$this->client->getUrl());
        }
    }

    /**
     * Checks if we're connected to the interwebs
     * @return boolean true/false
     */
    # TODO: Thread this shit.
    # FIXME: Doesn't rly work, does it now.. 
    #       Read and reset before checkErrors()
    #       Doesn't head so the error isn't read.
    private function isConnected(){
        #$socket = @fsockopen('www.example.com', 80);
        $socket = true;
        if(!$socket){
            #fclose($socket);
            #$this->session->set('error', 'It appears you have run out of interwebs');
            return false;
        }else{
            fclose($socket);
            return true;
        }
    }

    /**
     * Checks if any errors, sets $error and unsets session
     * @return          Defines the $error variable
     */
    # TODO: Will change to several errors handling
    private function checkErrors(){
        if($this->session->isset('error')){
            $this->error =  $this->session->get('error');
            $this->session->unset('error');
        }
    }

    /**
     * Returns any error message
     * @return string   Error message TODO: Will change
     */
    public function getErrors(){
        return $this->error;
    }

    /**
     * This will initialize a new cryptology class for encryption/decryption
     * This needs to be a function, such that when you create your first pw, 
     * we can encrypt the notes without refreshing the page. Else it's only 
     * run from the constructor.
     * @return [type] [description]
     */
    private function initCrypt(){
        $this->crypt = new Crypto();
    }

    /**
     * This will check if a password has been set for notes
     * @return bool True/False
     */
    public function issetNoteSettings(){
        if($this->noteSettings != null)
            return true;
        return false;
    }

    /**
     * This will check if weather information has been saved in db
     * @return bool True/False
     */
    public function issetWeather(){
        if($this->owm != null)
            return true;
        return false;
    }

    /**
     * This will validate if the credentials.php file has been build/altered
     * @return bool True/False
     */
    public function issetDb(){
        if($this->conn != null)
            return true;
        return false;
    }

    /**
     * Returns the (manipulated) weather value at specific time
     * @param  int|integer $hours Hours into the future (default=0, now)
     * @param  string      $val   What (manipulated) value to return
     * @return string             The value of the request
     */
    public function getWeather(int $hours=0, string $val='description'){
        $weather = $this->owm->getWeather($hours);
        $val = strtolower($val);

        if(empty($weather) || $weather == null)
            return '<i class="wi wi-na"></i>';

        # If requesting icon, return the WI HTML element
        if($val == 'icon')
            return '<i class="wi '.$this->owm->getIcon($weather['icon']).'"></i>';

        # When requesting temp, convert the value from Kelvin
        if($val == 'temp')
            return $this->owm->celsiusConverter($weather['temp']).' <i class="wi wi-celsius"></i>';

        # When requesting windDegree, return the correct WI arrow
        if($val == 'winddegree'){
            $deg = $weather['windDegree'];
            if($deg >= 230 && $deg <= 30)
                $deg = 'wi-direction-up';
            if($deg >= 30)
                $deg = 'wi-direction-up-right';
            if($deg >= 60)
                $deg = 'wi-direction-right';
            if($deg >= 120)
                $deg = 'wi-direction-down-right';
            if($deg >= 150)
                $deg = 'wi-direction-down';
            if($deg >= 210)
                $deg = 'wi-direction-down-left';
            if($deg >= 240)
                $deg = 'wi-direction-left';
            if($deg >= 300)
                $deg = 'wi-direction-up-left';

            return '<i class="wi '.$deg.' windDirection"></i>';
        }

        if($val == 'windspeed')
            return $weather['windSpeed'].' <small>m/s</small>';

        # If none of the above, just return the value of the request
        return $weather[$val];
    }

    /**
     * This will return the current weather settings
     * @return array   The OWN settings from the db 
     */
    public function getWeatherSettings(){
        return $this->owmSettings;
    }

    /**
      * Adds a note to the db
     */
    public function addNote(){
        # Check that value is not empty
        if(empty($_POST['note'])){
            $this->session->set('error', 'Empty note value');
        }else{
            $time = self::$time->timestamp('date');
            # Encrypt note if available
            $note = $_POST['note'];
            if(self::issetNoteSettings())
                $note = $this->crypt->encrypt($note, $this->noteSettings[0]['password']);
            $values = array('dateTime' => $time, 'note' => $note);
            $this->db->create('notes', $values);
        }
        header("location:".$this->client->getUrl()."#notes");
    }

    /**
     * Delete a note from the db
     */
    public function deleteNote(){
        $val = array('id' => $_POST['delNote']);
        $this->db->delete('notes',$val);
        header("location:".$this->client->getUrl()."#notes");
    }

    /**
     * Returns the notes (for the view)
     * @return array Notes and timestamps
     */
    public function getNotes(){
        return $this->notes;
    }

    /**
     * This will return the current note settings
     * @return array   The note settings (locked & hashed pw) from the db
     */
    public function getNoteSettings(){
        return $this->noteSettings;
    }   

    /**
     * Confirm password and update the locked setting in the db
     * @return         Changes locked (db) to 0
     */
    public function unlockNotes(){
        $pw = $_POST['password'];
        $eRR = false;
        # Check password 
        if(!password_verify($pw, $this->noteSettings[0]['password'])){
            $this->session->set('error', 'Incorrect password');
        # Update the unlock value in the database
        }else{
            $val = array('locked' => 0);
            $this->db->update('noteSettings', $val);
        }
        
        header("location:".$this->client->getUrl()."#notes");
    }

    public function lockNotes(){
        $val = array('locked' => 1);
        $this->db->update('noteSettings', $val);
        header("location:".$this->client->getUrl()."#notes");
    }

    /**
     * Adds a link to the database
     */
    public function addLink(){
        $eRR  = null;
        $data = ['link' => $_POST['url'],
                'head' => $_POST['head'],
                'description' => $_POST['desc'],
                'category' => ucfirst($_POST['category'])];

        # Check if empty URL value (only one required)
        if(empty($_POST['url'])){
            $this->session->set("error", "Empty URL value");
            $eRR = true;
        }

        # Check if URL already exists
        if(in_array($data['link'], $this->urls)){
            $this->session->set("error", "URL already exists");
            $eRR = true;
        }

        # Validate that it's truly a URL
        if(!$this->validate->valUrl($data['link'])){
            $this->session->set("error", "Not a valid URL");
            $eRR = true;
        }

        # Create the link in the database
        if($eRR == null)
            $this->db->create('links',$data);

        header("location:".$this->client->getUrl());
    }

    /**
     * Extracts all the URLs, making it easier when we have to validate if already exists
     * This will also pull out all the categories, now that's handling handling the values anyway
     * @return $array   Adds values to the $urls & $urlCategories arrays
     */
    private function substractUrls(){
        if(!empty($this->links)){
            foreach ($this->links as $key) {
                $this->urls[] = $key['link'];
                $this->urlCategories[] = $key['category'];
            }
            $this->urlCategories = array_unique($this->urlCategories);
        }
    }

    /**
     * Returns all the bookmark categories
     * @return array URL (bookmark) categories
     */
    public function getCategories(){
        return $this->urlCategories;
    }

    /**
     * Returns the saved links from the database
     * @return array Values from the db
     */
    public function getLinks(){
        return $this->links;
    }

    /**
     * This recursivly deletes links (bookmarks)
     * @return      Reloads the page
     */
    public function removeLinks(){
        foreach ($_POST as $key) {
            $val = array('id' => $key);
            $this->db->delete('links', $val);
        }
        header("location:".$this->client->getUrl());
    }

    /**
     * FIXME: Create a working db integrity checker
     * Check if a database for the startpage is available
     * @return bool True/False
     */
    public function checkDb(){

        if($this->conn == null)
            return false;

        #$this->dbBuilder = new DatabaseBuilder();

        # TODO: Spaghetti
        // $db  = $this->dbBuilder;
        // $val = $db->issetDb('startpage');
        // $val = $val + $db->issetTable('startpage','notes');
        // $val = $val + $db->issetTable('startpage','links');
        // $val = $val + $db->issetTable('startpage','noteSettings');
        // $val = $val + $db->issetColumn('startpage','noteSettings', 'id');
        // $val = $val + $db->issetColumn('startpage','noteSettings', 'password');
        // $val = $val + $db->issetColumn('startpage','noteSettings', 'locked');
        // $val = $val + $db->issetColumn('startpage','notes', 'id');
        // $val = $val + $db->issetColumn('startpage','notes', 'note');
        // $val = $val + $db->issetColumn('startpage','notes', 'dateTime');
        // $val = $val + $db->issetColumn('startpage','links', 'id');
        // $val = $val + $db->issetColumn('startpage','links', 'link');
        // $val = $val + $db->issetColumn('startpage','links', 'head');
        // $val = $val + $db->issetColumn('startpage','links', 'description');
        // $val = $val + $db->issetColumn('startpage','links', 'category');

        // # TODO: Way worse spaghetti
        // if($val == 14) #+1
        //     return true;
        // else
        //     return false;
    }

    /**
     * This will build the database IF the credentials was a success
     */
    public function addDb(){
        $uname = $_POST['uname'];
        $upass = $_POST['upass'];
        $host  = $_POST['host'];
        $eRR = false;
        # Check for empty values
        if(empty($uname) || empty($upass) || empty($host)){
            $this->session->set('error','All fields are required!');
            $eRR = true;
        }

        # Check if Credentials worked
        $db = new DatabaseBuilder($host,$uname,$upass);
        if(!$db->checkCredentials()){
            $this->session->set('error','The Host/Credentials values didn\'t work');
            $eRR = true;
        }

        if($eRR == false){
            # Build new Credentials file
            self::changeCredentials($uname, $upass, $host);
            # Build the database
            try{
                $db->createDb('startpage');
                $db->usingDb('startpage');
                $db->createTable('notes');
                $db->createTable('links');
                $db->createTable('noteSettings');
                $db->createTable('openweathermap');

                $db->usingTable('links');
                $db->createColumn('link','varchar(126)', false);
                $db->createColumn('head','varchar(126)');
                $db->createColumn('category','varchar(126)');
                $db->createColumn('description','varchar(256)');

                $db->usingTable('notes');
                $db->createColumn('note','longtext',false);
                $db->createColumn('dateTime','varchar(17)',false);

                $db->usingTable('noteSettings');
                $db->createColumn('password','varchar(256)', false);
                $db->createColumn('locked','int(11)',false,1);

                $db->usingTable('openweathermap');
                $db->createColumn('apiKey','varchar(256)', false);
                $db->createColumn('city','int(11)', false);
                $db->throwException(); # TODO: Will be changed
            }catch(Exception $e){
                $this->session->set('error',$e->getMessage());
            }
        }

        header("location:".$this->client->getUrl()."#settings");
    }

    /**
     * This will build a new '/resources/credentials.php' file
     * TODO: Should probably change this
     * @param  string $uname The username
     * @param  string $upass The password
     * @param  string $host  The host to connect
     * @return               Creates/Builds a new credentials.php file
     */
    private function changeCredentials(string $uname, string $upass, string $host){
        # Build a new Credentials file
        $newFile = "<?php\n\n";
        $newFile .= file_get_contents('resources/credentials.php.example',null,null,0,20);
        $newFile .= "\n\n";
        $newFile .= "   private \$host = '$host';\n";
        $newFile .= "   private \$user = '$uname';\n";
        $newFile .= "   private \$pass = '$upass';\n";
        $newFile .= "   private \$db   = 'startpage';\n";
        $newFile .= file_get_contents('resources/credentials.php.example',null,null,117);
        $newFile .= "?>";
        file_put_contents('resources/credentials.php', $newFile);
    }

    # FIXME: isn't working anymore. Might come in handy
    public function dropDb(){
        $this->dbBuilder->dropDb('startpage');
        # TODO: Success message
        header("location:".$this->client->getUrl()."#settings");
    }

    /**
     * Will create/update a password in the note settings table
     */
    public function addPw(){
        $eRR = false;
        $pw1 = $_POST['pw1'];
        $pw2 = $_POST['pw2'];

        # Current password, if there's one already
        if(isset($_POST['currentPw'])){
            $pw = $_POST['currentPw'];
            if(!password_verify($pw, $this->noteSettings[0]['password'])){
                $this->session->set('error','Incorrect password');
                $eRR = true;
            }
        }

        # Check for empty values
        if(empty($pw1) || empty($pw2)){
            $this->session->set('error','Both inputs are required');
            $eRR = true;
        }

        # Check that the two passwords matched
        if($pw1 !== $pw2){
            $this->session->set('error','The two passwords didn\'t match');
            $eRR = true;
        }

        # If no errors, create/update the password
        if($eRR == false){
            # Hash the PW and prepare the SQL
            $pw = password_hash($pw1, PASSWORD_DEFAULT);
            $val = array('password' => $pw);
            # To create or to update, that's the question
            # Here we also Encrypt or Decrypt/Ecrypt
            if(empty($this->noteSettings)){
                $this->db->create('noteSettings', $val);
                self::bulkEncrypt($pw);
            }else{
                $this->db->update('noteSettings',$val);
                self::bulkDecrypt($this->noteSettings[0]['password'], 1);
                self::bulkEncrypt($pw);
            }

            # Update the notes in the DB (YEAAA bitches, them notes are encrypted in the db too!)
            self::bulkUpdateNotes();
        }
        #TODO: Add success message
        header("location:".$this->client->getUrl());
    }

    /**
     * Will bulk encrypt every note in the $notes array
     * @param  string $pw The password (will be hashed beforehand) to encrypt with
     * @return            Updates the $notes array with the new, encrypted, values
     */
    private function bulkEncrypt(string $pw){
        # Make sure a crypt object has been initialized
        if($this->crypt == null)
            self::initCrypt();
        if($this->notes != null && !empty($this->notes)){
            foreach ($this->notes as $key) {
                $arr = array_search($key, $this->notes);
                $this->notes[$arr]['note'] = $this->crypt->encrypt($this->notes[$arr]['note'], $pw);
            }
        }
    }

    /**
     * Will bulk decrypt all the notes in the $notes array
     * @param  string $pw The password (will still be hashed beforehand) to decrypt with
     * @return            Updates $notes with the decryptet notes
     */
    private function bulkDecrypt(string $pw, int $updatePw=0){
        # Make sure a crypt object has been initialized
        if($this->crypt == null)
            self::initCrypt();
        if($this->notes != null && !empty($this->notes) && $this->noteSettings[0]['locked'] == $updatePw){
            foreach ($this->notes as $key) {
                $arr = array_search($key, $this->notes);
                $this->notes[$arr]['note'] = $this->crypt->decrypt($this->notes[$arr]['note'], $pw);
            }
        }
    }

    /**
     * This will update all the notes in the DB, to the newly created hashed string
     */
    private function bulkUpdateNotes(){
        foreach ($this->notes as $key) {
            $val = array('note' => $key['note']);
            $where = array('id' => $key['id']);
            $this->db->update('notes',$val,$where);
        }
    }

    /**
     * Add weather API details to db
     */
    public function addWeather(){
        $api = $_POST['api'];
        $city = $_POST['city'];
        $eRR = false;

        # Check for empty values
        if(empty($api) || empty($city)){
            $this->session->set('error','Both inputs are required');
            $eRR = true;
        }
        # Check if integer city id
        if(!is_numeric($city)){
            $this->session->set('error', 'The city id should be an integer value');
            $eRR = true;
        }
        # If no error, create/update the values
        if($eRR == false){
            $val = array('apiKey' => $api, 'city' => $city);
            if(self::issetWeather()){
                $where = array('id' => 1);
                $this->db->update('openweathermap',$val,$where);
            }else{
                $this->db->create('openweathermap',$val);
            }
        }
        #TODO: Success message 2614481
        header("location:".$this->client->getUrl());
    }
}

?>

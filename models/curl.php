<?php

class Curl {

    private $showError;
    private $json;
    private $data;

    public function __construct(){
        $this->showError = false;
        $this->json = true;
        $this->data = array();
    }

    /**
     * If error should be shown upon request failure
     * @param  bool|boolean $display True/False
     * @return                       Sets the $showError variable
     */
    public function showError(bool $display = false){
        $this->showError = $display;
    }

    /**
     * If the data should be treated as JSON
     * @param  bool|boolean $json True/False
     * @return                    Sets the $json variable
     */
    public function jsonDecode(bool $json = true){
        $this->json = $json;
    }

    /**
     * If you're doing a POST request instead of GET
     * @param  array  $data POST values
     * @return              Sets the $data variable and holds the data for execution
     */
    public function post(array $data){
        $this->data = $data;
    }

    /**
     * Performs the query
     * @param  string $url The URL to request/post to
     * @return string|array The responds
     */
    public function curl(string $url) {
        $init = curl_init($url);
        curl_setopt($init, CURLOPT_RETURNTRANSFER, 1);

        if($this->showError == true)
            curl_setopt($init, CURLOPT_FAILONERROR, true);

        if($this->data != null && !empty($this->data)){
            curl_setopt($init, CURLOPT_POST, 1);
            return;
        }

        $data = curl_exec($init);
        curl_close($init);

        if($this->json == true)
            $data = json_decode($data, true);

        return $data;
    }
}

?>

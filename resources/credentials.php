<?php


class Credentials {

   private $host = '';
   private $user = '';
   private $pass = '';
   private $db   = '';


    /**
     * Returns the db credentials - used once in the singleton
     * @return array Credentials
     */
    public function getDb(){
        return array('host' => $this->host,
                    'user' => $this->user,
                    'pass' => $this->pass,
                    'db' => $this->db);
    }
}
?>
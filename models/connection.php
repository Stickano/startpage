<?php

class Connection extends mysqli {

	public function __construct($host, $username, $password, $database) {
		@parent::__construct($host, $username, $password, $database);
	
        if(mysqli_connect_error())
            throw new Exception('Connection Error: '.mysqli_connect_error());
	}
}

?>

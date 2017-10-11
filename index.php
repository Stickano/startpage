<?php
echo'<!DOCTYPE html>';
echo'<html lang="en">';
echo'<head>';

	# Include the meta/headers 
	require_once('resources/meta.php');
    
echo'</head>';
echo'<body>';

    # This will load the appropriate view
    require_once('views/'.$singleton::$page.'.php');

echo'</body>';
echo'</html>';
?>

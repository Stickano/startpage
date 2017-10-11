<?php
    # Singleton
    require_once('resources/singleton.php');
    $singleton = Singleton::init();

    # Shortcut for some commonly used classes
    $controller = $singleton::$controller;
    $session = $singleton::$session;


    echo'<title>Start</title>';
    echo'<link rel="alternate" href="https://sloa.dk" hreflang="en" />';
    echo'<meta charset="utf-8" />';
    echo'<meta http-equiv="content-language" content="en" />';
    echo'<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
    echo'<meta name="author" content="Henrik Jeppesen" />';
    echo'<meta name="description" content="A custom startpage for your browser." />';
    echo'<meta name="keywords" content="startpage, localhost" />';
    echo'<meta name="robot" content="noindex, nofollow" />';
    echo'<meta name="viewport" content="width=device-width, initial-scale=0.8" />';
    echo'<link rel="shortcut icon" href="media/icon.ico" />';

    # Stylesheets
    # Using Weather-Icons (thx) https://erikflowers.github.io/weather-icons/
    # Using Font-Awesome  (thx) http://fontawesome.io/
    echo'<link href="css/weather-icons.min.css" rel="stylesheet" />';
    echo'<link href="css/font-awesome.min.css" rel="stylesheet" />';
    echo'<link href="css/styles.css" rel="stylesheet" />';
    echo'<link href="css/sass.css" rel="stylesheet" />';

    # JavaScript
    # Using jQuery (thx)
    echo'<script src="js/jquery-3.2.1.min.js"></script>';
?>

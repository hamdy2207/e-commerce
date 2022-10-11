<?php

    // error reporing

    ini_set('display_errors','On');
    error_reporting(E_ALL);

    // Routes
    include 'connect.php';
    $tpl    = "includes/templates/"; // Template Directory
    $lang   = 'includes/languages/'; // language directory
    $func   = 'includes/functions/'; // function directory
    $css    = "layout/css/"; // css directory
    $js     = "layout/js/"; // js directory
    

    
    include $lang . "en.php"; 
    include $func . 'functions.php';
    include $tpl . "header.php";
    include $tpl . "navbar.php";


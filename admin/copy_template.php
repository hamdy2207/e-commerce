<?php

/***
 * =============================
 * === TEMPLATE PAGE
 * =============================
 */

session_start();

$title = "";
if (!empty($_SESSION)) {
    include "init.php";


    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';


    if($action == 'Manage') {

    }elseif ($action == 'Edit') {  
              
    }elseif ($action == "Update") {

    }elseif($action == 'Add') {

    }elseif ($action == 'Insert') {
           
    }elseif ($action == "Delete") {

    }
    

    include $tpl . "footer.php";
} else {
    header("Location: index.php"); 
    exit();   
}
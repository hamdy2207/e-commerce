<?php


$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

if ($action == 'Manage') {
    echo 'welcom to manage';
} 
elseif ($action == 'add') {
    echo 'welcom to add';
}
elseif ($action == 'insert') {
    echo 'welcom to insert';
} 
else {
    echo 'Error the page not found';
}

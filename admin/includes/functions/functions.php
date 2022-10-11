<?php

function getTitle() {
    global $title;

    if (isset($title)) {
        echo $title;
    }else {
        echo lang('DEFAULT');
    }
}
function timeToRead($str){
    $sec = 3;
    $textLeng = strlen($str);
    if ($textLeng > 0) {
        $sec = round($textLeng / 15);
        if ($sec < 3){
            $sec = 3;
        }
    }
    return $sec;
}
function redHome($Msg,$url = null,$state = 'danger') {
    $sec = timeToRead($Msg);

    if ($url == null){
        $url = "index.php";
        $link = 'Home';
    }elseif($url !== "back") {
        $link = $url;
        $url = $url.'.php';
    }else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous';
        }
    }

    echo "<div class='alert alert-$state'>$Msg</div>";
    echo "<div class='alert alert-info'>you are going to $link page in $sec seconds</div>";
    // $link = null;
    header("refresh:$sec;url=$url");

    exit();
}
function checkItem ($select, $from , $value) {
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}

function countItem($item,$table){
    global $con;

    $statCount = $con->prepare("SELECT COUNT($item) FROM $table");
    $statCount->execute();
    return $statCount->fetchColumn();
}

function getLatest($select ,$table , $order, $limit = 5){
    global $con;
    $statLast = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $statLast->execute();
    return $statLast->fetchAll();
}

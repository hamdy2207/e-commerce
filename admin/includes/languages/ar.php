<?php

function lang($phrase) {
    static $trans = array(
        "message" => "مرحبا",
        "admin" => "مدير",
    );
    return $trans[$phrase]; 

}


?>
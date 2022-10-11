<?php

function lang($phrase) {
    static $trans = array(

        // Homepage

        "MESSAGE" => "Welcome",
        "ADMIN" => "Administrator",
        "ITEMS" => "Items",
        "MEMBERS" => "Members",
        "LOGS" => "Logs",
        "DEFAULT" => "Default",


    );
    return $trans[$phrase]; 

}


?>
<?php 

    function logData($logName,$message,$user){
        $date = date('Y/m/d');
        $time = date('H:i');
        RunSqlQuery("
            INSERT INTO $logName (date, time, message, user)
            VALUES('{$date}', '{$time}','{$message}','{$user}');
        ");
    }
    
    function get10MinuteTime(){
        return ((int)date('H'))*6 + round((int)date('i') /10);
    }
?>
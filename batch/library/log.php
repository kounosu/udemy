<?php

function writeLog($fileName, $message){
    $now = date("Y/m/d H:i:s");
    $log ="{$now} {$message}\n";

    $fp = fopen($fileName, "a");
    fwrite($fp,$log);
    fclose($fp);
}

?>
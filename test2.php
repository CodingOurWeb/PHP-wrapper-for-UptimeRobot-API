<?php

require_once('uptimerobot.class.php');

$UR = new UptimeRobot("");        
$UR->setFormat('xml');   
$UR->setApiKey("YOU-API-KEY-HERE");            

try {
    $params = array(
        'name' => 'Google',
        'uri' => 'http://www.google.com/',
        'type' => 1
    );
    $result = $UR->addMonitor($params);
    print_r($result);    
} 
catch (Exception $ex) {
    switch ($ex->getCode()) {
        case 1:
            echo $ex->getMessage();
            break;
        case 2:
            echo "You should try specifying an apiKey for once!";
            break;
        case 3:
            echo "You forgot a required key, you moron!";
            break;
        default:
            echo $ex->getCode(). ": ". $ex->getMessage();        
    }  
}

 
                                 
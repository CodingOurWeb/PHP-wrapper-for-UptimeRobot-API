<?php

require_once('uptimerobot.class.php');

$ur = new UptimeRobot("YOUR-API-KEY-HERE");   // instantiates new UptimeRobot object        
$ur->setFormat('json');                            // sets output format

# getMonitors
$result = $ur->getMonitors(null, 1, 0);            // gets all monitors
echo $result;                                      // outputs result

$monitors = array('115549', '150157');
echo $ur->getMonitors($monitors, 1, 0);            // gets only specified monitors and outputs the returned json string                                           

# addMonitor
$new = array(
    'name' => 'Google',
    'uri' => 'http://www.google.com/',
    'type' => 1
);
echo $ur->newMonitor($new);                        // adds a new monitor

# editMonitor
$edit = array(
    'name' => 'MyMedia (for WordPress)',
);
echo $ur->editMonitor(115549, $edit);              // edits an existing monitor's values

# deleteMonitor
$ur->setFormat('xml');
echo $ur->deleteMonitor(115551);                   // deletes an existing monitor

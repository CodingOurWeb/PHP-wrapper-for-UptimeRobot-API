<?php

class UptimeRobot
{
	private $base_uri = 'http://api.uptimerobot.com/';
	private $apiKey;
	private $format = "json";
	private $json_encap = "jsonUptimeRobotApi()";
    
    /**
    * Public constructor function
    * 
    * @param mixed $apiKey optional
    * @return UptimeRobot
    */
	public function __construct($apiKey = null)
	{
		$this->apiKey = $apiKey;
	}
	
    /**
    * Returns the API key
    * 
    */
    public function getApiKey()
    {
        return $this->apiKey;
    }
    
    /**
    * Sets the API key
    *     
    * @param string $apiKey required
    */
    public function setApiKey($apiKey)
    {
        if (empty($apiKey)) {
            throw new Exception('Value not specified: apiKey', 1);
        }
        $this->apiKey = $apiKey;
    }
    
    /**
    * Gets output format of API calls
    *     
    */
    public function getFormat()
    {
        return $this->format;   
    } 
    
    /**
    * Sets output format of API calls
    *    
    * @param mixed $format required
    */
    public function setFormat($format)
    {
        if (empty($format)) {
            throw new Exception('Value not specified: format', 1);
        }
        $this->format = $format;
    }
    
    /**
    * Returns the result of the API calls
    *     
    * @param mixed $url required
    */
    private function __fetch($url) 
    {
        if (empty($url)) {
            throw new Exception('Value not specified: url', 1);
        }
        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $file_contents = curl_exec ($ch);
        curl_close ($ch);

        switch ($this->format) {
            case "xml":
                return $file_contents;              
            default:
                return substr ($file_contents, strlen($this->json_encap) - 1, strlen ($file_contents) - strlen($this->json_encap));                                   
        }      
        return false;
    }
    
    /**
    * This is a Swiss-Army knife type of a method for getting any information on monitors
    * 
    * @param array $monitors        optional (if not used, will return all monitors in an account. 
    *                               Else, it is possible to define any number of monitors with their IDs like: monitors=15830-32696-83920)
    * @param bool $logs             optional (defines if the logs of each monitor will be returned. Should be set to 1 for getting the logs. Default is 0)
    * @param bool $alertContacts    optional (defines if the notified alert contacts of each notification will be returned. 
    *                               Should be set to 1 for getting them. Default is 0. Requires logs to be set to 1)
    */
	public function getMonitors($monitors = array(), $logs = 0, $alertContacts = 0)
	{   
        if (empty($this->apiKey)) {
            throw new Exception('Property not set: apiKey', 2);    
        }
        $url =  "{$this->base_uri}/getMonitors?apiKey={$this->apiKey}";
		if (!empty($monitors)) $url .= "&monitors=" . implode('-', $monitors);                    
		$url .= "&logs=$logs&alertContacts=$alertContacts&format={$this->format}";
		
		return $this->__fetch($url);
	}
    
    /**
    * New monitors of any type can be created using this method
    * 
    * @param array $params
    * 
    * $params can have the following keys:
    *    name           - required
    *    uri            - required
    *    type           - required
    *    subtype        - optional (required for port monitoring)
    *    port           - optional (required for port monitoring)
    *    keyword_type   - optional (required for keyword monitoring)
    *    keyword_value  - optional (required for keyword monitoring)
    */
    public function addMonitor($params = array())
    {
        if (empty($params['name']) || empty($params['uri']) || empty($params['type'])) {
            throw new Exception('Required key "name", "uri" or "type" not specified', 3);
        } else {
            extract($params);
        }
        if (empty($this->apiKey)) {
            throw new Exception('Property not set: apiKey', 2);    
        }

        $url =  "{$this->base_uri}/addMonitor?apiKey={$this->apiKey}&monitorFriendlyName=$name&monitorURL=$uri&monitorType=$type";
        
        if (isset($subtype)) $url .= "&monitorSubType=$subtype";
        if (isset($port)) $url .= "&monitorPort=$port";
        if (isset($keyword_type)) $url .= "&monitorKeywordType=$keyword_type";
        if (isset($keyword_value)) $url .= '&monitorKeywordValue='. urlencode($keyword_value);
        
        $url .= "&format={$this->format}";

        return $this->__fetch($url);
    }
    
    /**
    * Monitors can be edited using this method.
    *
    * Important: The type of a monitor can not be edited (like changing a HTTP monitor into a Port monitor). 
    * For such cases, deleting the monitor and re-creating a new one is adviced.
    * 
    * @param string $monitorId required
    * @param array $params required
    * 
    * $params can have the following keys:
    *    name           - required
    *    uri            - required
    *    type           - required
    *    subtype        - optional (required for port monitoring)
    *    port           - optional (required for port monitoring)
    *    keyword_type   - optional (required for keyword monitoring)
    *    keyword_value  - optional (required for keyword monitoring)
    */
    public function editMonitor($monitorId, $params = array())
    {
        if (empty($params)) {
            throw new Exception('Value not specified: params', 1);
        } else {
            extract($params);
        }
        if (empty($this->apiKey)) {
            throw new Exception('Property not set: apiKey', 2);    
        }
        
        $url = "{$this->base_uri}/editMonitor?apiKey={$this->apiKey}&monitorID=$monitorId";

        if (isset($name)) $url .= "&monitorFriendlyName=". urlencode($name);
        if (isset($uri)) $url .= "&monitorURL=$uri";
        if (isset($type)) $url .= "&monitorType=$type";
        if (isset($subtype)) $url .= "&monitorSubType=$subtype";
        if (isset($port)) $url .= "&monitorPort=$port";
        if (isset($keyword_type)) $url .= "&monitorKeywordType=$keyword_type";
        if (isset($keyword_value)) $url .= '&monitorKeywordValue='. urlencode($keyword_value);
        
        $url .= "&format={$this->format}";

        return $this->__fetch($url);        
    }
    
    /**
    * Monitors can be deleted using this method.
    * 
    * @param string $monitorId required
    */
    public function deleteMonitor($monitorId)
    {
        if (empty($monitorId)) {
            throw new Exception('Value not specified: monitorId', 1);
        }
        if (empty($this->apiKey)) {
            throw new Exception('Property not set: apiKey', 2);    
        }
        
        $url = "{$this->base_uri}/deleteMonitor?apiKey={$this->apiKey}&monitorID=$monitorId&format={$this->format}";
        
        return $this->__fetch($url);    
    }
}
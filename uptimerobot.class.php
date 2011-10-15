<?php

class UptimeRobot
{
	private $base_uri = "api.uptimerobot.com";
	private $apiKey = "u14573-5b1ff8413da2a8786d1f93af";
	private $format = "json";
	
    /**
    * Public constructor function
    * 
    * @param mixed $apiKey
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
    * @param string $apiKey
    */
    public function setApiKey($apiKey)
    {
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
    * @param mixed $format
    */
    public function setFormat($format)
    {
        $this->format = $format;
    }
    
    /**
    * Returns the result of the API calls
    *     
    * @param mixed $url
    */
    private function __fetch($url) 
    {
        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
    
    /**
    * put your comment there...
    * 
    * @param array $monitors
    * @param bool $logs
    * @param bool $alertContacts
    */
	public function getMonitors($monitors = array(), $logs = 0, $alertContacts = 0)
	{   
        $url = 'http://' . $this->base_uri . '/getMonitors?apiKey=' . $this->apiKey;
		if (!empty($monitors)) $url .= "&monitors=" . implode('-', $monitors);                    
		$url .= "&logs=$logs&alertContacts=$alertContacts&format=" . $this->format;
		
		return $this->__fetch($url);
	}
    
    /**
    * put your comment there...
    * 
    * @param array $params
    */
    public function newMonitor($params = array())
    {
        $url = 'http://' . $this->base_uri . '/addMonitor?apiKey=' . $this->apiKey;
        $url .= '&monitorFriendlyName='. $params['name'];
        $url .= '&monitorURL='. $params['url'];
        $url .= '&monitorType='. $params['type'];
        
        if (!empty($params['subtype'])) $url .= '&monitorSubType='. $params['subtype'];
        if (!empty($params['port'])) $url .= '&monitorPort='. $params['port'];
        if (!empty($params['keyword_type'])) $url .= '&monitorKeywordType='. $params['keyword_type'];
        if (!empty($params['keyword_value'])) $url .= '&monitorKeywordValue='. urlencode($params['keyword_value']);
        
        $url .= '&format='. $this->format;

        return $this->__fetch($url);
    }
    
    /**
    * put your comment there...
    * 
    * @param string $monitorId
    * @param array $params
    */
    public function editMonitor($monitorId, $params = array())
    {
        $url = 'http://' . $this->base_uri . '/editMonitor?apiKey=' . $this->apiKey;
        $url .= '&monitorID='. $monitorId;

        if (!empty($params['name'])) $url .= '&monitorFriendlyName='. urlencode($params['name']);
        if (!empty($params['url'])) $url .= '&monitorURL='. $params['url'];
        if (!empty($params['type'])) $url .= '&monitorType='. $params['type'];
        if (!empty($params['subtype'])) $url .= '&monitorSubType='. $params['subtype'];
        if (!empty($params['port'])) $url .= '&monitorPort='. $params['port'];
        if (!empty($params['keyword_type'])) $url .= '&monitorKeywordType='. $params['keyword_type'];
        if (!empty($params['keyword_value'])) $url .= '&monitorKeywordValue='. urlencode($params['keyword_value']);
        
        $url .= '&format='. $this->format;

        return $this->__fetch($url);        
    }
    
    /**
    * put your comment there...
    * 
    * @param string $monitorId
    */
    public function deleteMonitor($monitorId)
    {
        $url = 'http://' . $this->base_uri . '/deleteMonitor?apiKey=' . $this->apiKey. "&monitorID=$monitorId&format=". $this->format;
        
        return $this->__fetch($url);    
    }
}
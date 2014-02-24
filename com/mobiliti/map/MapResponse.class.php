<?php

/**
 * Description of MapResponse
 * Contém uma respota recebida pelo ui.map
 * @author reinaldo
 */
class MapResponse {

    
    private $imageURL;
    private $legendURL;
    private $extentToHTML;
    private $calloutPoint;
    private $pan_step;
    private $quantil_id;
    private $skip_click;
    
    function __construct($imageURL, $legendURL, $extentToHTML, $calloutPoint,$pan_step,$quantil_id,$skip_click) 
    {
        $this->imageURL = $imageURL;
        $this->legendURL = $legendURL;
        $this->extentToHTML = $extentToHTML;
        $this->calloutPoint = $calloutPoint;
        $this->pan_step = $pan_step;
        $this->quantil_id = $quantil_id;
        $this->skip_click = $skip_click;
    }

    public function getImageURL() {
        return $this->imageURL;
    }

    public function getLegendURL() {
        return $this->legendURL;
    }

    public function getExtentToHTML() {
        return $this->extentToHTML;
    }
    
    public function getCalloutPoint() {
        return $this->calloutPoint;
    }
    
    public function getPanStep() {
        return $this->pan_step;
    }
    
    public function getJSON(){
        
        $obj = array();
        $obj["imageURL"] = $this->getImageURL();
        $obj["legendURL"] =  $this->getLegendURL();
        $obj["extentToHTML"] =  $this->getExtentToHTML();
        $obj["calloutPoint"] =  $this->getCalloutPoint();
        $obj["panStep"] =  $this->getPanStep();
        $obj["quantilID"] =  $this->quantil_id;
        $obj["skip_click"] =  $this->skip_click;
        
        return json_encode($obj);
    }
   
}

?>

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Color
 *
 * @author Reinaldo Aparecido
 */
class Color {
    
    
    private $red;
    private $green;
    private $blue;
    
    function __construct($r, $g, $b) {

        $this->red = $r;
        $this->green = $g;
        $this->blue = $b;
    }
    
    
    public function getRed() {
        return $this->red;
    }

    public function setRed($red) {
        $this->red = $red;
    }

    public function getGreen() {
        return $this->green;
    }

    public function setGreen($green) {
        $this->green = $green;
    }

    public function getBlue() {
        return $this->blue;
    }

    public function setBlue($blue) {
        $this->blue = $blue;
    }

    
    public function setHexColor($hex) {

        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
           $r = hexdec(substr($hex,0,1).substr($hex,0,1));
           $g = hexdec(substr($hex,1,1).substr($hex,1,1));
           $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
           $r = hexdec(substr($hex,0,2));
           $g = hexdec(substr($hex,2,2));
           $b = hexdec(substr($hex,4,2));
        }
        
        $this->setRed($r);
        $this->setGreen($g);
        $this->setBlue($b);
       
    }
    
    public function getHexaColor() 
    {
        $hexr = dechex($this->getRed());
        $hexg = dechex($this->getGreen());
        $hexb = dechex($this->getBlue());
  
        if(strlen($hexr)==1)$hexr = "0".$hexr;
        if(strlen($hexg)==1)$hexg = "0".$hexg;
        if(strlen($hexb)==1)$hexb = "0".$hexb;
        
        return "#".$hexr.$hexg.$hexb;  
    }

}

?>

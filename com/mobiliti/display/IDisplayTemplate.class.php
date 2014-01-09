<?php

    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     *
     * @author Lorran
     */
    interface IDisplayTemplate {
        
        public function setTitle($title);

        public function setSubtitle($sub);

        public function setInfo($info);

        public function setText($text);

        public function setCanvasContent($obj);

        public function getHTML();
        
    }

?>

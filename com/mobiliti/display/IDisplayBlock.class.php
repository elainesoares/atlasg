<?php

    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     *
     * @author Lorran
     */
    interface IDisplayBlock {

        public function setTemplateUI($ITemp);

        public function draw();

        public function setSpatiality($esp);

        public function setIndicator($ind);

        public function setYear($year);

        public function setManyIndicators($year);
        
    }

?>

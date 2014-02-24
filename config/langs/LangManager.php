<?php
    class LangManager
    {
        private $lang_var = null;

        function LangManager($lang)
        {
            $this->lang_var = $lang;
        }

        public function getString($id)
        {
            return (isset($this->lang_var[$id])) ? $this->lang_var[$id] : ""; 
        }

    }
?>

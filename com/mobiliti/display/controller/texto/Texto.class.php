<?php

 require_once MOBILITI_PACKAGE."display/Block.class.php";
/**
 * Description of Texto
 *
 * @author Lorran
 */
    
class Texto extends Block {
    
    public $pTexto;
    private $templateHolder;
    public function __construct($texto,$template = null) {
        $this->templateHolder = $template;
        
        if($template != null)
            parent::__construct($template);
        
        $this->pTexto = $texto;
    }

    public function __destruct() {
        
    }
    
    public function replaceTags($variavel, $valor){
        $this->pTexto = str_replace("[$variavel]", $valor, $this->pTexto);
    }
    
    public function getTexto(){
        return $this->pTexto;
    }
    
    public function drawBlock(){
        if($this->templateHolder != null)
            parent::draw();
    }
}

?>

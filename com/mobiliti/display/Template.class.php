<?php

    require_once MOBILITI_PACKAGE."display/IDisplayTemplate.class.php";

/**
    * Created on 25/03/2013
    *
    * Classe principal para controlar os templates
    * 
    * @abstract IDisplayTemplate
    * @author Valter Lorran (valter@mobilidade-ti.com.br)
    * @version 1.0.0
    *
    */
class Template implements IDisplayTemplate {
    /**
     * Aqui vai ficar salvo o html do template
     * @var string
     */
    private $html;
    /**
     * Classe principal para controlar os templates
     * @param int $id Id do template a ser usado
     */
    public function __construct($id) {
        $idTemplate = str_pad($id, 2, "0", STR_PAD_LEFT);
        if(file_exists(MOBILITI_PACKAGE."display/templates/Template_$idTemplate.html"))
            $this->html = file_get_contents(MOBILITI_PACKAGE."display/templates/Template_$idTemplate.html");
        elseif(file_exists(MOBILITI_PACKAGE."display/templates/Template_$idTemplate.php"))
            $this->html = file_get_contents(MOBILITI_PACKAGE."display/templates/Template_$idTemplate.php");
        else
            trigger_error ("O arquivo Template_$idTemplate.php/html não existe.",E_USER_ERROR);
    }
    
    /**
     * Retorna o html no estado atual
     * @return string
     */
    public function getHTML() {
        return $this->html;
    }

    /**
     * Seta o canvas do bloco, caso tenha
     * 
     * @param object $obj Este objeto deve conter o método insert, que vai ser usado
     * aqui para ser inserido no local.
     */
    public function setCanvasContent($obj) {
        $this->Replacer("canvasContent", $obj);
    }
    
    /**
     * Seta a info do template
     * @param string $info informação sobre o bloco, vai depender do template
     */
    public function setInfo($info) {
        $this->Replacer("info", $info);
    }

    /**
     * Seta o subtítulo do template
     * @param string $sub
     */
    public function setSubtitle($sub) {
        $this->Replacer("subtitulo", $sub);
    }

    /**
     * Seta o texto do template
     * @param string $text
     */
    public function setText($text) {
        $this->Replacer("text", $text);
    }

    /**
     * Seta o título do template
     * @param string $title
     */
    public function setTitle($title) {
        $this->Replacer("titulo", $title);
    }
    
    /**
     * Seta os blocos dentro do bloco
     * @param array(blocks) $blocks
     */
    public function setBlocks($blocks){
        $ArrayDraw = array();
        foreach($blocks as $bloco){
            $ArrayDraw[] = $bloco->getHtml();
        }
        
        $this->Replacer("blocks", implode("",$ArrayDraw));
    }
    
    public function set($key,$valor){
        $this->Replacer($key, $valor);
    }
    
    /**
     * Da um replace nas variáveis no html
     * @param type $nomes
     * @param type $content
     */
    private function Replacer($nome,$content = ""){
        $this->html = str_replace("[$nome]", $content, $this->html);
    }
    
    
    

}

?>

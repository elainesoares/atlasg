<?php
    set_include_path(get_include_path() . PATH_SEPARATOR . "com/mobiliti/");
    require_once MOBILITI_PACKAGE."display/IDisplayBlock.class.php";
    require_once MOBILITI_PACKAGE."display/Template.class.php";
    
    /**
        * Created on 25/03/2013
        *
        * Classe principal para criar blocos que será usado principalmente no perfil
        * 
        * @abstract IDisplayBlock
        * @author Valter Lorran (valter@mobilidade-ti.com.br)
        * @version 1.0.0
        *
        */
    
    class Block implements IDisplayBlock
    {
        private $template;
        
        private $blocks = array();
        
        /*DEBUG*/
        public $idHolder;
        /*DEBUG*/
        
        /**
         * Classe principal para criar blocos que será usado principalmente no perfil
         * 
         * @param int $idTemplate Id do template, olha na pasta templates
         * @param int $idMunicipio Id do município
         * @param int $indicador Id do indicador
         * @param int $ano Id do ano
         * @param int $espacialidade Id da espacialidade, favor checkar constantes na classe Consulta.class.php
         */
        public function __construct($idTemplate) {
            $this->template = new Template($idTemplate);
            $this->idHolder = $idTemplate;
            $this->getData();
        }
        
        /**
         * Este método vai fazer a consulta e inserir os dados no template
         * 
         */
        private function getData(){
            //==================================================================
            // Consulta tudo
            //==================================================================
            
        }
        
        public function setData($variavel,$valor){
            $this->template->set($variavel, $valor);
        }
        
        /**
         * Método para desenhar o bloco.
         * @Importante O bloco será desenhado onde você chamar este método.
         */
        public function draw() {
            echo $this->template->getHTML();
        }

        public function setIndicator($ind) {
            
        }

        public function setManyIndicators($year) {
            
        }

        public function setSpatiality($esp) {
            
        }

        public function setTemplateUI($ITemp) {
            
        }

        public function setYear($year) {
            
        }
        
        /**
         * Adiciona um bloco á uma lista de blocos que poderá ser inseridos no bloco
         * com o método insertBlocks().
         * @depends insertBlocks()
         */
        public function addBlock($Bloco){
            $this->blocks[] = $Bloco;
        }
        
        /**
         * Insere os blocos da lista no html principal.
         */
        public function insertBlocks()
        {
            $this->template->setBlocks($this->blocks);
        }
        
        /**
         * Retorna o html atual do objeto template.
         * @return string
         */
        public function getHtml() {
            return $this->template->getHTML();
        }
    }
?>

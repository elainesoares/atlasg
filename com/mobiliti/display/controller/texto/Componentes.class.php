<?php

    /**
     * Description of Componetes
     *
     * @author Lorran
     */
    class Componentes extends Texto{
        
        private $texto = "Em [2010], o Índice de Desenvolvimento Humano Municipal (IDHM) de [municipio] é [idh].
            <br>O município está situado na faixa de Desenvolvimento Humano <Faixa_DH> (IDHM entre [idh_A] e [idh_B]).  
            <br>Entre 2000 e 2010, a dimensão que mais contribuiu para este crescimento foi a <1DimensaoMaisAumentou0010>,
            seguida pela <2DimensaoMaisAumentou0010> e pela <3DimensaoMaisAumentou0010>. 
            <br>Entre 1991 e 2000 a dimensão que mais contribuiu para este crescimento foi a <1DimensaoMaisAumentou9100>,
            seguida pela <2DimensaoMaisAumentou9100> e pela <3DimensaoMaisAumentou9100>.";

        public function __construct($template) {
            parent::__construct(&$this->texto, $template);
            
            $this->setData("titulo", "DESENVOLVIMENTO HUMANO");
            $this->setData("subtitulo", "COMPONENTES");
            $this->setData("info", "");
            $this->setData("canvasContent", "");
            
            $idhm = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM"); //IDHM            
        
            parent::replaceTags("municipio", TextBuilder::$nomeMunicipio);
            parent::replaceTags("2010", $idhm[2]["label_ano_referencia"]);
            parent::replaceTags("idh", $idhm[2]["valor"]);
            
           /*$classificacao = TextBuilder::getSituacaoIDH($idh);
            parent::replaceTags("situacao", $classificacao["media"]);
            parent::replaceTags("idh_A", $classificacao["range_A"]);
            parent::replaceTags("idh_B", $classificacao["range_B"]);*/
            $this->setData("text", $this->pTexto);
        }

        public function getTexto(){
            return parent::getTexto();
        }
        
        public function drawBlock(){
            return parent::drawBlock();
        }
        
        public function __destruct() {
            
        }
    }

?>

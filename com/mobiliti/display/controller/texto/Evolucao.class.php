<?php

    /**
     * Description of Evolucao
     *
     * @author Lorran
     */
    class Evolucao{

        private $texto = "O IDHM passou de [idh_a] em [ano_a] para [idh_b] em 
                          [ano_b] - uma taxa de crescimento de []%. O hiato de 
                          desenvolvimento humano, ou seja, a distância entre o 
                          IDHM do município e o limite máximo do índice, que é 
                          1, foi [crescimento] em 
                          [reducao_hiato]%.";
        
        private $mTexto = "Entre 2000 e 2010, a dimensão que mais contribuiu para 
                        este crescimento foi a [dimA0], com [dimAP0]%, seguida 
                        pela [dimA1], com [dimAP1]% e pela [dimA2], com 
                        [dimAP2]%. Entre 1991 e 2000 a dimensão que mais 
                        contribuiu para este crescimento foi a [dimB0], 
                        com [dimBP0]%, seguida pela [dimB1], com [dimBP1]% e pela 
                        [dimB2], com [dimBP2]%.";
        
        private $texto1;
        private $texto2;
        private $block;
        private $bd;
        
        public function __construct($municipio, $idmunicipio) {
            $this->texto1 = new Texto($this->texto);
            $this->texto2 = new Texto($this->texto);
            $this->texto3 = new Texto($this->mTexto);
            
            $bd = new bd();
            $this->block = new Block(2);
            
            $this->block->setData("subtitulo", "EVOLUÇÃO");
            $this->block->setData("info", "Entre 2000 e 2010");
            $this->block->setData("info2", "Entre 1991 e 2000");
            
            $SQL = "SELECT valor as v, label_ano_referencia as lb, sigla as s
                    FROM valor_variavel_mun
                    INNER JOIN ano_referencia ON (ano_referencia.id = fk_ano_referencia)
                    INNER JOIN variavel ON (variavel.id = fk_variavel)
                    WHERE (fk_municipio = $idmunicipio) and 
                          (ano_referencia.id IN (1,2,3)) and
                          (
                               (variavel.sigla ILIKE 'idhm') or
                               (variavel.sigla ILIKE 'idhrm') or
                               (variavel.sigla ILIKE 'idhlm') or
                               (variavel.sigla ILIKE 'idhm educação')
                          )

                   order by ano_referencia.id
                    ";
            $result = $bd->ExecutarSQL($SQL);
            $holder = array();
            foreach($result as $key=>$val){
                if(strpos($val['s'], ' '))
                    $val['s'] = 'idhem';
                $holder[strtolower($val['s']."_".$val['lb'])] = $val;
                unset($result[$key]);
            }
            
            //=========================Texto=1================================//
            
            $this->texto1->replaceTags("idh_a",$holder['idhm_2000']["v"]);
            $this->texto1->replaceTags("idh_b",$holder['idhm_2010']["v"]);
            
            $this->texto1->replaceTags("ano_a",$holder['idhm_2000']["lb"]);
            $this->texto1->replaceTags("ano_b",$holder['idhm_2010']["lb"]);
            
            if($holder['idhm_2000']["v"] < $holder['idhm_2010']["v"])
                $this->texto1->replaceTags("crescimento","aumentado");
            else if($holder['idhm_2000']["v"] > $holder['idhm_2010']["v"])
                $this->texto1->replaceTags("crescimento","reduzido");
            else
                $this->texto1->replaceTags("crescimento","mantido");
            
            //=========================Texto=2================================//
            
            $this->texto2->replaceTags("idh_a",$holder['idhm_1991']["v"]);
            $this->texto2->replaceTags("idh_b",$holder['idhm_2000']["v"]);
            
            $this->texto2->replaceTags("ano_a",$holder['idhm_1991']["lb"]);
            $this->texto2->replaceTags("ano_b",$holder['idhm_2000']["lb"]);
            
            if($holder['idhm_1991']["v"] < $holder['idhm_2000']["v"])
                $this->texto2->replaceTags("crescimento","aumentado");
            else if($holder['idhm_1991']["v"] > $holder['idhm_2000']["v"])
                $this->texto2->replaceTags("crescimento","reduzido");
            else
                $this->texto2->replaceTags("crescimento","mantido");
            
            //=========================Texto=3================================//
            
            $sortA = array();
            $sortB = array();
            
            $sortA['idhlm'] = $holder['idhlm_2010']['v'] - $holder['idhlm_2000']['v'];
            $sortA['idhrm'] = $holder['idhrm_2010']['v'] - $holder['idhrm_2000']['v'];
            $sortA['idhem'] = $holder['idhem_2010']['v'] - $holder['idhem_2000']['v'];
            
            $sortB['idhlm'] = $holder['idhlm_2000']['v'] - $holder['idhlm_1991']['v'];
            $sortB['idhrm'] = $holder['idhrm_2000']['v'] - $holder['idhrm_1991']['v'];
            $sortB['idhem'] = $holder['idhem_2000']['v'] - $holder['idhem_1991']['v'];
            
            arsort($sortA);
            arsort($sortB);
            $counter = 0;
            
            foreach($sortA as $key=>$val){
                switch ($key) {
                    case 'idhem':
                        $this->texto3->replaceTags("dimA{$counter}","Educação");
                        $n = (($holder['idhem_2010']['v']-$holder['idhem_2000']['v'])*100)/$holder['idhem_2000']['v'];
                        $p = number_format($n,2);
                        $this->texto3->replaceTags("dimAP{$counter}",$p);
                        break;
                    case 'idhlm':
                        $this->texto3->replaceTags("dimA{$counter}","Longevidade");
                        $n = (($holder['idhlm_2010']['v']-$holder['idhlm_2000']['v'])*100)/$holder['idhlm_2000']['v'];
                        $p = number_format($n,2);
                        $this->texto3->replaceTags("dimAP{$counter}",$p);
                        break;
                    case 'idhrm':
                        $this->texto3->replaceTags("dimA{$counter}","Renda");
                        $n = (($holder['idhrm_2010']['v']-$holder['idhrm_2000']['v'])*100)/$holder['idhrm_2000']['v'];
                        $p = number_format($n,2);
                        $this->texto3->replaceTags("dimAP{$counter}",$p);
                        break;
                }
                $counter++;
            }
            
            $counter = 0;
            foreach($sortB as $key=>$val){
                switch ($key) {
                    case 'idhem':
                        $this->texto3->replaceTags("dimB{$counter}","Educação");
                        $n = (($holder['idhem_2000']['v']-$holder['idhem_1991']['v'])*100)/$holder['idhem_1991']['v'];
                        $p = number_format($n,2);
                        $this->texto3->replaceTags("dimBP{$counter}",$p);
                        break;
                    case 'idhlm':
                        $this->texto3->replaceTags("dimB{$counter}","Longevidade");
                        $n = (($holder['idhlm_2000']['v']-$holder['idhlm_1991']['v'])*100)/$holder['idhlm_1991']['v'];
                        $p = number_format($n,2);
                        $this->texto3->replaceTags("dimBP{$counter}",$p);
                        break;
                    case 'idhrm':
                        $this->texto3->replaceTags("dimB{$counter}","Renda");
                        $n = (($holder['idhrm_2000']['v']-$holder['idhrm_1991']['v'])*100)/$holder['idhrm_1991']['v'];
                        $p = number_format($n,2);
                        $this->texto3->replaceTags("dimBP{$counter}",$p);
                        break;
                }
                $counter++;
            }
            
            $this->block->setData("text", $this->texto1->getTexto());
            $this->block->setData("text2", $this->texto2->getTexto());
            $this->block->setData("text3", $this->texto3->getTexto());
        }
        
        public function getTexto(){
            
        }
        
        public function __destruct() {
            
        }
        
        public function draw(){
            $this->block->draw();
        }
    }

?>

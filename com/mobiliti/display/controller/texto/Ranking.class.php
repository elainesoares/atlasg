<?php
    /**
     * Description of Ranking
     *
     * @author Lorran
     */
    class Ranking extends Texto {

        private $texto = "Em 2010, em relação aos [qt_municipios] municípios do 
                          Brasil, [municipio] ocupa a [rankA]ª 
                          posição, sendo que [rankAnt] municípios 
                          [%municipios_IDHM]% estão em situação melhor e 
                          [rankPos] municípios [%municipios_IDHM]% 
                          estão em situação igual ou pior. Em relação aos 
                          [munes] outros municípios de 
                          [estado], [municipio] ocupa a 
                          [rankEs]ª posição, sendo que 
                          [rankEsM] municípios estão em situação
                          melhor e [rankEsP] municípios estão em
                          situação pior ou igual.  ";

        public function __construct($municipio, $idmunicipio) {
            parent::__construct(&$this->texto,8);
            
            $bd = new bd();
            $qunt = $bd->ExecutarSQL("SELECT count(id) as a FROM municipio","ranking 1");
            $this->setData("subtitulo", "RANKING");
            parent::replaceTags("qt_municipios", number_format($qunt[0]['a'],0,'','.'));
            parent::replaceTags("municipio", $municipio);
            
            $SQL = "SELECT s.*
                    FROM (
                            SELECT fk_municipio,
                                 ROW_NUMBER() OVER(ORDER BY valor DESC) AS position
                            FROM valor_variavel_mun
                            INNER JOIN variavel ON (fk_variavel = variavel.id)
			    WHERE fk_ano_referencia = 3 and variavel.sigla ILIKE 'idhm'
                        ) s
                   WHERE s.fk_municipio = $idmunicipio";
            
            $arr = $bd->ExecutarSQL($SQL,"ranking 2");
            parent::replaceTags("rankA", $arr[0]["position"]);
            parent::replaceTags("rankAnt", ($arr[0]["position"] - 1));
            parent::replaceTags("rankPos", ($qunt[0]['a'] - $arr[0]["position"] - 1));
            
            $SQL = "SELECT s.*
                    FROM (
                            SELECT fk_municipio, estado.nome as nome, estado.id,
                                 ROW_NUMBER() OVER(ORDER BY valor DESC) AS position
                            FROM valor_variavel_mun
                            INNER JOIN variavel ON (fk_variavel = variavel.id)
                            INNER JOIN municipio ON (fk_municipio = municipio.id)
                            INNER JOIN estado ON (municipio.fk_estado = estado.id)
			    WHERE fk_ano_referencia = 3 and variavel.sigla ILIKE 'idhm' and
			    estado.id = (select fk_estado from municipio where id = $idmunicipio)
				 
                        ) s
                   WHERE s.fk_municipio = $idmunicipio";
            $arr2 = $bd->ExecutarSQL($SQL,"ranking 3");
            
            $quntEs = $bd->ExecutarSQL("SELECT count(id) as a FROM municipio where fk_estado = {$arr2[0]['id']}","ranking 4");
            parent::replaceTags("munes", $quntEs[0]["a"]);
            parent::replaceTags("estado", mb_convert_case($arr2[0]["nome"],MB_CASE_TITLE, "UTF-8"));
            parent::replaceTags("rankEs", $arr2[0]["position"]);
            parent::replaceTags("rankEsM", ($arr2[0]["position"] - 1));
            parent::replaceTags("rankEsP", ($quntEs[0]['a'] - $arr2[0]["position"] - 1));
            
            
            $this->setData("text", $this->pTexto);
            
        }

        public function getTexto(){
            return parent::getTexto();
        }

        public function __destruct() {

        }
    }

?>

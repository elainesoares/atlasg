<?php

    /**
     * Description of Incremento
     *
     * @author Lorran
     */

    class Incremento extends Texto {
        
        private $texto = "Entre 1991 e 2010, [municipio] teve um incremento no 
                          seu IDHM de [inc]%, [compA] da média 
                          de incremento nacional e [compB] da média de incremento estadual.";

        public function __construct($municipio, $idmunicipio) {
            $bd = new bd();
            parent::__construct(&$this->texto,7);

            $this->setData("subtitulo", "INCREMENTO");
            parent::replaceTags("municipio", $municipio);
            
            $SQL = "SELECT valor 
                    FROM valor_variavel_mun 
                    INNER JOIN variavel on (fk_variavel = variavel.id)
                    WHERE 
                    (fk_municipio = $idmunicipio) and (sigla ILIKE 'idhm') 
                    and (fk_ano_referencia IN (1,3))
                    order by fk_ano_referencia
                    ";
            
            $arr = $bd->ExecutarSQL($SQL,"INCREMENTO");
            $n = (($arr[1]["valor"]-$arr[0]["valor"])*100)/$arr[0]["valor"];
            $p = number_format($n,2);
            parent::replaceTags("inc", $p);
            
            $SQL = "SELECT valor FROM valor_variavel_estado 
                    INNER JOIN variavel on (fk_variavel = variavel.id) 
                    INNER JOIN municipio on (municipio.fk_estado = valor_variavel_estado.fk_estado) 
                    WHERE (municipio.id = $idmunicipio) and (sigla ILIKE 'idhm') 
                    and (fk_ano_referencia IN (1,3)) 
                    order by fk_ano_referencia
                    ";
            $arrB = $bd->ExecutarSQL($SQL,"INCREMENTO2");
            $nB = (($arrB[1]["valor"]-$arrB[0]["valor"])*100)/$arrB[0]["valor"];
            $pB = number_format($nB,2);
            if($pB > $p)
                parent::replaceTags("compB", 'abaixo');
            elseif($pB < $p)
                parent::replaceTags("compB", 'acima');
            else
                parent::replaceTags("compB", 'igual');
            
            $this->setData("text", $this->pTexto);
        }

        public function getTexto(){
            return parent::getTexto();
        }

        public function __destruct() {

        }
    }

?>

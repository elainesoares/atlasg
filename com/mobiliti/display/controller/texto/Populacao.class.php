<?php

    /**
     * Description of Populacao
     *
     * @author Lorran
     */
    class Populacao extends Texto {

        private $texto = "Entre 2000 e 2010, a população de [municipio] teve uma 
                        taxa média de crescimento anual de [cres]%. 
                        Na década anterior, de 1991 a 2000, a taxa média de 
                        crescimento anual foi de [tx_cres_pop_0010]%. No Estado, 
                        estas taxas foram de [tx_cresc_pop_estado_0010]% e 
                        [tx_cresc_pop_estado_9100]%, respectivamente. No país, 
                        foram de [tx_cresc_pop_pais_0010]% e [tx_cresc_pop_pais_9100]%, 
                        respectivamente.";

        public function __construct($municipio, $idmunicipio) {
            parent::__construct(&$this->texto,8);
            $bd = new bd();
            $SQL = "SELECT valor as v
                    FROM valor_variavel_mun 
                    INNER JOIN variavel ON (variavel.id = fk_variavel)
                    WHERE sigla ILIKE 'PESOTOT' and (fk_ano_referencia IN (2,3))
                    ORDER BY fk_ano_referencia
                    ";
            $arr = $bd->ExecutarSQL($SQL,"populacao 1");
            $porcentagem   = number_format(((($arr[1]['v']-$arr[0]['v'])/$arr[0]['v'])*100)/(10),2,',','.');
            $this->setData("subtitulo", "POPULAÇÃO");
            parent::replaceTags("municipio", $municipio);
            parent::replaceTags("cres", $porcentagem);
            $this->setData("text", $this->pTexto);
        }

        public function getTexto(){
            return parent::getTexto();
        }
        
        public function __destruct() {
            
        }
    }

?>

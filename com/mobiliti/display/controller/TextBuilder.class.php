<?php

require_once BASE_ROOT . 'config/config_path.php';
require_once MOBILITI_PACKAGE . 'display/controller/texto/Texto.class.php';
require_once MOBILITI_PACKAGE . 'consulta/bd.class.php';
require_once MOBILITI_PACKAGE . 'display/controller/chart/chartsPerfil.php';

//define("PATH_DIRETORIO", $path_dir);
/**
 * Description of GerenateTexts
 *
 * @author Lorran, 
 *         Andre Castro (versão 2)
 */

class TextBuilder {

    static public $idMunicipio = 0;
    static public $nomeMunicipio = "";
    static public $ufMunicipio = "";
    static public $bd;    
    static public $print;
    static public $munTratado = "";
    
    //Variável de configuração da Fonte de Dados do perfil
    static public $fontePerfil = "Fonte: Pnud, Ipea e FJP"; //@#Menos template 5

    static public function generateIDH_componente($block_componente) {

        if (TextBuilder::$print){ 
            $block_componente->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_componente->setData("quebra", "");
        
        $block_componente->setData("fonte", TextBuilder::$fontePerfil);
        $block_componente->setData("titulo", "IDHM");
        $block_componente->setData("canvasContent", getChartDesenvolvimentoHumano(TextBuilder::$idMunicipio));
               
        
        $block_componente->setData("subtitulo", "Componentes");
        $block_componente->setData("info", "");

        $idhm = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM"); //IDHM
        //TODO: Otimizar sqls trazendo os dados todos de uma só vez.
        $idhm_r = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM_R"); //IDHM_R
        $idhm_l = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM_L"); //IDHM_L
        $idhm_e = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM_E"); //IDHM_E

//        $str = "<p align='justify'>Em [2010], o Índice de Desenvolvimento Humano Municipal (IDHM) de [municipio] é [idh].
//            O município está situado na faixa de Desenvolvimento Humano [Faixa_DH]. 
//            
//            Entre 2000 e 2010, a dimensão que mais cresceu em termos absolutos foi [1DimensaoMaisAumentou2000a2010],
//            e o que menos cresceu foi [1DimensaoMenosAumentou2000a2010]. 
//                        
//            Entre 1991 e 2000, a dimensão que mais cresceu em termos absolutos foi [1DimensaoMaisAumentou1991a2000],
//            e o que menos cresceu foi [1DimensaoMenosAumentou1991a2000].</p>";
        
        $str = "<p align='justify'>O Índice de Desenvolvimento Humano Municipal (IDHM) de [municipio] é [idh], em [2010].
            O município está situado na faixa de Desenvolvimento Humano [Faixa_DH].
            
            Entre 2000 e 2010, a dimensão que mais cresceu em termos absolutos foi [Dimensao2000a2010].
                        
            Entre 1991 e 2000, a dimensão que mais cresceu em termos absolutos foi [Dimensao1991a2000].</p>";

        $texto = new Texto($str);
        $texto->replaceTags("2010", $idhm[2]["label_ano_referencia"]);
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto->replaceTags("idh", number_format($idhm[2]["valor"], 3, ",", "."));
        
        $texto->replaceTags("Faixa_DH", TextBuilder::getSituacaoIDH($idhm[2]["valor"]));

          $texto->replaceTags("Dimensao2000a2010", TextBuilder::getDimensao($idhm_r, $idhm_l, $idhm_e, array("faixa0010" => true, "faixa9100" => false)));
          $texto->replaceTags("Dimensao1991a2000", TextBuilder::getDimensao($idhm_r, $idhm_l, $idhm_e, array("faixa0010" => false, "faixa9100" => true)));

//        $texto->replaceTags("1DimensaoMaisAumentou2000a2010", TextBuilder::getDimensao($idhm_r, $idhm_l, $idhm_e, array("faixa0010" => true, "faixa9100" => false, "maisAumentou" => true, "menosAumentou" => false)));
//
//        $texto->replaceTags("1DimensaoMenosAumentou2000a2010", TextBuilder::getDimensao($idhm_r, $idhm_l, $idhm_e, array("faixa0010" => true, "faixa9100" => false, "maisAumentou" => false, "menosAumentou" => true)));
//
//        $texto->replaceTags("1DimensaoMaisAumentou1991a2000", TextBuilder::getDimensao($idhm_r, $idhm_l, $idhm_e, array("faixa0010" => false, "faixa9100" => true, "maisAumentou" => true, "menosAumentou" => false)));
//
//        $texto->replaceTags("1DimensaoMenosAumentou1991a2000", TextBuilder::getDimensao($idhm_r, $idhm_l, $idhm_e, array("faixa0010" => false, "faixa9100" => true, "maisAumentou" => false, "menosAumentou" => true)));

        $block_componente->setData("text", $texto->getTexto());
    }

    static function getSituacaoIDH($idhm) {

        if ($idhm >= 0.000 && $idhm <= 0.499)
            return "Muito Baixo (IDHM entre 0 e 0,499)";
        else if ($idhm >= 0.500 && $idhm <= 0.599)
            return "Baixo (IDHM entre 0,5 e 0,599)";
        else if ($idhm >= 0.600 && $idhm <= 0.699)
            return "Médio (IDHM entre 0,6 e 0,699)";
        else if ($idhm >= 0.700 && $idhm <= 0.799)
            return "Alto (IDHM entre 0,700 e 0,799)";
        else if ($idhm >= 0.800 && $idhm <= 1.000)
            return "Muito Alto (IDHM entre 0,8 e 1)";
    }

//    static function getDimensao($idhm_r, $idhm_l, $idhm_e, $confDimensao) {
//
//        if ($confDimensao["faixa0010"]) {//2010 - 2000
//            $idhm_r0010 = array("dimensao" => "Renda", "valor" => str_replace(".",",",number_format(($idhm_r[2]["valor"] - $idhm_r[1]["valor"]), 2)));
//            $idhm_l0010 = array("dimensao" => "Longevidade", "valor" => str_replace(".",",",number_format(($idhm_l[2]["valor"] - $idhm_l[1]["valor"]), 2)));
//            $idhm_e0010 = array("dimensao" => "Educação", "valor" => str_replace(".",",",number_format(($idhm_e[2]["valor"] - $idhm_e[1]["valor"]), 2)));
//        } else if ($confDimensao["faixa9100"]) {//1991 - 2000        
//            $idhm_r0010 = array("dimensao" => "Renda", "valor" => str_replace(".",",",number_format(($idhm_r[1]["valor"] - $idhm_r[0]["valor"]), 2)));
//            $idhm_l0010 = array("dimensao" => "Longevidade", "valor" => str_replace(".",",",number_format(($idhm_l[1]["valor"] - $idhm_l[0]["valor"]), 2)));
//            $idhm_e0010 = array("dimensao" => "Educação", "valor" => str_replace(".",",",number_format(($idhm_e[1]["valor"] - $idhm_e[0]["valor"]), 2)));
//        }
//
//        if ($confDimensao["maisAumentou"])
//            $arr = max($idhm_r0010, $idhm_l0010, $idhm_e0010);
//        else if ($confDimensao["menosAumentou"])
//            $arr = min($idhm_r0010, $idhm_l0010, $idhm_e0010);
//
//        return $arr["dimensao"] . " (com crescimento de " . $arr["valor"] . ")";
//    }
    
    static function getDimensao($idhm_r, $idhm_l, $idhm_e, $confDimensao) {
        
        if ($confDimensao["faixa0010"]) {//2010 - 2000
            $idhm_r0010 = array("valor" => number_format(($idhm_r[2]["valor"] - $idhm_r[1]["valor"]), 3, ",", "."), "dimensao" => "Renda");
            $idhm_l0010 = array("valor" => number_format(($idhm_l[2]["valor"] - $idhm_l[1]["valor"]), 3, ",", "."), "dimensao" => "Longevidade");
            $idhm_e0010 = array("valor" => number_format(($idhm_e[2]["valor"] - $idhm_e[1]["valor"]), 3, ",", "."), "dimensao" => "Educação");
        } else if ($confDimensao["faixa9100"]) {//1991 - 2000        
            $idhm_r0010 = array("valor" => number_format(($idhm_r[1]["valor"] - $idhm_r[0]["valor"]), 3, ",", "."), "dimensao" => "Renda");
            $idhm_l0010 = array("valor" => number_format(($idhm_l[1]["valor"] - $idhm_l[0]["valor"]), 3, ",", "."), "dimensao" => "Longevidade");
            $idhm_e0010 = array("valor" => number_format(($idhm_e[1]["valor"] - $idhm_e[0]["valor"]), 3, ",", "."), "dimensao" => "Educação");
        }
  
        $max = max($idhm_r0010, $idhm_l0010, $idhm_e0010);        
        $min = min($idhm_r0010, $idhm_l0010, $idhm_e0010);
        
        if ($max["dimensao"] === "Renda"){
            if ($min["dimensao"] === "Educação"){
                return $max["dimensao"] . " (com crescimento de " . $max["valor"] . ")" . ", seguida por Longevidade e por " . $min["dimensao"];
            }else if ($min["dimensao"] === "Longevidade"){
                return $max["dimensao"] . " (com crescimento de " . $max["valor"] . ")" . ", seguida por Educação e por " . $min["dimensao"];
            }
        }                
        else if ($max["dimensao"] === "Educação"){
            if ($min["dimensao"] === "Renda"){
                return $max["dimensao"] . " (com crescimento de " . $max["valor"] . ")" . ", seguida por Longevidade e por " . $min["dimensao"];
            }else if ($min["dimensao"] === "Longevidade"){
                return $max["dimensao"] . " (com crescimento de " . $max["valor"] . ")" . ", seguida por Renda e por " . $min["dimensao"];
            }
        }
        else if ($max["dimensao"] === "Longevidade"){
            if ($min["dimensao"] === "Educação"){
                return $max["dimensao"] . " (com crescimento de " . $max["valor"] . ")" . ", seguida por Renda e por " . $min["dimensao"];
            }else if ($min["dimensao"] === "Renda"){
                return $max["dimensao"] . " (com crescimento de " . $max["valor"] . ")" . ", seguida por Educação e por " . $min["dimensao"];
            }
        }        
    }

    static function getVariaveis_table($municipio, $variavel) {

        /*
          $SQL = "SELECT nomecurto, valor
          FROM valor_variavel_mun INNER JOIN variavel
          ON fk_variavel = id
          WHERE (fk_municipio = $municipio) AND
          (fk_ano_referencia = 1 OR fk_ano_referencia = 2 OR fk_ano_referencia = 3)
          AND (";

          $num = count($arrVariaveis);
          $count = 1;
          foreach ($arrVariaveis as &$value) {
          if ($count != $num)
          $SQL .= "sigla LIKE '$value' OR ";
          else
          $SQL .= "sigla LIKE '$value');";

          $count++;
          }

          echo $SQL;
         */

        $SQL = "SELECT label_ano_referencia, nomecurto,  nome_perfil, valor
                FROM valor_variavel_mun INNER JOIN variavel
                ON fk_variavel = variavel.id
                INNER JOIN ano_referencia
                ON ano_referencia.id = fk_ano_referencia
                WHERE fk_municipio = $municipio and sigla like '$variavel'
                 ORDER BY label_ano_referencia";

        //echo $SQL . "<br><br>"; 
        return TextBuilder::$bd->ExecutarSQL($SQL, "getVariaveis_table");
    }
    
   static function my_number_format($number,$decimais=2,$separador_decimal=",",$separador_milhar="."){ 
        // Número vazio? 
        if(trim($number)=="") return $number; 
        // se for um double precisamos garantir que não será covertido em 
        // notação científica e que valores terminados em .90 tenha o zero removido 
        if(is_float($number) || is_double($number)){ 
        $number = sprintf("%.{$decimais}f",$number); 
        } 
        // Convertendo para uma string numérica 
        $number = preg_replace('#\D#','',$number); 

        // separando a parte decimal 
        $decimal=''; 
        if($decimais>0){ 
        $number = sprintf("%.{$decimais}f",($number / pow(10,$decimais))); 
        if(preg_match("#^(\d+)\D(\d{{$decimais}})$#",$number,$matches)){ 
        $decimal=$separador_decimal . $matches[2]; 
        $number=$matches[1]; 
        } 
        } 
        // formatando a parte inteira 
        if($separador_milhar!=''){ 
        $number = implode($separador_milhar,array_reverse(array_map('strrev',str_split(strrev($number),3)))); 
        } 
        return $number . $decimal; 
    } 

    static function getVariaveis_Uf($municipio, $variavel) {

        $SQL = "SELECT label_ano_referencia, nomecurto, valor 
                FROM valor_variavel_estado
                INNER JOIN municipio
                ON municipio.fk_estado = valor_variavel_estado.fk_estado
                INNER JOIN variavel
                ON fk_variavel = variavel.id
                INNER JOIN ano_referencia
                ON ano_referencia.id = fk_ano_referencia
                WHERE municipio.id = $municipio and sigla like '$variavel'
                 ORDER BY label_ano_referencia;";

        return TextBuilder::$bd->ExecutarSQL($SQL, "getVariaveis_Uf");
    }

    static function getVariaveis_Brasil($municipio, $variavel) {

        $SQL = "SELECT label_ano_referencia, nomecurto, valor 
                FROM valor_variavel_pais
                INNER JOIN pais
                ON pais.id = valor_variavel_pais.fk_pais
                INNER JOIN estado
                ON pais.id = estado.fk_pais
                INNER JOIN municipio
                ON municipio.fk_estado = estado.id
                INNER JOIN variavel
                ON fk_variavel = variavel.id
                INNER JOIN ano_referencia
                ON ano_referencia.id = fk_ano_referencia
                WHERE municipio.id = $municipio and variavel.sigla like '$variavel' ORDER BY label_ano_referencia;";

        return TextBuilder::$bd->ExecutarSQL($SQL, "getVariaveis_Brasil");
    }

    //TODO: Otmizar código das tabelas, criando uma classe Builder específica
    static public function generateIDH_table_componente($block_table_componente) {

        $variaveis = array();
        //TODO: Otimizar sqls trazendo os dados todos de uma só vez.
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM_E")); //IDHM Educação
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND18M")); //"% de 18 ou mais anos com fundamental completo"
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FREQ5A6")); //% de 5 a 6 anos na escola
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND11A13")); //% de 12 a 14 anos nos anos finais do fundamental
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND15A17")); //% de 16 a 18 anos com fundamental completo
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_MED18A20")); //% de 19 a 21 anos com médio completo
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM_L")); //IDHM Longevidade
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "ESPVIDA")); //Esperança de vida ao nascer
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM_R")); //IDHM Renda
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "RDPC")); //Renda per capita

        $block_table_componente->setData("titulo", "IDHM e componentes");
        $block_table_componente->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_componente->setData("caption", "Índice de Desenvolvimento Humano Municipal e seus componentes");
        $block_table_componente->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_componente->setData("ano1", $variaveis[0][0]["label_ano_referencia"]); // Ano 1 (1991)
        $block_table_componente->setData("ano2", $variaveis[0][1]["label_ano_referencia"]); // Ano 2 (2000)
        $block_table_componente->setData("ano3", $variaveis[0][2]["label_ano_referencia"]); // Ano 3 (2010)

        for ($i = 1; $i <= count($variaveis); $i++) {            
            
            if ($i === 1 || $i === 7 || $i === 9 ){
                $block_table_componente->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_componente->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 3, ",", ".")); // Ano 1 (1991)
                $block_table_componente->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 3, ",", ".")); // Ano 2 (2000)
                $block_table_componente->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 3, ",", ".")); // Ano 3 (2010)
            }else{
                $block_table_componente->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_componente->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_componente->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_componente->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
            
        }
    }

    static public function generateIDH_evolucao($block_evolucao) {

        $idhm = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM"); //IDHM
        $idhm_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "IDHM"); //IDHM do Estado
        $idhm_brasil = TextBuilder::getVariaveis_Brasil(TextBuilder::$idMunicipio, "IDHM"); //IDHM do Brasil

        $block_evolucao->setData("subtitulo", "Evolução");
        $block_evolucao->setData("fonte", TextBuilder::$fontePerfil);
        //----------------------------------------------------------------------------------------
        //Evolução entre os anos de 2000 e 2010
        $block_evolucao->setData("info1", "");
        $str1 = "<b>Entre 2000 e 2010</b><br>O IDHM passou de [IDHM2000] em 2000 para [IDHM2010] em 2010 - uma taxa de crescimento de [Tx_crescimento_0010]%.
            O hiato de desenvolvimento humano, ou seja, a distância entre o IDHM do município e o limite máximo do índice, que é 1,
            foi [reduzido_aumentado] em [reducao_hiato_0010]% entre 2000 e 2010.<br><br>";
        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        $texto1->replaceTags("IDHM2000", number_format($idhm[1]["valor"], 3, ",", "."));
        $texto1->replaceTags("IDHM2010", number_format($idhm[2]["valor"], 3, ",", "."));
        $texto1->replaceTags("Tx_crescimento_0010", number_format((($idhm[2]["valor"] / $idhm[1]["valor"]) - 1) * 100, 2, ",", "."));

        //Cálculo do HIATO
        $reducao_hiato_0010 = (($idhm[2]["valor"] - $idhm[1]["valor"]) / (1 - $idhm[1]["valor"])) * 100;
        $texto1->replaceTags("reducao_hiato_0010", number_format($reducao_hiato_0010, 2, ",", "."));

        if ($reducao_hiato_0010 >= 0)
            $texto1->replaceTags("reduzido_aumentado", "reduzido");
        else
            $texto1->replaceTags("reduzido_aumentado", "aumentado");

        $block_evolucao->setData("text1", $texto1->getTexto());

        //----------------------------------------------------------------------------------------
        //Evolução entre os anos de 1991 e 2000
        $block_evolucao->setData("info2", "");
        $str2 = "<b>Entre 1991 e 2000</b><br>O IDHM passou de [IDHM1991] em 1991 para [IDHM2000] em 2000 - uma taxa de crescimento de [Tx_crescimento_9100]%.
             O hiato de desenvolvimento humano, ou seja, a distância entre o IDHM do município e o limite máximo do índice, que é 1,
             foi [reduzido_aumentado] em [reducao_hiato_9100]% entre 1991 e 2000.<br><br>";
        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        $texto2->replaceTags("IDHM1991", number_format($idhm[0]["valor"], 3, ",", "."));
        $texto2->replaceTags("IDHM2000", number_format($idhm[1]["valor"], 3, ",", "."));
        $texto2->replaceTags("Tx_crescimento_9100", number_format((($idhm[1]["valor"] / $idhm[0]["valor"]) - 1) * 100, 2, ",", "."));

        //Cálculo do HIATO
        $reducao_hiato_9100 = (($idhm[1]["valor"] - $idhm[0]["valor"]) / (1 - $idhm[0]["valor"])) * 100;
        $texto2->replaceTags("reducao_hiato_9100", number_format($reducao_hiato_9100, 2, ",", "."));

        if ($reducao_hiato_9100 >= 0)
            $texto2->replaceTags("reduzido_aumentado", "reduzido");
        else
            $texto2->replaceTags("reduzido_aumentado", "aumentado");

        $block_evolucao->setData("text2", $texto2->getTexto());

        //----------------------------------------------------------------------------------------
        //Evolução entre os anos de 1991 e 2010
        $block_evolucao->setData("info3", "");
        $str3 = "<b>Entre 1991 e 2010</b><br>[municipio] teve um incremento no seu IDHM de [Tx_crescimento_9110]% nas últimas duas décadas,
            [abaixo_acima] média de crescimento nacional ([tx_cresc_Brasil9110]%) e [abaixo_acima_uf] média de crescimento estadual ([tx_cresc_Estado9110]%).
            O hiato de desenvolvimento humano, ou seja, a distância entre o IDHM do município e o limite máximo do índice, que é 1,
            foi [reduzido_aumentado] em [reducao_hiato_9110]% entre 1991 e 2010.";
        $texto3 = new Texto($str3);
        $texto3->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        //Taxa de Crescimento
        $tx_cresc_9110 = (($idhm[2]["valor"] / $idhm[0]["valor"]) - 1) * 100;
        $texto3->replaceTags("Tx_crescimento_9110", number_format($tx_cresc_9110, 2, ",", "."));

        //----------------------------------------
        //Taxa de Crescimento em relação ao BRASIL
        $tx_cresc_Brasil9110 = (($idhm_brasil[2]["valor"] / $idhm_brasil[0]["valor"]) - 1) * 100;
        
        //TODO: Tirar o igual da comparação (feito pq a base não é oficial e há replicações
        if ($tx_cresc_Brasil9110 < $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Brasil9110", number_format(abs($tx_cresc_Brasil9110), 2, ",", "."));
            $texto3->replaceTags("abaixo_acima", "acima da");
        }else if ($tx_cresc_Brasil9110 == $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Brasil9110", number_format(abs($tx_cresc_Brasil9110), 2, ",", "."));
            $texto3->replaceTags("abaixo_acima", "igual à");
        }else if ($tx_cresc_Brasil9110 > $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Brasil9110", number_format(abs($tx_cresc_Brasil9110), 2, ",", "."));
            $texto3->replaceTags("abaixo_acima", "abaixo da");
        }            

        //----------------------------------------
        //Taxa de Crescimento em relação ao ESTADO
        $tx_cresc_Estado9110 = (($idhm_uf[2]["valor"] / $idhm_uf[0]["valor"]) - 1) * 100;
        
        //TODO: Tirar o igual da comparação (feito pq a base não é oficial e há replicações
        if ($tx_cresc_Estado9110 < $tx_cresc_9110){
            $texto3->replaceTags("abaixo_acima_uf", "acima da");
            $texto3->replaceTags("tx_cresc_Estado9110", number_format(abs($tx_cresc_Estado9110), 2, ",", "."));
        }  
        else if ($tx_cresc_Estado9110 == $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Estado9110", number_format(abs($tx_cresc_Estado9110), 2, ",", "."));
            $texto3->replaceTags("abaixo_acima_uf", "igual à");
        }
        else if ($tx_cresc_Estado9110 > $tx_cresc_9110){
            $texto3->replaceTags("abaixo_acima_uf", "abaixo da");
            $texto3->replaceTags("tx_cresc_Estado9110", number_format(abs($tx_cresc_Estado9110), 2, ",", "."));
        }
        
        //Cálculo do HIATO
        $reducao_hiato_9110 = (($idhm[2]["valor"] - $idhm[0]["valor"]) / (1 - $idhm[0]["valor"])) * 100;
        $texto3->replaceTags("reducao_hiato_9110", number_format($reducao_hiato_9110, 2, ",", "."));

        if ($reducao_hiato_9110 >= 0)
            $texto3->replaceTags("reduzido_aumentado", "reduzido");
        else
            $texto3->replaceTags("reduzido_aumentado", "aumentado");

        $block_evolucao->setData("text3", $texto3->getTexto());
        
        if (TextBuilder::$print){ 
            $block_evolucao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_evolucao->setData("quebra", "");

        $block_evolucao->setData("canvasContent", getChartEvolucao(TextBuilder::$idMunicipio));
    }
    
    static public function generateIDH_table_taxa_hiato($block_table_taxa_hiato) {
        
        $idhm = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM"); //IDHM

        $block_table_taxa_hiato->setData("titulo", "");
        $block_table_taxa_hiato->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_taxa_hiato->setData("ano1", "Taxa de Crescimento"); // Taxa de Crescimento
        $block_table_taxa_hiato->setData("ano2", "Hiato de Desenvolvimento"); // Hiato de Desenvolvimento

        $taxa9100 = number_format((($idhm[1]["valor"] / $idhm[0]["valor"]) - 1) * 100, 2, ",", ".");
        $taxa0010 = number_format((($idhm[2]["valor"] / $idhm[1]["valor"]) - 1) * 100, 2, ",", ".");        
        $taxa9110 = number_format((($idhm[2]["valor"] / $idhm[0]["valor"]) - 1) * 100, 2, ",", ".");

        $reducao_hiato_0010 = number_format((($idhm[2]["valor"] - $idhm[1]["valor"]) / (1 - $idhm[1]["valor"])) * 100, 2, ",", ".");
        $reducao_hiato_9100 = number_format((($idhm[1]["valor"] - $idhm[0]["valor"]) / (1 - $idhm[0]["valor"])) * 100, 2, ",", ".");
        $reducao_hiato_9110 = number_format((($idhm[2]["valor"] - $idhm[0]["valor"]) / (1 - $idhm[0]["valor"])) * 100, 2, ",", ".");

        //TODO: FAZER O MAIS E MENOS DA TAXA E HIATO UTILIZANDO IMAGENS
        
            $block_table_taxa_hiato->setData("v1", "Entre 1991 e 2000");
            
            if($taxa9100 >= 0)
                $block_table_taxa_hiato->setData("v1_a2", "+ " . $taxa9100 . "%"); // Ano 1 (1991)
            else 
                $block_table_taxa_hiato->setData("v1_a2", "- " . $taxa9100 . "%"); // Ano 1 (1991)
            
            if($reducao_hiato_9100 >= 0)
                $block_table_taxa_hiato->setData("v1_a3", "+ " . $reducao_hiato_9100 . "%"); // Ano 2 (2000)
            else
                $block_table_taxa_hiato->setData("v1_a3", "- " . $reducao_hiato_9100 . "%"); // Ano 2 (2000)
                
            $block_table_taxa_hiato->setData("v2", "Entre 2000 e 2010");
            
            if($taxa0010 >= 0)
                $block_table_taxa_hiato->setData("v2_a2", "+ " . $taxa0010 . "%"); // Ano 1 (1991)
            else 
                $block_table_taxa_hiato->setData("v2_a2", "- " . $taxa0010 . "%"); // Ano 1 (1991)
            
            if($reducao_hiato_0010 >= 0)
                $block_table_taxa_hiato->setData("v2_a3", "+ " . $reducao_hiato_0010 . "%"); // Ano 2 (2000)
            else
                $block_table_taxa_hiato->setData("v2_a3", "- " . $reducao_hiato_0010 . "%"); // Ano 2 (2000)
            
            $block_table_taxa_hiato->setData("v3", "Entre 1991 e 2010");
            
            if($taxa9110 >= 0)
                $block_table_taxa_hiato->setData("v3_a2", "+ " . $taxa9110 . "%"); // Ano 1 (1991)
            else 
                $block_table_taxa_hiato->setData("v3_a2", "- " . $taxa9110 . "%"); // Ano 1 (1991)
            
            if($reducao_hiato_9110 >= 0)
                $block_table_taxa_hiato->setData("v3_a3", "+ " . $reducao_hiato_9110 . "%"); // Ano 2 (2000)
            else
                $block_table_taxa_hiato->setData("v3_a3", "- " . $reducao_hiato_9110 . "%"); // Ano 2 (2000)

    }

    static function getRanking() {

        $SQL = "SELECT ROW_NUMBER() OVER(ORDER BY valor DESC, municipio.nome ASC), municipio.id, municipio.nome, nomecurto, valor 
        FROM valor_variavel_mun INNER JOIN variavel
        ON fk_variavel = variavel.id
        INNER JOIN municipio
        ON municipio.id = valor_variavel_mun.fk_municipio
        WHERE sigla like 'IDHM' AND fk_ano_referencia = 3;";

        return TextBuilder::$bd->ExecutarSQL($SQL, "getRanking");
    }

    static function getUf($municipio) {

        $SQL = "SELECT estado.id, estado.nome
            FROM municipio
            INNER JOIN estado
            ON estado.id = municipio.fk_estado
            WHERE municipio.id= $municipio;";

        return TextBuilder::$bd->ExecutarSQL($SQL, "getRanking");
    }

    static function getRankingUf($estado) {

        $SQL = "SELECT ROW_NUMBER() OVER(ORDER BY valor DESC, municipio.nome ASC), municipio.id, municipio.nome, estado.id as id_uf, estado.nome, nomecurto, valor 
            FROM valor_variavel_mun INNER JOIN variavel
            ON fk_variavel = variavel.id
            INNER JOIN municipio
            ON municipio.id = valor_variavel_mun.fk_municipio
            INNER JOIN estado
            ON estado.id = municipio.fk_estado
            WHERE sigla like 'IDHM' AND fk_ano_referencia = 3 AND estado.id= $estado;";

        return TextBuilder::$bd->ExecutarSQL($SQL, "getRanking");
    }

    static function getIDHM_Igual($idhm) {

        $SQL = "SELECT municipio.id, municipio.nome, nomecurto, valor 
            FROM valor_variavel_mun INNER JOIN variavel
            ON fk_variavel = variavel.id
            INNER JOIN municipio
            ON municipio.id = valor_variavel_mun.fk_municipio
            WHERE valor = $idhm AND fk_ano_referencia = 3
            ORDER BY  municipio.nome ASC";

        return TextBuilder::$bd->ExecutarSQL($SQL, "getIDHM_Igual");
    }

    static function getIDHM_Igual_Uf($idhm, $estado) {

        $SQL = "SELECT municipio.id, municipio.nome, nomecurto, valor 
            FROM valor_variavel_mun INNER JOIN variavel
            ON fk_variavel = variavel.id
            INNER JOIN municipio
            ON municipio.id = valor_variavel_mun.fk_municipio
            INNER JOIN estado
            ON estado.id = municipio.fk_estado
            WHERE valor = $idhm AND fk_ano_referencia = 3 AND estado.id = $estado 
            ORDER BY  municipio.nome ASC";

        return TextBuilder::$bd->ExecutarSQL($SQL, "getIDHM_Igual");
    }

    static public function generateIDH_ranking($block_ranking) {

        $ranking = TextBuilder::getRanking(); //IDHM
        $uf = TextBuilder::getUf(TextBuilder::$idMunicipio); //IDHM
        $ranking_uf = TextBuilder::getRankingUf($uf[0]["id"]); //IDHM

        $block_ranking->setData("subtitulo", "Ranking");
        $block_ranking->setData("info", "");
        
        $str = "[municipio] ocupa a [ranking_municipio_IDHM]ª posição, em 2010, em relação aos 5.565 municípios do Brasil, 
            sendo que [municipios_melhor_IDHM] ([municipios_melhor_IDHM_p]%) municípios estão em situação melhor e [municipios_pior_IDHM] ([municipios_pior_IDHM_p]%) municípios
            estão em situação igual ou pior.";
        
        if (TextBuilder::$idMunicipio != 735){
            $str = $str . " Em relação aos [numero_municipios_estado] outros municípios de [estado_municipio], [municipio] ocupa a
            [ranking_estados_IDHM]ª posição, sendo que [municipios_melhor_IDHM_estado] ([municipios_melhor_IDHM_p_estado]%) municípios estão em situação melhor e [municipios_pior_IDHM_estado] ([municipios_pior_IDHM_p_estado]%) municípios
            estão em situação pior ou igual.";
        }   
         
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        //RANKING POR MUN
        $count = 1;
        
        $sqlinha = "select posicao_idh,posicao_e_idh from rank where fk_municipio = ".TextBuilder::$idMunicipio;
        $var = TextBuilder::$bd->ExecutarSQL($sqlinha, "sqlinha_teste");
        $l_posicao_idh = $var[0]['posicao_idh'];
        $l_posicao_e_idh = $var[0]['posicao_e_idh'];
        $l_posicao_idh_menores = $var[0]['posicao_idh'] - 1;
        $l_posicao_e_idh_menores = $var[0]['posicao_e_idh'] - 1;
        
        
        
        foreach ($ranking as &$value) {
            if ($value["id"] == TextBuilder::$idMunicipio) {
                $texto->replaceTags("ranking_municipio_IDHM", $var[0]['posicao_idh']);
                $idhm_igual = TextBuilder::getIDHM_Igual($value["valor"]); //IDHM

                $count_iguais = 1;
                foreach ($idhm_igual as &$value2) {
                    if ($value2["id"] == TextBuilder::$idMunicipio) {
                        $texto->replaceTags("municipios_melhor_IDHM", $var[0]['posicao_idh']-1);
                        $texto->replaceTags("municipios_melhor_IDHM_p", number_format((($var[0]['posicao_idh']-1) / count($ranking)) * 100, 2, ",", "."));
                        $texto->replaceTags("municipios_pior_IDHM", TextBuilder::my_number_format(count($ranking) - ($var[0]['posicao_idh']-1),0));
                        $texto->replaceTags("municipios_pior_IDHM_p", number_format(((count($ranking) - ($var[0]['posicao_idh']-1)) / count($ranking)) * 100, 2, ",", "."));
                        break;
                    }
                    $count_iguais++;
                }
                break;
            }
            $count++;
        }

        //RANKING POR ESTADO
        $texto->replaceTags("estado_municipio", $uf[0]["nome"]);
        $texto->replaceTags("numero_municipios_estado", count($ranking_uf));

        $count = 1;
        foreach ($ranking_uf as &$value) {
            if ($value["id"] == TextBuilder::$idMunicipio) {

                $texto->replaceTags("ranking_estados_IDHM", $var[0]['posicao_e_idh']);
                $idhm_igual = TextBuilder::getIDHM_Igual_Uf($value["valor"], $uf[0]["id"]); //IDHM

                $count_iguais = 1;
                foreach ($idhm_igual as &$value2) {
                    if ($value2["id"] == TextBuilder::$idMunicipio) {
                        $texto->replaceTags("municipios_melhor_IDHM_estado", $var[0]['posicao_e_idh']-1);
                        $texto->replaceTags("municipios_melhor_IDHM_p_estado", number_format((($var[0]['posicao_e_idh']-1) / count($ranking_uf)) * 100, 2, ",", "."));
                        $texto->replaceTags("municipios_pior_IDHM_estado", TextBuilder::my_number_format((count($ranking_uf) - ($var[0]['posicao_e_idh']-1)),0));
                        $texto->replaceTags("municipios_pior_IDHM_p_estado", number_format(((count($ranking_uf) - ($var[0]['posicao_e_idh']-1)) / count($ranking_uf)) * 100, 2, ",", "."));
                        break;
                    }
                    $count_iguais++;
                }
                break;
            }
            $count++;
        }

        $block_ranking->setData("text", $texto->getTexto());
        
//        if (TextBuilder::$print){ 
//            $block_ranking->setData("quebra", "<div style='page-break-after: always'></div>");
//        }else
//            $block_ranking->setData("quebra", "");
    }

    // OUTRA CATEGORIA
    static public function generateDEMOGRAFIA_SAUDE_populacao($block_populacao) {

        $pesotot = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESOTOT"); //PESOTOT
        $pesotot_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "PESOTOT"); //PESOTOT do Estado
        $pesotot_brasil = TextBuilder::getVariaveis_Brasil(TextBuilder::$idMunicipio, "PESOTOT"); //PESOTOT do Brasil
        
        $pesorur = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESORUR"); //PESORUR
        $pesourb = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESOURB"); //PESORUR
        
        if (TextBuilder::$print){ 
            $block_populacao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_populacao->setData("quebra", "");
        
        $block_populacao->setData("fonte", TextBuilder::$fontePerfil);
        $block_populacao->setData("titulo", "Demografia e Saúde");
        $block_populacao->setData("subtitulo", "População");
        $block_populacao->setData("info", "");
        $str = "Entre 2000 e 2010, a população de [municipio] teve uma taxa média de crescimento anual
            de [tx_cres_pop_0010]%. Na década anterior, de 1991 a 2000, a taxa média de crescimento anual
            foi de [tx_cres_pop_9100]%. No Estado, estas taxas foram de [tx_cresc_pop_estado_0010]% entre 2000 e 2010 e 
            [tx_cresc_pop_estado_9100]% entre 1991 e 2000. No país, foram de [tx_cresc_pop_pais_0010]% entre 2000 e 2010 e [tx_cresc_pop_pais_9100]% entre 1991 e 2000.
            Nas últimas duas décadas, a taxa de urbanização cresceu [tx_urbanizacao]%.";

        //TODO: Verificar a existencia desde cálculo
        //<br><br>Nas últimas duas décadas, a taxa de urbanização cresceu <cresc_txurb>%.";
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto->replaceTags("tx_cres_pop_0010", number_format((pow(($pesotot[2]["valor"] / $pesotot[1]["valor"]), 1 / 10) - 1) * 100, 2, ",", ".")); //(((PESOTOT 2010 / PESOTOT 2000)^(1/10))-1)*100
        $texto->replaceTags("tx_cres_pop_9100", number_format((pow(($pesotot[1]["valor"] / $pesotot[0]["valor"]), 1 / 9) - 1) * 100, 2, ",", "."));  //(((PESOTOT 2000 / PESOTOT 1991)^(1/9))-1)*100

        $texto->replaceTags("tx_cresc_pop_estado_0010", number_format(pow(($pesotot_uf[2]["valor"] / $pesotot_uf[1]["valor"]), 1 / 10), 2, ",", "."));
        $texto->replaceTags("tx_cresc_pop_estado_9100", number_format(pow(($pesotot_uf[1]["valor"] / $pesotot_uf[0]["valor"]), 1 / 9), 2, ",", "."));

        $texto->replaceTags("tx_cresc_pop_pais_0010", number_format(pow(($pesotot_brasil[2]["valor"] / $pesotot_brasil[1]["valor"]), 1 / 10), 2, ",", "."));
        $texto->replaceTags("tx_cresc_pop_pais_9100", number_format(pow(($pesotot_brasil[1]["valor"] / $pesotot_brasil[0]["valor"]), 1 / 9), 2, ",", "."));
        
        $texto->replaceTags("tx_urbanizacao", number_format( ( ((($pesourb[2]["valor"]/$pesotot[2]["valor"])*100) - (($pesourb[0]["valor"]/$pesotot[0]["valor"])*100))/(($pesourb[0]["valor"]/$pesotot[0]["valor"])*100) )* 100, 2, ",", "."));
                
        $block_populacao->setData("text", $texto->getTexto());
        $block_populacao->setData("tableContent", "");
    }

    static public function generateIDH_table_populacao($block_table_populacao) {

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESOTOT")); //População Total
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "HOMEMTOT")); //Homens
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "MULHERTOT")); //Mulheres
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESOURB")); //População Urbana
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESORUR")); //População Rural
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "")); //Taxa de Urbanização

        $block_table_populacao->setData("titulo", "População");
        $block_table_populacao->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_populacao->setData("caption", "População Total, por Gênero, Rural/Urbana e Taxa de Urbanização");
        $block_table_populacao->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_populacao->setData("coluna1", "População");
        $block_table_populacao->setData("coluna2", "% do Total");
        $block_table_populacao->setData("ano1", $variaveis[0][0]["label_ano_referencia"]); // Ano 1 (1991)
        $block_table_populacao->setData("ano2", $variaveis[0][1]["label_ano_referencia"]); // Ano 2 (2000)
        $block_table_populacao->setData("ano3", $variaveis[0][2]["label_ano_referencia"]); // Ano 3 (2010)

        for ($i = 1; $i <= count($variaveis); $i++) {
            if ($i != 6) {
                $block_table_populacao->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_populacao->setData("v$i" . "_a1", TextBuilder::my_number_format($variaveis[$i - 1][0]["valor"],0)); // Ano 1 (1991)
                $block_table_populacao->setData("v$i" . "__a1", number_format((($variaveis[$i - 1][0]["valor"] / $variaveis[0][0]["valor"]) * 100), 2, ",", ".")); // Ano 1 (1991)
                $block_table_populacao->setData("v$i" . "_a2", TextBuilder::my_number_format($variaveis[$i - 1][1]["valor"],0)); // Ano 2 (2000)
                $block_table_populacao->setData("v$i" . "__a2", number_format((($variaveis[$i - 1][1]["valor"] / $variaveis[0][1]["valor"]) * 100), 2, ",", ".")); // Ano 2 (2000)
                $block_table_populacao->setData("v$i" . "_a3", TextBuilder::my_number_format($variaveis[$i - 1][2]["valor"],0)); // Ano 3 (2010)
                $block_table_populacao->setData("v$i" . "__a3", number_format((($variaveis[$i - 1][2]["valor"] / $variaveis[0][2]["valor"]) * 100), 2, ",", ".")); // Ano 3 (2010) 
            } else {
                $block_table_populacao->setData("v$i", "Taxa de Urbanização"); //(PESOURB/PESOTOT)*100
                $block_table_populacao->setData("v$i" . "_a1", "-"); // Ano 1 (1991)
                $block_table_populacao->setData("v$i" . "__a1", number_format((($variaveis[3][0]["valor"] / $variaveis[0][0]["valor"]) * 100), 2, ",", ".")); // Ano 1 (1991)
                $block_table_populacao->setData("v$i" . "_a2", "-"); // Ano 2 (2000)
                $block_table_populacao->setData("v$i" . "__a2", number_format((($variaveis[3][1]["valor"] / $variaveis[0][1]["valor"]) * 100), 2, ",", ".")); // Ano 2 (2000)
                $block_table_populacao->setData("v$i" . "_a3", "-"); // Ano 3 (2010)
                $block_table_populacao->setData("v$i" . "__a3", number_format((($variaveis[3][2]["valor"] / $variaveis[0][2]["valor"]) * 100), 2, ",", ".")); // Ano 3 (2010)
            }
        }
    }

    static public function generateDEMOGRAFIA_SAUDE_etaria($block_etaria) {

        $tenv = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ENV"); //T_ENV
        $rd = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "RAZDEP"); //T_ENV

        $block_etaria->setData("subtitulo", "Estrutura Etária");
        $block_etaria->setData("info", "");
        
//         $str = "O índice de envelhecimento da população de [municipio] 
//             evoluiu de [indice_envelhecimento00]% para [indice_envelhecimento10]% entre 2000 e 2010
//             e neste período o percentual de pessoas que vivem em famílias com razão de dependência
//             acima de 75% passou de [rz_dependencia00]% para [rz_dependencia10]%.
//             Entre 1991 e 2000, o índice de envelhecimento evoluiu de [indice_envelhecimento91]% 
//             para [indice_envelhecimento00]% e o percentual de pessoas que vivem em famílias com razão de dependência
//             acima de 75% foi de [rz_dependencia91]% para [rz_dependencia00]%.";
                 
        $str = "Entre 2000 e 2010, a razão de dependência de [municipio] passou de [rz_dependencia00]%
            para [rz_dependencia10]% e o índice de envelhecimento evoluiu de [indice_envelhecimento00]% para [indice_envelhecimento10]%.
            Entre 1991 e 2000, a razão de dependência foi de [rz_dependencia91]% para [rz_dependencia00]%,
            enquanto o índice de envelhecimento evoluiu de [indice_envelhecimento91]% para [indice_envelhecimento00]%.";
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto->replaceTags("indice_envelhecimento00", number_format($tenv[1]["valor"], 2, ",", "."));
        $texto->replaceTags("indice_envelhecimento10", number_format($tenv[2]["valor"], 2, ",", "."));
        $texto->replaceTags("indice_envelhecimento91", number_format($tenv[0]["valor"], 2, ",", "."));
        $texto->replaceTags("rz_dependencia00", number_format($rd[1]["valor"], 2, ",", "."));
        $texto->replaceTags("rz_dependencia10", number_format($rd[2]["valor"], 2, ",", "."));
        $texto->replaceTags("rz_dependencia91", number_format($rd[0]["valor"], 2, ",", "."));
        $block_etaria->setData("text", $texto->getTexto());
        
        $block_etaria->setData("block_box1", "<b>O que é razão de<br> dependência?</b><br>
            população de menos<br> de 14 anos e de 65 anos <br>(população dependente) <br>
            ou mais em relação à<br> população de 15 a 64 anos <br>(população potencialmente ativa)");
        $block_etaria->setData("block_box2","<b>O que é índice de<br> envelhecimento?</b><br>
            população de 65 anos <br>ou mais em relação à <br>população de menos<br> de 15 anos");
        
        $block_etaria->setData("tableContent", "");
    }

    static public function generateIDH_table_etaria($block_table_etaria) {

        $pesotot = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESOTOT"); //PESOTOT

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESO15")); //Menos de 15 anos (PESOTOT-PESO15)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "")); //15 a 64 anos
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESO65")); //65 anos ou mais
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "RAZDEP")); //Razão de Dependência(Planilha Piramide)               
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ENV")); //Índice de Envelhecimento

        $block_table_etaria->setData("titulo", "Estrutura Etária");
        $block_table_etaria->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_etaria->setData("caption", "Estrutura Etária da População");
        $block_table_etaria->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_etaria->setData("coluna1", "População");
        $block_table_etaria->setData("coluna2", "% do Total");
        $block_table_etaria->setData("ano1", $variaveis[0][0]["label_ano_referencia"]); // Ano 1 (1991)
        $block_table_etaria->setData("ano2", $variaveis[0][1]["label_ano_referencia"]); // Ano 2 (2000)
        $block_table_etaria->setData("ano3", $variaveis[0][2]["label_ano_referencia"]); // Ano 3 (2010)

        for ($i = 1; $i <= count($variaveis); $i++) {
            if ($i == 1) {
                $block_table_etaria->setData("v$i", "Menos de 15 anos");
                $block_table_etaria->setData("v$i" . "_a1", TextBuilder::my_number_format($pesotot[0]["valor"] - $variaveis[$i - 1][0]["valor"],0)); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "__a1", number_format((($pesotot[0]["valor"] - $variaveis[$i - 1][0]["valor"]) / $pesotot[0]["valor"]) * 100, 2, ",", ".")); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "_a2", TextBuilder::my_number_format($pesotot[1]["valor"] - $variaveis[$i - 1][1]["valor"],0)); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "__a2", number_format((($pesotot[1]["valor"] - $variaveis[$i - 1][1]["valor"]) / $pesotot[1]["valor"]) * 100, 2, ",", ".")); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "_a3", TextBuilder::my_number_format($pesotot[2]["valor"] - $variaveis[$i - 1][2]["valor"],0)); // Ano 3 (2010)
                $block_table_etaria->setData("v$i" . "__a3", number_format((($pesotot[2]["valor"] - $variaveis[$i - 1][2]["valor"]) / $pesotot[2]["valor"]) * 100, 2, ",", ".")); // Ano 3 (2010)
            } else if ($i == 2) {
                $block_table_etaria->setData("v$i", "15 a 64 anos");
                $block_table_etaria->setData("v$i" . "_a1", TextBuilder::my_number_format($variaveis[$i - 2][0]["valor"] - $variaveis[$i][0]["valor"],0)); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "__a1", number_format((($variaveis[$i - 2][0]["valor"] - $variaveis[$i][0]["valor"]) / $pesotot[0]["valor"]) * 100, 2, ",", ".")); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "_a2", TextBuilder::my_number_format($variaveis[$i - 2][1]["valor"] - $variaveis[$i][1]["valor"],0)); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "__a2", number_format((($variaveis[$i - 2][1]["valor"] - $variaveis[$i][1]["valor"]) / $pesotot[1]["valor"]) * 100, 2, ",", ".")); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "_a3", TextBuilder::my_number_format($variaveis[$i - 2][2]["valor"] - $variaveis[$i][2]["valor"],0)); // Ano 3 (2010)
                $block_table_etaria->setData("v$i" . "__a3", number_format((($variaveis[$i - 2][2]["valor"] - $variaveis[$i][2]["valor"]) / $pesotot[2]["valor"]) * 100, 2, ",", ".")); // Ano 3 (2010)
            } else if ($i == 3) {
                $block_table_etaria->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_etaria->setData("v$i" . "_a1", TextBuilder::my_number_format($variaveis[$i - 1][0]["valor"],0)); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "__a1", number_format(($variaveis[$i - 1][0]["valor"] / $pesotot[0]["valor"]) * 100, 2, ",", ".")); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "_a2", TextBuilder::my_number_format($variaveis[$i - 1][1]["valor"],0)); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "__a2", number_format(($variaveis[$i - 1][1]["valor"] / $pesotot[1]["valor"]) * 100, 2, ",", ".")); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "_a3", TextBuilder::my_number_format($variaveis[$i - 1][2]["valor"],0)); // Ano 3 (2010)
                $block_table_etaria->setData("v$i" . "__a3", number_format(($variaveis[$i - 1][2]["valor"] / $pesotot[2]["valor"]) * 100, 2, ",", ".")); // Ano 3 (2010)
            } else if ($i == 4) {
                $block_table_etaria->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_etaria->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "__a1", number_format(($variaveis[$i - 1][0]["valor"] / $pesotot[0]["valor"]) * 100, 2, ",", ".")); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "__a2", number_format(($variaveis[$i - 1][1]["valor"] / $pesotot[1]["valor"]) * 100, 2, ",", ".")); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
                $block_table_etaria->setData("v$i" . "__a3", number_format(($variaveis[$i - 1][2]["valor"] / $pesotot[2]["valor"]) * 100, 2, ",", ".")); // Ano 3 (2010)
            } else {
                $block_table_etaria->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_etaria->setData("v$i" . "_a1", "-"); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "__a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_etaria->setData("v$i" . "_a2", "-"); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "__a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_etaria->setData("v$i" . "_a3", "-"); // Ano 3 (2010)
                $block_table_etaria->setData("v$i" . "__a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
        }
        
        if (TextBuilder::$print){ 
            $block_table_etaria->setData("quebra1", "<div style='page-break-after: always'></div>");
        }else
            $block_table_etaria->setData("quebra1", "");
        
        $block_table_etaria->setData("canvasContent1", getChartPiramideEtaria1(TextBuilder::$idMunicipio));
        $block_table_etaria->setData("canvasContent2", getChartPiramideEtaria2(TextBuilder::$idMunicipio));    
        $block_table_etaria->setData("canvasContent3", getChartPiramideEtaria3(TextBuilder::$idMunicipio));
        
    }

    static public function generateDEMOGRAFIA_SAUDE_longevidade1($block_longevidade) {

        $mort1 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "MORT1"); //MORT1
        $mort1_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "MORT1"); //MORT1 do Estado
        $mort1_brasil = TextBuilder::getVariaveis_Brasil(TextBuilder::$idMunicipio, "MORT1"); //MORT1 do Brasil
                
        if (TextBuilder::$print){ 
            $block_longevidade->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_longevidade->setData("quebra", "");
        
        $block_longevidade->setData("subtitulo", "Longevidade, mortalidade e fecundidade");
        $block_longevidade->setData("info", "");
        $str1 = "A mortalidade infantil (mortalidade de crianças com menos de um ano) em [municipio] [mort1_diminuiu_aumentou] [reducao_mortalinfantil0010]%,
            passando de [mortinfantil00] por mil nascidos vivos em 2000 para [mortinfantil10]  por mil nascidos vivos em 2010.
            Segundo os Objetivos de Desenvolvimento do Milênio das Nações Unidas, a mortalidade infantil para o Brasil deve estar abaixo de 17,9 óbitos por mil em 2015.
            Em 2010, as taxas de mortalidade infantil do estado e do país eram [mortinfantil10_Estado] e [mortinfantil10_Brasil] por mil nascidos vivos, respectivamente.";
        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        //TODO: Tem que ser sempre positivo
        $texto1->replaceTags("reducao_mortalinfantil0010", abs(number_format((($mort1[1]["valor"] - $mort1[2]["valor"]) / $mort1[1]["valor"]) * 100, 2, ",", ".")));
        $texto1->replaceTags("mortinfantil00", number_format($mort1[1]["valor"], 1, ",", "."));
        $texto1->replaceTags("mortinfantil10", number_format($mort1[2]["valor"], 1, ",", "."));
        $texto1->replaceTags("mortinfantil10_Estado", number_format($mort1_uf[2]["valor"], 1, ",", "."));
        $texto1->replaceTags("mortinfantil10_Brasil", number_format($mort1_brasil[2]["valor"], 1, ",", "."));

        //TODO: Tirar o igual da comparação (feito pq a base não é oficial e há replicações
        if ($mort1[2]["valor"] <= $mort1[1]["valor"])
            $texto1->replaceTags("mort1_diminuiu_aumentou", "reduziu");
        else if ($mort1[2]["valor"] > $mort1[1]["valor"])
            $texto1->replaceTags("mort1_diminuiu_aumentou", "aumentou");
        
        $block_longevidade->setData("text2", "");
        $block_longevidade->setData("tableContent", "");

        $block_longevidade->setData("text1", $texto1->getTexto());
        
//        if (TextBuilder::$print){ 
//            $block_longevidade->setData("quebra", "");            
//        }else
//            $block_longevidade->setData("quebra", "");

    }

    static public function generateIDH_table_longevidade($block_table_longevidade) {

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "ESPVIDA")); //Esperança de vida ao nascer (anos)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "MORT1")); //Mortalidade até 1 ano de idade (por mil nascidos vivos)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "MORT5")); //Mortalidade até 5 anos de idade (por mil nascidos vivos)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "FECTOT")); //Taxa de fecundidade total (filhos por mulher) 

        $block_table_longevidade->setData("titulo", "");
        $block_table_longevidade->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_longevidade->setData("caption", "Longevidade, Mortalidade e Fecundidade");
        $block_table_longevidade->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_longevidade->setData("ano1", $variaveis[0][0]["label_ano_referencia"]); // Ano 1 (1991)
        $block_table_longevidade->setData("ano2", $variaveis[0][1]["label_ano_referencia"]); // Ano 2 (2000)
        $block_table_longevidade->setData("ano3", $variaveis[0][2]["label_ano_referencia"]); // Ano 3 (2010)

        for ($i = 1; $i <= count($variaveis); $i++) {
            $block_table_longevidade->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
            $block_table_longevidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 1, ",", ".")); // Ano 1 (1991)
            $block_table_longevidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 1, ",", ".")); // Ano 2 (2000)
            $block_table_longevidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 1, ",", ".")); // Ano 3 (2010)
        }
    }
    
       static public function generateDEMOGRAFIA_SAUDE_longevidade2($block_longevidade) {

        $espvida = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "ESPVIDA"); //ESPVIDA
        $espvida_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "ESPVIDA"); //ESPVIDA do Estado
        $espvida_brasil = TextBuilder::getVariaveis_Brasil(TextBuilder::$idMunicipio, "ESPVIDA"); //ESPVIDA do Brasil

        $block_longevidade->setData("subtitulo", "");
        $block_longevidade->setData("info", "");

        $str2 = "A esperança de vida ao nascer é o indicador utilizado para compor a dimensão Longevidade do Índice de Desenvolvimento Humano Municipal (IDHM).
            Em [municipio], a esperança de vida ao nascer aumentou [aumento_esp_nascer0010] anos nas últimas duas décadas, passando de [esp_nascer91] anos em 1991 para [esp_nascer00] anos em 2000,
            e para [esp_nascer10] anos em 2010. Em 2010, a esperança de vida ao nascer média para o estado é de [esp_nascer10_estado] anos e, para o país,
            de [esp_nascer10_pais] anos.";
        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto2->replaceTags("aumento_esp_nascer0010", number_format($espvida[2]["valor"] - $espvida[0]["valor"], 1, ",", "."));
        $texto2->replaceTags("esp_nascer91", number_format($espvida[0]["valor"], 1, ",", "."));
        $texto2->replaceTags("esp_nascer00", number_format($espvida[1]["valor"], 1, ",", "."));
        $texto2->replaceTags("esp_nascer10", number_format($espvida[2]["valor"], 1, ",", "."));
        $texto2->replaceTags("esp_nascer10_estado", number_format($espvida_uf[2]["valor"], 1, ",", "."));
        $texto2->replaceTags("esp_nascer10_pais", number_format($espvida_brasil[2]["valor"], 1, ",", "."));
        $block_longevidade->setData("text2", $texto2->getTexto());
        
        $block_longevidade->setData("text1", "");
        $block_longevidade->setData("tableContent", "");
        
        if (TextBuilder::$print){ 
            $block_longevidade->setData("quebra", "");
        }else
            $block_longevidade->setData("quebra", "");
    }

    static public function generateEDUCACAO_nivel_educacional($block_nivel_educacional) {

//        $t_freq4a6 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FREQ4A6"); //T_FREQ4A6 
//        $t_fund12a14 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND12A14");  //T_FUND12A14 
//        $t_fund16a18 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND16A18");  //T_FUND16A18 
//        $t_med19a21 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_MED19A21");  //T_MED19A21 
        
        $t_freq4a6 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FREQ5A6"); //T_FREQ5A6 - Mudou variável
        $t_fund12a14 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND11A13");  //T_FUND11A13 - Mudou variável
        $t_fund16a18 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND15A17");  //T_FUND15A17 - Mudou variável
        $t_med19a21 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_MED18A20");  //T_MED18A20 - Mudou variável
        
        $t_atraso_0_fund = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ATRASO_0_FUND");  //T_ATRASO_0_FUND 
        $t_flfund = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FLFUND");  //T_FLFUND 

        $t_atraso_0_med = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ATRASO_0_MED");  //T_ATRASO_0_MED 
        $t_flmed = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FLMED");  //T_FLMED 
        $t_flsuper = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FLSUPER");  //T_FLSUPER 

        $t_freq6a14 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FREQ6A14");  //T_FREQ6A14 
        $t_freq15a17 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FREQ15A17");  //T_FREQ15A17 
        //$t_freq18a24 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FREQ18A24");  //T_FREQ15A17 
        
        if (TextBuilder::$print){ 
            $block_nivel_educacional->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra", "");
        
        $block_nivel_educacional->setData("fonte", TextBuilder::$fontePerfil);
        $block_nivel_educacional->setData("titulo", "Educação");
        $block_nivel_educacional->setData("subtitulo", "Crianças e Jovens");
        $block_nivel_educacional->setData("info", "");
        $str1 = "<br>A proporção de crianças e jovens frequentando ou tendo completado determinados ciclos indica a situação da educação entre a 
            população em idade escolar do município e compõe o IDHM Educação. 
        <br><br>No período de 2000 a 2010, a proporção de <b>crianças de 5 a 6 anos na escola</b> cresceu [cresc_4-6esc_0010]% e no de período 1991 e 2000,
        [cresc_4-6esc_9100]%. A proporção de <b>crianças de 11 a 13 anos frequentando os anos finais do ensino fundamental</b> cresceu [cresc_12-14esc_0010]%
        entre 2000 e 2010 e [cresc_12-14esc_9100]%  entre 1991 e 2000. 
        
        <br><br>A proporção de <b>jovens entre 15 e 17 anos com ensino fundamental completo</b> cresceu [cresc_16-18fund_0010]% no período de 2000 a 2010 e
        [cresc_16-18fund_9100]% no período de 1991 a 2000. E a proporção de <b>jovens entre 18 e 20 anos com ensino médio completo</b> cresceu [cresc_19-21medio_0010]%
        entre 2000 e 2010 e [cresc_19-21medio_9100]% entre 1991 e 2000.";

        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        $texto1->replaceTags("cresc_4-6esc_0010", number_format((($t_freq4a6[2]["valor"] - $t_freq4a6[1]["valor"]) / $t_freq4a6[1]["valor"]) * 100, 2, ",", "."));
        $texto1->replaceTags("cresc_4-6esc_9100", number_format((($t_freq4a6[1]["valor"] - $t_freq4a6[0]["valor"]) / $t_freq4a6[0]["valor"]) * 100, 2, ",", "."));

        $texto1->replaceTags("cresc_12-14esc_0010", number_format((($t_fund12a14[2]["valor"] - $t_fund12a14[1]["valor"]) / $t_fund12a14[1]["valor"]) * 100, 2, ",", "."));
        $texto1->replaceTags("cresc_12-14esc_9100", number_format((($t_fund12a14[1]["valor"] - $t_fund12a14[0]["valor"]) / $t_fund12a14[0]["valor"]) * 100, 2, ",", "."));

        $texto1->replaceTags("cresc_16-18fund_0010", number_format((($t_fund16a18[2]["valor"] - $t_fund16a18[1]["valor"]) / $t_fund16a18[1]["valor"]) * 100, 2, ",", "."));
        $texto1->replaceTags("cresc_16-18fund_9100", number_format((($t_fund16a18[1]["valor"] - $t_fund16a18[0]["valor"]) / $t_fund16a18[0]["valor"]) * 100, 2, ",", "."));

        $texto1->replaceTags("cresc_19-21medio_0010", number_format((($t_med19a21[2]["valor"] - $t_med19a21[1]["valor"]) / $t_med19a21[1]["valor"]) * 100, 2, ",", "."));
        $texto1->replaceTags("cresc_19-21medio_9100", number_format((($t_med19a21[1]["valor"] - $t_med19a21[0]["valor"]) / $t_med19a21[0]["valor"]) * 100, 2, ",", "."));

        $block_nivel_educacional->setData("text1", $texto1->getTexto());        

        if (TextBuilder::$print){ 
            $block_nivel_educacional->setData("quebra4", "<div style=margin-top: 20px;'></div>");
        }else
            $block_nivel_educacional->setData("quebra4", "");
        
        $block_nivel_educacional->setData("canvasContent1", getChartFluxoEscolar(TextBuilder::$idMunicipio));
        
        if (TextBuilder::$print){ 
            $block_nivel_educacional->setData("quebra1", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra1", "");
        
        $block_nivel_educacional->setData("canvasContent2", getChartFrequenciaEscolar(TextBuilder::$idMunicipio));
        
        $str2 = "<br>Em 2010, [tx_fund_sematraso_10]%  dos alunos entre 6 e 14 anos de [municipio] estavam cursando
        o ensino fundamental regular  na série correta para a idade. Em 2000 eram [tx_fund_sematraso_00]%  e, em 1991,
        [tx_fund_sematraso_91]%. Entre os jovens de 15 a 17 anos, [tx_medio_sematraso_10]% estavam cursando o ensino médio regular sem atraso.
        Em 2000 eram [tx_medio_sematraso_00]% e, em 1991, [tx_medio_sematraso_91]%. 
        Entre os alunos de 18 a 24 anos, [t_flsuper_10]% estavam cursando o ensino superior em 2010, [t_flsuper_00]% em 2000 e [t_flsuper_91]% em 1991.
        <br><br>
        Nota-se que, em 2010 , [p6a14]% das crianças de 6 a 14 anos não frequentavam a escola, percentual que,
        entre os jovens de 15 a 17 anos atingia [p15a17]%.";

        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto2->replaceTags("tx_fund_sematraso_10", number_format(($t_atraso_0_fund[2]["valor"] * $t_flfund[2]["valor"]) / 100, 2, ",", "."));
        $texto2->replaceTags("tx_fund_sematraso_00", number_format(($t_atraso_0_fund[1]["valor"] * $t_flfund[1]["valor"]) / 100, 2, ",", "."));
        $texto2->replaceTags("tx_fund_sematraso_91", number_format(($t_atraso_0_fund[0]["valor"] * $t_flfund[0]["valor"]) / 100, 2, ",", "."));

        $texto2->replaceTags("tx_medio_sematraso_10", number_format(($t_atraso_0_med[2]["valor"] * $t_flmed[2]["valor"]) / 100, 2, ",", "."));
        $texto2->replaceTags("tx_medio_sematraso_00", number_format(($t_atraso_0_med[1]["valor"] * $t_flmed[1]["valor"]) / 100, 2, ",", "."));
        $texto2->replaceTags("tx_medio_sematraso_91", number_format(($t_atraso_0_med[0]["valor"] * $t_flmed[0]["valor"]) / 100, 2, ",", "."));
        
        $texto2->replaceTags("t_flsuper_10", number_format($t_flsuper[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("t_flsuper_00", number_format($t_flsuper[1]["valor"], 2, ",", "."));
        $texto2->replaceTags("t_flsuper_91", number_format($t_flsuper[0]["valor"], 2, ",", "."));       
                
        $texto2->replaceTags("p6a14", number_format(100 - $t_freq6a14[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("p15a17", number_format(100 - $t_freq15a17[2]["valor"], 2, ",", "."));
        
        $block_nivel_educacional->setData("text2", $texto2->getTexto());
        
        if (TextBuilder::$print){ 
            $block_nivel_educacional->setData("quebra2", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra2", "");
        
        $block_nivel_educacional->setData("canvasContent3", getChartFrequenciaDe6a14(TextBuilder::$idMunicipio));
              
        $block_nivel_educacional->setData("canvasContent4", getChartFrequenciaDe15a17(TextBuilder::$idMunicipio));
        $block_nivel_educacional->setData("canvasContent5", getChartFrequenciaDe18a24(TextBuilder::$idMunicipio));
        
        if (TextBuilder::$print){ 
            $block_nivel_educacional->setData("quebra3", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra3", "");
        
    }

    static public function generateEDUCACAO_populacao_adulta($block_populacao_adulta) {

//        $t_analf25m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ANALF25M"); //T_ANALF25M  
//        $t_fundin25m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND25M");  //T_FUNDIN25M 
//        $t_medin25m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_MED25M");  //T_MEDIN25M 
        
        $t_analf25m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ANALF18M"); //T_ANALF18M - Mudou variável
        $t_fundin25m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND18M");  //T_FUND18M  - Mudou variável
        $t_medin25m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_MED18M");  //T_MED18M  - Mudou variável
        
//        $t_analf25m_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "T_ANALF25M"); //T_ANALF25M  
//        $t_fundin25m_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "T_FUND25M");  //T_FUNDIN25M 
//        $t_medin25m_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "T_MED25M");  //T_MEDIN25M 
        
        //$t_analf25m_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "T_ANALF18M"); //T_ANALF18M - Mudou variável 
        $t_fundin25m_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "T_FUND18M");  //T_FUND18M - Mudou variável
        $t_medin25m_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "T_MED18M");  //T_MED18M - Mudou variável
        
        $e_anosesperados = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "E_ANOSESTUDO"); //E_ANOSESTUDO
        $e_anosesperados_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "E_ANOSESTUDO");  //E_ANOSESTUDO
               
        $uf = TextBuilder::getUf(TextBuilder::$idMunicipio); //UF
        
        $block_populacao_adulta->setData("subtitulo", "População Adulta");
        $block_populacao_adulta->setData("fonte", TextBuilder::$fontePerfil);
        $block_populacao_adulta->setData("info", "");
        $str = "A escolaridade da população adulta é importante indicador de acesso a conhecimento e também compõe o IDHM Educação.
            <br><br>Em 2010, [25_fund_10]% da população de 18 anos ou mais de idade tinha completado o ensino fundamental e [25_medio_10]%
            o ensino médio. Em [estado_municipio], [25_fund_10_Estado]% e [25_medio_10_Estado]% respectivamente.
            Esse indicador carrega uma grande inércia, em função do peso das gerações mais antigas e de menos escolaridade.
            <br><br>A taxa de analfabetismo da população de 18 anos ou mais [diminuiu_aumentou] [25_analf_9110] nas últimas duas décadas.";
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto->replaceTags("estado_municipio", $uf[0]["nome"]);
        
        $texto->replaceTags("25_fund_10", number_format($t_fundin25m[2]["valor"], 2, ",", "."));
        //$texto->replaceTags("25_fund_10", str_replace(".",",",number_format($t_fundin25m[2]["valor"], 2)));
        $texto->replaceTags("25_medio_10", number_format($t_medin25m[2]["valor"], 2, ",", "."));
        //$texto->replaceTags("25_analf_00_Estado", number_format($t_analf25m_uf[2]["valor"], 2, ",", "."));
        $texto->replaceTags("25_fund_10_Estado", number_format($t_fundin25m_uf[2]["valor"], 2, ",", "."));
        $texto->replaceTags("25_medio_10_Estado", number_format($t_medin25m_uf[2]["valor"], 2, ",", "."));
        
        $dif_analf = $t_analf25m[2]["valor"] - $t_analf25m[0]["valor"];
        if ($dif_analf > 0){
            $texto->replaceTags("diminuiu_aumentou", "aumentou");
            $texto->replaceTags("25_analf_9110", number_format($dif_analf, 2, ",", ".") . "%");
        }else if ($dif_analf == 0) {
            $texto->replaceTags("diminuiu_aumentou", "se manteve");
            $texto->replaceTags("25_analf_9110", "");
        }else if ($dif_analf < 0) {
            $texto->replaceTags("diminuiu_aumentou", "diminuiu");
            $texto->replaceTags("25_analf_9110", number_format(abs($dif_analf), 2, ",", ".") . "%");
        }
        
        $block_populacao_adulta->setData("text", $texto->getTexto());
        $block_populacao_adulta->setData("canvasContent", getChartEscolaridadePopulacao(TextBuilder::$idMunicipio));
        
        $block_populacao_adulta->setData("subtitulo2", "Anos Esperados de Estudo");
        $block_populacao_adulta->setData("info2", "");
        $str2 = "Os anos esperados de estudo indicam o número de anos que a criança que inicia 
        a vida escolar no ano de referência tende a completar.
        Em 2010, [municipio] tinha [e_anosestudo10] anos esperados de estudo, 
        em 2000 tinha [e_anosestudo00] anos e em 1991 [e_anosestudo91] anos. 
        Enquanto que  [estado_municipio], tinha [ufe_anosestudo10] anos esperados de estudo em 2010,
        [ufe_anosestudo00] anos em 2000 e [ufe_anosestudo91] anos em 1991.";
        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto2->replaceTags("estado_municipio", $uf[0]["nome"]);
        
        $texto2->replaceTags("e_anosestudo10", number_format($e_anosesperados[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("e_anosestudo00", number_format($e_anosesperados[1]["valor"], 2, ",", "."));
        $texto2->replaceTags("e_anosestudo91", number_format($e_anosesperados[0]["valor"], 2, ",", "."));
        
        $texto2->replaceTags("ufe_anosestudo10", number_format($e_anosesperados_uf[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("ufe_anosestudo00", number_format($e_anosesperados_uf[1]["valor"], 2, ",", "."));
        $texto2->replaceTags("ufe_anosestudo91", number_format($e_anosesperados_uf[0]["valor"], 2, ",", "."));
        
        $block_populacao_adulta->setData("text2", $texto2->getTexto());
     
    }

    static public function generateRENDA($block_renda) {

        $rdpc = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "RDPC"); //RDPC  
        $pind = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PIND");  //PIND 
        $pop = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "POP");  //POP 
        $gini = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "GINI");  //GINI 

        if (TextBuilder::$print){ 
            $block_renda->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_renda->setData("quebra", "");

        $block_renda->setData("fonte", TextBuilder::$fontePerfil);
        $block_renda->setData("titulo", "Renda");
        $block_renda->setData("subtitulo", "");
        $block_renda->setData("info", "");
        $str = "A renda per capita média de [municipio] [caiu_cresceu] [tx_cresc_renda]%  nas últimas duas décadas,
            passando de R$[renda91] em 1991 para R$[renda00] em 2000 e R$[renda10] em 2010. A taxa média anual de crescimento foi
            de [tx_cresc_renda9100]% no primeiro período e [tx_cresc_renda0010]% no segundo. A extrema pobreza (medida pela proporção de pessoas com
            renda domiciliar per capita inferior a R$ 70,00, em reais de agosto de 2010) passou de [tx_pobreza_91]%  em 1991 para [tx_pobreza_00]%
            em 2000 e para [tx_pobreza_10]%  em 2010.
            <br><br>A desigualdade [diminuiu_aumentou]: o Índice de Gini passou de [gini_91] em 1991 para [gini_00] em 2000 e  para [gini_10] em 2010. 
            ";
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        //TODO: Tirar o igual da comparação (feito pq a base não é oficial e há replicações
        if ($rdpc[2]["valor"] >= $rdpc[0]["valor"])
            $texto->replaceTags("caiu_cresceu", "cresceu");
        else if ($rdpc[2]["valor"] < $rdpc[0]["valor"])
            $texto->replaceTags("caiu_cresceu", "caiu");

        $texto->replaceTags("tx_cresc_renda", number_format((($rdpc[2]["valor"] - $rdpc[0]["valor"]) / $rdpc[0]["valor"]) * 100, 2, ",", "."));
        $texto->replaceTags("renda91", number_format($rdpc[0]["valor"], 2, ",", "."));
        $texto->replaceTags("renda00", number_format($rdpc[1]["valor"], 2, ",", "."));
        $texto->replaceTags("renda10", number_format($rdpc[2]["valor"], 2, ",", "."));
        $texto->replaceTags("tx_cresc_renda9100", number_format((($rdpc[1]["valor"] - $rdpc[0]["valor"]) / $rdpc[0]["valor"]) * 100, 2, ",", "."));
        $texto->replaceTags("tx_cresc_renda0010", number_format((($rdpc[2]["valor"] - $rdpc[1]["valor"]) / $rdpc[1]["valor"]) * 100, 2, ",", "."));

        $texto->replaceTags("tx_pobreza_91", number_format($pind[0]["valor"], 2, ",", "."));
        $texto->replaceTags("tx_pobreza_00", number_format($pind[1]["valor"], 2, ",", "."));
        $texto->replaceTags("tx_pobreza_10", number_format($pind[2]["valor"], 2, ",", "."));
        //$texto->replaceTags("red_extrema_pobreza", str_replace(".",",",number_format((( ($pind[0]["valor"] * $pop[0]["valor"]) - ($pind[2]["valor"] * $pop[2]["valor"]) ) / ($pind[0]["valor"] * $pop[0]["valor"])) * 100, 2)));

        if ($gini[2]["valor"] < $gini[0]["valor"])
            $texto->replaceTags("diminuiu_aumentou", "diminuiu");
        else if ($gini[2]["valor"] == $gini[0]["valor"])
            $texto->replaceTags("diminuiu_aumentou", "se manteve");
        else if ($gini[2]["valor"] > $gini[0]["valor"])
            $texto->replaceTags("diminuiu_aumentou", "aumentou");

        $texto->replaceTags("gini_91", number_format($gini[0]["valor"], 2, ",", "."));
        $texto->replaceTags("gini_00", number_format($gini[1]["valor"], 2, ",", "."));
        $texto->replaceTags("gini_10", number_format($gini[2]["valor"], 2, ",", "."));

        $block_renda->setData("text", $texto->getTexto());
        $block_renda->setData("block_box1", "<br><b>O que é Índice de Gini?</b><br>
            É um instrumento usado para medir<br>
            o grau de concentração de renda.<br>
            Ele aponta a diferença entre os <br>
            rendimentos dos mais pobres e dos <br>
            mais ricos. Numericamente, varia <br>
            de 0 a 1, sendo que 0 representa <br>
            a situação de total igualdade, ou seja,<br>
            todos têm a mesma renda, e o valor <br>
            1 significa completa desigualdade<br>
            de renda, ou seja, se uma só pessoa <br>
            detém toda a renda do lugar. ");
        

        // GRAFICO
        $block_renda->setData("tableContent", "");
        $block_renda->setData("tableContent2", "");
        
                
//        if (TextBuilder::$print){ 
//            $block_renda->setData("quebra", "<div style='page-break-after: always'></div>");
//        }else
//            $block_renda->setData("quebra", "");
    }

    static public function generateIDH_table_renda($block_table_renda) {

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "RDPC")); //Renda per capita média (R$ de 2010)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PIND")); //Proporção de extremamente pobres - total (%)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PMPOB")); //Proporção de pobres 
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "GINI")); //Índice de Gini

        $block_table_renda->setData("titulo", "");
        $block_table_renda->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_renda->setData("caption", "Renda, Pobreza e Desigualdade");
        $block_table_renda->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_renda->setData("ano1", $variaveis[0][0]["label_ano_referencia"]); // Ano 1 (1991)
        $block_table_renda->setData("ano2", $variaveis[0][1]["label_ano_referencia"]); // Ano 2 (2000)
        $block_table_renda->setData("ano3", $variaveis[0][2]["label_ano_referencia"]); // Ano 3 (2010)

        for ($i = 1; $i <= count($variaveis); $i++) {
            
            if ($i == 2){
                $block_table_renda->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_renda->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_renda->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_renda->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_renda->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else if ($i == 3){
                $block_table_renda->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_renda->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_renda->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_renda->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_renda->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else{
                $block_table_renda->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_renda->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_renda->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_renda->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
            
        }
    } 
    
    static public function generateIDH_table_renda2($block_table_renda2) {

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PREN20")); //20% mais pobres
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PREN40")); //40% mais pobres
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PREN60")); //60% mais pobres
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PREN80")); //80% mais pobres
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PREN20RICOS")); //20% mais ricos

        $block_table_renda2->setData("titulo", "");
        $block_table_renda2->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_renda2->setData("caption", "Porcentagem da Renda Apropriada por Estratos da População");
        $block_table_renda2->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_renda2->setData("ano1", $variaveis[0][0]["label_ano_referencia"]); // Ano 1 (1991)
        $block_table_renda2->setData("ano2", $variaveis[0][1]["label_ano_referencia"]); // Ano 2 (2000)
        $block_table_renda2->setData("ano3", $variaveis[0][2]["label_ano_referencia"]); // Ano 3 (2010)

        for ($i = 1; $i <= count($variaveis); $i++) {
            
            if ($i == 1){
                $block_table_renda2->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_renda2->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_renda2->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_renda2->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_renda2->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
            else if ($i == 2){
                $block_table_renda2->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_renda2->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_renda2->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_renda2->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_renda2->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
            else if ($i == 3){
                $block_table_renda2->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_renda2->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_renda2->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_renda2->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_renda2->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
            else if ($i == 4){
                $block_table_renda2->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_renda2->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_renda2->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_renda2->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_renda2->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
            else if ($i == 5){
                $block_table_renda2->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_renda2->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_renda2->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_renda2->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_renda2->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
            
        }

    }

    static public function generateTRABALHO1($block_trabalho) {

        $t_ativ18m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ATIV18M"); //T_ATIV18M  
        $t_des18m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_DES18M");  //T_DES18M 
        
        if (TextBuilder::$print){ 
            $block_trabalho->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_trabalho->setData("quebra", "");
        
        $block_trabalho->setData("fonte", TextBuilder::$fontePerfil);
        $block_trabalho->setData("titulo", "Trabalho");
        $block_trabalho->setData("canvasContent", getChartTrabalho(TextBuilder::$idMunicipio));
       
        $block_trabalho->setData("subtitulo", "");
        $block_trabalho->setData("info", "");

        $str1 = "Entre 2000 e 2010, a <b>taxa de atividade</b> da população de 18 anos ou mais (ou seja, o percentual dessa população que era economicamente ativa)
            passou de [tx_ativ_18m_00]% em 2000 para [tx_ativ_18m_10]% em 2010. Ao mesmo tempo, sua <b>taxa de desocupação</b> (ou seja, o percentual da população economicamente ativa
            que estava desocupada) passou de [tx_des18m_00]% em 2000 para [tx_des18m_10]% em 2010.";
        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        $texto1->replaceTags("tx_ativ_18m_00", number_format($t_ativ18m[1]["valor"], 2, ",", "."));
        $texto1->replaceTags("tx_ativ_18m_10", number_format($t_ativ18m[2]["valor"], 2, ",", "."));

        $texto1->replaceTags("tx_des18m_00", number_format($t_des18m[1]["valor"], 2, ",", "."));
        $texto1->replaceTags("tx_des18m_10", number_format($t_des18m[2]["valor"], 2, ",", "."));

        $block_trabalho->setData("text1", $texto1->getTexto());
        $block_trabalho->setData("text2", "");
        
                                
//        if (TextBuilder::$print){ 
//            $block_trabalho->setData("quebra", "<div style='page-break-after: always'></div>");
//        }else
//            $block_trabalho->setData("quebra", "");

        
    }

    static public function generateIDH_table_trabalho($block_table_trabalho) {

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ATIV18M")); //Taxa de atividade
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_DES18M")); //Taxa de desocupação
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_FORMAL")); //Grau de formalização dos ocuupados
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_FUND")); //% de empregados com fundamental completo
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_MED")); //% de empregados com médio completo
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "REN1")); //% com até 1 s.m. 
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "REN2")); //% com até 2 s.m. 
        //array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "THEILtrab")); //% Theil dos rendimentos do trabalho  

        $block_table_trabalho->setData("titulo", "");
        $block_table_trabalho->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_trabalho->setData("caption", "Ocupação da população de 18 anos ou mais");
        $block_table_trabalho->setData("titulo1", "Nível educacional dos ocupados");
        $block_table_trabalho->setData("titulo2", "Rendimento médio");
        $block_table_trabalho->setData("t", "");
        $block_table_trabalho->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_trabalho->setData("ano1", $variaveis[0][0]["label_ano_referencia"]); // Ano 1 (1991)
        $block_table_trabalho->setData("ano2", $variaveis[0][1]["label_ano_referencia"]); // Ano 2 (2000)
        $block_table_trabalho->setData("ano3", $variaveis[0][2]["label_ano_referencia"]); // Ano 3 (2010)

        for ($i = 1; $i <= count($variaveis); $i++) {
            $block_table_trabalho->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
            //$block_table_trabalho->setData("v$i"."_a1", $variaveis[$i-1][0]["valor"]); // Ano 1 (1991)
            $block_table_trabalho->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
            $block_table_trabalho->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
        }
    }
    
    static public function generateTRABALHO2($block_trabalho) {

        $p_agro = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_AGRO");  //P_AGRO 
        $p_extr = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_EXTR");  //P_EXTR
        $p_transf = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_TRANSF");  //P_TRANSF
        $p_constr = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_CONSTR"); //P_CONSTR
        $p_siup = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_SIUP");  //P_SIUP
        $p_com = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_COM");  //P_COM
        $p_serv = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "P_SERV");  //P_SERV
        
        $block_trabalho->setData("titulo", "");
        $block_trabalho->setData("canvasContent", "");
        $block_trabalho->setData("subtitulo", "");
        $block_trabalho->setData("info", "");

        $str2 = "Em 2010, das pessoas ocupadas na faixa etária de 18 anos ou mais, [p_agro_10]%   trabalhavam no setor agropecuário,  [p_extr_10]%
            na indústria extrativa, [p_transf_10]%  na indústria de transformação, [p_constr_10]%  no setor de  construção, [p_siup_10]%   nos setores de utilidade pública, [p_com_10]%
            no comércio e [p_serv_10]%  no setor de serviços. ";
        $texto2 = new Texto($str2);
        
        $texto2->replaceTags("p_agro_10", number_format($p_agro[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("p_extr_10", number_format($p_extr[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("p_transf_10", number_format($p_transf[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("p_constr_10", number_format($p_constr[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("p_siup_10", number_format($p_siup[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("p_com_10", number_format($p_com[2]["valor"], 2, ",", "."));
        $texto2->replaceTags("p_serv_10", number_format($p_serv[2]["valor"], 2, ",", "."));
        
        $block_trabalho->setData("text2", $texto2->getTexto());
        $block_trabalho->setData("text1", "");

        
    }

    static public function generateHABITACAO($block_habitacao) {

        if (TextBuilder::$print){ 
            $block_habitacao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_habitacao->setData("quebra", "");
        
        $block_habitacao->setData("fonte", TextBuilder::$fontePerfil);
        $block_habitacao->setData("titulo", "Habitação");
        $block_habitacao->setData("subtitulo", "");
        $block_habitacao->setData("info", "");
        $block_habitacao->setData("tableContent", "");

    }

    static public function generateIDH_table_habitacao($block_table_habitacao) {

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_AGUA")); //água encanada
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_LUZ")); //energia elétrica
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_LIXO")); //coleta de lixo*

        $block_table_habitacao->setData("titulo", "");
        $block_table_habitacao->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_habitacao->setData("caption", "Indicadores de Habitação");
        $block_table_habitacao->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_habitacao->setData("ano1", $variaveis[0][0]["label_ano_referencia"]); // Ano 1 (1991)
        $block_table_habitacao->setData("ano2", $variaveis[0][1]["label_ano_referencia"]); // Ano 2 (2000)
        $block_table_habitacao->setData("ano3", $variaveis[0][2]["label_ano_referencia"]); // Ano 3 (2010)

        for ($i = 1; $i <= count($variaveis); $i++) {
            
            if ($i == 3) {
                $block_table_habitacao->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_habitacao->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_habitacao->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_habitacao->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else{            
                $block_table_habitacao->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_habitacao->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_habitacao->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_habitacao->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
        }
        
         if (TextBuilder::$print){ 
            $block_table_habitacao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_table_habitacao->setData("quebra", "");

    }

    static public function generateVULNERABILIDADE($block_vulnerabilidade) {

        if (TextBuilder::$print){ 
            $block_vulnerabilidade->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_vulnerabilidade->setData("quebra", "");
        
        $block_vulnerabilidade->setData("fonte", TextBuilder::$fontePerfil);
        $block_vulnerabilidade->setData("titulo", "Vulnerabilidade social");
        $block_vulnerabilidade->setData("subtitulo", "");
        $block_vulnerabilidade->setData("info", "");

        $block_vulnerabilidade->setData("tableContent", "");
    }

    static public function generateIDH_table_vulnerabilidade($block_table_vulnerabilidade) {

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "MORT1")); //Mortalidade infantil
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FORA4A5")); //Percentual de pessoas de 4 a 5 anos de idade fora da escola
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FORA6A14")); //Percentual de pessoas de 6 a 14 anos de idade fora da escola
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_NESTUDA_NTRAB_MMEIO")); //Percentual de pessoas de 15 a 24 anos de idade que não estuda e não trabalha e cuja renda per capita <½  salário mínimo
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_M10A14CF")); //Percentual de mulheres de 10 a 14 anos de idade que tiveram filhos
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_M15A17CF")); //Percentual de mulheres de 15 a 17 anos de idade que tiveram filhos
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ATIV1014")); //Taxa de atividade de crianças e jovens que possuem entre 10 e 14 anos de idade
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_MULCHEFEFIF014")); //Percentual de mães chefes de família sem fundamental completo com filhos menores de 15 anos 
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_RMAXIDOSO")); //Percentual de pessoas em domicílios com renda per capita < ½ salário mínimo e cuja principal renda é de pessoa com 65 anos ou mais
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PINDCRI")); //Percentual de crianças que vivem em extrema pobreza, ou seja, em domicílios com renda per capita abaixo de R% 70,00.
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PPOB")); //#XXPercentual de pessoas em domicílios com renda per capita inferior a R$ 225,00 (1/2 salário mínimo em agosto/2010)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUNDIN18MINF")); //Percentual de pessoas de 18 anos ou mais sem fundamental completo e em ocupação informal
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "AGUA_ESGOTO")); //Percentual de pessoas em domicílios cujo abastecimento de água não seja por rede geral ou esgotamento sanitário não realizado por rede coletora de esgoto ou fossa séptica

        $block_table_vulnerabilidade->setData("titulo", "Crianças e Jovens");
        $block_table_vulnerabilidade->setData("titulo1", "Família");
        $block_table_vulnerabilidade->setData("titulo2", "Trabalho e Renda");
        $block_table_vulnerabilidade->setData("titulo3", "Condição de Moradia");
        $block_table_vulnerabilidade->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_vulnerabilidade->setData("t", "");
        $block_table_vulnerabilidade->setData("caption", "Vulnerabilidade Social");
        $block_table_vulnerabilidade->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_vulnerabilidade->setData("ano1", $variaveis[0][0]["label_ano_referencia"]); // Ano 1 (1991)
        $block_table_vulnerabilidade->setData("ano2", $variaveis[0][1]["label_ano_referencia"]); // Ano 2 (2000)
        $block_table_vulnerabilidade->setData("ano3", $variaveis[0][2]["label_ano_referencia"]); // Ano 3 (2010)

        for ($i = 1; $i <= count($variaveis); $i++) {
            if ($i == 1) {
                $block_table_vulnerabilidade->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_vulnerabilidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_vulnerabilidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_vulnerabilidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else if ($i == 2) {
                $block_table_vulnerabilidade->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                
                if ($variaveis[$i - 1][0] == 0)
                   $block_table_vulnerabilidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                else
                   $block_table_vulnerabilidade->setData("v$i" . "_a1", "-"); // Ano 1 (1991) 
                
                $block_table_vulnerabilidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_vulnerabilidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else if ($i == 4) {
                $block_table_vulnerabilidade->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_vulnerabilidade->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                
                if ($variaveis[$i - 1][0] == 0)
                    $block_table_vulnerabilidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                else
                    $block_table_vulnerabilidade->setData("v$i" . "_a1", "-");
                
                $block_table_vulnerabilidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_vulnerabilidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else if ($i == 7) {
                $block_table_vulnerabilidade->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
                
                if ($variaveis[$i - 1][0] == 0)
                    $block_table_vulnerabilidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                else
                    $block_table_vulnerabilidade->setData("v$i" . "_a1", "-"); // Ano 1 (1991)
                
                $block_table_vulnerabilidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_vulnerabilidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else if ($i == 9) {
                $block_table_vulnerabilidade->setData("v$i", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_vulnerabilidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_vulnerabilidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_vulnerabilidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else if ($i == 10){
                $block_table_vulnerabilidade->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_vulnerabilidade->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_vulnerabilidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_vulnerabilidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_vulnerabilidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else if ($i == 11){
                $block_table_vulnerabilidade->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_vulnerabilidade->setData("v".$i."_tt", $variaveis[$i - 1][0]["nome_perfil"]);
                $block_table_vulnerabilidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_vulnerabilidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_vulnerabilidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else if ($i == 12){
                $block_table_vulnerabilidade->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                
                if ($variaveis[$i - 1][0] == 0)
                    $block_table_vulnerabilidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                else
                    $block_table_vulnerabilidade->setData("v$i" . "_a1", "-"); // Ano 1 (1991)
                
                $block_table_vulnerabilidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_vulnerabilidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }else{
                $block_table_vulnerabilidade->setData("v$i", $variaveis[$i - 1][0]["nomecurto"]);
                $block_table_vulnerabilidade->setData("v$i" . "_a1", number_format($variaveis[$i - 1][0]["valor"], 2, ",", ".")); // Ano 1 (1991)
                $block_table_vulnerabilidade->setData("v$i" . "_a2", number_format($variaveis[$i - 1][1]["valor"], 2, ",", ".")); // Ano 2 (2000)
                $block_table_vulnerabilidade->setData("v$i" . "_a3", number_format($variaveis[$i - 1][2]["valor"], 2, ",", ".")); // Ano 3 (2010)
            }
        }
    }

}

?>

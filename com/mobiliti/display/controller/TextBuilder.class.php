<?php

require_once BASE_ROOT . 'config/config_path.php';
require_once BASE_ROOT . 'config/config_gerais.php';
require_once MOBILITI_PACKAGE . 'display/controller/texto/Texto.class.php';
require_once MOBILITI_PACKAGE . 'consulta/bd.class.php';
require_once MOBILITI_PACKAGE . 'display/controller/chart/chartsPerfil.php';
require_once MOBILITI_PACKAGE . 'display/controller/Formulas.class.php';

//define("PATH_DIRETORIO", $path_dir);
/**
 * Description of GerenateTexts
 *
 * @author Andre Castro (versão 2)
 */

class TextBuilder {

    static public $idMunicipio = 0;
    static public $nomeMunicipio = "";
    static public $ufMunicipio = "";
    static public $bd;    
    static public $print;
    static public $lang = "pt";
    
    //Variável de configuração da Fonte de Dados do perfil
    static public $fontePerfil = "Fonte: Pnud, Ipea e FJP"; //@#Menos template 5

    static public function generateIDH_componente($block_componente) {

        if (TextBuilder::$print){ 
            $block_componente->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_componente->setData("quebra", "");
        
        $block_componente->setData("fonte", TextBuilder::$fontePerfil);
        $block_componente->setData("titulo", "IDHM"); //@Translate
        $block_componente->setData("subtitulo", "Componentes"); //@Translate
        $block_componente->setData("canvasContent", getChartDesenvolvimentoHumano(TextBuilder::$idMunicipio, TextBuilder::$lang));        
        $block_componente->setData("info", "");

        $idhm = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM"); //IDHM
        $idhm_r = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM_R"); //IDHM_R
        $idhm_l = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM_L"); //IDHM_L
        $idhm_e = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM_E"); //IDHM_E
        
        //@Translate
        $str = "O Índice de Desenvolvimento Humano Municipal (IDHM) de [municipio] é [idh], em [2010].
            O município está situado na faixa de Desenvolvimento Humano [Faixa_DH].            
            Entre 2000 e 2010, a dimensão que mais cresceu em termos absolutos foi [Dimensao2000a2010].                        
            Entre 1991 e 2000, a dimensão que mais cresceu em termos absolutos foi [Dimensao1991a2000].
            ";

        $texto = new Texto($str);
        $texto->replaceTags("2010", Formulas::getLabelAno2010($idhm));
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto->replaceTags("idh", Formulas::getIDH2010($idhm));
        
        $texto->replaceTags("Faixa_DH", Formulas::getSituacaoIDH($idhm, TextBuilder::$lang));

        $texto->replaceTags("Dimensao2000a2010", Formulas::getDimensao(TextBuilder::$lang, $idhm_r, $idhm_l, $idhm_e, array("faixa0010" => true, "faixa9100" => false)));
        $texto->replaceTags("Dimensao1991a2000", Formulas::getDimensao(TextBuilder::$lang, $idhm_r, $idhm_l, $idhm_e, array("faixa0010" => false, "faixa9100" => true)));

        $block_componente->setData("text", $texto->getTexto());
    }

    static public function generateIDH_table_componente($block_table_componente) {

        $variaveis = array();

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

        $block_table_componente->setData("titulo", "IDHM e componentes");//@Translate
        $block_table_componente->setData("caption", "Índice de Desenvolvimento Humano Municipal e seus componentes");//@Translate
        $block_table_componente->setData("fonte", TextBuilder::$fontePerfil);        
        $block_table_componente->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);

        Formulas::printTableComponente($block_table_componente, $variaveis);
    }

    static public function generateIDH_evolucao($block_evolucao) {

        $idhm = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM"); //IDHM
        $idhm_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "IDHM"); //IDHM do Estado
        $idhm_brasil = TextBuilder::getVariaveis_Brasil(TextBuilder::$idMunicipio, "IDHM"); //IDHM do Brasil

        $block_evolucao->setData("subtitulo", "Evolução");//@Translate
        $block_evolucao->setData("fonte", TextBuilder::$fontePerfil);
        //----------------------------------------------------------------------------------------
        //Evolução entre os anos de 2000 e 2010
        $block_evolucao->setData("info1", "");
        
        //@Translate
        $str1 = "<b>Entre 2000 e 2010</b><br>
            O IDHM passou de [IDHM2000] em 2000 para [IDHM2010] em 2010 - uma taxa de crescimento de [Tx_crescimento_0010]%.
            O hiato de desenvolvimento humano, ou seja, a distância entre o IDHM do município e o limite máximo do índice, que é 1,
            foi [reduzido_aumentado] em [reducao_hiato_0010]% entre 2000 e 2010.<br><br>";
        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        $texto1->replaceTags("IDHM2000", Formulas::getIDH2000($idhm));
        $texto1->replaceTags("IDHM2010", Formulas::getIDH2010($idhm));
        $texto1->replaceTags("Tx_crescimento_0010", Formulas::getTaxaCrescimento0010($idhm));

        //Cálculo do HIATO
        //$reducao_hiato_0010 = (($idhm[2]["valor"] - $idhm[1]["valor"]) / (1 - $idhm[1]["valor"])) * 100;
        $texto1->replaceTags("reducao_hiato_0010", Formulas::getReducaoHiato0010($idhm));

        if (Formulas::getReducaoHiato0010puro($idhm) >= 0)
            $texto1->replaceTags("reduzido_aumentado", "reduzido");//@Translate
        else
            $texto1->replaceTags("reduzido_aumentado", "aumentado");//@Translate

        $block_evolucao->setData("text1", $texto1->getTexto());

        //----------------------------------------------------------------------------------------
        //Evolução entre os anos de 1991 e 2000
        $block_evolucao->setData("info2", "");
        
        //@Translate
        $str2 = "<b>Entre 1991 e 2000</b><br>
            O IDHM passou de [IDHM1991] em 1991 para [IDHM2000] em 2000 - uma taxa de crescimento de [Tx_crescimento_9100]%.
            O hiato de desenvolvimento humano, ou seja, a distância entre o IDHM do município e o limite máximo do índice, que é 1,
            foi [reduzido_aumentado] em [reducao_hiato_9100]% entre 1991 e 2000.<br><br>";
        
        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        $texto2->replaceTags("IDHM1991", Formulas::getIDH1991($idhm));
        $texto2->replaceTags("IDHM2000", Formulas::getIDH2000($idhm));
        $texto2->replaceTags("Tx_crescimento_9100", Formulas::getTaxaCrescimento9100($idhm));

        //Cálculo do HIATO
        //$reducao_hiato_9100 = (($idhm[1]["valor"] - $idhm[0]["valor"]) / (1 - $idhm[0]["valor"])) * 100;
        $texto2->replaceTags("reducao_hiato_9100", Formulas::getReducaoHiato9100($idhm));

        if (Formulas::getReducaoHiato9100puro($idhm) >= 0)
            $texto2->replaceTags("reduzido_aumentado", "reduzido");//@Translate
        else
            $texto2->replaceTags("reduzido_aumentado", "aumentado");//@Translate

        $block_evolucao->setData("text2", $texto2->getTexto());

        //----------------------------------------------------------------------------------------
        //Evolução entre os anos de 1991 e 2010
        $block_evolucao->setData("info3", "");
        
        //@Translate
        $str3 = "<b>Entre 1991 e 2010</b><br>
            [municipio] teve um incremento no seu IDHM de [Tx_crescimento_9110]% nas últimas duas décadas,
            [abaixo_acima] média de crescimento nacional ([tx_cresc_Brasil9110]%) e [abaixo_acima_uf] média de crescimento estadual ([tx_cresc_Estado9110]%).
            O hiato de desenvolvimento humano, ou seja, a distância entre o IDHM do município e o limite máximo do índice, que é 1,
            foi [reduzido_aumentado] em [reducao_hiato_9110]% entre 1991 e 2010.";
        
        $texto3 = new Texto($str3);
        $texto3->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        //Taxa de Crescimento
        $tx_cresc_9110 = Formulas::getTaxaCrescimento9110($idhm);
        $texto3->replaceTags("Tx_crescimento_9110", $tx_cresc_9110);

        //----------------------------------------
        //Taxa de Crescimento em relação ao BRASIL
        $tx_cresc_Brasil9110 = Formulas::getTaxaCrescimento9110BRASIL($idhm_brasil);
        
        if ($tx_cresc_Brasil9110 < $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Brasil9110", abs($tx_cresc_Brasil9110));
            $texto3->replaceTags("abaixo_acima", "acima da");//@Translate
        }else if ($tx_cresc_Brasil9110 == $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Brasil9110", abs($tx_cresc_Brasil9110));
            $texto3->replaceTags("abaixo_acima", "igual à");//@Translate
        }else if ($tx_cresc_Brasil9110 > $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Brasil9110", abs($tx_cresc_Brasil9110));
            $texto3->replaceTags("abaixo_acima", "abaixo da");//@Translate
        }            

        //----------------------------------------
        //Taxa de Crescimento em relação ao ESTADO
        $tx_cresc_Estado9110 = Formulas::getTaxaCrescimento9110ESTADO($idhm_uf);
        
        if ($tx_cresc_Estado9110 < $tx_cresc_9110){
            $texto3->replaceTags("abaixo_acima_uf", "acima da");//@Translate
            $texto3->replaceTags("tx_cresc_Estado9110", abs($tx_cresc_Estado9110));
        }  
        else if ($tx_cresc_Estado9110 == $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Estado9110", abs($tx_cresc_Estado9110));
            $texto3->replaceTags("abaixo_acima_uf", "igual à");//@Translate
        }
        else if ($tx_cresc_Estado9110 > $tx_cresc_9110){
            $texto3->replaceTags("abaixo_acima_uf", "abaixo da");//@Translate
            $texto3->replaceTags("tx_cresc_Estado9110", abs($tx_cresc_Estado9110));
        }
        
        //Cálculo do HIATO
        //$reducao_hiato_9110 = (($idhm[2]["valor"] - $idhm[0]["valor"]) / (1 - $idhm[0]["valor"])) * 100;
        $texto3->replaceTags("reducao_hiato_9110", Formulas::getReducaoHiato9110($idhm));

        if (Formulas::getReducaoHiato9110puro($idhm) >= 0)
            $texto3->replaceTags("reduzido_aumentado", "reduzido");//@Translate
        else
            $texto3->replaceTags("reduzido_aumentado", "aumentado");//@Translate

        $block_evolucao->setData("text3", $texto3->getTexto());
        
        if (TextBuilder::$print){ 
            $block_evolucao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_evolucao->setData("quebra", "");

        $block_evolucao->setData("canvasContent", getChartEvolucao(TextBuilder::$idMunicipio, TextBuilder::$lang));
    }
    
    static public function generateIDH_table_taxa_hiato($block_table_taxa_hiato) {
        
        $idhm = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM"); //IDHM

        $block_table_taxa_hiato->setData("titulo", "");
        $block_table_taxa_hiato->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_taxa_hiato->setData("ano1", "Taxa de Crescimento");//@Translate
        $block_table_taxa_hiato->setData("ano2", "Hiato de Desenvolvimento");//@Translate

        $taxa9100 = Formulas::getTaxa9100($idhm);
        $taxa0010 = Formulas::getTaxa0010($idhm);        
        $taxa9110 = Formulas::getTaxa9110($idhm);

        $reducao_hiato_0010 = Formulas::getReducaoHiato0010($idhm);
        $reducao_hiato_9100 = Formulas::getReducaoHiato9100($idhm);
        $reducao_hiato_9110 = Formulas::getReducaoHiato9110($idhm);

        //TODO: FAZER O MAIS E MENOS DA TAXA E HIATO UTILIZANDO IMAGENS
        
            $block_table_taxa_hiato->setData("v1", "Entre 1991 e 2000");//@Translate
            
                Formulas::printTableTaxaHiatoEntre9100($block_table_taxa_hiato, $taxa9100, $reducao_hiato_9100);
                
            $block_table_taxa_hiato->setData("v2", "Entre 2000 e 2010");//@Translate
            
                Formulas::printTableTaxaHiatoEntre0010($block_table_taxa_hiato, $taxa0010, $reducao_hiato_0010);
            
            $block_table_taxa_hiato->setData("v3", "Entre 1991 e 2010");//@Translate
            
                Formulas::printTableTaxaHiatoEntre9110($block_table_taxa_hiato, $taxa9110, $reducao_hiato_9110);

    }

    static public function generateIDH_ranking($block_ranking) {

        $ranking = TextBuilder::getRanking(); //IDHM
        $uf = TextBuilder::getUf(TextBuilder::$idMunicipio); //IDHM
        $ranking_uf = TextBuilder::getRankingUf($uf[0]["id"]); //IDHM

        $block_ranking->setData("subtitulo", "Ranking");//@Translate
        $block_ranking->setData("info", "");
        
        //@Translate
        $str = "[municipio] ocupa a [ranking_municipio_IDHM]ª posição, em 2010, em relação aos 5.565 municípios do Brasil, 
            sendo que [municipios_melhor_IDHM] ([municipios_melhor_IDHM_p]%) municípios estão em situação melhor e [municipios_pior_IDHM] ([municipios_pior_IDHM_p]%) municípios
            estão em situação igual ou pior.";
        
        if (TextBuilder::$idMunicipio != 735){
            //@Translate
            $str = $str . " Em relação aos [numero_municipios_estado] outros municípios de [estado_municipio], [municipio] ocupa a
            [ranking_estados_IDHM]ª posição, sendo que [municipios_melhor_IDHM_estado] ([municipios_melhor_IDHM_p_estado]%) municípios estão em situação melhor e [municipios_pior_IDHM_estado] ([municipios_pior_IDHM_p_estado]%) municípios
            estão em situação pior ou igual.";
        }   
         
        $texto = new Texto($str);

        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);       
        
        Formulas::getRanking($block_ranking, $texto, $ranking, $uf, $ranking_uf);
        
    }

    // OUTRA CATEGORIA
    static public function generateDEMOGRAFIA_SAUDE_populacao($block_populacao) {

        $pesotot = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESOTOT"); //PESOTOT
        $pesourb = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESOURB"); //PESORUR
        $pesotot_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "PESOTOT"); //PESOTOT do Estado
        $pesotot_brasil = TextBuilder::getVariaveis_Brasil(TextBuilder::$idMunicipio, "PESOTOT"); //PESOTOT do Brasil        
        //$pesorur = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESORUR"); //PESORUR        
        
        if (TextBuilder::$print){ 
            $block_populacao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_populacao->setData("quebra", "");
        
        $block_populacao->setData("fonte", TextBuilder::$fontePerfil);
        $block_populacao->setData("titulo", "Demografia e Saúde");//@Translate
        $block_populacao->setData("subtitulo", "População");//@Translate
        $block_populacao->setData("info", "");
        
        //@Translate
        $str = "Entre 2000 e 2010, a população de [municipio] teve uma taxa média de crescimento anual
            de [tx_cres_pop_0010]%. Na década anterior, de 1991 a 2000, a taxa média de crescimento anual
            foi de [tx_cres_pop_9100]%. No Estado, estas taxas foram de [tx_cresc_pop_estado_0010]% entre 2000 e 2010 e 
            [tx_cresc_pop_estado_9100]% entre 1991 e 2000. No país, foram de [tx_cresc_pop_pais_0010]% entre 2000 e 2010 e [tx_cresc_pop_pais_9100]% entre 1991 e 2000.
            Nas últimas duas décadas, a taxa de urbanização cresceu [tx_urbanizacao]%.";

        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto->replaceTags("tx_cres_pop_0010", Formulas::getTaxaCrescimentoPop0010($pesotot)); //(((PESOTOT 2010 / PESOTOT 2000)^(1/10))-1)*100
        $texto->replaceTags("tx_cres_pop_9100", Formulas::getTaxaCrescimentoPop9100($pesotot));  //(((PESOTOT 2000 / PESOTOT 1991)^(1/9))-1)*100

        $texto->replaceTags("tx_cresc_pop_estado_0010", Formulas::getTaxaCrescimentoPop0010ESTADO($pesotot_uf));
        $texto->replaceTags("tx_cresc_pop_estado_9100", Formulas::getTaxaCrescimentoPop9100ESTADO($pesotot_uf));

        $texto->replaceTags("tx_cresc_pop_pais_0010", Formulas::getTaxaCrescimentoPop0010BRASIL($pesotot_brasil));
        $texto->replaceTags("tx_cresc_pop_pais_9100", Formulas::getTaxaCrescimentoPop9100BRASIL($pesotot_brasil));
        
        $texto->replaceTags("tx_urbanizacao", Formulas::getTaxaUrbanizacao($pesourb, $pesotot));
                
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

        $block_table_populacao->setData("titulo", "População");//@Translate
        $block_table_populacao->setData("caption", "População Total, por Gênero, Rural/Urbana e Taxa de Urbanização");//@Translate
        $block_table_populacao->setData("fonte", TextBuilder::$fontePerfil);        
        $block_table_populacao->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_populacao->setData("coluna1", "População");//@Translate
        $block_table_populacao->setData("coluna2", "% do Total");//@Translate
        
        $stringTaxaUrbanizacao = "Taxa de Urbanização";//@Translate
        Formulas::printTablePopulacao($block_table_populacao, $variaveis, $stringTaxaUrbanizacao);
        
    }

    static public function generateDEMOGRAFIA_SAUDE_etaria($block_etaria) {

        $tenv = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ENV"); //T_ENV
        $rd = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "RAZDEP"); //T_ENV

        $block_etaria->setData("subtitulo", "Estrutura Etária");//@Translate
        $block_etaria->setData("info", "");
                 
        //@Translate
        $str = "Entre 2000 e 2010, a razão de dependência de [municipio] passou de [rz_dependencia00]%
            para [rz_dependencia10]% e a taxa de envelhecimento evoluiu de [indice_envelhecimento00]% para [indice_envelhecimento10]%.
            Entre 1991 e 2000, a razão de dependência foi de [rz_dependencia91]% para [rz_dependencia00]%,
            enquanto a taxa de envelhecimento evoluiu de [indice_envelhecimento91]% para [indice_envelhecimento00]%.";
        
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto->replaceTags("indice_envelhecimento91", Formulas::getIndiceEnvelhecimento91($tenv));
        $texto->replaceTags("indice_envelhecimento00", Formulas::getIndiceEnvelhecimento00($tenv));
        $texto->replaceTags("indice_envelhecimento10", Formulas::getIndiceEnvelhecimento10($tenv));
        
        $texto->replaceTags("rz_dependencia91", Formulas::getRazaoDependencia91($rd));
        $texto->replaceTags("rz_dependencia00", Formulas::getRazaoDependencia00($rd));
        $texto->replaceTags("rz_dependencia10", Formulas::getRazaoDependencia10($rd));
        
        $block_etaria->setData("text", $texto->getTexto());
        
        //@Translate
        $block_etaria->setData("block_box1", "<b>O que é razão de<br> dependência?</b><br>
            Percentual da população <br>
            de menos de 15 anos e <br>
            da população de 65 anos <br>
            e mais (população <br>
            dependente) em relação <br>
            à população de 15 <br>
            a 64 anos (população <br>
            potencialmente ativa).");
        $block_etaria->setData("block_box2","<b>O que é taxa de<br> envelhecimento?</b><br>
            Razão entre a população <br>
            de 65 anos ou mais <br>
            de idade em relação <br>
            à população total.");
        
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

        $block_table_etaria->setData("titulo", "Estrutura Etária");//@Translate        
        $block_table_etaria->setData("caption", "Estrutura Etária da População");//@Translate
        $block_table_etaria->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_etaria->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        $block_table_etaria->setData("coluna1", "População");//@Translate
        $block_table_etaria->setData("coluna2", "% do Total");//@Translate
        
        $stringMenos15anos = "Menos de 15 anos"; //@Translate //Não é uma variável específica
        $string15a64anos = "15 a 64 anos"; //@Translate //Não é uma variável específica
        Formulas::printTableEtaria($block_table_etaria, $variaveis, $pesotot, $stringMenos15anos, $string15a64anos);
        
        if (TextBuilder::$print){ 
            $block_table_etaria->setData("quebra1", "<div style='page-break-after: always'></div>");
        }else
            $block_table_etaria->setData("quebra1", "");
        
        $block_table_etaria->setData("canvasContent1", getChartPiramideEtaria1(TextBuilder::$idMunicipio, TextBuilder::$lang));
        $block_table_etaria->setData("canvasContent2", getChartPiramideEtaria2(TextBuilder::$idMunicipio, TextBuilder::$lang));    
        $block_table_etaria->setData("canvasContent3", getChartPiramideEtaria3(TextBuilder::$idMunicipio, TextBuilder::$lang));
        
    }

    static public function generateDEMOGRAFIA_SAUDE_longevidade1($block_longevidade) {

        $mort1 = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "MORT1"); //MORT1
        $mort1_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "MORT1"); //MORT1 do Estado
        $mort1_brasil = TextBuilder::getVariaveis_Brasil(TextBuilder::$idMunicipio, "MORT1"); //MORT1 do Brasil
                
        if (TextBuilder::$print){ 
            $block_longevidade->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_longevidade->setData("quebra", "");
        
        $block_longevidade->setData("subtitulo", "Longevidade, mortalidade e fecundidade");//@Translate
        $block_longevidade->setData("info", "");
        
        //@Translate
        $str1 = "A mortalidade infantil (mortalidade de crianças com menos de um ano) em [municipio] [mort1_diminuiu_aumentou] [reducao_mortalinfantil0010]%,
            passando de [mortinfantil00] por mil nascidos vivos em 2000 para [mortinfantil10]  por mil nascidos vivos em 2010.
            Segundo os Objetivos de Desenvolvimento do Milênio das Nações Unidas, a mortalidade infantil para o Brasil deve estar abaixo de 17,9 óbitos por mil em 2015.
            Em 2010, as taxas de mortalidade infantil do estado e do país eram [mortinfantil10_Estado] e [mortinfantil10_Brasil] por mil nascidos vivos, respectivamente.";
        
        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        //TODO: Tem que ser sempre positivo
        $texto1->replaceTags("reducao_mortalinfantil0010", Formulas::getReducaoMortalidadeInfantil0010($mort1));
        $texto1->replaceTags("mortinfantil00", Formulas::getMortalidadeInfantil00($mort1));
        $texto1->replaceTags("mortinfantil10", Formulas::getMortalidadeInfantil10($mort1));
        $texto1->replaceTags("mortinfantil10_Estado", Formulas::getMortalidadeInfantil10ESTADO($mort1_uf));
        $texto1->replaceTags("mortinfantil10_Brasil", Formulas::getMortalidadeInfantil10BRASIL($mort1_brasil));

        //TODO: Tirar o igual da comparação (feito pq a base não é oficial e há replicações
        if (Formulas::getMortalidadeInfantil10puro($mort1) <= Formulas::getMortalidadeInfantil00puro($mort1))
            $texto1->replaceTags("mort1_diminuiu_aumentou", "reduziu");//@Translate
        else if (Formulas::getMortalidadeInfantil10puro($mort1) > Formulas::getMortalidadeInfantil00puro($mort1))
            $texto1->replaceTags("mort1_diminuiu_aumentou", "aumentou");//@Translate
        
        $block_longevidade->setData("text2", "");
        $block_longevidade->setData("tableContent", "");

        $block_longevidade->setData("text1", $texto1->getTexto());

    }

    static public function generateIDH_table_longevidade($block_table_longevidade) {

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "ESPVIDA")); //Esperança de vida ao nascer (anos)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "MORT1")); //Mortalidade até 1 ano de idade (por mil nascidos vivos)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "MORT5")); //Mortalidade até 5 anos de idade (por mil nascidos vivos)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "FECTOT")); //Taxa de fecundidade total (filhos por mulher) 

        $block_table_longevidade->setData("titulo", "");
        $block_table_longevidade->setData("caption", "Longevidade, Mortalidade e Fecundidade");//@Translate
        $block_table_longevidade->setData("fonte", TextBuilder::$fontePerfil);        
        $block_table_longevidade->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        
        Formulas::printTableLongevidade($block_table_longevidade, $variaveis);
        
    }
    
       static public function generateDEMOGRAFIA_SAUDE_longevidade2($block_longevidade) {

        $espvida = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "ESPVIDA"); //ESPVIDA
        $espvida_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "ESPVIDA"); //ESPVIDA do Estado
        $espvida_brasil = TextBuilder::getVariaveis_Brasil(TextBuilder::$idMunicipio, "ESPVIDA"); //ESPVIDA do Brasil

        $block_longevidade->setData("subtitulo", "");
        $block_longevidade->setData("info", "");

        //@Translate
        $str2 = "A esperança de vida ao nascer é o indicador utilizado para compor a dimensão Longevidade do Índice de Desenvolvimento Humano Municipal (IDHM).
            Em [municipio], a esperança de vida ao nascer aumentou [aumento_esp_nascer0010] anos nas últimas duas décadas, passando de [esp_nascer91] anos em 1991 para [esp_nascer00] anos em 2000,
            e para [esp_nascer10] anos em 2010. Em 2010, a esperança de vida ao nascer média para o estado é de [esp_nascer10_estado] anos e, para o país,
            de [esp_nascer10_pais] anos.";
        
        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto2->replaceTags("aumento_esp_nascer0010", Formulas::getAumentoEsperancaVidaNascer0010($espvida));
        $texto2->replaceTags("esp_nascer91", Formulas::getEsperancaVidaNascer91($espvida));
        $texto2->replaceTags("esp_nascer00", Formulas::getEsperancaVidaNascer00($espvida));
        $texto2->replaceTags("esp_nascer10", Formulas::getEsperancaVidaNascer10($espvida));
        $texto2->replaceTags("esp_nascer10_estado", Formulas::getEsperancaVidaNascer10ESTADO($espvida_uf));
        $texto2->replaceTags("esp_nascer10_pais", Formulas::getEsperancaVidaNascer10BRASIL($espvida_brasil));
        $block_longevidade->setData("text2", $texto2->getTexto());
        
        $block_longevidade->setData("text1", "");
        $block_longevidade->setData("tableContent", "");
        
        if (TextBuilder::$print){ 
            $block_longevidade->setData("quebra", "");
        }else
            $block_longevidade->setData("quebra", "");
    }

    static public function generateEDUCACAO_nivel_educacional($block_nivel_educacional) {
       
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
        $block_nivel_educacional->setData("titulo", "Educação");//@Translate
        $block_nivel_educacional->setData("subtitulo", "Crianças e Jovens");//@Translate
        $block_nivel_educacional->setData("info", "");
        
        //@Translate
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

        $texto1->replaceTags("cresc_4-6esc_0010", Formulas::getCrescimento4a6Esc0010($t_freq4a6));
        $texto1->replaceTags("cresc_4-6esc_9100", Formulas::getCrescimento4a6Esc9100($t_freq4a6));

        $texto1->replaceTags("cresc_12-14esc_0010", Formulas::getCrescimento12a14Esc0010($t_fund12a14));
        $texto1->replaceTags("cresc_12-14esc_9100", Formulas::getCrescimento12a14Esc9100($t_fund12a14));

        $texto1->replaceTags("cresc_16-18fund_0010", Formulas::getCrescimento16a18Fund0010($t_fund16a18));
        $texto1->replaceTags("cresc_16-18fund_9100", Formulas::getCrescimento16a18Fund9100($t_fund16a18));

        $texto1->replaceTags("cresc_19-21medio_0010", Formulas::getCrescimento19a21Medio0010($t_med19a21));
        $texto1->replaceTags("cresc_19-21medio_9100", Formulas::getCrescimento19a21Medio9100($t_med19a21));

        $block_nivel_educacional->setData("text1", $texto1->getTexto());        

        if (TextBuilder::$print){ 
            $block_nivel_educacional->setData("quebra4", "<div style=margin-top: 20px;'></div>");
        }else
            $block_nivel_educacional->setData("quebra4", "");
        
        $block_nivel_educacional->setData("canvasContent1", getChartFluxoEscolar(TextBuilder::$idMunicipio, TextBuilder::$lang));
        
        if (TextBuilder::$print){ 
            $block_nivel_educacional->setData("quebra1", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra1", "");
        
        $block_nivel_educacional->setData("canvasContent2", getChartFrequenciaEscolar(TextBuilder::$idMunicipio, TextBuilder::$lang));
        
        //@Translate
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
        $texto2->replaceTags("tx_fund_sematraso_10", Formulas::getTxFundSemAtraso10($t_atraso_0_fund, $t_flfund));
        $texto2->replaceTags("tx_fund_sematraso_00", Formulas::getTxFundSemAtraso00($t_atraso_0_fund, $t_flfund));
        $texto2->replaceTags("tx_fund_sematraso_91", Formulas::getTxFundSemAtraso91($t_atraso_0_fund, $t_flfund));

        $texto2->replaceTags("tx_medio_sematraso_10", Formulas::getTxMedioSemAtraso10($t_atraso_0_med, $t_flmed));
        $texto2->replaceTags("tx_medio_sematraso_00", Formulas::getTxMedioSemAtraso00($t_atraso_0_med, $t_flmed));
        $texto2->replaceTags("tx_medio_sematraso_91", Formulas::getTxMedioSemAtraso91($t_atraso_0_med, $t_flmed));
        
        $texto2->replaceTags("t_flsuper_10", Formulas::getTxFLSuper10($t_flsuper));
        $texto2->replaceTags("t_flsuper_00", Formulas::getTxFLSuper00($t_flsuper));
        $texto2->replaceTags("t_flsuper_91", Formulas::getTxFLSuper91($t_flsuper));       
                
        $texto2->replaceTags("p6a14", Formulas::getP6a14($t_freq6a14));
        $texto2->replaceTags("p15a17", Formulas::getP15a17($t_freq15a17));
        
        $block_nivel_educacional->setData("text2", $texto2->getTexto());
        
        if (TextBuilder::$print){ 
            $block_nivel_educacional->setData("quebra2", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra2", "");
        
        $block_nivel_educacional->setData("canvasContent3", getChartFrequenciaDe6a14(TextBuilder::$idMunicipio, TextBuilder::$lang));              
        $block_nivel_educacional->setData("canvasContent4", getChartFrequenciaDe15a17(TextBuilder::$idMunicipio, TextBuilder::$lang));
        $block_nivel_educacional->setData("canvasContent5", getChartFrequenciaDe18a24(TextBuilder::$idMunicipio, TextBuilder::$lang));
        
        if (TextBuilder::$print){ 
            $block_nivel_educacional->setData("quebra3", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra3", "");
        
    }

    static public function generateEDUCACAO_populacao_adulta($block_populacao_adulta) {

        $t_analf25m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ANALF18M"); //T_ANALF18M - Mudou variável
        $t_fundin25m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_FUND18M");  //T_FUND18M  - Mudou variável
        $t_medin25m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_MED18M");  //T_MED18M  - Mudou variável
        
        $t_fundin25m_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "T_FUND18M");  //T_FUND18M - Mudou variável
        $t_medin25m_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "T_MED18M");  //T_MED18M - Mudou variável
        
        $e_anosesperados = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "E_ANOSESTUDO"); //E_ANOSESTUDO
        $e_anosesperados_uf = TextBuilder::getVariaveis_Uf(TextBuilder::$idMunicipio, "E_ANOSESTUDO");  //E_ANOSESTUDO
               
        $uf = TextBuilder::getUf(TextBuilder::$idMunicipio); //UF
        
        $block_populacao_adulta->setData("subtitulo", "População Adulta");//@Translate
        $block_populacao_adulta->setData("fonte", TextBuilder::$fontePerfil);
        $block_populacao_adulta->setData("info", "");
        
        //@Translate
        $str = "A escolaridade da população adulta é importante indicador de acesso a conhecimento e também compõe o IDHM Educação.
            <br><br>Em 2010, [25_fund_10]% da população de 18 anos ou mais de idade tinha completado o ensino fundamental e [25_medio_10]%
            o ensino médio. Em [estado_municipio], [25_fund_10_Estado]% e [25_medio_10_Estado]% respectivamente.
            Esse indicador carrega uma grande inércia, em função do peso das gerações mais antigas e de menos escolaridade.
            <br><br>A taxa de analfabetismo da população de 18 anos ou mais [diminuiu_aumentou] [25_analf_9110] nas últimas duas décadas.";
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto->replaceTags("estado_municipio", $uf[0]["nome"]);
        
        $texto->replaceTags("25_fund_10", Formulas::get25Fund10($t_fundin25m));
        $texto->replaceTags("25_medio_10", Formulas::get25Medio10($t_medin25m));
        $texto->replaceTags("25_fund_10_Estado", Formulas::get25Fund10ESTADO($t_fundin25m_uf));
        $texto->replaceTags("25_medio_10_Estado", Formulas::get25Medio10ESTADO($t_medin25m_uf));
        
        $dif_analf = Formulas::getDifAnalf($t_analf25m);
        if ($dif_analf > 0){
            $texto->replaceTags("diminuiu_aumentou", "aumentou");//@Translate
            $texto->replaceTags("25_analf_9110", number_format($dif_analf, 2, ",", ".") . "%");
        }else if ($dif_analf == 0) {
            $texto->replaceTags("diminuiu_aumentou", "se manteve");//@Translate
            $texto->replaceTags("25_analf_9110", "");
        }else if ($dif_analf < 0) {
            $texto->replaceTags("diminuiu_aumentou", "diminuiu");//@Translate
            $texto->replaceTags("25_analf_9110", number_format(abs($dif_analf), 2, ",", ".") . "%");
        }
        
        $block_populacao_adulta->setData("text", $texto->getTexto());
        $block_populacao_adulta->setData("canvasContent", getChartEscolaridadePopulacao(TextBuilder::$idMunicipio, TextBuilder::$lang));
        
        $block_populacao_adulta->setData("subtitulo2", "Anos Esperados de Estudo");//@Translate
        $block_populacao_adulta->setData("info2", "");
        
        //@Translate
        $str2 = "Os anos esperados de estudo indicam o número de anos que a criança que inicia 
        a vida escolar no ano de referência tende a completar.
        Em 2010, [municipio] tinha [e_anosestudo10] anos esperados de estudo, 
        em 2000 tinha [e_anosestudo00] anos e em 1991 [e_anosestudo91] anos. 
        Enquanto que  [estado_municipio], tinha [ufe_anosestudo10] anos esperados de estudo em 2010,
        [ufe_anosestudo00] anos em 2000 e [ufe_anosestudo91] anos em 1991.";
        
        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder::$nomeMunicipio);
        $texto2->replaceTags("estado_municipio", $uf[0]["nome"]);
        
        $texto2->replaceTags("e_anosestudo10", Formulas::getEAnosEstudo10($e_anosesperados));
        $texto2->replaceTags("e_anosestudo00", Formulas::getEAnosEstudo00($e_anosesperados));
        $texto2->replaceTags("e_anosestudo91", Formulas::getEAnosEstudo91($e_anosesperados));
        
        $texto2->replaceTags("ufe_anosestudo10", Formulas::getEAnosEstudo10ESTADO($e_anosesperados_uf));
        $texto2->replaceTags("ufe_anosestudo00", Formulas::getEAnosEstudo00ESTADO($e_anosesperados_uf));
        $texto2->replaceTags("ufe_anosestudo91", Formulas::getEAnosEstudo91ESTADO($e_anosesperados_uf));
        
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
        $block_renda->setData("titulo", "Renda");//@Translate
        $block_renda->setData("subtitulo", "");
        $block_renda->setData("info", "");
        
        //@Translate
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
        if (Formulas::getRenda10puro($rdpc) >= Formulas::getRenda91puro($rdpc))
            $texto->replaceTags("caiu_cresceu", "cresceu");//@Translate
        else if (Formulas::getRenda10puro($rdpc) < Formulas::getRenda91puro($rdpc))
            $texto->replaceTags("caiu_cresceu", "caiu");//@Translate

        $texto->replaceTags("tx_cresc_renda", Formulas::getTxCrescRenda($rdpc));
        $texto->replaceTags("renda91", Formulas::getRenda91($rdpc));
        $texto->replaceTags("renda00", Formulas::getRenda00($rdpc));
        $texto->replaceTags("renda10", Formulas::getRenda10($rdpc));
        $texto->replaceTags("tx_cresc_renda9100", Formulas::getTxCrescRenda9100($rdpc));
        $texto->replaceTags("tx_cresc_renda0010", Formulas::getTxCrescRenda0010($rdpc));

        $texto->replaceTags("tx_pobreza_91", Formulas::getTxPobreza91($pind));
        $texto->replaceTags("tx_pobreza_00", Formulas::getTxPobreza00($pind));
        $texto->replaceTags("tx_pobreza_10", Formulas::getTxPobreza10($pind));
        //$texto->replaceTags("red_extrema_pobreza", str_replace(".",",",number_format((( ($pind[0]["valor"] * $pop[0]["valor"]) - ($pind[2]["valor"] * $pop[2]["valor"]) ) / ($pind[0]["valor"] * $pop[0]["valor"])) * 100, 2)));

        if (Formulas::getGini10puro($gini) < Formulas::getGini91puro($gini))
            $texto->replaceTags("diminuiu_aumentou", "diminuiu");//@Translate
        else if (Formulas::getGini10puro($gini) == Formulas::getGini91puro($gini))
            $texto->replaceTags("diminuiu_aumentou", "se manteve");//@Translate
        else if (Formulas::getGini10puro($gini) > Formulas::getGini91puro($gini))
            $texto->replaceTags("diminuiu_aumentou", "aumentou");//@Translate

        $texto->replaceTags("gini_91", Formulas::getGini91($gini));
        $texto->replaceTags("gini_00", Formulas::getGini00($gini));
        $texto->replaceTags("gini_10", Formulas::getGini10($gini));

        $block_renda->setData("text", $texto->getTexto());
        //@Translate
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

    }

    static public function generateIDH_table_renda($block_table_renda) {

        $variaveis = array();
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "RDPC")); //Renda per capita média (R$ de 2010)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PIND")); //Proporção de extremamente pobres - total (%)
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PMPOB")); //Proporção de pobres 
        array_push($variaveis, TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "GINI")); //Índice de Gini

        $block_table_renda->setData("titulo", "");
        $block_table_renda->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_renda->setData("caption", "Renda, Pobreza e Desigualdade");//@Translate
        $block_table_renda->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        
        Formulas::printTableRenda($block_table_renda, $variaveis);          
        
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
        $block_table_renda2->setData("caption", "Porcentagem da Renda Apropriada por Estratos da População");//@Translate
        $block_table_renda2->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
                
        Formulas::printTableRenda2($block_table_renda2, $variaveis);

    }

    static public function generateTRABALHO1($block_trabalho) {

        $t_ativ18m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_ATIV18M"); //T_ATIV18M  
        $t_des18m = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "T_DES18M");  //T_DES18M 
        
        if (TextBuilder::$print){ 
            $block_trabalho->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_trabalho->setData("quebra", "");
        
        $block_trabalho->setData("fonte", TextBuilder::$fontePerfil);
        $block_trabalho->setData("titulo", "Trabalho");//@Translate
        $block_trabalho->setData("canvasContent", getChartTrabalho(TextBuilder::$idMunicipio, TextBuilder::$lang));
       
        $block_trabalho->setData("subtitulo", "");
        $block_trabalho->setData("info", "");

        //@Translate
        $str1 = "Entre 2000 e 2010, a <b>taxa de atividade</b> da população de 18 anos ou mais (ou seja, o percentual dessa população que era economicamente ativa)
            passou de [tx_ativ_18m_00]% em 2000 para [tx_ativ_18m_10]% em 2010. Ao mesmo tempo, sua <b>taxa de desocupação</b> (ou seja, o percentual da população economicamente ativa
            que estava desocupada) passou de [tx_des18m_00]% em 2000 para [tx_des18m_10]% em 2010.";
        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder::$nomeMunicipio);

        $texto1->replaceTags("tx_ativ_18m_00", Formulas::getTxAtiv18m00($t_ativ18m));
        $texto1->replaceTags("tx_ativ_18m_10", Formulas::getTxAtiv18m10($t_ativ18m));

        $texto1->replaceTags("tx_des18m_00", Formulas::getTxDes18m00($t_des18m));
        $texto1->replaceTags("tx_des18m_10", Formulas::getTxDes18m10($t_des18m));

        $block_trabalho->setData("text1", $texto1->getTexto());
        $block_trabalho->setData("text2", "");
        
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
        $block_table_trabalho->setData("caption", "Ocupação da população de 18 anos ou mais");//@Translate
        $block_table_trabalho->setData("titulo1", "Nível educacional dos ocupados");//@Translate
        $block_table_trabalho->setData("titulo2", "Rendimento médio");//@Translate
        $block_table_trabalho->setData("t", "");
        $block_table_trabalho->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        
        Formulas::printTableTrabalho($block_table_trabalho, $variaveis);
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

        //@Translate
        $str2 = "Em 2010, das pessoas ocupadas na faixa etária de 18 anos ou mais, [p_agro_10]%   trabalhavam no setor agropecuário,  [p_extr_10]%
            na indústria extrativa, [p_transf_10]%  na indústria de transformação, [p_constr_10]%  no setor de  construção, [p_siup_10]%   nos setores de utilidade pública, [p_com_10]%
            no comércio e [p_serv_10]%  no setor de serviços. ";
        $texto2 = new Texto($str2);
        
        $texto2->replaceTags("p_agro_10", Formulas::getPAgro10($p_agro));
        $texto2->replaceTags("p_extr_10", Formulas::getPExtr10($p_extr));
        $texto2->replaceTags("p_transf_10", Formulas::getPTransf10($p_transf));
        $texto2->replaceTags("p_constr_10", Formulas::getPConstr10($p_constr));
        $texto2->replaceTags("p_siup_10", Formulas::getPSiup10($p_siup));
        $texto2->replaceTags("p_com_10", Formulas::getPCom10($p_com));
        $texto2->replaceTags("p_serv_10", Formulas::getPServ10($p_serv));
        
        $block_trabalho->setData("text2", $texto2->getTexto());
        $block_trabalho->setData("text1", "");

        
    }

    static public function generateHABITACAO($block_habitacao) {

        if (TextBuilder::$print){ 
            $block_habitacao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_habitacao->setData("quebra", "");
        
        $block_habitacao->setData("fonte", TextBuilder::$fontePerfil);
        $block_habitacao->setData("titulo", "Habitação");//@Translate
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
        $block_table_habitacao->setData("caption", "Indicadores de Habitação");//@Translate
        $block_table_habitacao->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        
        $texto = " *Somente para população urbana";
        Formulas::printTableHabitacao($block_table_habitacao, $variaveis, $texto);
        
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
        $block_vulnerabilidade->setData("titulo", "Vulnerabilidade social");//@Translate
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

        $block_table_vulnerabilidade->setData("titulo", "Crianças e Jovens");//@Translate
        $block_table_vulnerabilidade->setData("titulo1", "Família");//@Translate
        $block_table_vulnerabilidade->setData("titulo2", "Trabalho e Renda");//@Translate
        $block_table_vulnerabilidade->setData("titulo3", "Condição de Moradia");//@Translate
        $block_table_vulnerabilidade->setData("fonte", TextBuilder::$fontePerfil);
        $block_table_vulnerabilidade->setData("t", "");
        $block_table_vulnerabilidade->setData("caption", "Vulnerabilidade Social");//@Translate
        $block_table_vulnerabilidade->setData("municipio", TextBuilder::$nomeMunicipio . " - " . TextBuilder::$ufMunicipio);
        
        Formulas::printTableVulnerabilidade($block_table_vulnerabilidade, $variaveis);
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
    
    static function getVariaveis_table($municipio, $variavel) {

        $SQL = "SELECT label_ano_referencia, lang_var.nomecurto,  lang_var.nome_perfil, lang_var.definicao, valor
                FROM valor_variavel_mun INNER JOIN variavel
                ON fk_variavel = variavel.id
                INNER JOIN ano_referencia
                ON ano_referencia.id = fk_ano_referencia
                INNER JOIN lang_var
                ON variavel.id = lang_var.fk_variavel
                WHERE fk_municipio = $municipio and sigla like '$variavel' and lang like '". TextBuilder::$lang ."'
                 ORDER BY label_ano_referencia";

        //echo $SQL . "<br><br>"; 
        return TextBuilder::$bd->ExecutarSQL($SQL, "getVariaveis_table");
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

}

?>

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

class TextBuilder_EN {

    static public $idMunicipio = 0;
    static public $nomeMunicipio = "";
    static public $ufMunicipio = "";
    static public $bd;    
    static public $print;
    static public $lang = "en";
    
    //Variável de configuração da Fonte de Dados do perfil
    static public $fontePerfil = "Source: UNDP, Ipea and FJP"; //@#Menos template 5

    static public function generateIDH_componente($block_componente) {

        if (TextBuilder_EN::$print){ 
            $block_componente->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_componente->setData("quebra", "");
        
        $block_componente->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_componente->setData("titulo", "MHDI"); //@Translate
        $block_componente->setData("subtitulo", "Components"); //@Translate
        $block_componente->setData("canvasContent", getChartDesenvolvimentoHumano(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));        
        $block_componente->setData("info", "");

        $idhm = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "IDHM"); //IDHM
        $idhm_r = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "IDHM_R"); //IDHM_R
        $idhm_l = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "IDHM_L"); //IDHM_L
        $idhm_e = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "IDHM_E"); //IDHM_E
        
        //@Translate
        $str = "The Municipal Human Development Index (MHDI) of [municipio] is [idh] in [2010].
            The municipality is located in category [Faixa_DH] Human Development.            
            Between 2000 and 2010, the dimension that grew the most in absolute terms was [Dimensao2000a2010].                        
            Between 1991 and 2000, the dimension that grew the most in absolute terms was [Dimensao1991a2000].
            ";

        $texto = new Texto($str);
        $texto->replaceTags("2010", Formulas::getLabelAno2010($idhm));
        $texto->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);
        $texto->replaceTags("idh", Formulas::getIDH2010($idhm));
        
        $texto->replaceTags("Faixa_DH", Formulas::getSituacaoIDH($idhm, TextBuilder_EN::$lang));

        $texto->replaceTags("Dimensao2000a2010", Formulas::getDimensao(TextBuilder_EN::$lang, $idhm_r, $idhm_l, $idhm_e, array("faixa0010" => true, "faixa9100" => false)));
        $texto->replaceTags("Dimensao1991a2000", Formulas::getDimensao(TextBuilder_EN::$lang, $idhm_r, $idhm_l, $idhm_e, array("faixa0010" => false, "faixa9100" => true)));

        $block_componente->setData("text", $texto->getTexto());
    }

    static public function generateIDH_table_componente($block_table_componente) {

        $variaveis = array();

        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "IDHM_E")); //IDHM Educação
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FUND18M")); //"% de 18 ou mais anos com fundamental completo"
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FREQ5A6")); //% de 5 a 6 anos na escola
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FUND11A13")); //% de 12 a 14 anos nos anos finais do fundamental
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FUND15A17")); //% de 16 a 18 anos com fundamental completo
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_MED18A20")); //% de 19 a 21 anos com médio completo
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "IDHM_L")); //IDHM Longevidade
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "ESPVIDA")); //Esperança de vida ao nascer
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "IDHM_R")); //IDHM Renda
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "RDPC")); //Renda per capita

        $block_table_componente->setData("titulo", "MHDI and components");//@Translate
        $block_table_componente->setData("caption", "Municipal Human Development Index (MHDI)");//@Translate
        $block_table_componente->setData("fonte", TextBuilder_EN::$fontePerfil);        
        $block_table_componente->setData("municipio", TextBuilder_EN::$nomeMunicipio . " - " . TextBuilder_EN::$ufMunicipio);

        Formulas::printTableComponente($block_table_componente, $variaveis);
    }

    static public function generateIDH_evolucao($block_evolucao) {

        $idhm = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "IDHM"); //IDHM
        $idhm_uf = TextBuilder_EN::getVariaveis_Uf(TextBuilder_EN::$idMunicipio, "IDHM"); //IDHM do Estado
        $idhm_brasil = TextBuilder_EN::getVariaveis_Brasil(TextBuilder_EN::$idMunicipio, "IDHM"); //IDHM do Brasil

        $block_evolucao->setData("subtitulo", "Evolution");//@Translate
        $block_evolucao->setData("fonte", TextBuilder_EN::$fontePerfil);
        //----------------------------------------------------------------------------------------
        //Evolução entre os anos de 2000 e 2010
        $block_evolucao->setData("info1", "");
        
        //@Translate
        $str1 = "<b>Between 2000 and 2010</b><br>
            The MHDI increased from [IDHM2000] in 2000 to [IDHM2010] in 2010 - growth rate being [Tx_crescimento_0010]%.
            The gap of human development, in other words, the distance between the MHDI of the municipality and the maximum index value of 1, was
            [reduzido_aumentado] em [reducao_hiato_0010]% between the years 2000 and 2010.<br><br>";
        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);

        $texto1->replaceTags("IDHM2000", Formulas::getIDH2000($idhm));
        $texto1->replaceTags("IDHM2010", Formulas::getIDH2010($idhm));
        $texto1->replaceTags("Tx_crescimento_0010", Formulas::getTaxaCrescimento0010($idhm));

        //Cálculo do HIATO
        //$reducao_hiato_0010 = (($idhm[2]["valor"] - $idhm[1]["valor"]) / (1 - $idhm[1]["valor"])) * 100;
        $texto1->replaceTags("reducao_hiato_0010", Formulas::getReducaoHiato0010($idhm));

        if (Formulas::getReducaoHiato0010puro($idhm) >= 0)
            $texto1->replaceTags("reduzido_aumentado", "reduced");//@Translate
        else
            $texto1->replaceTags("reduzido_aumentado", "increased");//@Translate

        $block_evolucao->setData("text1", $texto1->getTexto());

        //----------------------------------------------------------------------------------------
        //Evolução entre os anos de 1991 e 2000
        $block_evolucao->setData("info2", "");
        
        //@Translate
        $str2 = "<b>Between 1991 and 2000</b><br>
            The MHDI increased from [IDHM1991] in 1991 to [IDHM2000] in 2000 - the growth rate being [Tx_crescimento_9100]%.
            The human development gap, in other words, the distance between the MHDI of the municipality and the maximum index value of 1, was
            [reduzido_aumentado] by [reducao_hiato_9100]% between the years 1991 and 2000.<br><br>";
        
        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);

        $texto2->replaceTags("IDHM1991", Formulas::getIDH1991($idhm));
        $texto2->replaceTags("IDHM2000", Formulas::getIDH2000($idhm));
        $texto2->replaceTags("Tx_crescimento_9100", Formulas::getTaxaCrescimento9100($idhm));

        //Cálculo do HIATO
        //$reducao_hiato_9100 = (($idhm[1]["valor"] - $idhm[0]["valor"]) / (1 - $idhm[0]["valor"])) * 100;
        $texto2->replaceTags("reducao_hiato_9100", Formulas::getReducaoHiato9100($idhm));

        if (Formulas::getReducaoHiato9100puro($idhm) >= 0)
            $texto2->replaceTags("reduzido_aumentado", "reduced");//@Translate
        else
            $texto2->replaceTags("reduzido_aumentado", "increased");//@Translate

        $block_evolucao->setData("text2", $texto2->getTexto());

        //----------------------------------------------------------------------------------------
        //Evolução entre os anos de 1991 e 2010
        $block_evolucao->setData("info3", "");
        
        //@Translate
        $str3 = "<b>Between 1991 and 2010</b><br>
            The MHDI of [municipio] increased [Tx_crescimento_9110]% in the last two decades which was
            [abaixo_acima] the average national growth ([tx_cresc_Brasil9110]%) and [abaixo_acima_uf] the average state growth ([tx_cresc_Estado9110]%).
            The human development gap, in other words, the distance between the MHDI of the municipality and the maximum index (which is 1) was
            [reduzido_aumentado] by [reducao_hiato_9110]% between 1991 and 2010.";
        
        $texto3 = new Texto($str3);
        $texto3->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);

        //Taxa de Crescimento
        $tx_cresc_9110 = Formulas::getTaxaCrescimento9110($idhm);
        $texto3->replaceTags("Tx_crescimento_9110", $tx_cresc_9110);

        //----------------------------------------
        //Taxa de Crescimento em relação ao BRASIL
        $tx_cresc_Brasil9110 = Formulas::getTaxaCrescimento9110BRASIL($idhm_brasil);
        
        if ($tx_cresc_Brasil9110 < $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Brasil9110", abs($tx_cresc_Brasil9110));
            $texto3->replaceTags("abaixo_acima", "above");//@Translate
        }else if ($tx_cresc_Brasil9110 == $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Brasil9110", abs($tx_cresc_Brasil9110));
            $texto3->replaceTags("abaixo_acima", "equal to");//@Translate
        }else if ($tx_cresc_Brasil9110 > $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Brasil9110", abs($tx_cresc_Brasil9110));
            $texto3->replaceTags("abaixo_acima", "below");//@Translate
        }            

        //----------------------------------------
        //Taxa de Crescimento em relação ao ESTADO
        $tx_cresc_Estado9110 = Formulas::getTaxaCrescimento9110ESTADO($idhm_uf);
        
        if ($tx_cresc_Estado9110 < $tx_cresc_9110){
            $texto3->replaceTags("abaixo_acima_uf", "above");//@Translate
            $texto3->replaceTags("tx_cresc_Estado9110", abs($tx_cresc_Estado9110));
        }  
        else if ($tx_cresc_Estado9110 == $tx_cresc_9110){
            $texto3->replaceTags("tx_cresc_Estado9110", abs($tx_cresc_Estado9110));
            $texto3->replaceTags("abaixo_acima_uf", "equal to");//@Translate
        }
        else if ($tx_cresc_Estado9110 > $tx_cresc_9110){
            $texto3->replaceTags("abaixo_acima_uf", "below");//@Translate
            $texto3->replaceTags("tx_cresc_Estado9110", abs($tx_cresc_Estado9110));
        }
        
        //Cálculo do HIATO
        //$reducao_hiato_9110 = (($idhm[2]["valor"] - $idhm[0]["valor"]) / (1 - $idhm[0]["valor"])) * 100;
        $texto3->replaceTags("reducao_hiato_9110", Formulas::getReducaoHiato9110($idhm));

        if (Formulas::getReducaoHiato9110puro($idhm) >= 0)
            $texto3->replaceTags("reduzido_aumentado", "reduced");//@Translate
        else
            $texto3->replaceTags("reduzido_aumentado", "increased");//@Translate

        $block_evolucao->setData("text3", $texto3->getTexto());
        
        if (TextBuilder_EN::$print){ 
            $block_evolucao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_evolucao->setData("quebra", "");

        $block_evolucao->setData("canvasContent", getChartEvolucao(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));
    }
    
    static public function generateIDH_table_taxa_hiato($block_table_taxa_hiato) {
        
        $idhm = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "IDHM"); //IDHM

        $block_table_taxa_hiato->setData("titulo", "");
        $block_table_taxa_hiato->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_table_taxa_hiato->setData("ano1", "Growth rate");//@Translate
        $block_table_taxa_hiato->setData("ano2", "Development gap");//@Translate

        $taxa9100 = Formulas::getTaxa9100($idhm);
        $taxa0010 = Formulas::getTaxa0010($idhm);        
        $taxa9110 = Formulas::getTaxa9110($idhm);

        $reducao_hiato_0010 = Formulas::getReducaoHiato0010($idhm);
        $reducao_hiato_9100 = Formulas::getReducaoHiato9100($idhm);
        $reducao_hiato_9110 = Formulas::getReducaoHiato9110($idhm);

        //TODO: FAZER O MAIS E MENOS DA TAXA E HIATO UTILIZANDO IMAGENS
        
            $block_table_taxa_hiato->setData("v1", "Between 1991 and 2000");//@Translate
            
                Formulas::printTableTaxaHiatoEntre9100($block_table_taxa_hiato, $taxa9100, $reducao_hiato_9100);
                
            $block_table_taxa_hiato->setData("v2", "Between 2000 and 2010");//@Translate
            
                Formulas::printTableTaxaHiatoEntre0010($block_table_taxa_hiato, $taxa0010, $reducao_hiato_0010);
            
            $block_table_taxa_hiato->setData("v3", "Between 1991 and 2010");//@Translate
            
                Formulas::printTableTaxaHiatoEntre9110($block_table_taxa_hiato, $taxa9110, $reducao_hiato_9110);

    }

    static public function generateIDH_ranking($block_ranking) {

        $ranking = TextBuilder_EN::getRanking(); //IDHM
        $uf = TextBuilder_EN::getUf(TextBuilder_EN::$idMunicipio); //IDHM
        $ranking_uf = TextBuilder_EN::getRankingUf($uf[0]["id"]); //IDHM

        $block_ranking->setData("subtitulo", "Ranking");//@Translate
        $block_ranking->setData("info", "");
        
        //@Translate
        $str = "Comparing the 5,565 municipalities of Brazil, [municipio] holds the [ranking_municipio_IDHM]th position in 2010, with
            [municipios_melhor_IDHM] ([municipios_melhor_IDHM_p]%) of Brazilian municipalities in a better situation and 
            [municipios_pior_IDHM] ([municipios_pior_IDHM_p]%) of municipalities in the same situation or worse.";
        
        if (TextBuilder_EN::$idMunicipio != 735 && TextBuilder_EN::$lang == "pt"){ #@Verificar Isso!
            //@Translate
            $str = $str . " Em relação aos [numero_municipios_estado] outros municípios de [estado_municipio], [municipio] ocupa a
            [ranking_estados_IDHM]ª posição, sendo que [municipios_melhor_IDHM_estado] ([municipios_melhor_IDHM_p_estado]%) municípios estão em situação melhor e [municipios_pior_IDHM_estado] ([municipios_pior_IDHM_p_estado]%) municípios
            estão em situação pior ou igual.";
        }   
         
        $texto = new Texto($str);

        $texto->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);       
        
        Formulas::getRanking($block_ranking, $texto, $ranking, $uf, $ranking_uf);
        
    }

    // OUTRA CATEGORIA
    static public function generateDEMOGRAFIA_SAUDE_populacao($block_populacao) {

        $pesotot = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PESOTOT"); //PESOTOT
        $pesourb = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PESOURB"); //PESORUR
        $pesotot_uf = TextBuilder_EN::getVariaveis_Uf(TextBuilder_EN::$idMunicipio, "PESOTOT"); //PESOTOT do Estado
        $pesotot_brasil = TextBuilder_EN::getVariaveis_Brasil(TextBuilder_EN::$idMunicipio, "PESOTOT"); //PESOTOT do Brasil        
        //$pesorur = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PESORUR"); //PESORUR        
        
        if (TextBuilder_EN::$print){ 
            $block_populacao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_populacao->setData("quebra", "");
        
        $block_populacao->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_populacao->setData("titulo", "Demography and health");//@Translate
        $block_populacao->setData("subtitulo", "Population");//@Translate
        $block_populacao->setData("info", "");
        
        //@Translate
        $str = "Between 2000 and 2010, the population of [municipio] had an average annual growth of
            [tx_cres_pop_0010]%. In the previous decade, 1991-2000, the average annual growth rate was
            [tx_cres_pop_9100]%. At the state level, these rates were [tx_cresc_pop_estado_0010]% between 2000 and 2010 and 
            [tx_cresc_pop_estado_9100]% between 1991 and 2000. In the country, they were [tx_cresc_pop_pais_0010]%
            between 2000 and 2010 and [tx_cresc_pop_pais_9100]% between 1991 and 2000.
            In the last two decades, the urbanization rate increased by [tx_urbanizacao]%.";

        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);
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
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PESOTOT")); //População Total
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "HOMEMTOT")); //Homens
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "MULHERTOT")); //Mulheres
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PESOURB")); //População Urbana
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PESORUR")); //População Rural
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "")); //Taxa de Urbanização

        $block_table_populacao->setData("titulo", "Population");//@Translate
        $block_table_populacao->setData("caption", "Total Population by Gender, Rural / Urban and Urbanization rate");//@Translate
        $block_table_populacao->setData("fonte", TextBuilder_EN::$fontePerfil);        
        $block_table_populacao->setData("municipio", TextBuilder_EN::$nomeMunicipio . " - " . TextBuilder_EN::$ufMunicipio);
        $block_table_populacao->setData("coluna1", "Population");//@Translate
        $block_table_populacao->setData("coluna2", "% of total");//@Translate
        
        $stringTaxaUrbanizacao = "Urbanization Rate";//@Translate
        Formulas::printTablePopulacao($block_table_populacao, $variaveis, $stringTaxaUrbanizacao);
        
    }

    static public function generateDEMOGRAFIA_SAUDE_etaria($block_etaria) {

        $tenv = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_ENV"); //T_ENV
        $rd = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "RAZDEP"); //T_ENV

        $block_etaria->setData("subtitulo", "Age structure");//@Translate
        $block_etaria->setData("info", "");
                 
        //@Translate
        $str = "Between 2000 and 2010, the dependency ratio of [municipio] went from [rz_dependencia00]%
            to [rz_dependencia10]% and the aging index increased from [indice_envelhecimento00]% to
            [indice_envelhecimento10]%.
            Between 1991 and 2000, the dependency ratio went from [rz_dependencia91]% to [rz_dependencia00]%,
            while the aging index increased from [indice_envelhecimento91]% to [indice_envelhecimento00]%.";
        
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);
        $texto->replaceTags("indice_envelhecimento91", Formulas::getIndiceEnvelhecimento91($tenv));
        $texto->replaceTags("indice_envelhecimento00", Formulas::getIndiceEnvelhecimento00($tenv));
        $texto->replaceTags("indice_envelhecimento10", Formulas::getIndiceEnvelhecimento10($tenv));
        
        $texto->replaceTags("rz_dependencia91", Formulas::getRazaoDependencia91($rd));
        $texto->replaceTags("rz_dependencia00", Formulas::getRazaoDependencia00($rd));
        $texto->replaceTags("rz_dependencia10", Formulas::getRazaoDependencia10($rd));
        
        $block_etaria->setData("text", $texto->getTexto());
        
        //@Translate
        $block_etaria->setData("block_box1", "<b>What is the dependency<br> ratio?</b><br>
            Percentage of the population <br>
            aged less than 15 years <br>
            and population aged 65 years <br>
            and older (population <br>
            dependent) compared to <br>
            the population aged 15-64 <br>
            years (potentially active <br>
            population).");
        $block_etaria->setData("block_box2","<b>What is the<br> aging index?</b><br>
            Population aged 65 <br>
            years or older compared <br>
            to the population aged <br>
            less than 15 years.");
        
        $block_etaria->setData("tableContent", "");
    }

    static public function generateIDH_table_etaria($block_table_etaria) {

        $pesotot = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PESOTOT"); //PESOTOT

        $variaveis = array();
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PESO15")); //Menos de 15 anos (PESOTOT-PESO15)
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "")); //15 a 64 anos
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PESO65")); //65 anos ou mais
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "RAZDEP")); //Razão de Dependência(Planilha Piramide)               
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_ENV")); //Índice de Envelhecimento

        $block_table_etaria->setData("titulo", "Age structure");//@Translate        
        $block_table_etaria->setData("caption", "The age structure");//@Translate
        $block_table_etaria->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_table_etaria->setData("municipio", TextBuilder_EN::$nomeMunicipio . " - " . TextBuilder_EN::$ufMunicipio);
        $block_table_etaria->setData("coluna1", "Population");//@Translate
        $block_table_etaria->setData("coluna2", "% of total");//@Translate
        
        $stringMenos15anos = "Less than 15 years (of age)"; //@Translate //Não é uma variável específica
        $string15a64anos = "15 to 64 years"; //@Translate //Não é uma variável específica
        Formulas::printTableEtaria($block_table_etaria, $variaveis, $pesotot, $stringMenos15anos, $string15a64anos);
        
        if (TextBuilder_EN::$print){ 
            $block_table_etaria->setData("quebra1", "<div style='page-break-after: always'></div>");
        }else
            $block_table_etaria->setData("quebra1", "");
        
        $block_table_etaria->setData("canvasContent1", getChartPiramideEtaria1(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));
        $block_table_etaria->setData("canvasContent2", getChartPiramideEtaria2(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));    
        $block_table_etaria->setData("canvasContent3", getChartPiramideEtaria3(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));
        
    }

    static public function generateDEMOGRAFIA_SAUDE_longevidade1($block_longevidade) {

        $mort1 = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "MORT1"); //MORT1
        $mort1_uf = TextBuilder_EN::getVariaveis_Uf(TextBuilder_EN::$idMunicipio, "MORT1"); //MORT1 do Estado
        $mort1_brasil = TextBuilder_EN::getVariaveis_Brasil(TextBuilder_EN::$idMunicipio, "MORT1"); //MORT1 do Brasil
                
        if (TextBuilder_EN::$print){ 
            $block_longevidade->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_longevidade->setData("quebra", "");
        
        $block_longevidade->setData("subtitulo", "Longevity, Mortality and Fertility");//@Translate
        $block_longevidade->setData("info", "");
        
        //@Translate
        $str1 = "The infant mortality (mortality of children under one year) in [municipio] [mort1_diminuiu_aumentou] [reducao_mortalinfantil0010]%,
            from [mortinfantil00] per thousand of live births in 2000 to [mortinfantil10] per thousand live births in 2010.
            According to the Millennium Development Goals of the United Nations, infant mortality in Brazil should be below 17.9 deaths per thousand in 2015.
            In 2010, the infant mortality rates in the state and the country were [mortinfantil10_Estado] and [mortinfantil10_Brasil] per thousand live births.";
        
        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);
        //TODO: Tem que ser sempre positivo
        $texto1->replaceTags("reducao_mortalinfantil0010", Formulas::getReducaoMortalidadeInfantil0010($mort1));
        $texto1->replaceTags("mortinfantil00", Formulas::getMortalidadeInfantil00($mort1));
        $texto1->replaceTags("mortinfantil10", Formulas::getMortalidadeInfantil10($mort1));
        $texto1->replaceTags("mortinfantil10_Estado", Formulas::getMortalidadeInfantil10ESTADO($mort1_uf));
        $texto1->replaceTags("mortinfantil10_Brasil", Formulas::getMortalidadeInfantil10BRASIL($mort1_brasil));

        //TODO: Tirar o igual da comparação (feito pq a base não é oficial e há replicações
        if (Formulas::getMortalidadeInfantil10puro($mort1) <= Formulas::getMortalidadeInfantil00puro($mort1))
            $texto1->replaceTags("mort1_diminuiu_aumentou", "reduced");//@Translate
        else if (Formulas::getMortalidadeInfantil10puro($mort1) > Formulas::getMortalidadeInfantil00puro($mort1))
            $texto1->replaceTags("mort1_diminuiu_aumentou", "increased");//@Translate
        
        $block_longevidade->setData("text2", "");
        $block_longevidade->setData("tableContent", "");

        $block_longevidade->setData("text1", $texto1->getTexto());

    }

    static public function generateIDH_table_longevidade($block_table_longevidade) {

        $variaveis = array();
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "ESPVIDA")); //Esperança de vida ao nascer (anos)
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "MORT1")); //Mortalidade até 1 ano de idade (por mil nascidos vivos)
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "MORT5")); //Mortalidade até 5 anos de idade (por mil nascidos vivos)
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "FECTOT")); //Taxa de fecundidade total (filhos por mulher) 

        $block_table_longevidade->setData("titulo", "");
        $block_table_longevidade->setData("caption", "Longevity, Mortality and Fertility");//@Translate
        $block_table_longevidade->setData("fonte", TextBuilder_EN::$fontePerfil);        
        $block_table_longevidade->setData("municipio", TextBuilder_EN::$nomeMunicipio . " - " . TextBuilder_EN::$ufMunicipio);
        
        Formulas::printTableLongevidade($block_table_longevidade, $variaveis);
        
    }
    
       static public function generateDEMOGRAFIA_SAUDE_longevidade2($block_longevidade) {

        $espvida = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "ESPVIDA"); //ESPVIDA
        $espvida_uf = TextBuilder_EN::getVariaveis_Uf(TextBuilder_EN::$idMunicipio, "ESPVIDA"); //ESPVIDA do Estado
        $espvida_brasil = TextBuilder_EN::getVariaveis_Brasil(TextBuilder_EN::$idMunicipio, "ESPVIDA"); //ESPVIDA do Brasil

        $block_longevidade->setData("subtitulo", "");
        $block_longevidade->setData("info", "");

        //@Translate
        $str2 = "The life expectancy at birth is the indicator that composes the Longevity dimension in the Municipal Human Development Index (MHDI).
            In [municipio], life expectancy at birth increased by [aumento_esp_nascer0010] years in the last two decades, from [esp_nascer91] years in 1991 to [esp_nascer00] years in 2000,
            and to [esp_nascer10] years in 2010. In 2010, life expectancy at birth for the state average is [esp_nascer10_estado] years and for the country,
            [esp_nascer10_pais] years.";
        
        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);
        $texto2->replaceTags("aumento_esp_nascer0010", Formulas::getAumentoEsperancaVidaNascer0010($espvida));
        $texto2->replaceTags("esp_nascer91", Formulas::getEsperancaVidaNascer91($espvida));
        $texto2->replaceTags("esp_nascer00", Formulas::getEsperancaVidaNascer00($espvida));
        $texto2->replaceTags("esp_nascer10", Formulas::getEsperancaVidaNascer10($espvida));
        $texto2->replaceTags("esp_nascer10_estado", Formulas::getEsperancaVidaNascer10ESTADO($espvida_uf));
        $texto2->replaceTags("esp_nascer10_pais", Formulas::getEsperancaVidaNascer10BRASIL($espvida_brasil));
        $block_longevidade->setData("text2", $texto2->getTexto());
        
        $block_longevidade->setData("text1", "");
        $block_longevidade->setData("tableContent", "");
        
        if (TextBuilder_EN::$print){ 
            $block_longevidade->setData("quebra", "");
        }else
            $block_longevidade->setData("quebra", "");
    }

    static public function generateEDUCACAO_nivel_educacional($block_nivel_educacional) {
       
        $t_freq4a6 = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FREQ5A6"); //T_FREQ5A6 - Mudou variável
        $t_fund12a14 = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FUND11A13");  //T_FUND11A13 - Mudou variável
        $t_fund16a18 = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FUND15A17");  //T_FUND15A17 - Mudou variável
        $t_med19a21 = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_MED18A20");  //T_MED18A20 - Mudou variável
        
        $t_atraso_0_fund = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_ATRASO_0_FUND");  //T_ATRASO_0_FUND 
        $t_flfund = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FLFUND");  //T_FLFUND 

        $t_atraso_0_med = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_ATRASO_0_MED");  //T_ATRASO_0_MED 
        $t_flmed = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FLMED");  //T_FLMED 
        $t_flsuper = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FLSUPER");  //T_FLSUPER 

        $t_freq6a14 = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FREQ6A14");  //T_FREQ6A14 
        $t_freq15a17 = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FREQ15A17");  //T_FREQ15A17 
        //$t_freq18a24 = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FREQ18A24");  //T_FREQ15A17 
        
        if (TextBuilder_EN::$print){ 
            $block_nivel_educacional->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra", "");
        
        $block_nivel_educacional->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_nivel_educacional->setData("titulo", "Education");//@Translate
        $block_nivel_educacional->setData("subtitulo", "Children and young people");//@Translate
        $block_nivel_educacional->setData("info", "");
        
        //@Translate
        $str1 = "<br>The share of children and young people attending school or having completed the given educational grades indicates the state of education among the school-age population of a municipality – and hence, it is part of MHDI Education.. 
        <br><br>In 2000-2010 the share of <b>children aged 5 to 6 years attending school</b>
        and grew [cresc_4-6esc_0010]% and, in 1991- 2000,
        [cresc_4-6esc_9100]%. The share of <b>children aged 11 to 13 years attending the final years of primary education
        </b> grew [cresc_12-14esc_0010]%
        between 2000 and 2010, and [cresc_12-14esc_9100]% between 1991 and 2000. 
        
        <br><br>The share of <b>young people aged between 15 to 17 years having completed primary education</b>
        increased by [cresc_16-18fund_0010]% between 2000 and 2010 and by
        [cresc_16-18fund_9100]% between 1991 and 2000. And the share of <b>young people aged between 18 and 20 years having completed secondary education</b>
        grew [cresc_19-21medio_0010]%
        between 2000 and 2010, and [cresc_19-21medio_9100]% between 1991 and 2000.";

        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);

        $texto1->replaceTags("cresc_4-6esc_0010", Formulas::getCrescimento4a6Esc0010($t_freq4a6));
        $texto1->replaceTags("cresc_4-6esc_9100", Formulas::getCrescimento4a6Esc9100($t_freq4a6));

        $texto1->replaceTags("cresc_12-14esc_0010", Formulas::getCrescimento12a14Esc0010($t_fund12a14));
        $texto1->replaceTags("cresc_12-14esc_9100", Formulas::getCrescimento12a14Esc9100($t_fund12a14));

        $texto1->replaceTags("cresc_16-18fund_0010", Formulas::getCrescimento16a18Fund0010($t_fund16a18));
        $texto1->replaceTags("cresc_16-18fund_9100", Formulas::getCrescimento16a18Fund9100($t_fund16a18));

        $texto1->replaceTags("cresc_19-21medio_0010", Formulas::getCrescimento19a21Medio0010($t_med19a21));
        $texto1->replaceTags("cresc_19-21medio_9100", Formulas::getCrescimento19a21Medio9100($t_med19a21));

        $block_nivel_educacional->setData("text1", $texto1->getTexto());        

        if (TextBuilder_EN::$print){ 
            $block_nivel_educacional->setData("quebra4", "<div style=margin-top: 20px;'></div>");
        }else
            $block_nivel_educacional->setData("quebra4", "");
        
        $block_nivel_educacional->setData("canvasContent1", getChartFluxoEscolar(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));
        
        if (TextBuilder_EN::$print){ 
            $block_nivel_educacional->setData("quebra1", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra1", "");
        
        $block_nivel_educacional->setData("canvasContent2", getChartFrequenciaEscolar(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));
        
        //@Translate
        $str2 = "<br>In 2010, [tx_fund_sematraso_10]% of the students aged between 6 and 14 years of [municipio]
        attended primary school regularly, in grades appropriate to their age.
        In 2000, this percentage was [tx_fund_sematraso_00]% and in 1991
        [tx_fund_sematraso_91]%.
        [tx_medio_sematraso_10]% of young people aged15 to 17 years who were attending school regularly, without educational delays.
        In 2000, the percentage was [tx_medio_sematraso_00]% and, in 1991, [tx_medio_sematraso_91]%. 
        [t_flsuper_10]% of students aged 18 to 24 years were attending higher education in 2010,
        [t_flsuper_00]% in 2000 and [t_flsuper_91]% in 1991.
        <br><br>
        It is noteworthy that, in 2010, [p6a14]% of the children aged 6 to14 years did not attend school.
        Among young people aged 15 to 17 years, the percentage reached [p15a17]%.";

        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);
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
        
        if (TextBuilder_EN::$print){ 
            $block_nivel_educacional->setData("quebra2", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra2", "");
        
        $block_nivel_educacional->setData("canvasContent3", getChartFrequenciaDe6a14(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));              
        $block_nivel_educacional->setData("canvasContent4", getChartFrequenciaDe15a17(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));
        $block_nivel_educacional->setData("canvasContent5", getChartFrequenciaDe18a24(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));
        
        if (TextBuilder_EN::$print){ 
            $block_nivel_educacional->setData("quebra3", "<div style='page-break-after: always'></div>");
        }else
            $block_nivel_educacional->setData("quebra3", "");
        
    }

    static public function generateEDUCACAO_populacao_adulta($block_populacao_adulta) {

        $t_analf25m = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_ANALF18M"); //T_ANALF18M - Mudou variável
        $t_fundin25m = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FUND18M");  //T_FUND18M  - Mudou variável
        $t_medin25m = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_MED18M");  //T_MED18M  - Mudou variável
        
        $t_fundin25m_uf = TextBuilder_EN::getVariaveis_Uf(TextBuilder_EN::$idMunicipio, "T_FUND18M");  //T_FUND18M - Mudou variável
        $t_medin25m_uf = TextBuilder_EN::getVariaveis_Uf(TextBuilder_EN::$idMunicipio, "T_MED18M");  //T_MED18M - Mudou variável
        
        $e_anosesperados = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "E_ANOSESTUDO"); //E_ANOSESTUDO
        $e_anosesperados_uf = TextBuilder_EN::getVariaveis_Uf(TextBuilder_EN::$idMunicipio, "E_ANOSESTUDO");  //E_ANOSESTUDO
               
        $uf = TextBuilder_EN::getUf(TextBuilder_EN::$idMunicipio); //UF
        
        $block_populacao_adulta->setData("subtitulo", "Adult population");//@Translate
        $block_populacao_adulta->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_populacao_adulta->setData("info", "");
        
        //@Translate
        $str = "The educational level of the adult population is an important indicator in terms of access to knowledge – hence, it is part of MHDI Education.
            <br><br>In 2010, [25_fund_10]% of the population aged 18 years or older had completed primary education and [25_medio_10]%
            had completed secondary education. In [estado_municipio], [25_fund_10_Estado]% and [25_medio_10_Estado]%.
            This indicator bears inertia - depending on the weight of the older and less-educated generation.
            <br><br>The illiteracy rate of the population aged 18 years or older [diminuiu_aumentou] [25_analf_9110] in the last two decades.";
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);
        $texto->replaceTags("estado_municipio", $uf[0]["nome"]);
        
        $texto->replaceTags("25_fund_10", Formulas::get25Fund10($t_fundin25m));
        $texto->replaceTags("25_medio_10", Formulas::get25Medio10($t_medin25m));
        $texto->replaceTags("25_fund_10_Estado", Formulas::get25Fund10ESTADO($t_fundin25m_uf));
        $texto->replaceTags("25_medio_10_Estado", Formulas::get25Medio10ESTADO($t_medin25m_uf));
        
        $dif_analf = Formulas::getDifAnalf($t_analf25m);
        if ($dif_analf > 0){
            $texto->replaceTags("diminuiu_aumentou", "increased");//@Translate
            $texto->replaceTags("25_analf_9110", number_format($dif_analf, 2, ",", ".") . "%");
        }else if ($dif_analf == 0) {
            $texto->replaceTags("diminuiu_aumentou", "remained");//@Translate
            $texto->replaceTags("25_analf_9110", "");
        }else if ($dif_analf < 0) {
            $texto->replaceTags("diminuiu_aumentou", "decreased");//@Translate
            $texto->replaceTags("25_analf_9110", number_format(abs($dif_analf), 2, ",", ".") . "%");
        }
        
        $block_populacao_adulta->setData("text", $texto->getTexto());
        $block_populacao_adulta->setData("canvasContent", getChartEscolaridadePopulacao(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));
        
        $block_populacao_adulta->setData("subtitulo2", "Expected years of schooling");//@Translate
        $block_populacao_adulta->setData("info2", "");
        
        //@Translate
        $str2 = "The expected years of schooling indicates the number of years that a child who starts his/her school life in the reference year tends to attend.
        In 2010, in [municipio], the expected years of schooling of the population was [e_anosestudo10] years; 
        in 2000, it was [e_anosestudo00] years and, in 1991, [e_anosestudo91] years. 
        The expected years of schooling in [estado_municipio] were [ufe_anosestudo10] years in 2010,
        [ufe_anosestudo00] years in 2000 and [ufe_anosestudo91] years in 1991.";
        
        $texto2 = new Texto($str2);
        $texto2->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);
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

        $rdpc = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "RDPC"); //RDPC  
        $pind = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PIND");  //PIND 
        $pop = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "POP");  //POP 
        $gini = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "GINI");  //GINI 

        if (TextBuilder_EN::$print){ 
            $block_renda->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_renda->setData("quebra", "");

        $block_renda->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_renda->setData("titulo", "Income");//@Translate
        $block_renda->setData("subtitulo", "");
        $block_renda->setData("info", "");
        
        //@Translate
        $str = "During the last two decades, the average income per capita of [municipio] [caiu_cresceu] [tx_cresc_renda]%,
            from R$[renda91], in 1991, to R$[renda00] in 2000 and R$[renda10] in 2010.
            In the first period the average annual growth rate was
            [tx_cresc_renda9100]% and, in the second period, [tx_cresc_renda0010]%.
            Extreme poverty (as measured by the proportion of people with income per capita below R$ 70,00 in August 2010) went from
            [tx_pobreza_91]% in 1991 to [tx_pobreza_00]%
            in 2000 and to [tx_pobreza_10]%  in 2010.
            <br><br>The inequality [diminuiu_aumentou]: the Gini coefficient rose from [gini_91]
            in 1991 to [gini_00] in 2000 and to [gini_10] in 2010. 
            ";
        $texto = new Texto($str);
        $texto->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);

        //TODO: Tirar o igual da comparação (feito pq a base não é oficial e há replicações
        if (Formulas::getRenda10puro($rdpc) >= Formulas::getRenda91puro($rdpc))
            $texto->replaceTags("caiu_cresceu", "grew");//@Translate
        else if (Formulas::getRenda10puro($rdpc) < Formulas::getRenda91puro($rdpc))
            $texto->replaceTags("caiu_cresceu", "fell");//@Translate

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
            $texto->replaceTags("diminuiu_aumentou", "decreased");//@Translate
        else if (Formulas::getGini10puro($gini) == Formulas::getGini91puro($gini))
            $texto->replaceTags("diminuiu_aumentou", "remained");//@Translate
        else if (Formulas::getGini10puro($gini) > Formulas::getGini91puro($gini))
            $texto->replaceTags("diminuiu_aumentou", "increased");//@Translate

        $texto->replaceTags("gini_91", Formulas::getGini91($gini));
        $texto->replaceTags("gini_00", Formulas::getGini00($gini));
        $texto->replaceTags("gini_10", Formulas::getGini10($gini));

        $block_renda->setData("text", $texto->getTexto());
        //@Translate
        $block_renda->setData("block_box1", "<br><b>What is the Gini Index?</b><br>
            The Gini index is an instrument used<br>
            to measure the degree of income concentration.<br>
            It points out the difference <br>
            between the poorest and richest incomes. <br>
            Numerically, it varies from 0 to 1, <br>
            with 0 representing a situation of  <br>
            complete equality or everyone having <br>
            the same income. The value of 1 means <br>
            complete income inequality, or a situation <br>
            where only one person holds all <br>
            the income in a given place. ");
        
        // GRAFICO
        $block_renda->setData("tableContent", "");
        $block_renda->setData("tableContent2", "");

    }

    static public function generateIDH_table_renda($block_table_renda) {

        $variaveis = array();
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "RDPC")); //Renda per capita média (R$ de 2010)
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PIND")); //Proporção de extremamente pobres - total (%)
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PMPOB")); //Proporção de pobres 
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "GINI")); //Índice de Gini

        $block_table_renda->setData("titulo", "");
        $block_table_renda->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_table_renda->setData("caption", "Income, poverty and inequality");//@Translate
        $block_table_renda->setData("municipio", TextBuilder_EN::$nomeMunicipio . " - " . TextBuilder_EN::$ufMunicipio);
        
        Formulas::printTableRenda($block_table_renda, $variaveis);          
        
    } 
    
    static public function generateIDH_table_renda2($block_table_renda2) {

        $variaveis = array();
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PREN20")); //20% mais pobres
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PREN40")); //40% mais pobres
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PREN60")); //60% mais pobres
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PREN80")); //80% mais pobres
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PREN20RICOS")); //20% mais ricos

        $block_table_renda2->setData("titulo", "");
        $block_table_renda2->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_table_renda2->setData("caption", "Percentage of appropriate income by shares of the population");//@Translate
        $block_table_renda2->setData("municipio", TextBuilder_EN::$nomeMunicipio . " - " . TextBuilder_EN::$ufMunicipio);
                
        Formulas::printTableRenda2($block_table_renda2, $variaveis);

    }

    static public function generateTRABALHO1($block_trabalho) {

        $t_ativ18m = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_ATIV18M"); //T_ATIV18M  
        $t_des18m = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_DES18M");  //T_DES18M 
        
        if (TextBuilder_EN::$print){ 
            $block_trabalho->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_trabalho->setData("quebra", "");
        
        $block_trabalho->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_trabalho->setData("titulo", "Labour");//@Translate
        $block_trabalho->setData("canvasContent", getChartTrabalho(TextBuilder_EN::$idMunicipio, TextBuilder_EN::$lang));
       
        $block_trabalho->setData("subtitulo", "");
        $block_trabalho->setData("info", "");

        //@Translate
        $str1 = "Between 2000 and 2010, the <b>activity rate</b> of the population aged 18 years or older (that is, the percentage of 
            the economically-active population) increased from [tx_ativ_18m_00]% in 2000 to [tx_ativ_18m_10]% in 2010.
            At the same time, the <b>unemployment rate</b> (that is, the percentage of the economically-active,
            but unemployed, population) increased from [tx_des18m_00]% in 2000 to [tx_des18m_10]% in 2010.";
        $texto1 = new Texto($str1);
        $texto1->replaceTags("municipio", TextBuilder_EN::$nomeMunicipio);

        $texto1->replaceTags("tx_ativ_18m_00", Formulas::getTxAtiv18m00($t_ativ18m));
        $texto1->replaceTags("tx_ativ_18m_10", Formulas::getTxAtiv18m10($t_ativ18m));

        $texto1->replaceTags("tx_des18m_00", Formulas::getTxDes18m00($t_des18m));
        $texto1->replaceTags("tx_des18m_10", Formulas::getTxDes18m10($t_des18m));

        $block_trabalho->setData("text1", $texto1->getTexto());
        $block_trabalho->setData("text2", "");
        
    }

    static public function generateIDH_table_trabalho($block_table_trabalho) {

        $variaveis = array();
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_ATIV18M")); //Taxa de atividade
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_DES18M")); //Taxa de desocupação
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_FORMAL")); //Grau de formalização dos ocuupados
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_FUND")); //% de empregados com fundamental completo
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_MED")); //% de empregados com médio completo
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "REN1")); //% com até 1 s.m. 
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "REN2")); //% com até 2 s.m. 
        //array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "THEILtrab")); //% Theil dos rendimentos do trabalho  

        $block_table_trabalho->setData("titulo", "");
        $block_table_trabalho->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_table_trabalho->setData("caption", "Employment rates of the population aged 18 years or older");//@Translate
        $block_table_trabalho->setData("titulo1", "Educational level of the employed");//@Translate
        $block_table_trabalho->setData("titulo2", "Average income");//@Translate
        $block_table_trabalho->setData("t", "");
        $block_table_trabalho->setData("municipio", TextBuilder_EN::$nomeMunicipio . " - " . TextBuilder_EN::$ufMunicipio);
        
        Formulas::printTableTrabalho($block_table_trabalho, $variaveis);
    }
    
    static public function generateTRABALHO2($block_trabalho) {

        $p_agro = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_AGRO");  //P_AGRO 
        $p_extr = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_EXTR");  //P_EXTR
        $p_transf = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_TRANSF");  //P_TRANSF
        $p_constr = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_CONSTR"); //P_CONSTR
        $p_siup = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_SIUP");  //P_SIUP
        $p_com = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_COM");  //P_COM
        $p_serv = TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "P_SERV");  //P_SERV
        
        $block_trabalho->setData("titulo", "");
        $block_trabalho->setData("canvasContent", "");
        $block_trabalho->setData("subtitulo", "");
        $block_trabalho->setData("info", "");

        //@Translate
        $str2 = "In 2010 [p_agro_10]% of the employed people aged 18 years or older were employed in the agricultural sector,
            [p_extr_10]% in mining, [p_transf_10]% in manufacturing, [p_constr_10]% in the construction sector,
            [p_siup_10]% in the public sector, [p_com_10]% in trade and [p_serv_10]%
            in service sector. ";
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

        if (TextBuilder_EN::$print){ 
            $block_habitacao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_habitacao->setData("quebra", "");
        
        $block_habitacao->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_habitacao->setData("titulo", "Housing");//@Translate
        $block_habitacao->setData("subtitulo", "");
        $block_habitacao->setData("info", "");
        $block_habitacao->setData("tableContent", "");

    }

    static public function generateIDH_table_habitacao($block_table_habitacao) {

        $variaveis = array();
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_AGUA")); //água encanada
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_LUZ")); //energia elétrica
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_LIXO")); //coleta de lixo*

        $block_table_habitacao->setData("titulo", "");
        $block_table_habitacao->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_table_habitacao->setData("caption", "Indicators of housing");//@Translate
        $block_table_habitacao->setData("municipio", TextBuilder_EN::$nomeMunicipio . " - " . TextBuilder_EN::$ufMunicipio);
        
        $texto = " *For the urban population only";
        Formulas::printTableHabitacao($block_table_habitacao, $variaveis, $texto);
        
         if (TextBuilder_EN::$print){ 
            $block_table_habitacao->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_table_habitacao->setData("quebra", "");

    }

    static public function generateVULNERABILIDADE($block_vulnerabilidade) {

        if (TextBuilder_EN::$print){ 
            $block_vulnerabilidade->setData("quebra", "<div style='page-break-after: always'></div>");
        }else
            $block_vulnerabilidade->setData("quebra", "");
        
        $block_vulnerabilidade->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_vulnerabilidade->setData("titulo", "Social vulnerability");//@Translate
        $block_vulnerabilidade->setData("subtitulo", "");
        $block_vulnerabilidade->setData("info", "");

        $block_vulnerabilidade->setData("tableContent", "");
    }

    static public function generateIDH_table_vulnerabilidade($block_table_vulnerabilidade) {

        $variaveis = array();
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "MORT1")); //Mortalidade infantil
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FORA4A5")); //Percentual de pessoas de 4 a 5 anos de idade fora da escola
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FORA6A14")); //Percentual de pessoas de 6 a 14 anos de idade fora da escola
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_NESTUDA_NTRAB_MMEIO")); //Percentual de pessoas de 15 a 24 anos de idade que não estuda e não trabalha e cuja renda per capita <½  salário mínimo
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_M10A14CF")); //Percentual de mulheres de 10 a 14 anos de idade que tiveram filhos
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_M15A17CF")); //Percentual de mulheres de 15 a 17 anos de idade que tiveram filhos
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_ATIV1014")); //Taxa de atividade de crianças e jovens que possuem entre 10 e 14 anos de idade
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_MULCHEFEFIF014")); //Percentual de mães chefes de família sem fundamental completo com filhos menores de 15 anos 
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_RMAXIDOSO")); //Percentual de pessoas em domicílios com renda per capita < ½ salário mínimo e cuja principal renda é de pessoa com 65 anos ou mais
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PINDCRI")); //Percentual de crianças que vivem em extrema pobreza, ou seja, em domicílios com renda per capita abaixo de R% 70,00.
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "PPOB")); //#XXPercentual de pessoas em domicílios com renda per capita inferior a R$ 225,00 (1/2 salário mínimo em agosto/2010)
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "T_FUNDIN18MINF")); //Percentual de pessoas de 18 anos ou mais sem fundamental completo e em ocupação informal
        array_push($variaveis, TextBuilder_EN::getVariaveis_table(TextBuilder_EN::$idMunicipio, "AGUA_ESGOTO")); //Percentual de pessoas em domicílios cujo abastecimento de água não seja por rede geral ou esgotamento sanitário não realizado por rede coletora de esgoto ou fossa séptica

        $block_table_vulnerabilidade->setData("titulo", "Children and young people");//@Translate
        $block_table_vulnerabilidade->setData("titulo1", "Family");//@Translate
        $block_table_vulnerabilidade->setData("titulo2", "Labour and Income");//@Translate
        $block_table_vulnerabilidade->setData("titulo3", "Living conditions");//@Translate
        $block_table_vulnerabilidade->setData("fonte", TextBuilder_EN::$fontePerfil);
        $block_table_vulnerabilidade->setData("t", "");
        $block_table_vulnerabilidade->setData("caption", "Vulnerability");//@Translate
        $block_table_vulnerabilidade->setData("municipio", TextBuilder_EN::$nomeMunicipio . " - " . TextBuilder_EN::$ufMunicipio);
        
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

        $SQL = "SELECT label_ano_referencia, lang_var.nomecurto, lang_var.nome_perfil, lang_var.definicao, valor
                FROM valor_variavel_mun INNER JOIN variavel
                ON fk_variavel = variavel.id
                INNER JOIN ano_referencia
                ON ano_referencia.id = fk_ano_referencia
                INNER JOIN lang_var
                ON variavel.id = lang_var.fk_variavel
                WHERE fk_municipio = $municipio and sigla like '$variavel' and lang like '". TextBuilder_EN::$lang ."'
                 ORDER BY label_ano_referencia";

        //echo $SQL . "<br><br>"; 
        return TextBuilder_EN::$bd->ExecutarSQL($SQL, "getVariaveis_table");
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

        return TextBuilder_EN::$bd->ExecutarSQL($SQL, "getVariaveis_Uf");
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

        return TextBuilder_EN::$bd->ExecutarSQL($SQL, "getVariaveis_Brasil");
    }
    
    static function getRanking() {

        $SQL = "SELECT ROW_NUMBER() OVER(ORDER BY valor DESC, municipio.nome ASC), municipio.id, municipio.nome, nomecurto, valor 
        FROM valor_variavel_mun INNER JOIN variavel
        ON fk_variavel = variavel.id
        INNER JOIN municipio
        ON municipio.id = valor_variavel_mun.fk_municipio
        WHERE sigla like 'IDHM' AND fk_ano_referencia = 3;";

        return TextBuilder_EN::$bd->ExecutarSQL($SQL, "getRanking");
    }

    static function getUf($municipio) {

        $SQL = "SELECT estado.id, estado.nome
            FROM municipio
            INNER JOIN estado
            ON estado.id = municipio.fk_estado
            WHERE municipio.id= $municipio;";

        return TextBuilder_EN::$bd->ExecutarSQL($SQL, "getRanking");
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

        return TextBuilder_EN::$bd->ExecutarSQL($SQL, "getRanking");
    }

    static function getIDHM_Igual($idhm) {

        $SQL = "SELECT municipio.id, municipio.nome, nomecurto, valor 
            FROM valor_variavel_mun INNER JOIN variavel
            ON fk_variavel = variavel.id
            INNER JOIN municipio
            ON municipio.id = valor_variavel_mun.fk_municipio
            WHERE valor = $idhm AND fk_ano_referencia = 3
            ORDER BY  municipio.nome ASC";

        return TextBuilder_EN::$bd->ExecutarSQL($SQL, "getIDHM_Igual");
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

        return TextBuilder_EN::$bd->ExecutarSQL($SQL, "getIDHM_Igual");
    } 

}

?>

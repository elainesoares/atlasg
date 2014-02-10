<?php

//$comPath = BASE_ROOT . "/com/mobiliti//";
require_once '../../../../config/config_path.php';
require_once '../../../../config/config_gerais.php';
require_once MOBILITI_PACKAGE . 'display/controller/Perfil.class.php';
require_once MOBILITI_PACKAGE . 'display/controller/TextBuilderRM.class.php';
require_once MOBILITI_PACKAGE . 'display/controller/TextBuilder_EN.class.php';
require_once MOBILITI_PACKAGE . 'display/Block.class.php';
//require_once $comPath . "util/protect_sql_injection.php";

 if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: {$path_dir}404");
    }
    
$cidade = $_POST["city"];
$lang = $_POST["lang"];

$perfil = new Perfil($cidade);

TextBuilder::$idMunicipio = $perfil->getCityId();
//TextBuilder::$nomeMunicipio = mb_convert_case($perfil->getCityName(), MB_CASE_TITLE, "UTF-8");
TextBuilder::$nomeMunicipio = $perfil->getCityName();
TextBuilder::$ufMunicipio = $perfil->getUfName();
TextBuilder::$print = false;        
        
TextBuilder_EN::$idMunicipio = $perfil->getCityId();
//TextBuilder_EN::$nomeMunicipio = mb_convert_case($perfil->getCityName(), MB_CASE_TITLE, "UTF-8");
TextBuilder_EN::$nomeMunicipio = $perfil->getCityName();
TextBuilder_EN::$ufMunicipio = $perfil->getUfName();
TextBuilder_EN::$print = false;

$perfil->drawScriptsMaps();
$perfil->drawMap();
$perfil->drawBoxes();



switch ($lang) {
    case "pt":    
    
        //IDH ----------------------------------  
        $block_componente = new Block(1);
        TextBuilderRM::generateIDH_componente($block_componente);        
        $block_componente->draw();

        $block_table_componente = new Block(15);
        TextBuilderRM::generateIDH_table_componente($block_table_componente);
        $block_table_componente->draw();

        $block_evolucao = new Block(2);
        TextBuilderRM::generateIDH_evolucao($block_evolucao);
        $block_evolucao->draw();
        
        $block_table_taxa_hiato = new Block(24);
        TextBuilderRM::generateIDH_table_taxa_hiato($block_table_taxa_hiato);
        $block_table_taxa_hiato->draw();        

        $block_ranking = new Block(4);
        TextBuilderRM::generateIDH_ranking($block_ranking);
        $block_ranking->draw();
   
        //DEMOGRAFIA ------------------------------  
        $block_populacao = new Block(6);
        TextBuilderRM::generateDEMOGRAFIA_SAUDE_populacao($block_populacao);
        $block_populacao->draw();

        $block_table_populacao = new Block(16);
        TextBuilderRM::generateIDH_table_populacao($block_table_populacao);
        $block_table_populacao->draw();

        $block_etaria = new Block(7);
        TextBuilderRM::generateDEMOGRAFIA_SAUDE_etaria($block_etaria);
        $block_etaria->draw();

        $block_table_etaria = new Block(17);
        TextBuilderRM::generateIDH_table_etaria($block_table_etaria);
        $block_table_etaria->draw();

        $block_longevidade1 = new Block(8);
        TextBuilderRM::generateDEMOGRAFIA_SAUDE_longevidade1($block_longevidade1);
        $block_longevidade1->draw();

        $block_table_longevidade = new Block(18);
        TextBuilderRM::generateIDH_table_longevidade($block_table_longevidade);
        $block_table_longevidade->draw();
        
        $block_longevidade2 = new Block(8);
        TextBuilderRM::generateDEMOGRAFIA_SAUDE_longevidade2($block_longevidade2);
        $block_longevidade2->draw();
    
        //EDUCACAO ---------------------------------- 
        $block_nivel_educacional = new Block(9);
        TextBuilderRM::generateEDUCACAO_nivel_educacional($block_nivel_educacional);
        $block_nivel_educacional->draw();

        $block_populacao_adulta = new Block(10);
        TextBuilderRM::generateEDUCACAO_populacao_adulta($block_populacao_adulta);
        $block_populacao_adulta->draw();
    
        //RENDA -------------------------------------  
        $block_renda = new Block(11);
        TextBuilderRM::generateRENDA($block_renda);
        $block_renda->draw();

        $block_table_renda = new Block(27);
        TextBuilderRM::generateIDH_table_renda($block_table_renda);
        $block_table_renda->draw();

        $block_table_renda2 = new Block(19);
        TextBuilderRM::generateIDH_table_renda2($block_table_renda2);
        $block_table_renda2->draw();
    
        //TRABALHO ------------------------------------- 
        $block_trabalho1 = new Block(12);
        TextBuilderRM::generateTRABALHO1($block_trabalho1);
        $block_trabalho1->draw(); 

        $block_table_trabalho = new Block(20);
        TextBuilderRM::generateIDH_table_trabalho($block_table_trabalho);
        $block_table_trabalho->draw();
        
        $block_trabalho2 = new Block(25);
        TextBuilderRM::generateTRABALHO2($block_trabalho2);
        $block_trabalho2->draw();

        //HABITACAO ------------------------------------
        $block_habitacao = new Block(13);
        TextBuilderRM::generateHABITACAO($block_habitacao);
        $block_habitacao->draw();

        $block_table_habitacao = new Block(22);
        TextBuilderRM::generateIDH_table_habitacao($block_table_habitacao);
        $block_table_habitacao->draw();
  
        //VULNERABILIDADE ------------------------------  
        $block_vulnerabilidade = new Block(26);
        TextBuilderRM::generateVULNERABILIDADE($block_vulnerabilidade);
        $block_vulnerabilidade->draw();

        $block_table_vulnerabilidade = new Block(21);
        TextBuilderRM::generateIDH_table_vulnerabilidade($block_table_vulnerabilidade);
        $block_table_vulnerabilidade->draw();

       break;
   
//    case "en":
//    
//        //IDH ----------------------------------  
//        $block_componente = new Block(1);
//        TextBuilder_EN::generateIDH_componente($block_componente);        
//        $block_componente->draw();
//
//        $block_table_componente = new Block(15);
//        TextBuilder_EN::generateIDH_table_componente($block_table_componente);
//        $block_table_componente->draw();
//
//        $block_evolucao = new Block(2);
//        TextBuilder_EN::generateIDH_evolucao($block_evolucao);
//        $block_evolucao->draw();
//        
//        $block_table_taxa_hiato = new Block(24);
//        TextBuilder_EN::generateIDH_table_taxa_hiato($block_table_taxa_hiato);
//        $block_table_taxa_hiato->draw();        
//
//        $block_ranking = new Block(4);
//        TextBuilder_EN::generateIDH_ranking($block_ranking);
//        $block_ranking->draw();
//   
//        //DEMOGRAFIA ------------------------------  
//        $block_populacao = new Block(6);
//        TextBuilder_EN::generateDEMOGRAFIA_SAUDE_populacao($block_populacao);
//        $block_populacao->draw();
//
//        $block_table_populacao = new Block(16);
//        TextBuilder_EN::generateIDH_table_populacao($block_table_populacao);
//        $block_table_populacao->draw();
//
//        $block_etaria = new Block(7);
//        TextBuilder_EN::generateDEMOGRAFIA_SAUDE_etaria($block_etaria);
//        $block_etaria->draw();
//
//        $block_table_etaria = new Block(17);
//        TextBuilder_EN::generateIDH_table_etaria($block_table_etaria);
//        $block_table_etaria->draw();
//
//        $block_longevidade1 = new Block(8);
//        TextBuilder_EN::generateDEMOGRAFIA_SAUDE_longevidade1($block_longevidade1);
//        $block_longevidade1->draw();
//
//        $block_table_longevidade = new Block(18);
//        TextBuilder_EN::generateIDH_table_longevidade($block_table_longevidade);
//        $block_table_longevidade->draw();
//        
//        $block_longevidade2 = new Block(8);
//        TextBuilder_EN::generateDEMOGRAFIA_SAUDE_longevidade2($block_longevidade2);
//        $block_longevidade2->draw();
//    
//        //EDUCACAO ---------------------------------- 
//        $block_nivel_educacional = new Block(9);
//        TextBuilder_EN::generateEDUCACAO_nivel_educacional($block_nivel_educacional);
//        $block_nivel_educacional->draw();
//
//        $block_populacao_adulta = new Block(10);
//        TextBuilder_EN::generateEDUCACAO_populacao_adulta($block_populacao_adulta);
//        $block_populacao_adulta->draw();
//    
//        //RENDA -------------------------------------  
//        $block_renda = new Block(11);
//        TextBuilder_EN::generateRENDA($block_renda);
//        $block_renda->draw();
//
//        $block_table_renda = new Block(27);
//        TextBuilder_EN::generateIDH_table_renda($block_table_renda);
//        $block_table_renda->draw();
//
//        $block_table_renda2 = new Block(19);
//        TextBuilder_EN::generateIDH_table_renda2($block_table_renda2);
//        $block_table_renda2->draw();
//    
//        //TRABALHO ------------------------------------- 
//        $block_trabalho1 = new Block(12);
//        TextBuilder_EN::generateTRABALHO1($block_trabalho1);
//        $block_trabalho1->draw(); 
//
//        $block_table_trabalho = new Block(20);
//        TextBuilder_EN::generateIDH_table_trabalho($block_table_trabalho);
//        $block_table_trabalho->draw();
//        
//        $block_trabalho2 = new Block(25);
//        TextBuilder_EN::generateTRABALHO2($block_trabalho2);
//        $block_trabalho2->draw();
//
//        //HABITACAO ------------------------------------
//        $block_habitacao = new Block(13);
//        TextBuilder_EN::generateHABITACAO($block_habitacao);
//        $block_habitacao->draw();
//
//        $block_table_habitacao = new Block(22);
//        TextBuilder_EN::generateIDH_table_habitacao($block_table_habitacao);
//        $block_table_habitacao->draw();
//  
//        //VULNERABILIDADE ------------------------------  
//        $block_vulnerabilidade = new Block(26);
//        TextBuilder_EN::generateVULNERABILIDADE($block_vulnerabilidade);
//        $block_vulnerabilidade->draw();
//
//        $block_table_vulnerabilidade = new Block(21);
//        TextBuilder_EN::generateIDH_table_vulnerabilidade($block_table_vulnerabilidade);
//        $block_table_vulnerabilidade->draw();
//
//       break;

    default:
       break;

        $_GET = null;
        $_POST = null;
        $_REQUEST = null;
}
?>

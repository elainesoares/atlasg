<?php

//$comPath = BASE_ROOT . "/com/mobiliti//";

require_once '../../../../config/config_path.php';
require_once MOBILITI_PACKAGE . 'display/controller/PerfilPrint.class.php';
require_once MOBILITI_PACKAGE . 'display/controller/TextBuilder.class.php';
require_once MOBILITI_PACKAGE . 'display/Block.class.php';
//require_once $comPath . "util/protect_sql_injection.php";

//define("BLOCO_COMPONENTE", 1);
//define("TABLE_COMPONENTE", 15);
//define("BLOCO_EVOLUCAO", 2);
//define("BLOCO_RANKING", 4);

$cidade = $_POST["city"];
$pagina = (int) $_POST["page"];

unset($_POST["page"]);

$perfil = new PerfilPrint($cidade);

TextBuilder::$idMunicipio = $perfil->getCityId();
//TextBuilder::$nomeMunicipio = mb_convert_case($perfil->getCityName(), MB_CASE_TITLE, "UTF-8");
TextBuilder::$nomeMunicipio = $perfil->getCityName();
TextBuilder::$ufMunicipio = $perfil->getUfName();
TextBuilder::$print = true;
TextBuilder::$munTratado = $cidade;

switch ($pagina) {
    case 0:
        $perfil->drawScriptsMaps();
        $perfil->drawMap();
        $perfil->drawBoxes();

//    break;
//    case 1:
        
       // echo "<div style='margin-top:60%;'>";
        
//        //IDH ----------------------------------  
        $block_componente = new Block(1);
        TextBuilder::generateIDH_componente($block_componente);
        $block_componente->draw();

        $block_table_componente = new Block(15);
        TextBuilder::generateIDH_table_componente($block_table_componente);
        $block_table_componente->draw();

        $block_evolucao = new Block(2);
        TextBuilder::generateIDH_evolucao($block_evolucao);
        $block_evolucao->draw();
        
        $block_table_taxa_hiato = new Block(24);
        TextBuilder::generateIDH_table_taxa_hiato($block_table_taxa_hiato);
        $block_table_taxa_hiato->draw();        

        $block_ranking = new Block(4);
        TextBuilder::generateIDH_ranking($block_ranking);
        $block_ranking->draw();

//    break;
//    case 2:
        
       // echo "</div><div style='margin-top:55%;'>";
        
        //DEMOGRAFIA ------------------------------  
        $block_populacao = new Block(6);
        TextBuilder::generateDEMOGRAFIA_SAUDE_populacao($block_populacao);
        $block_populacao->draw();        
        
        $block_table_populacao = new Block(16);
        TextBuilder::generateIDH_table_populacao($block_table_populacao);
        $block_table_populacao->draw();

        $block_etaria = new Block(7);
        TextBuilder::generateDEMOGRAFIA_SAUDE_etaria($block_etaria);
        $block_etaria->draw();
        
       // echo "</div><div style='margin-top:62%;'>";
        
        $block_table_etaria = new Block(17);
        TextBuilder::generateIDH_table_etaria($block_table_etaria);
        $block_table_etaria->draw();

        $block_longevidade1 = new Block(8);
        TextBuilder::generateDEMOGRAFIA_SAUDE_longevidade1($block_longevidade1);
        $block_longevidade1->draw();

        $block_table_longevidade = new Block(18);
        TextBuilder::generateIDH_table_longevidade($block_table_longevidade);
        $block_table_longevidade->draw();
        
        $block_longevidade2 = new Block(8);
        TextBuilder::generateDEMOGRAFIA_SAUDE_longevidade2($block_longevidade2);
        $block_longevidade2->draw();
        
//    break;
//    case 3:

        //EDUCACAO ---------------------------------- 
        $block_nivel_educacional = new Block(9);
        TextBuilder::generateEDUCACAO_nivel_educacional($block_nivel_educacional);
        $block_nivel_educacional->draw();
        
        //echo "</div><div style='margin-top:40%;'>";
        
        $block_populacao_adulta = new Block(10);
        TextBuilder::generateEDUCACAO_populacao_adulta($block_populacao_adulta);
        $block_populacao_adulta->draw();

//    break;
//    case 4:
    
        //RENDA -------------------------------------  
        $block_renda = new Block(11);
        TextBuilder::generateRENDA($block_renda);
        $block_renda->draw();
// echo "</div><div style='margin-top:45%;'>";
        $block_table_renda = new Block(27);
        TextBuilder::generateIDH_table_renda($block_table_renda);
        $block_table_renda->draw();
        
       
        
        $block_table_renda2 = new Block(19);
        TextBuilder::generateIDH_table_renda2($block_table_renda2);
        $block_table_renda2->draw();

//    break;
//    case 5:
    
        //TRABALHO ------------------------------------- 
        $block_trabalho1 = new Block(12);
        TextBuilder::generateTRABALHO1($block_trabalho1);
        $block_trabalho1->draw();
        
       // echo "</div><div style='margin-top:25%;'>";
        
        $block_table_trabalho = new Block(20);
        TextBuilder::generateIDH_table_trabalho($block_table_trabalho);
        $block_table_trabalho->draw();
        
        $block_trabalho2 = new Block(25);
        TextBuilder::generateTRABALHO2($block_trabalho2);
        $block_trabalho2->draw();
        
//    break;
//    case 6:

        //HABITACAO ------------------------------------
        $block_habitacao = new Block(13);
        TextBuilder::generateHABITACAO($block_habitacao);
        $block_habitacao->draw();

        $block_table_habitacao = new Block(22);
        TextBuilder::generateIDH_table_habitacao($block_table_habitacao);
        $block_table_habitacao->draw();
        
//    break;
//    case 7:
        
     //   echo "</div><div style='margin-top:55%;'>";
        
        //VULNERABILIDADE ------------------------------  
        $block_vulnerabilidade = new Block(13);
        TextBuilder::generateVULNERABILIDADE($block_vulnerabilidade);
        $block_vulnerabilidade->draw();

        $block_table_vulnerabilidade = new Block(21);
        TextBuilder::generateIDH_table_vulnerabilidade($block_table_vulnerabilidade);
        $block_table_vulnerabilidade->draw();

       break;
    default:
        break;

        $_GET = null;
        $_POST = null;
        $_REQUEST = null;
}
?>

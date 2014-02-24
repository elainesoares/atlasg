<?php
    session_start();

    include_once './config/config_gerais.php';
    include_once './config/config_path.php';
    include_once './com/mobiliti/services/URL.class.php';
    
    
    function QuebrarUrlGet($url,$x){
        while(isset($url[$x])){
            $k = explode("=", addslashes($url[$x]));
            $_REQUEST[$k[0]] = $k[1];
            $x++;
        }
    }
    
    $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
    $gets = explode("/",$_GET["cod"]);
    
    //verifica a linguagem 
    $lang_var = null;
    $ltemp = "";
    
    if($gets[0] == "pt" || $gets[0] == "en" || $gets[0] == "es")
    {
        $ltemp = array_shift ( $gets );
    }
    else
    {
       $ltemp = "pt"; 
    }
  
    include_once 'config/langs/lang_' . $ltemp . '.php';
    $_SESSION["lang"] = $ltemp;

    
    include_once 'config/langs/LangManager.php'; 
    $lang_mng = new LangManager($lang_var);

    // ----- lingaguem
    
    $pag = @$gets[0];
    if (sizeof($gets)>1){
        $pagNext = $gets[1];
    }
    else{
        $pagNext = "";
    }
    if (sizeof($gets)>2){
        $pagNext2 = $gets[2];
    }
    else{
        $pagNext2 = "";
    }
    /*======================= Includes das páginas ===========================*/
    #Home
    if($pag == "home" || $pag == ""){
        if($pagNext == 'testes'){
            include "web/pagina_testes.php"; 
        }
        else if($pagNext == 'errourl'){
            include "web/pagina_erro_url.php"; 
        }
        else if($pagNext == 'teste2' && $pagNext != ''){
            include "web/home_teste2.php"; 
        }
        else if($pagNext != "teste" && $pagNext != ''){
            include 'web/pagina_nao_encontrada.php';
        }
        else if($pagNext == '' || $pag == 'home'){
            include "./home_base.php"; 
        }
    }
    
    else if($pag == "destaques"){
        if($pagNext != "" && $pagNext != "metodologia" && $pagNext != "faixas_idhm" && $pagNext != "idhm_brasil" && $pagNext != "educacao" && $pagNext != "longevidade" && $pagNext != "renda"){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
          include "./destaques_base.php";
        }
    }
    
    else if($pag == "destaque" && $pagNext == 'pdf'){
        $filename = 'destaque/pdf/'.$gets[2];
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename='.basename($filename));
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: '. filesize($filename));
        readfile($filename);
        die();
    }
    
    #Árvore do IDHM
    else if($pag == 'arvore'){
        if(sizeof($gets) >= 5 && $gets[4] != ""){
            $municipio1Arvore = $gets[1].'/'.$gets[2];
            $municipio2Arvore = $gets[3].'/'.$gets[4];
        }
        else if(sizeof($gets) < 5 || $pagNext == 'aleatorio'){
            $municipio1Arvore = 0;
            $municipio2Arvore = 0;
        }
        else if($pagNext == 'semconexao'){
            include "web/pagina_sem_conexao.php"; 
        }
            
        if($pagNext == 'aleatorio' || $pagNext == ''){
            $aleatorio = true;
        }
        else{
            $aleatorio = false;
        }
        
        if(($pagNext == 'aleatorio' && $pagNext2 == '') || $pagNext == 'municipio' || $pagNext == 'estado' || $pagNext == ''){
            include './arvore_base.php';
        }
        else{
            include 'web/pagina_nao_encontrada.php';
        }
        
    }
    
    #Impressão Árvore IDHM
    else if($pag == 'arvore_print'){
        if(sizeof($gets) >= 5 && $gets[4] != ""){
            $municipio1Arvore = $gets[1].'/'.$gets[2];
            $municipio2Arvore = $gets[3].'/'.$gets[4];
        }
        else if($pagNext == 'nulo' && $pagNext2 == 'nulo'){
            $municipio1Arvore = 'nulo';
            $municipio2Arvore = 'nulo';
        }
        
        else if($pagNext != 'nulo' && $gets[3] == 'nulo'){
            $municipio1Arvore = $gets[1].'/'.$gets[2];;
            $municipio2Arvore = 'nulo';
        }
        
        else if($pagNext == 'nulo' && $gets[3] != 'nulo'){
            $municipio1Arvore = 'nulo';
            $municipio2Arvore = $gets[2].'/'.$gets[3];
        }
        
        include './arvore_print_base.php';
    }
    
    #Ranking
    else if($pag == "ranking"){
        if($pagNext != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else if($pagNext == 'semconexao'){
            include "web/pagina_sem_conexao.php"; 
        }
        else{
            include "./ranking_base.php";
        }
    }
	
    #Graficos
    else if($pag == "graficos"){
        if($pagNext2 != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
            include "./graficos.php";
        }
    }
    
    #Consulta
    else if($pag == "consulta"){
        if($pagNext2 != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else if($pagNext == 'semconexao'){
            include "web/pagina_sem_conexao.php"; 
        }
        else if($pagNext == "imprimir"){
            if($pagNext2 != ""){
                include 'web/pagina_nao_encontrada.php';
            }
            include "com/mobiliti/tabela/imprimir.table.php";
            return;
        }
        else if($pagNext == "download"){
            if($pagNext2 != ""){
                include 'web/pagina_nao_encontrada.php';
            }
            include "com/mobiliti/tabela/download.table.php";
            return;
        }
        else if($pagNext != 'padrao' && $pagNext != 'imprimir' && $pagNext != 'download'){
            include "./index.php";
        }
        else{
            include "./index.php";
        }
    }

    #Perfis
    else if($pag == "perfil"){
            if($pagNext2 != ""){
                include 'web/pagina_nao_encontrada.php';
            }
            else if($pagNext == 'semconexao'){
                include "web/pagina_sem_conexao.php"; 
            }
            else{
                $MunicipioPefil = @$gets[1];
                include "./perfil_base.php";
            }
    }
    
//    #Perfil Municipal
//    else if($pag == "perfil_m"){
//            if($pagNext2 != ""){
//                include 'web/pagina_nao_encontrada.php';
//            }
//            else{
//                $MunicipioPefil = @$gets[1];
//                include "./perfil_base.php";
//            }
//    }
//    
//    #Perfil RMs
//    else if($pag == "perfil_rm"){
//            if($pagNext2 != ""){
//                include 'web/pagina_nao_encontrada.php';
//            }
//            else{
//                $MunicipioPefil = @$gets[1];
//                include "./perfil_base.php";
//            }
//    }
    
    #Perfil para Impressão
    else if($pag == "perfil_print"){
        if($pagNext2 != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
            $MunicipioPefil = $gets[1];
            include "./perfil_print_base.php";
        }    
    }
    
    #Pesquisa
    else if($pag == "pesquisa"){
        if($pagNext != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
            include "./tempInputs.php";
        }
    }
    
    #O Atlas
    else if($pag == "o_atlas"){
        if($pagNext != "" && $pagNext != "o_atlas_" && $pagNext != "quem_faz" && $pagNext != "para_que" && $pagNext != "processo" && $pagNext != "desenvolvimento_humano" && $pagNext != "idhm" && $pagNext != "metodologia" && $pagNext != "glossario" && $pagNext != "perguntas_frequentes" && $pagNext != 'tutorial'){
            include 'web/pagina_nao_encontrada.php';
        }
        else if($pagNext == 'metodologia' && $pagNext2 != 'idhm_longevidade' && $pagNext2 != 'idhm_educacao' && $pagNext2 != 'idhm_renda' && $pagNext2 != ''){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
            include "./o_atlas_base.php";
        }
    }

    #Download
    else if($pag == "download"){
        if($pagNext != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
            include "./download_base.php";
        }
    } 
    
     #Impressão do mapa
    else if($pag == "imprimir_mapa"){
        if($pagNext != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
            $MunicipioPefil = $gets[1];
            include "./imprimir_mapa.php";
        }
    }
    
    #Imprensa
    else if($pag == "imprensa"){
        if($pagNext != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
            include './imprensa_base.php';
        }
    }
    
    #Fale Conosco
    else if($pag == "fale_conosco"){
        if($pagNext != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
            include './fale_conosco_base.php';
        }
    }
    
    
    #Dados brutos
    else if($pag == "dadosbrutos")
    {
        if($pagNext != "")
        {
            $file =  'dadosbrutos/' . $pagNext;
            if (file_exists($file)) 
            {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                exit;
            }
        }
    }
    
    
    #Download de Navegadores
    else if($pag == "atualizacao_navegadores"){
        if($pagNext != ""){
            include 'web/pagina_nao_encontrada.php';
        }
        else{
            include 'web/consulta_desabilitada.php';
        }
    }
    else if($pag == "admin"){
//        include 'com/mobiliti/administrativo/main.php';
        include 'web/pagina_nao_encontrada.php';
    }else if($pag == "teste"){
        if($pagNext == 'bloqueio-geral')
            include 'testes/bloqueio_geral.php';
        elseif($pagNext == 'bloqueio-consulta')
            include 'testes/bloqueio_consulta.php';
    }else if($pag == "notas"){
            include 'web/avisos.php';
    }
    else if($pag == "update_lang")
    {
        if(isset($gets[1]))
           $lang  = $gets[1];
        else 
            $lang = "pt";

        
         include 'com/mobiliti/language/update_lang.php';
    }
    else{
        include 'web/pagina_nao_encontrada.php';
    }
?>

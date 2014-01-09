<?php
    $base="atlasg"; #descricao:Descricao exemplo

    $dominio="http://localhost";

    $path_dir="{$dominio}/{$base}/";
        
    define("BASE_ROOT","c:/ms4w/apps/$base/");

    //Caminho para o mapas IPEA
    define("URL_MAPAS_IPEA","$dominio/i3geo/");
    
    //Navegadores bloqueados
    $NavegadoresBloqueados=array("Firefox"=>6,"Safari"=>4);
    $NavegadoresBloqueadosGERAL=array("IE"=>8,"Firefox"=>4,"Opera"=>12,"Safari"=>3);
    
    /**
    * Configurações de mapas
    * verifica se é um servidor Linux ou Windows
    */
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
        //NÃO ALTERE AQUI ESSA É A VERSÃO WINDOWS!!
        define("MAP_FILE_ZERO", BASE_ROOT."com/mobiliti/map/zero.map");
        define("MAP_FILE_SELECTION", BASE_ROOT."com/mobiliti/map/selection.map");
        define("MAP_IMG_PATH", "/ms4w/apps/$base/tmp/");
        define("MAP_IMG_URL", "/$base/tmp/");  
    }
    else {
        define("MAP_FILE_ZERO","/var/www/$base/com/mobiliti/map/zero.map");
        define("MAP_FILE_SELECTION","/var/www/$base/com/mobiliti/map/selection.map");
        define("MAP_IMG_PATH","/var/www/$base/tmp/");
        define("MAP_IMG_URL","/$base/tmp/");
    }

    define("MAP_DELTA_X",8);
    define("MAP_HIGHLIGHT_COLOR_RED",255);
    define("MAP_HIGHLIGHT_COLOR_GREEN",255);
    define("MAP_HIGHLIGHT_COLOR_BLUE",255);
    
    define("EXIBIR_ALERTA_DOWNLOAD","true");
    define("EXIBIR_ALERTA_DOWNLOAD_MENSAGEM","Alguns dados são preliminares ou não estão disponíveis para o ano de 1991. Consulte os detalhes <a href=\'./notas/\' target=\'_blank\'>aqui</a>.");
        
       
    define("MOBILITI_PACKAGE",BASE_ROOT."com/mobiliti/");
  
    //Arquivo contendo TODA a base de indicadores (caminho completo, pois poderá estar em outro servidor)
    define("BASE_COMPLETA_XLS","dadosbrutos/atlas2013_dados_brutos.xls");
    //define("BASE_COMPLETA_XLS","https://dl.dropboxusercontent.com/u/26443338/altas2013_dados_brutos.xls");
    define("BASE_COMPLETA_CSV","https://dl.dropboxusercontent.com/u/104022554/relatorio.csv");
        
    //Arquivo template para exportar tabelas em XLS (caminho a partir da base)
    define("TEMPLATE_XLS","");

       
    //Limitador de exibição de resultados na tabela, default = 10
    define("LIMITE_EXIBICAO_TABELA",6000);
    
    //IDH dos indicadores do IDH
    define("INDICADOR_IDH",196);
    define("INDICADOR_LONGEVIDADE",198);
    define("INDICADOR_RENDA",197);
    define("INDICADOR_EDUCACAO",199);
    

    define("JS_LIMITE_TELA",113000);
    define("JS_LIMITE_DOWN",112000);


    
    define("QUINTIL_COLORS","{\"colors\":[\"#c5d9e7\",\"#92b8d3\",\"#6699c2\",\"#337eae\",\"#005f9d\"]}");
    define("BORDA_ESTADO","#999999");
    define("BORDA_CIDADE","#cccccc");
    
    
?>

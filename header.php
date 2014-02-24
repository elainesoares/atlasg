<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!-- saved from url=(0014)about:internet -->
<?php
   if(!isset($_SESSION)) 
   { 
        session_start(); 
   } 

    include_once("com/mobiliti/util/language.php");
    include_once("com/mobiliti/util/protect_sql_injection.php");
    include_once("config/config_path.php"); 
    include_once("config/config_gerais.php"); 
?>

<script type="text/javascript">
   var JS_LIMITE_TELA = "<?php echo JS_LIMITE_TELA;  ?>";
   var JS_LIMITE_DOWN = "<?php echo JS_LIMITE_DOWN;  ?>";
   
   var JS_INDICADOR_IDH = "<?php echo INDICADOR_IDH;  ?>";
   var JS_INDICADOR_LONGEVIDADE = "<?php echo INDICADOR_LONGEVIDADE;  ?>";
   var JS_INDICADOR_RENDA = "<?php echo INDICADOR_RENDA;  ?>";
   var JS_INDICADOR_EDUCACAO = "<?php echo INDICADOR_EDUCACAO;  ?>"; 
</script>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="FW 8 DW 8 XHTML" />
        <meta http-equiv="X-UA-Compatible" content="IE=IE8" />
        
        <base href="<?php echo $path_dir ?>" />
        
            <link rel="shortcut icon" href="img/icons/favicon.png">

        <!-- Folhas de estilo -->
        <link rel="stylesheet" href="com/mobiliti/componentes/indicador/seletor_indicador.css" type="text/css" />
        <link rel="stylesheet" href="com/mobiliti/componentes/local/seletor_lugares.css" type="text/css" />
        <link rel="stylesheet" href="com/mobiliti/componentes/local/local.css" type="text/css" />
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css" /> 
        <link rel="stylesheet" href="css/site.css" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css' />
        <!--[if lte IE 9]>
            <link rel="stylesheet" type="text/css" href="css/override-css-ie-9-lower.css" />
        <![endif]-->
        
        <link rel="stylesheet" media="screen" type="text/css" href="css/colorpicker/colorpicker.css" />
        <link rel="stylesheet" media="screen" type="text/css" href="css/colorpicker/layout.css" />
        
        <!--[if IE]>
        <style>
.blue_button{
    background-color: rgb(8, 122, 204) !important;
}
#buscaPerfil{
    line-height: 100% !important;
}</style>
        <![endif]-->
        <!--  Js Libs  -->
        <script src="<?php echo $path_dir ?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>        
        <script src="<?php echo $path_dir ?>bootstrap/js/bootstrap.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>js/bootstrapx-clickover.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>com/mobiliti/componentes/indicador/seletor_indicador.js" type="text/javascript"></script>
		<script src="<?php // echo $path_dir ?>com/mobiliti/componentes/indicador/seletor_indicador_graficos.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>js/history.min.js" type="text/javascript"></script>
        <script type='text/javascript' charset='utf-8' src="<?php echo $path_dir ?>js/simple-slider.js"></script>
        <script src="<?php echo $path_dir ?>js/search.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>js/jquery.imgareaselect.pack.js" type="text/javascript" ></script>
        <script src="<?php echo $path_dir ?>js/seletor-espacializacao.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>js/url.handler.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>js/util.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>js/jquery.cycle.all.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>js/teste.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>js/loading.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>com/mobiliti/componentes/selector/SelectorIndicator.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>com/mobiliti/componentes/local/local.js" type="text/javascript"></script>
		<script src="<?php echo $path_dir ?>com/mobiliti/componentes/local/local_graficos.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>com/mobiliti/componentes/local_indicador/local.js" type="text/javascript"></script>
		<script src="<?php // echo $path_dir ?>com/mobiliti/componentes/local_indicador/local_graficos.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>com/mobiliti/componentes/geral/geral.js" type="text/javascript"></script>
        <script src="<?php echo $path_dir ?>com/mobiliti/componentes/local/seletor_lugares.js" type="text/javascript"></script>
		<script src="<?php echo $path_dir ?>com/mobiliti/componentes/local/seletor_lugares_graficos.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo $path_dir ?>js/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="<?php echo $path_dir ?>js/jquery.jscrollpane.min.js"></script>
        <script type="text/javascript" src="<?php echo $path_dir ?>js/scroll-startstop.events.jquery.js"></script>
        <script src="<?php echo $path_dir ?>js/styleRadioCheckbox.js" type="text/javascript"></script>
        
        <script type="text/javascript" src="js/colorpicker/colorpicker.js"></script>
        <script type="text/javascript" src="js/colorpicker/eye.js"></script>
        <script type="text/javascript" src="js/colorpicker/utils.js"></script>
        <script type="text/javascript" src="js/colorpicker/layout.js?ver=1.0.2"></script>
        
        <script type="text/javascript" src="config/langs/LangManager.js"></script>
        
        <?php

            $ltemp = @$_SESSION["lang"];
             
            switch($ltemp)
            {
                case "pt":
                    include_once 'config/langs/lang_pt.php';
                    break;
                case "en":
                    include_once 'config/langs/lang_en.php';
                    break;
                case "es":
                    include_once 'config/langs/lang_es.php';
                    break;
                default :
                    include_once 'config/langs/lang_pt.php';
                    $_SESSION["lang"] = "pt";
                    break;
            } 
            
           
            include_once 'config/langs/LangManager.php';             
            $lang_mng = new LangManager($lang_var);
        ?>
        <script type="text/javascript">
            var global_pvt_lang_object =  <?php echo json_encode($lang_var);?> ;
            var lang_mng = new LangManager();
        </script>
            
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />

        <!-- tags seo facebook -->
        <meta property='og:title' content='<?php if(isset($meta_title2)) echo $meta_title2; else echo $meta_title; ?>' />
        
        <?php $geral_title = $lang_mng->getString("geral_title");
        ?>
        
        
        <meta property='og:url' content='<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI']; ?>'/>
        <meta property='og:image' content='<?php echo $path_dir;?>img/marca vertical cor.png'/>
        <meta property='og:type' content='website' />
        <meta property='og:site_name' content='<?php if(isset($title2)) echo $title2.' | '.$geral_title; else echo $title.' | '.$geral_title; ?>'/> 
        <meta name="description" content="<?php if(isset($meta_description2)) echo $meta_description2; else echo $meta_description; ?>" />
        <meta property="og:description" content="<?php if(isset($meta_description2)) echo $meta_description2; else echo $meta_description; ?>" />
        <title><?php if(isset($title2)) echo $title2.' | '.$geral_title; else echo $title.' | '.$geral_title; ?></title>
	
	<meta name="twitter:card" content="summary">
	<meta name="twitter:url" content="<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI']; ?>">
	<meta name="twitter:title" content="<?php if(isset($title2)) echo $title2; else echo $title.' | '.$geral_title; ?>'">
	<meta name="twitter:description" content="<?php if(isset($meta_description2)) echo $meta_description2; else echo $meta_description; ?>">
	<meta name="twitter:image" content="<?php echo $path_dir;?>img/marca vertical cor.png">
    </head>
    
    
    
    
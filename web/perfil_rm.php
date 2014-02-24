<?php
//header('Content-Type: text/html; charset=utf-8');
ob_start();
/*
  require_once 'config/conexao.class.php';
  require_once 'com/mobiliti/consulta/bd.class.php';
  require_once 'com/mobiliti/display/IDisplayTemplate.class.php';
  require_once 'com/mobiliti/display/BlockTabela.class.php';
  require_once 'com/mobiliti/display/Block.class.php';
  require_once 'com/mobiliti/util/protect_sql_injection.php';
 * 
 */

set_include_path(get_include_path() . PATH_SEPARATOR . "com/mobiliti/");
require_once 'display/controller/PerfilRM.class.php';

$perfil = new PerfilRM($MunicipioPefil);

?>

<meta name="description" content="" />
<script type="text/javascript">

    $(document).ready(function()
    {
        $('#toolTipPrintDown').tooltip({html:true, delay: 500});
        $("#perfil_title").html(lang_mng.getString("home_titlePerfilRM"));
        
       // readyGo();
    });
    
</script>

<div id="content">
    <div class="containerPerfilTop" id="teste2">
        <div class="containerTitlePage">
            <div class="titlePerfilPage">
                <div class="titletopPage" id="perfil_title"></div>

<?php if ($MunicipioPefil != null) { ?>
    <a href="<?php echo $path_dir . "perfil_print/" . $MunicipioPefil ?>" target="_blank" onMouseOver="document.images['iconPerfilImpressao'].src = 'img/icons/print_gray.png';" onMouseOut="document.images['iconPerfilImpressao'].src = 'img/icons/print_gray.png';">            
    <div class="btn_print" data-original-title='Gera perfil em modo de impressão e/ou realiza download em formato PDF.' id='toolTipPrintDown' style="float:right; margin-top: 40px;">  
        <button type="button" class="gray_button big_bt" >     
            <img  src="img/icons/print_gray.png"/>
        </button>    
    </div>
</a>
<?php } ?>

            </div>
        </div>
        <div class="perfil-search-main"  id="perfil_search">
            <a class="buttonBuscaPagePerfil" onclick="buscaPerfil()" id="busca"><img class="lupaBusca" src="./img/lupa_busca.png" /></a>
        </div>
        <div id="erroBusca" class="erro_BuscaPerfil">*Selecione um município para continuar</div>
         
<?php
$perfil->drawScripts();   
$perfil->drawNome();
$perfil->drawMenu();
?>
    </div>
</div>

<div id="MainContentPerfil" class="blockArea">
</div>
<?php
//==========================================================================
//Include corpo do site
//==========================================================================

$content = ob_get_contents();
if ($MunicipioPefil == null) 
    $title = $lang_mng->getString("mapa_title");
else
    $title = $lang_mng->getString("mapa_title_mun").$perfil->getNomeCru();
$meta_title = $lang_mng->getString("mapa_metaTitle");
$meta_description = $lang_mng->getString("mapa_metaDescricao");
ob_end_clean();
include "base.php";
$BancoDeDados = null;
?>


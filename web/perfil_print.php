<?php
header('Content-Type: text/html; charset=utf-8');
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
require_once 'display/controller/PerfilPrint.class.php';
$perfil = new PerfilPrint($MunicipioPefil);
?>

<script type="text/javascript">

    $(document).ready(function()
    {
        $('#toolTipPrint').tooltip({html:true, delay: 500});
        $('#toolTipDown').tooltip({html:true, delay: 500});
       // readyGo();
    });
    
</script>

<div id="content">
    <div class="containerPerfilTopPrint">
        <div class="containerTitlePagePrint">
            <div style="margin-top: -10px; float: right; width: 144px;"> 
                <a onclick="javascript:self.print();" style="cursor: pointer;" onMouseOver="document.images['iconPerfilImpressao'].src = 'img/icons/print_gray.png';" onMouseOut="document.images['iconPerfilImpressao'].src = 'img/icons/print_gray.png';">
                    <div class="btn_print" data-original-title='Imprimir Perfil' style="margin-top: 14px;" id='toolTipPrint'>
                        <button type="button" class="gray_button big_bt" >
                        <!--<a onclick="javascript:self.print();" style="cursor: pointer;" onMouseOver="document.images['iconPerfilImpressao'].src = 'img/icons/imprimirdown.png';" onMouseOut="document.images['iconPerfilImpressao'].src = 'img/icons/imprimir.png';">-->
                            <img src="img/icons/print_gray.png"/> 
                        </button>
                    </div>
                </a>
                <?php
                    $divisao = explode('_', $MunicipioPefil);
                    $nomeCru = mb_convert_case($divisao[0], MB_CASE_TITLE, "UTF-8");
                    $ufCru = $divisao[1];
                ?>
                <a href="http://pdfcrowd.com/url_to_pdf/?pdf_name=AtlasIDHM2013_Perfil_<?php echo $nomeCru . "_". $ufCru;?>&footer_text=Pag.%p%20de%n%20" onMouseOver="document.images['iconPerfilDownload'].src = 'img/icons/download_gray.png';" onMouseOut="document.images['iconPerfilDownload'].src = 'img/icons/download_gray.png';">
                    <div class="btn_print" data-original-title='Download PDF' style="margin-right: 10px; margin-top: 14px;" id='toolTipDown'>
                        <button type="button" class="gray_button big_bt" >
                        <!--<a href="http://pdfcrowd.com/url_to_pdf/" onMouseOver="document.images['iconPerfilDownload'].src = 'img/icons/downloaddown.png';" onMouseOut="document.images['iconPerfilDownload'].src = 'img/icons/download.png';">-->
                            <img src="img/icons/download_gray.png"/>
                        </button>
                    </div>
                </a>
            </div>
        </div>

<?php
$perfil->drawScripts();
if ($MunicipioPefil != null) {
    $perfil->drawNome();
}
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
ob_end_clean();
$title = "Perfil do Município de " . str_replace("-"," ",$nomeCru) . ", ". strtoupper(str_replace("-"," ",$ufCru));;
include "base.php";
$BancoDeDados = null;
?>


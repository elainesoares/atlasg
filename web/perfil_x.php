<?php
//header('Content-Type: text/html; charset=utf-8');
ob_start();

?>

<meta name="description" content="" />

<div id="content">
    <div class="containerPerfilTop" id="teste2">
        <div class="containerTitlePage">
            <div class="titlePerfilPage">
                <div class="titletopPage" id="perfil_title"></div>

                <li><a href="<?php echo $_SESSION["lang"];?>/perfil_m/" id="menu_perfil">Perfil Municipal</a></li>
                <li><a href="<?php echo $_SESSION["lang"];?>/perfil_rm/" id="menu_perfil">Perfil RMs</a></li>

                
            </div>
        </div>
    </div>
</div>

<?php
//==========================================================================
//Include corpo do site
//==========================================================================

$content = ob_get_contents();
ob_end_clean();
include "base.php";
$BancoDeDados = null;
?>


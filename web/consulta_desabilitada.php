<?php ob_start(); ?>
<div id="content">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage">Consulta</div>
            </div>
            <div class="iconAtlas">
                <img src="./img/icons/table_gray.png" class="buttonDesabilitado">
                <img src="./img/icons/brazil_gray.png" class="buttonDesabilitado">
            </div>
        </div>
    </div>
</div>
<div class="linhaDivisoria"></div>
<?php include 'download_navegadores.php'; ?>

<?php 
    $title = "Atualização de Navegador";
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
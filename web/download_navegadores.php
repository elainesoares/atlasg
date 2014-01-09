
<div id="content">
    <div class="contentAtualizacaoNav">
        <div class="areaImagemErro" style=" ">
            <img src="./img/icons/error.png" style=""/>
        </div>
        <div class="containerAtualizacao" style=" ">
            <?php if (isset($blockall)) { ?>
                Atenção! Seu navegador está desatualizado e você não pode acessar o Atlas.
            <?php } else if(isset($is_mobile)) { ?>
                Atenção! Seu dispositivo não suporta o Atlas.
            <?php }else { ?>
                Atenção! Seu navegador está desatualizado e você não pode acessar algumas funcionalidades do Atlas.
            <?php }?>
        </div>
        <p style="margin-bottom: 20px;">Atualize o seu navegador favorito para continuar:</p>
        <p><img src="./img/icons/firefox.png" style="margin-right: 20px;"/><a href="https://www.mozilla.org/pt-BR/firefox/new/" target="_blank">Firefox</a></p>
        <p><img src="./img/icons/googlechrome.png"  style="width: 32px; height: 32px; margin-right: 20px;"/><a href="http://www.google.com/intl/pt-BR/chrome/" target="_blank">Google Chrome</a></p>
        <p><img src="./img/icons/safari.png"  style="width: 32px; height: 32px; margin-right: 20px;"/><a href="http://www.apple.com/br/safari/" target="_blank">Safari</a></p>
        <p><img src="./img/icons/ie.png"  style="width: 32px; height: 32px; margin-right: 20px;"/><a href="http://www.microsoft.com/pt-br/download/internet-explorer-10-details.aspx" target="_blank">Internet Explorer</a></p>
        <p style="margin-bottom: 20px;">Ou baixe os dados brutos:
        </p>
        <p>
            <a href="<?php echo BASE_COMPLETA_CSV ?>">
                <img src="./img/btn_download.png" />
            </a>
            <br />
            <br />
            <br />
        </p>
        <div class="clear"></div>
    </div>
</div>



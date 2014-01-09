<?php
    $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
    $gets = explode("/",$url);
    $pag = $gets[2];
?>
<?php //if($pag != 'perfil_print') {?>
<div class="clear"></div>
<div id="footer">
    <div class="footerbottomCenter">
        <div class="footerLeft">
            <div class="redesSociais">
                 Acompanhe as novidades
                 <p>
                    <a href="<?php echo $home_FooterFacebook ?>" target="_blank"><img src="./img/footer/facebook.png" alt="Facebook"/></a>
                    <a href="<?php echo $home_FooterTwitter ?>" target="_blank"><img src="./img/footer/twitter.png" alt="Twitter"/></a>
                    <a href="<?php echo $home_FooterYoutube ?>" target="_blank"><img src="./img/footer/youtube.png" alt="Youtube"/></a>
                 </p>
             </div>
             <div class="menuFooter">
                 <?php // echo $home_faleConosco; ?>
                <!--<a href="<?php //echo $home_imprensa;?>" target="_blank" >Imprensa</a><br />-->
                <a href="<?php echo $path_dir.$home_perguntasFrequentes;?>">Perguntas Frequentes</a><br />
            </div>
            <div class="versao">
            <?php 
                //if($versao == true)
                 //   echo $versao; 
            ?></div>
        </div>
        <div class="footerRight">
            <div class="realizacao">
            <p>Realização:<p>
            <div class="logosFooter">
                <a href="<?php echo $home_footerPNUD  ?>" target="_blank"><img src="./img/footer/logo_PNUD.png" alt="PNUD"/></a>
                <a href="<?php echo $home_footerFJP  ?>" target="_blank"><img src="./img/footer/logo_fundacao_ joao_pinheiro.png" style="margin-top: 30px; border-left: 2px solid #CDCDCD; border-right: 2px solid #CDCDCD; padding-right: 5px; padding-left: 5px;" alt="Fundação João Pinheiro"/></a>
                <a href="<?php echo $home_footerIPEA ?>" target="_blank"><img src="./img/footer/logo_ipea.png" style="margin-top: 55px;" alt="IPEA"/></a>
            </div>
        </div>
        </div>
    </div>
</div>

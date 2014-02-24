<?php ob_start(); ?>
<div class="contentPaginaNaoEncontrada">
       <div class="containerPaginaNaoEncontrada">
           <div class="areaImagemErro">
                <img src="./img/icons/error.png"/>
           </div>
           <div class="erro_PaginaNaoEncontrada">
               <p class="p1_erro">Erro - Desconfiguração URL</p>
               <p class="p2_erro">A página que você procura não pôde ser acessada</p>
               <p class="p_erromotivo">Possíveis motivos:</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Domínio configurado não corresponde ao domínio utilizado.</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Nome base do projeto configurado não corresponde ao nome utilizado.</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Nome base do projeto configurado não corresponde ao nome do projeto na pasta raiz.</p>
           </div>
           <div class="clear"></div>
       </div>
</div>

<?php 
    $title = 'Erro - Desconfiguração URL';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
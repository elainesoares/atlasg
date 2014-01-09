<?php ob_start(); ?>
<div class="contentPaginaNaoEncontrada">
       <div class="containerPaginaNaoEncontrada">
           <div class="areaImagemErro">
                <img src="./img/icons/error.png"/>
           </div>
           <div class="erro_PaginaNaoEncontrada">
               <p class="p1_erro">Erro 404</p>
               <p class="p2_erro">A página que você procura não pôde ser encontrada.</p>
               <p class="p_erromotivo">Possíveis motivos:</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>O conteúdo não está mais no ar.</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>A página mudou de lugar.</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Você digitou o endereço errado.</p>
           </div>
           <div class="clear"></div>
       </div>
</div>

<?php 
    $title = 'Erro - Página não encontrada';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
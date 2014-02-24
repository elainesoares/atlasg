<?php ob_start(); ?>
<div class="contentPaginaNaoEncontrada">
       <div class="containerPaginaNaoEncontrada">
           <div class="areaImagemErro">
                <img src="./img/icons/error.png"/>
           </div>
           <div class="erro_PaginaNaoEncontrada">
               <p class="p1_erro">Erro - Sem conexão ao Banco de Dados</p>
               <p class="p2_erro">A página que você procura não pôde ser acessada</p>
               <p class="p_erromotivo">Possíveis motivos:</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Dados de acesso ao banco não são válidos.</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Usuário de acesso ao banco não tem permissão.</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Servidor de banco de dados indisponível.</p>
           </div>
           <div class="clear"></div>
       </div>
</div>

<?php 
    $title = 'Erro - Sem conexão ao Bando de Dados';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
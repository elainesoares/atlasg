

    <div id='btnInTeste' class="tempInputs"></div>
<?php
    
    $content = ob_get_contents();
    $title = "Pesquisa por texto";
    ob_end_clean();
    include "web/base.php";
    $BancoDeDados = null;
?>

<div class="contentCenterMenu">
    <div class="mainMenuTopPrint">   
        <div class="imgLogoPrint">
            <img src="./img/logos/branca.png" alt="IDH" style="z-index: 1"/>
        </div>
        <?php 
            if($pag == 'perfil_print'){
        ?>
            <script src="js/charts/function_format.js" type="text/javascript"></script> 
            <script src="js/charts/charts_perfil.js" type="text/javascript"></script>
        <?php
                echo "<div class='titlePrintPerfil'>".$title_print."</div>";
            }            
            else{
                echo "<div class='titlePrint'>".$title_print."</div>";
            }
        ?>
        
    </div> 
</div>
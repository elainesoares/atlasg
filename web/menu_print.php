<div class="contentCenterMenu">
    <div class="mainMenuTopPrint">   
        <div class="imgLogoPrint">
            <img src=<?php echo "./img/logos/branca_pt.png";?> alt="" style="z-index: 1"/>
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
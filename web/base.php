<?php
    include("./header.php");
    include_once('config/conexao.class.php');
    
    $base_expl = explode("/",$base);
    $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
    $gets = explode("/",$_GET["cod"]);
    
    $ltemp = "";
    
    if($gets[0] == "pt" || $gets[0] == "en" || $gets[0] == "es")
    {
        array_shift ( $gets );
    }    

        $pag = $gets[0];
        if(sizeof($gets)>1){
	$pagNext = $gets[1];
        }
    
?>
<script>
    $(document).ready(function(){
        rez();
    })
function rez(){
    pag = '<?=$pag?>';
    pagNext = '<?=$pagNext?>';
    pagNext2 = '<?=$pagNext2?>';
    if(pag == 'destaques' || pag == 'consulta' || pag == 'perfil' || pag == 'download' || pag == 'ranking' || pag == 'arvore' || 
        (pag == 'o_atlas' && (pagNext == '' || pagNext == 'o_atlas_' || pagNext == 'quem_faz' || pagNext == 'para_que' || pagNext == 'processo' || pagNext == 'desenvolvimento_humano' || pagNext == 'idhm' || pagNext == 'metodologia' && (pagNext2 == 'idhm_longevidade' || pagNext2 == 'idhm_educacao' || pagNext2 == 'idhm_renda') || pagNext == 'glossario' || pagNext == 'perguntas_frequentes' || pagNext == 'tutorial' || pagNext == ''))){
        document.getElementById("setaMenu").style.display = 'block';
        var pos = $(".mainMenuTopUl .ativo").position();
        var largura = $(".mainMenuTopUl .ativo").css("width");
        tamanho = parseInt(largura.length);
        tamanho2 = tamanho - 2;
        numberLargura = parseInt(largura.substr(0,tamanho2));
        pos.top = pos.top + 46;
        metLargura = numberLargura / 2;
        pos.left = parseInt(pos.left + metLargura - 35) ;
        
        $("#setaMenu").css("left",pos.left+"px");
        $("#setaMenu").css("top",pos.top+"px");
    }
}

</script>
<body id="body" onresize="rez()">
    <?php
        require_once 'block_all.php';
        if($pag == "destaques" || $pag == "consulta" || $pag == "perfil" || $pag == "ranking" || $pag == "o_atlas" || $pag == "download" || $pag == "arvore"){
            echo "<div class='contentMenu' style=''>";
            require_once "web/menu.php";
            echo "</div>
                <div class='speratorShadow'></div>
            ";
        }
        else if($pag == 'arvore_print' || $pag == 'perfil_print' || $pag == 'imprimir_mapa'){
            require_once 'web/menu_print.php';
            echo "<div class='speratorShadow'></div>";
        }
        else if($pag == 'home' || $pag == ''){
            echo "<div class='contentMenu'>";
            require_once 'web/menu.php';
            echo "</div>"; 
           
        }
        
        else{
            echo "<div class='contentMenu'>";
            require_once 'web/menu.php';
            echo "</div>"; 
            echo "<div class='speratorShadow'></div>";
        }
    ?>
    <div id="center" class="defaltWidthContent">
        <?php echo $content; ?>
    </div>
    
    <?php
        $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
        $gets = explode("/",$_GET["cod"]);
    
        if($gets[0] == "pt" || $gets[0] == "en" || $gets[0] == "es"){
            array_shift ( $gets );
        }
            $pag = $gets[0];
	if(sizeof($gets)>1){
                $pagNext = $gets[1];
	}
        
        if($pag == "destaques" || $pag == "consulta" || $pag == "perfil" || $pag == "ranking" || $pag == "o_atlas" || $pag == "download" || $pag == "arvore"){
            echo "<div class='speratorShadowFooter'></div>";
            include 'web/footer.php';
        }
    
        else if($pag == 'arvore_print' || $pag == 'perfil_print' || $pag == 'imprimir_mapa'){
             echo "<div class='speratorShadowFooter'></div>";
            include("web/footer_print.php");
        }
        
        else if($pag == 'home' || $pag == ''){
            if($pagNext != '')
                echo "<div class='speratorShadowFooter'></div>";
            include("web/footer.php");
        }
        
        else{
            echo "<div class='speratorShadowFooter'></div>";
            include("web/footer_print.php");
        }
    ?>
    <div id="maskTransparent"></div>
    <div id="contentLoading">
	<div id='contentLoading-text' style='margin-top:0px !important'></div>
	<div><img src='img/map/ajax-loader.gif' /></div>
    </div>
</body>

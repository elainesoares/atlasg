<?php
    include("./header.php");
    include_once('config/conexao.class.php');
    
	$base_expl = explode("/",$base);
        $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
        $gets = explode("/",$url);
	if(sizeof($base_expl)== 1){
		$pag = $gets[2];
		if(sizeof($gets)>3){
			$pagNext = $gets[4];
		}
	}
	else if(sizeof($base_expl)== 2){
		$pag = $gets[3];
		if(sizeof($gets)>3){
			$pagNext = $gets[3];
		}
	}
    
?>
<!--<script>
    $(document).ready(function(){
        rez();
    })
function rez(){
    pag = '<?=$pag?>';
    pagNext = '<?=$pagNext?>';
    pagNext2 = '<?=$pagNext2?>';
    if(pag == 'destaques' || pag == 'consulta' || pag == 'perfil' || pag == 'download' || pag == 'ranking' || pag == 'arvore' || pag == 'o_atlas'){
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

</script>-->
<body id="body" onresize="rez()">
    <?php
    
//        $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
//        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
//        $palmpre = strpos($_SERVER['HTTP_USER_AGENT'], "webOS");
//        $berry = strpos($_SERVER['HTTP_USER_AGENT'], "BlackBerry");
//        $ipod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
//        if ($iphone || $android || $palmpre || $ipod || $berry == true) {
//            $is_mobile = true;
//            include BASE_ROOT.'web/comp_bloqueio.php';
//            include("footer_print.php");
//            die();
//        }
//        require_once 'block_all.php';
//        if($pag == "destaques" || $pag == "consulta" || $pag == "perfil" || $pag == "ranking" || $pag == "o_atlas" || $pag == "download" || $pag == "arvore"){
//            echo "<div class='contentMenu' style=''>";
//            require_once "web/menu.php";
//            echo "</div>
//                <div class='speratorShadow'></div>
//            ";
//        }
//        else if($pag == 'arvore_print' || $pag == 'perfil_print' || $pag == 'imprimir_mapa'){
//            require_once 'web/menu_print.php';
//            echo "<div class='speratorShadow'></div>";
//        }
//        else if($pag == 'home' || $pag == ''){
//            echo "<div class='contentMenu'>";
//            require_once 'web/menu.php';
//            echo "</div>"; 
//            if($pagNext != '')
//                echo "<div class='speratorShadow'></div>";
//        }
        
//        else{
        if($pag == 'graficos'){
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
	if(sizeof($base_expl)== 1){
		$pag = $gets[2];
		if(sizeof($gets)>3){
			$pagNext = $gets[3];
		}
	}
	else if(sizeof($base_expl)== 2){
		$pag = $gets[3];
		if(sizeof($gets)>3){
			$pagNext = $gets[3];
		}
	}
        if($pag == "destaques" || $pag == "consulta" || $pag == "perfil" || $pag == "ranking" || $pag == "o_atlas" || $pag == "download" || $pag == "arvore" || $pag == 'graficos'){
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
    <div id="contentLoading"></div>
</body>

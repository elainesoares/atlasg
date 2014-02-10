<?php
    ob_start(); 
    include ('./config/config_home.php');
    $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);

	$base_expl = explode("/",$base);
    $gets = explode("/",$url);
	if(sizeof($base_expl)== 1){
		$pag = $gets[2];
		if(sizeof($gets)> 3){
			$pagNext = $gets[3];
		}
		else{
			$pagNext = '';
		}
		if(sizeof($gets) > 4){
			$pagNext2 = $gets[4];
		}
		else{
			$pagNext2 = '';
		}
	}
	else if(sizeof($base_expl)== 2){
		$pag = $gets[3];
		if(sizeof($gets) > 4){
			$pagNext = $gets[4];
		}
		else{
			$pagNext = '';
		}
		if(sizeof($gets) > 5){
			$pagNext2 = $gets[5];
		}
		else{
			$pagNext2 = '';
		}
	}
?>

<?php
if($pag == 'perfil' || $pag == 'perfil_print'){
?>
<script src="js/charts/function_format.js" type="text/javascript"></script> 
<script src="js/charts/charts_perfil.js" type="text/javascript"></script>
<?php
}
?>

<div class="contentCenterMenu">
    <div class="mainMenuTop">   
        <img src="./img/setaMenu.png" id="setaMenu" style="display: none;position: absolute; width: 80px" alt=""/>
        <div class="imgLogo">
            <a href=""><img src="./img/logos/branca.png" alt="IDH"/></a>
            <?php
                if($tarja_EmDesenvolvimento == true){
                    echo "<div class='tarja_EmDesenvolvimento'>
                        <img src='./img/em_desenvolvimento.png' />
                    </div>";
                }
            ?>
        </div>
        <ul class="mainMenuTopUl">
         
        </ul>
    </div> 
</div>

<!--<script type="text/javascript">
        pag = '<?=$pag?>';
        pagNext = '<?=$pagNext?>';
        pagNext2 = '<?=$pagNext2?>';
        $(document).ready(function(){
            rez();
        if(
        (pag == 'destaques')
            | (pag == 'consulta' && pagNext == 'padrao' && pagNext2 == '') | (pag == 'perfil' && pagNext2 == '') | (pag == 'download' | pag == 'ranking') | 
            (pag == 'arvore' && (pagNext == '' || pagNext == 'municipio' || pagNext == 'estado' || pagNext == 'aleatorio'))  | (pag == 'o_atlas' && pagNext == '')){
            document.getElementById("setaMenu").style.display = 'block';
            var pos = $(".mainMenuTopUl .ativo").position();
            var largura = $(".mainMenuTopUl .ativo").css("width");
            tamanho = parseInt(largura.length);
            tamanho2 = tamanho - 2;
            numberLargura = parseInt(largura.substr(0,tamanho2));
            pos.top = pos.top + 46;
            metLargura = numberLargura / 2;
            pos.left = parseInt(pos.left + metLargura - 37) ;

            $("#setaMenu").css("left",pos.left+"px");
            $("#setaMenu").css("top",pos.top+"px");
            }
        });
</script>-->

<?php 
//    $content = ob_get_contents();
//    ob_end_clean();
?>
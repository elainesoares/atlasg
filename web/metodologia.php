<?php
    //require_once '../url.php';
//    echo $pag;
    $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
    $gets = explode("/",$url);
    $pag = $gets[3];
    
    $server = $_SERVER['SERVER_NAME']; 
    $endereco = $_SERVER ['REQUEST_URI'];
    $url = $server.$endereco;
    $separator = (explode('/', $url));
	$base_expl = explode("/",$base);
?>

<script type="text/javascript">
    function myfunction2(valor){
        pag = '<?=$path_dir?>' + 'o_atlas/metodologia/';

        if(valor == 1){
            url = pag + "idhm_longevidade";
        }

        else if(valor == 2){
            url = pag + "idhm_educacao";
        }

        else if(valor == 3){
            url = pag + "idhm_renda";
        }
        
        location.href= url;
    }
</script>

<div id="processo" style="width:900px; height: 1600px;">
    <div id ="areatitle">Metodologia do IDHM 2013</div>
    
    <div class="menuAtlasMet">
        <ul class="menuAtlasMetUl">
            <li><a onclick="myfunction2('1')" 
                <?php 
                if(sizeof($base_expl)== 1){
                    if($separator[4] == 'idhm_longevidade' || $separator[3] == '') {echo 'class="ativo2"';}
                }
                 else if (sizeof($base_expl)== 2){
                     if($separator[5] == 'idhm_longevidade' || $separator[4] == '') {echo 'class="ativo2"';}
                 }
                 ?>>IDHM LONGEVIDADE</a><span class='ballMarker'>&bull;</span></li>
            <li><a onclick="myfunction2('2')" 
                <?php 
                    if(sizeof($base_expl)== 1){
                        if($separator[4] == 'idhm_educacao') {echo 'class="ativo2"';}
                    }
                    else if (sizeof($base_expl)== 2){
                       if($separator[5] == 'idhm_educacao') {echo 'class="ativo2"';} 
                    }
                 ?> >IDHM EDUCAÇÃO</a><span class='ballMarker'>&bull;</span></li>
            <li><a onclick="myfunction2('3')" 
                <?php 
                    if(sizeof($base_expl)== 1){
                        if($separator[4] == 'idhm_renda') {echo 'class="ativo2"';}
                    }
                    else if (sizeof($base_expl)== 2){
                        if($separator[5] == 'idhm_renda') {echo 'class="ativo2"';}
                    }
                 ?> >IDHM RENDA</a></li>
        </ul>
    </div>
    <div class="linhaDivisoriaMet"></div>
    
    <?php
	
        if(sizeof($base_expl)== 1){
                if($separator[4] == 'idhm_longevidade' || $separator[4] == ''){
                    include 'idhm_longevidade.php';
                }
                
                else if($separator[4] == 'idhm_educacao'){
                    include 'idhm_educacao.php';
                }
                
                else if($separator[4] == 'idhm_renda'){
                    include 'idhm_renda.php';
                }
        }
        else if(sizeof($base_expl)== 2){
            if($separator[5] == 'idhm_longevidade' || $separator[5] == ''){
                    include 'idhm_longevidade.php';
                }
                
                else if($separator[5] == 'idhm_educacao'){
                    include 'idhm_educacao.php';
                }
                
                else if($separator[5] == 'idhm_renda'){
                    include 'idhm_renda.php';
                }
        }
                
            ?>
</div>
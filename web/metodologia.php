<?php
    $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
    $separator = explode("/",$_GET["cod"]);
    
    if($separator[0] == "pt" || $separator[0] == "en" || $separator[0] == "es")
    {
        array_shift ( $separator );
    } 
?>

<script type="text/javascript">
    function myfunction2(valor){
        lang = '<?=$_SESSION["lang"]?>';
        pag = '<?=$path_dir?>' + lang + '/o_atlas/metodologia/';

        if(valor == 1){
            url = pag + "idhm_longevidade/";
        }

        else if(valor == 2){
            url = pag + "idhm_educacao/";
        }

        else if(valor == 3){
            url = pag + "idhm_renda/";
        }
        
        location.href= url;
    }
</script>

<div id="processo" style="width:900px; height: 1600px;">
    <div class="areatitle" id='atlas_Metodologia'></div>
    
    <div class="menuAtlasMet">
        <ul class="menuAtlasMetUl">
            <li><a id="atlas_menuIdhmLongevidade" onclick="myfunction2('1')" 
                <?php if($separator[2] == 'idhm_longevidade' || $separator[0] == '') {echo 'class="ativo2"'; } ?>>IDHM LONGEVIDADE</a><span class='ballMarker'>&bull;</span></li>
            <li><a id="atlas_menuIdhmEducacao" onclick="myfunction2('2')" 
                <?php if($separator[2] == 'idhm_educacao') {echo 'class="ativo2"';}?> >IDHM EDUCAÇÃO</a><span class='ballMarker'>&bull;</span></li>
            <li><a id="atlas_menuIdhmRenda" onclick="myfunction2('3')" 
                <?php if($separator[2] == 'idhm_renda') {echo 'class="ativo2"';} ?> >IDHM RENDA</a></li>
        </ul>
    </div>
    <div class="linhaDivisoriaMet"></div>
    
    <?php
                if($separator[2] == 'idhm_longevidade' || $separator[1] == ''){
                    include $_SESSION["lang"].'/o_atlas/idhm_longevidade.php';
                }
                
                else if($separator[2] == 'idhm_educacao'){
                    include $_SESSION["lang"].'/o_atlas/idhm_educacao.php';
                }
                
                else if($separator[2] == 'idhm_renda'){
                    include $_SESSION["lang"].'/o_atlas/idhm_renda.php';
                }
            ?>
</div>

<script type="text/javascript">
    $("#atlas_Metodologia").html(lang_mng.getString("atlas_Metodologia"));
    $("#atlas_menuIdhmLongevidade").html(lang_mng.getString("atlas_menuIdhmLongevidade"));
    $("#atlas_menuIdhmEducacao").html(lang_mng.getString("atlas_menuIdhmEducacao"));
    $("#atlas_menuIdhmRenda").html(lang_mng.getString("atlas_menuIdhmRenda"));
</script>
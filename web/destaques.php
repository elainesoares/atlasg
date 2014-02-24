<?php
    $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
    $separator = explode("/",$_GET["cod"]);
    
    
    if($separator[0] == "pt" || $separator[0] == "en" || $separator[0] == "es")
    {
        array_shift ($separator);
    }
?>

<script type="text/javascript">
    function myfunction(valor){
        lang = '<?=$_SESSION["lang"]?>';
        pag = '<?=$path_dir?>' + lang + '/destaques/';

        if(valor == 1){
            url = pag + "metodologia/";
        }

        else if(valor == 2){
            url = pag + "faixas_idhm/";
        }

        else if(valor == 3){
            url = pag + "idhm_brasil/";
        }
        
        else if(valor == 4){
            url = pag + "educacao/";
        }
        
        else if(valor == 5){
            url = pag + "longevidade/";
        }

        else if(valor == 6){
            url = pag + "renda/";
        }
        
        location.href= url;
    }
</script>

<div class="contentPages">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div id='destaques_title' class="titletopPage"></div>
            </div>
        </div>   
         <div class="menuAtlas" >
            <ul class="menuAtlasUl" >
                <li><a id='destaques_metodologia' onclick="myfunction('1')" style="font-size:13px;" 
                    <?php
                            if($separator[1] == 'metodologia' || $separator[1] == '' )
                                echo 'class="ativo2"';
                     ?>></a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id='destaques_faixas_idhm' onclick="myfunction('2')" style="font-size:13px;" 
                    <?php
                            if($separator[1] == 'faixas_idhm')
                                echo 'class="ativo2"';
                   ?> ></a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id='destaques_idhmBrasil' onclick="myfunction('3')" style="font-size:13px;" 
                    <?php 
                            if($separator[1] == 'idhm_brasil')
                                echo 'class="ativo2"';
                    ?> ></a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id='destaques_educacao' onclick="myfunction('4')" style="font-size:13px;" 
                    <?php 
                            if($separator[1] == 'educacao')
                                echo 'class="ativo2"';
                    ?> ></a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id='destaques_longevidade' onclick="myfunction('5')" style="font-size:13px;" 
                    <?php 
                           if($separator[1] == 'longevidade')
                                echo 'class="ativo2"';
                    ?> ></a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id='destaques_renda' onclick="myfunction('6')" style="font-size:13px;" 
                    <?php 
                           if($separator[1] == 'renda')
                                echo 'class="ativo2"';
                    ?> ></a>
                </li>
            </ul>
        </div>
        <div class="linhaDivisoria"></div>
        
        <div id="conteudo_atlas">
            <?php
                    if($separator[1] == 'metodologia' || $separator[1] == ''){
                        include $_SESSION["lang"].'/destaques/metodologia.php';
                    }

                    else if($separator[1] == 'faixas_idhm'){
                        include $_SESSION["lang"].'/destaques/faixas_idhm.php';
                    }

                    else if($separator[1] == 'idhm_brasil'){
                        include $_SESSION["lang"].'/destaques/idhm_brasil.php';
                    }

                    else if($separator[1] == 'educacao'){
                        include $_SESSION["lang"].'/destaques/educacao.php';
                    }

                    else if($separator[1] == 'longevidade'){
                        include $_SESSION["lang"].'/destaques/longevidade.php';
                    }

					else if($separator[1] == 'renda'){
                        include $_SESSION["lang"].'/destaques/renda.php';
                    }
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
     $("#destaques_longevidade").html(lang_mng.getString("destaques_longevidade"));
     $("#destaques_renda").html(lang_mng.getString("destaques_renda"));
     $("#destaques_educacao").html(lang_mng.getString("destaques_educacao"));
     $("#destaques_idhmBrasil").html(lang_mng.getString("destaques_idhmBrasil"));
     $("#destaques_faixas_idhm").html(lang_mng.getString("destaques_faixas_idhm"));
     $("#destaques_metodologia").html(lang_mng.getString("destaques_metodologia"));
     $("#destaques_title").html(lang_mng.getString("destaques_title"));
</script>

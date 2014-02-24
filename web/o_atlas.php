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
        pag = '<?=$path_dir?>' + lang + '/o_atlas/';
        lang = '<?=$_SESSION["lang"]?>';

        if(valor == 1){
            url = pag + "o_atlas_/";
        }

        else if(valor == 2){
            url = pag + "quem_faz/";
        }

        else if(valor == 3){
            url = pag + "para_que/";
        }
        
        else if(valor == 4){
            url = pag + "processo/";
        }
        
        else if(valor == 5){
            url = pag + "desenvolvimento_humano/";
        }
        
        else if(valor == 6){
            url = pag + "idhm/";
        }
        
        else if(valor == 7){
            url = pag + "metodologia/idhm_longevidade/";
        }
        
        else if(valor == 8){
            url = pag + "glossario/";
        }
        
        else if(valor == 9){
            url = pag + "perguntas_frequentes/";
        }
        
        else if(valor == 10){
            url = pag + "tutorial";
        }
        
        location.href= url;
    }
</script>

<div class="contentPages">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage" id="atlas_titleOAtlas"></div>
            </div>
        </div>  
         <div class="menuAtlas">
            <ul class="menuAtlasUl" style="margin-left: 19px">
                <li><a id="atlas_menuOAtlas" onclick="myfunction('1')" 
                    <?php 
                            if($separator[1] == 'o_atlas_' || $separator[1] == '' )
                                echo 'class="ativo2"';
                     ?>>O ATLAS</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id="atlas_menuQuemFaz" onclick="myfunction('2')" 
                    <?php 
                                if($separator[1] == 'quem_faz')
                                echo 'class="ativo2"';
                   ?> >QUEM FAZ</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id="atlas_menuParaQue" onclick="myfunction('3')" 
                    <?php 
                                if($separator[1] == 'para_que')
                                echo 'class="ativo2"';
                    ?> >PARA QUE</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id="atlas_menuProcesso" onclick="myfunction('4')" 
                    <?php 
                                if($separator[1] == 'processo')
                                echo 'class="ativo2"';
                        ?> >PROCESSO</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id="atlas_menuDesenvolvimentoHumano" onclick="myfunction('5')" 
                    <?php
                                if($separator[1] == 'desenvolvimento_humano')
                                echo 'class="ativo2"';
                       ?> >DESENVOLVIMENTO HUMANO</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id="atlas_menuIdhm" onclick="myfunction('6')" 
                    <?php 
                                if($separator[1] == 'idhm')
                                echo 'class="ativo2"';
                        ?> >IDHM</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id="atlas_menuMetodologia" onclick="myfunction('7')" 
                    <?php 
                                if($separator[1] == 'metodologia')
                                echo 'class="ativo2"';
                        ?> >METODOLOGIA</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id="atlas_menuGlossario" onclick="myfunction('8')"
                    <?php
                                if($separator[1] == 'glossario')
                                echo 'class="ativo2"';
                       ?> >GLOSSÁRIO</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a id="atlas_menuFAQ" onclick="myfunction('9')"
                    <?php
                                if($separator[1] == 'perguntas_frequentes')
                                echo 'class="ativo2"';
                    ?> >PERGUNTAS FREQUENTES</a><span class='ballMarker'>&bull;</span></li>
                <li><a id="atlas_menututorial" onclick="myfunction('10')"
                    <?php
                                if($separator[1] == 'tutorial')
                                echo 'class="ativo2"';
                    ?> ></a></li>
            </ul>
        </div>
        <div class="linhaDivisoria"></div>
        
        <div id="conteudo_atlas">
            <?php
                    if($separator[1] == 'o_atlas_' || $separator[1] == ''){
                        include $_SESSION["lang"].'/o_atlas/o_atlas_.php';
                    }

                    else if($separator[1] == 'quem_faz'){
                        include $_SESSION["lang"].'/o_atlas/quem_faz.php';
                    }

                    else if($separator[1] == 'para_que'){
                        include $_SESSION["lang"].'/o_atlas/para_que.php';
                    }
                    else if($separator[1] == 'processo'){
                        include $_SESSION["lang"].'/o_atlas/processo.php';
                    }
                    else if($separator[1] == 'desenvolvimento_humano'){
                        include $_SESSION["lang"].'/o_atlas/desenvolvimento_humano.php';
                    }
                    else if($separator[1] == 'idhm'){
                        include $_SESSION["lang"].'/o_atlas/idhm.php';
                    }
                    else if($separator[1] == 'metodologia'){
                        include 'metodologia.php';
                    }

                    else if($separator[1] == 'glossario'){
                        include $_SESSION["lang"].'/o_atlas/glossario.php';
                    }

                    else if($separator[1] == 'perguntas_frequentes'){
                        include $_SESSION["lang"].'/o_atlas/perguntas_frequentes.php';
                    }
                    
                    else if($separator[1] == 'tutorial'){
                        include 'tutorial.php';
                    }
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
     $("#atlas_titleOAtlas").html(lang_mng.getString("atlas_titleOAtlas"));
     $("#atlas_menuOAtlas").html(lang_mng.getString("atlas_menuOAtlas"));
     $("#atlas_menuQuemFaz").html(lang_mng.getString("atlas_menuQuemFaz"));
     $("#atlas_menuParaQue").html(lang_mng.getString("atlas_menuParaQue"));
     $("#atlas_menuProcesso").html(lang_mng.getString("atlas_menuProcesso"));
     $("#atlas_menuDesenvolvimentoHumano").html(lang_mng.getString("atlas_menuDesenvolvimentoHumano"));
     $("#atlas_menuIdhm").html(lang_mng.getString("atlas_menuIdhm"));
     $("#atlas_menuMetodologia").html(lang_mng.getString("atlas_menuMetodologia"));
     $("#atlas_menuGlossario").html(lang_mng.getString("atlas_menuGlossario"));
     $("#atlas_menuFAQ").html(lang_mng.getString("atlas_menuFAQ"));
     $("#atlas_menututorial").html(lang_mng.getString("atlas_menututorial"));
</script>

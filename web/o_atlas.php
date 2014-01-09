<?php
    //require_once '../url.php';
//    echo $pag;
    $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
//    echo $url;
    $gets = explode("/",$url);
    if(isset($gets[3])){
        $pag = $gets[3];
    }
    
    $server = $_SERVER['SERVER_NAME']; 
    $endereco = $_SERVER ['REQUEST_URI'];
    $url = $server.$endereco;
    $separator = (explode('/', $url));
	$base_expl = explode("/",$base);
?>

<script type="text/javascript">
    function myfunction(valor){
//        console.log('myfunction');
        pag = '<?=$path_dir?>' + 'o_atlas/';

        if(valor == 1){
            url = pag + "o_atlas_";
        }

        else if(valor == 2){
            url = pag + "quem_faz";
        }

        else if(valor == 3){
            url = pag + "para_que";
        }
        
        else if(valor == 4){
            url = pag + "processo";
        }
        
        else if(valor == 5){
            url = pag + "desenvolvimento_humano";
        }
        
        else if(valor == 6){
            url = pag + "idhm";
        }
        
        else if(valor == 7){
            url = pag + "metodologia/idhm_longevidade";
        }
        
        else if(valor == 8){
            url = pag + "glossario";
        }
        
        else if(valor == 9){
            url = pag + "perguntas_frequentes";
        }
        
        location.href= url;
    }
</script>

<div class="contentPages">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage">O Atlas</div>
            </div>
        </div>   
         <div class="menuAtlas">
		  <?php //echo sizeof($base_expl);
		  //echo $separator[3];?>
            <ul class="menuAtlasUl">
                <li><a onclick="myfunction('1')" 
                    <?php 
                        if(sizeof($base_expl)== 1){
                            //echo '1';
                            if($separator[3] == 'o_atlas_' || $separator[3] == '' )
                                echo 'class="ativo2"';
                        }
                        else if (sizeof($base_expl)== 2){
                            //echo '2';
                            if(isset($separator[4])){
                                if($separator[4] == 'o_atlas_' || $separator[4] == '')
                                echo 'class="ativo2"';
                            }
                         }
                     ?>>O ATLAS</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a onclick="myfunction('2')" 
                    <?php 
                        if(sizeof($base_expl)== 1){
                                if($separator[3] == 'quem_faz')
                                echo 'class="ativo2"';
                        }
                        else if (sizeof($base_expl)== 2){
                            if(isset($separator[4])){
                                if($separator[4] == 'quem_faz')
                                    echo 'class="ativo2"';
                            }
                        }
                   ?> >QUEM FAZ</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a onclick="myfunction('3')" 
                    <?php 
                        if(sizeof($base_expl)== 1){
                                if($separator[3] == 'para_que')
                                echo 'class="ativo2"';
                        }
                        else if (sizeof($base_expl)== 2){
                            if(isset($separator[4])){
                                if($separator[4] == 'para_que')
                                    echo 'class="ativo2"';
                            }
                        }
                    ?> >PARA QUE</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a onclick="myfunction('4')" 
                    <?php 
                        if(sizeof($base_expl)== 1){
                                if($separator[3] == 'processo')
                                echo 'class="ativo2"';
                        }
                        else if (sizeof($base_expl)== 2){
                            if(isset($separator[4])){
                                if($separator[4] == 'processo')
                                    echo 'class="ativo2"';
                            }
                        }
                    ?> >PROCESSO</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a onclick="myfunction('5')" 
                    <?php 
                        if(sizeof($base_expl)== 1){
                                if($separator[3] == 'desenvolvimento_humano')
                                echo 'class="ativo2"';
                        }
                        else if (sizeof($base_expl)== 2){
                            if(isset($separator[4])){
                                if($separator[4] == 'desenvolvimento_humano')
                                    echo 'class="ativo2"';
                            }
                        }
                    ?> >DESENVOLVIMENTO HUMANO</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a onclick="myfunction('6')" 
                    <?php 
                    if(sizeof($base_expl)== 1){
                                if($separator[3] == 'idhm')
                                echo 'class="ativo2"';
                        }
                        else if (sizeof($base_expl)== 2){
                            if(isset($separator[4]) && $separator[4] == 'idhm')
                                echo 'class="ativo2"';
                        }
                    ?> >IDHM</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a onclick="myfunction('7')" 
                    <?php 
                        if(sizeof($base_expl)== 1){
                                if($separator[3] == 'metodologia')
                                echo 'class="ativo2"';
                        }
                        else if (sizeof($base_expl)== 2){
                            if(isset($separator[4])){
                                if($separator[4] == 'metodologia')
                                    echo 'class="ativo2"';
                            }
                        }
                    ?> >METODOLOGIA</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a onclick="myfunction('8')"
                    <?php 
                        if(sizeof($base_expl)== 1){
                                if($separator[3] == 'glossario')
                                echo 'class="ativo2"';
                        }
                        else if (sizeof($base_expl)== 2){
                            if(isset($separator[4])){
                                if($separator[4] == 'glossario')
                                    echo 'class="ativo2"';
                            }
                        }
                   ?> >GLOSSÁRIO</a><span class='ballMarker'>&bull;</span>
                </li>
                <li><a onclick="myfunction('9')"
                    <?php 
                     if(sizeof($base_expl)== 1){
                                if($separator[3] == 'perguntas_frequentes')
                                echo 'class="ativo2"';
                        }
                        else if (sizeof($base_expl)== 2){
                            if(isset($separator[4])){
                                if($separator[4] == 'perguntas_frequentes')
                                    echo 'class="ativo2"';
                            }
                        }
                    ?> >PERGUNTAS FREQUENTES</a></li>
            </ul>
        </div>
        <div class="linhaDivisoria"></div>
        
        <div id="conteudo_atlas">
            <?php
			 //echo $separator[4];
                if(sizeof($base_expl)== 1){
                    if($separator[3] == 'o_atlas_' || $separator[3] == ''){
                        include 'o_atlas_.php';
                    }

                    else if($separator[3] == 'quem_faz'){
                        include 'quem_faz.php';
                    }

                    else if($separator[3] == 'para_que'){
                        include 'para_que.php';
                    }

                    else if($separator[3] == 'processo'){
                        include 'processo.php';
                    }

                    else if($separator[3] == 'desenvolvimento_humano'){
                        include 'desenvolvimento_humano.php';
                    }

                    else if($separator[3] == 'idhm'){
                        include 'idhm.php';
                    }

                    else if($separator[3] == 'metodologia'){
                        include 'metodologia.php';
                    }

                    else if($separator[3] == 'glossario'){
                        include 'glossario.php';
                    }

                    else if($separator[3] == 'perguntas_frequentes'){
                        include 'perguntas_frequentes.php';
                    }
                }
              
             if(sizeof($base_expl)== 2){
			 //echo 'entrei';
                    if($separator[4] == 'o_atlas_' || $separator[3] == ''){
                        include 'o_atlas_.php';
                    }

                    else if($separator[4] == 'quem_faz'){
                        include 'quem_faz.php';
                    }

                    else if($separator[4] == 'para_que'){
                        include 'para_que.php';
                    }

                    else if($separator[4] == 'processo'){
                        include 'processo.php';
                    }

                    else if($separator[4] == 'desenvolvimento_humano'){
                        include 'desenvolvimento_humano.php';
                    }

                    else if($separator[4] == 'idhm'){
                        include 'idhm.php';
                    }

                    else if($separator[4] == 'metodologia'){
                        include 'metodologia.php';
                    }

                    else if($separator[4] == 'glossario'){
                        include 'glossario.php';
                    }

                    else if($separator[4] == 'perguntas_frequentes'){
                        include 'perguntas_frequentes.php';
                    }
                }
                
                //else if($separator[2] == 'o_atlas'){
                   // include 'o_atlas_.php';
                //}
            ?>
        </div>
    </div>
</div>

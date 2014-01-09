<?php
    //Include do arquivo de configuração da Home
    require_once './config/config_home.php';
    require_once './config/config_path.php';
    ob_start(); 
?>

<script type="text/javascript">
    var baseUrl = "<?php echo $path_dir."perfil/" ?>";
    var storedName = "";
            
    $(document).ready(function() {
        inputHandler.add($('#perfil_search_home'), 'buscaHome', 2, "", false, getNomeMunUF);
    });

    function getNomeMunUF(nome) {
	storedName =retira_acentos(nome);
        buscaPerfil()
    }

    function buscaPerfil() {
        if ($("#buscaHome").attr("i") != 0)
            RedirectSearch(retira_acentos(storedName));
        else if(storedName == "")
            document.getElementById('erroBusca').style.display= "block";
        }

    function RedirectSearch(nome) {
        window.location = baseUrl + nome;
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
    
    var currentPosition = 0;
    var slideWidth = 352;
    var slides = $('.slide');
    var numberOfSlides = slides.length;
    var slideShowInterval;
    var speed = 3000;
    
    //Assign a timer, so it will run periodically
    //slideShowInterval = setInterval(changePosition/*, speed*/);
    
    slides.wrapAll('<div id="slidesHolder"></div>')
    slides.css({ 'float' : 'left' });
    
    //set #slidesHolder width equal to the total width of all the slides
    $('#slidesHolder').css('width', slideWidth * numberOfSlides);
    $('#slideshow')
        .prepend('<span class="nav_slide" id="leftNav">Move Left</span>')
        .append('<span class="nav_slide" id="rightNav">Move Right</span>');
    
    manageNav(currentPosition);
    
    //tell the buttons what to do when clicked
    $('.nav_slide').bind('click', function() {
        //determine new position
        currentPosition = ($(this).attr('id')=='rightNav')? currentPosition+1 : currentPosition-1;
										
        //hide/show controls
        manageNav(currentPosition);
        clearInterval(slideShowInterval);
        //slideShowInterval = setInterval(changePosition/*, speed*/);
        moveSlide();
    });
		
    function manageNav(position) {
        //hide left arrow if position is first slide
        if(position==0){ 
            $('#leftNav').hide() 
        }
        else { 
            $('#leftNav').show() 
        }
        
        //hide right arrow is slide position is last slide
        if(position==numberOfSlides-1){
            $('#rightNav').hide() 
        }
        else { 
            $('#rightNav').show() 
        }
    }

		
    /*changePosition: this is called when the slide is moved by the 
    timer and NOT when the next or previous buttons are clicked*/
    function changePosition() {
        if(currentPosition == numberOfSlides - 1) {
	currentPosition = 0;
	manageNav(currentPosition);
        } 
        else {
            currentPosition++;
            manageNav(currentPosition);
        }
        //moveSlide();
    }
		
    //moveSlide: this function moves the slide 
    function moveSlide() {
        $('#slidesHolder')
            .animate({'marginLeft' : slideWidth*(-currentPosition)});
    }
    
   
});
</script>

<div class="contentCenter">     
    <!-- ============================ BANNER =================================== -->
    <div class="banner" style="height: 300px;">
        <div>
            <iframe width="900" height="300" src="//www.youtube.com/embed/K7Cftgj250Y?rel=0" frameborder="0" allowfullscreen></iframe>

            <!-- Button to trigger modal -->
            <a href="#myModal" role="button" class="btn" data-toggle="modal">Launch demo modal</a>

            <!-- Modal -->
            <div id="myModal" class="modal_video hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <!--<iframe id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" src="//player.vimeo.com/video/72588912?byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff&amp;autoplay=1" width="841" height="349" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>-->
              <!--<iframe id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" src="//player.vimeo.com/video/55165865?byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff&amp;autoplay=1" width="841" height="473" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>-->
              <!--<iframe  src="//player.vimeo.com/video/71596828?byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff&amp;autoplay=1" width="941" height="529" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>-->  
                <iframe src="//player.vimeo.com/video/72672337?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff&amp;autoplay=0" width="900" height="506" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
            
        </div>
    </div>

   <!-- ========================== PERFIL ====================================== --> 
   <div class="containerPerfil">
        <div class="contentPerfil">
            <div class="contentTitlePefil">
                <div class="titulo_divs">
                    <div id="h1Home">Perfil</div>
                    Consulte o perfil de seu município
                </div>
            </div>
            
            <div class="buscaHome">
                <div id="erroBusca" class="erro_BuscaHome">*Selecione um município para continuar</div>
            	<div class="perfil-search-main_home"  id="perfil_search_home">
                            <!--<a href="<?php echo $home_LinkBannerTop[0] ?>" ><button title="Pesquise" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 44px; margin-left: 30px; margin-top: 28px;">Pesquise</button></a>-->
                            <a onclick="buscaPerfil()" id="busca"><button title="Busca" type="button" name="" value="" class="blue_button big_bt"  style=" padding:5px 15px; margin-top: 18px; margin-right: 10px; float: right">Busca</button></a>
	            </div>
	            
            </div>
            <p style="margin-left: 230px;">Digite o nome do município Ex.: Pouso Alegre, Juruti, Lajeado, etc.</p>
        </div>
   </div>

    <!-- ============================ CONSULTA ==================================== -->
    <div class="atlasHome">
        <div class="contentAtlasHome">
            <div class="contentAtlasHomeEsquerda">
                <div class="titulo_divs">
                    <div id="h1Home">Consulta</div>
                    <!--<h1 id="h1Home">Consulta</h1>-->
                    Crie seus próprios mapas e gráficos. É fácil e rápido.
                </div>
                <!--<a href="<?php echo $path_dir; ?>consulta" class="buttonPesquise">Pesquise</a>-->
                <a href="<?php echo $path_dir; ?>consulta" ><button title="Pesquise" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 40px; margin-top: 148px; margin-left: 0px;">Pesquise</button></a>
            </div>
            <div class="contentAtlasSlide">
                <div id="slideshow">
                    <div id="slideshowWindow">
                       <div class="slide" id="passo1">
                            <img src="<?php echo $home_Consulta[0] ?>" alt='' />
                       </div>
                       <div class="slide" id="passo2">
                            <img src="<?php echo $home_Consulta[1] ?>" alt=''/>
                       </div>
                       <div class="slide" id="passo3">
                            <img src="<?php echo $home_Consulta[2] ?>" alt=''/>
                       </div>
                    </div>
               </div>
            </div>
        </div> 
    </div>

    <!-- ======================= LATERAL DIREITA ============================== -->
    <div class="lateral_direita_home">
        <a href="<?php echo $path_dir; ?>arvore">
            <div class="containerMetodologia">
                <div class="contentMetodologia">
                    <div class="titulo_divs">
                        <div id="h1Home">IDHM Árvore</div>
                        <!--<h1 id="h1Home">Metodologia</h1>-->
                        Conheça a representação gráfica do IDHM.
                    </div>
                </div>
            </div>
        </a>
        <a href="<?php echo $path_dir; ?>download">
            <div class="containerDownloadHome">
                <div class="contentDownloadHome">
                    <div class="titulo_divs">
                        <div id="h1Home">Download</div>
                        <!--<h1 id="h1Home">Download</h1>-->
                       Baixe os dados do Atlas Brasil 2013.
    <!--                     <a href="<?php echo BASE_COMPLETA_CSV ?>"><button class="buttonDownload_home">Baixar</button></a> -->

                    </div>
                    <img src="./img/download_home.png" class="dowloadHome" alt=""/>
                </div>
            </div>
        </a>
        
    </div>
    
</div><!-- Fim da div contentCenter -->
<script>
    $(document).ready(function(){
        $('.carousel').carousel({
            interval: 3000
        });
    });
</script>

<?php
    $title = '';
    $meta_title = '';
    $meta_description = '';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>

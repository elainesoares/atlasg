<?php
    //Include do arquivo de configuração da Home
//    require_once './config/config_home.php';
//    require_once './config/config_path.php';
    require_once './com/mobiliti/util/language.php';

?>

<script type="text/javascript">
    var baseUrl = "<?php echo $path_dir."$ltemp/perfil/" ?>";
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
            document.getElementById('home_erroBusca').style.display= "block";
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
    <div class="banner">
        
        <div id="myCarousel" class="carousel"><!-- class of slide for animation -->
            <a class="carousel-control-modified left" href="#myCarousel" data-slide="prev" ><!--<img src="./img/setaLeft.png" alt="Left"/>--> <!--a&lsaquo;--></a>
             <a class="carousel-control-modified right" href="#myCarousel" data-slide="next"><!--<img src="./img/setaRight.png" alt="Right"/>--> <!--&rsaquo;--></a>
            <center>
                <!--<div class="carousel-inner">-->
                    <div class="carousel-inner">
                    <div class="item active">
                        <a href="<?php echo $home_LinkBannerTop[0] ?>" data-toggle='modal' role='button'><img src="<?php echo $home_ImgBannerTop[0] ?>"/></a>
                        <div class='carousel-caption-modified'>
                            <div class='titleBanner'><a href="<?php echo $home_LinkBannerTop[0] ?>" data-toggle='modal' role='button'><span id="<?php echo "home_banner0" ?>"></span></a></div>
                        </div>
                    </div>
                    <?php 
                       $cont = 1;
                        for($cont; $cont <= 2; $cont++){
						
                    ?>
                                <div class='item'>
                                    <a href="<?php echo $ltemp.'/'.$home_LinkBannerTop[$cont]; ?>"><img src="<?php echo $home_ImgBannerTop[$cont]; ?>" class='imgBanner'/></a>
                                    <div class='carousel-caption-modified'>
                                        <div class='titleBanner'><a href="<?php echo $ltemp.'/'.$home_LinkBannerTop[$cont]; ?>"><span id="<?php echo "home_banner".$cont; ?>"></span></a></div>
                                    </div>
                                </div>
                    <?php               
                        };
                    ?>
                </div>
            </center>
       </div>
        <div id="myModal" class="modal_video hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <iframe width="853" height="480" src="//www.youtube.com/embed/K7Cftgj250Y?rel=0" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>

    <?php
        if(buscaPerfil_has_lang(@$_SESSION["lang"])){
   ?>
   <!-- ========================== PERFIL ====================================== --> 
   <div class="containerPerfil">
        <div class="contentPerfil">
            <div class="contentTitlePefil">
                <div class="titulo_divs">
                    <div class="h1Home" id="home_titlePerfil"></div>
                    <span id="home_textoPerfil"></span>
                </div>
            </div>
            
            <div class="buscaHome">
                <div id="home_erroBusca" class="erro_BuscaHome"></div>
            	<div class="perfil-search-main_home"  id="perfil_search_home">
                            <!--<a href="<?php echo $home_LinkBannerTop[0] ?>" ><button title="Pesquise" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 44px; margin-left: 30px; margin-top: 28px;">Pesquise</button></a>-->
                            <a onclick="buscaPerfil()" id="busca"><button id="home_buttonBusca" title="" type="button" name="" value="" class="blue_button big_bt"  style=" padding:5px 15px; margin-top: 18px; margin-right: 10px; float: right"></button></a>
	            </div>
	            
            </div>
            <p style="margin-left: 230px;" id="home_exemploBusca"></p>
        </div>
   </div>
   <?php 
        }
   ?>

    <!-- ============================ CONSULTA ==================================== -->
    <div class="atlasHome">
        <div class="contentAtlasHome">
            <div class="contentAtlasHomeEsquerda">
                <div class="titulo_divs">
                    <div class="h1Home" id="home_titleConsulta"></div>
                    <!--<h1 id="h1Home">Consulta</h1>-->
                    <span id="home_textoConsulta"></span>
                </div>
                <!--<a href="<?php echo $path_dir; ?>consulta" class="buttonPesquise">Pesquise</a>-->
                <a href="<?php echo $path_dir.$ltemp; ?>/consulta" ><button id="home_buttonPesquisa" title="Pesquise" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 40px; margin-top: 148px; margin-left: 0px;"></button></a>
            </div>
            <div class="contentAtlasSlide">
                <div id="slideshow">
                    <div id="slideshowWindow">
                       <div class="slide" id="passo1">
                            <img src="img/passos_consulta/<?php echo $_SESSION["lang"]; ?>/passo1.jpg" alt='' />
                       </div>
                       <div class="slide" id="passo2">
                            <img src="img/passos_consulta/<?php echo $_SESSION["lang"]; ?>/passo2.jpg" alt=''/>
                       </div>
                       <div class="slide" id="passo3">
                            <img src="img/passos_consulta/<?php echo $_SESSION["lang"]; ?>/passo3.jpg" alt=''/>
                       </div>
                    </div>
               </div>
            </div>
        </div> 
    </div>

    <!-- ======================= LATERAL DIREITA ============================== -->
    <div class="lateral_direita_home">
        <a href="<?php echo $path_dir.$ltemp; ?>/graficos">
            <div class="containerMetodologia">
                <div class="contentMetodologia">
                    <div class="titulo_divs">
                        <div class="h1Home" id="home_titlegraficos"></div>
                        <!--<h1 id="h1Home">Metodologia</h1>-->
                        <span id="home_textograficos"></span>
                    </div>
                </div>
            </div>
        </a>
        <a href="<?php echo $path_dir.$ltemp; ?>/download">
            <div class="containerDownloadHome">
                <div class="contentDownloadHome">
				<?php
					if(@$_SESSION["lang"] == "en")
						echo "<div class='titulo_divs' style='margin-top: -9px;'>";
					else
						echo "<div class='titulo_divs' >";
				?>
					
                        <div class="h1Home" id="home_titleDownload"></div>
                        <!--<h1 id="h1Home">Download</h1>-->
                       <span id="home_textoDownload"></span>
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
    
    $("#home_titlePerfil").html(lang_mng.getString("home_titlePerfil"));
    $("#home_banner0").html(lang_mng.getString("home_banner0"));
    $("#home_banner1").html(lang_mng.getString("home_banner1"));
    $("#home_banner2").html(lang_mng.getString("home_banner2"));
    $("#home_textoPerfil").html(lang_mng.getString("home_textoPerfil"));
    $("#home_exemploBusca").html(lang_mng.getString("home_exemploBusca"));
    $("#home_erroBusca").html(lang_mng.getString("home_erroBusca"));
    $("#home_buttonBusca").html(lang_mng.getString("home_buttonBusca"));
    $("#home_titleConsulta").html(lang_mng.getString("home_titleConsulta"));
    $("#home_textoConsulta").html(lang_mng.getString("home_textoConsulta"));
    $("#home_buttonPesquisa").html(lang_mng.getString("home_buttonPesquisa"));
    $("#home_titleidhmArvore").html(lang_mng.getString("home_titleidhmArvore"));
    $("#home_textoidhmArvore").html(lang_mng.getString("home_textoidhmArvore"));
    $("#home_titlegraficos").html(lang_mng.getString("home_titlegraficos"));
    $("#home_textograficos").html(lang_mng.getString("home_textograficos"));
    $("#home_titleDownload").html(lang_mng.getString("home_titleDownload"));
    $("#home_textoDownload").html(lang_mng.getString("home_textoDownload"));
</script>

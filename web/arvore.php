<!-- CSS -->
<link rel="stylesheet" type="text/css" href="hdi/css/smoothness/jquery-ui-1.8.12.custom.css" media="screen" />
<link rel="stylesheet" type="text/css" href="hdi/css/main.css" media="screen" />

<!-- jQuery 
<script type="text/javascript" src="hdi/js/jquery-ui-1.8.12.custom.min.js"></script>-->

<!-- Raphael Library -->
<script type="text/javascript" src="hdi/js/raphael.js"></script>
<script type="text/javascript" src="hdi/js/raphael.serialize.js"></script> 

<!--<script type="text/javascript" src="../js/util.js"></script>--> 

<!-- Javascript for HDI  --> 
<script type="text/javascript" src="hdi/js/hdi_tree.js"></script>
<!-- Problema --------------------------------------------------------  -->
<script type="text/javascript" src="hdi/js/hdi_onload.js"></script>

<?php
    include_once './hdi/getMunicipios.php'; 
    //include_once './config/conexao.class.php';
    include_once './hdi/getUrl.php';  
?>

<script type="text/javascript">
    var municipio1 = 0;
    var municipio2 = 0;
    var ano1= 3;
    var ano2 = 3;
    var ano1_ = '2010';
    var ano2_ = '2010';
    var nome_municipio1 = 0;
    var nome_municipio2 = 0;
    var espacialidade1 = 0;
    var espacialidade2 = 0;
    var municipio1Arvore = '<?=$municipio1Arvore?>';
    var municipio2Arvore = '<?=$municipio2Arvore?>';
    var aleatorio = '<?=$aleatorio?>';
    path_dir = '<?=$path_dir?>';

    $(document).ready(function() {
        if(municipio1Arvore == 0 && municipio2Arvore == 0 && aleatorio == false){
            $("#ano1").simpleSlider("setValue", ano1_);
            $("#ano2").simpleSlider("setValue", ano2_);
            inputHandler.add($('#idh_search1'),'mun1',1,"",false,getNomeMunUF1);
            setAno();
            inputHandler.add($('#idh_search2'),'mun2',1,"",false,getNomeMunUF2);
            
        }
        else if(municipio1Arvore != 0 && municipio2Arvore != 0 && aleatorio == false){
            setAno();
            getUrl();
            setSliderAno();
            dados();
            inputHandler.add($('#idh_search1'),'mun1',1,nomeMunImp1,false,getNomeMunUF1);
            inputHandler.add($('#idh_search2'),'mun2',1,nomeMunImp2,false,getNomeMunUF2);
        }
        
        else if(aleatorio == true){
            getUrl();
            url = path_dir+'arvore/'+espacialidade1+"/"+nome_municipio1+'_2010/'+espacialidade2+"/"+nome_municipio2+'_2010';
            location.href= url;
            inputHandler.add($('#idh_search1'),'mun1',1,nomeMunImp1,false,getNomeMunUF1);
            inputHandler.add($('#idh_search2'),'mun2',1,nomeMunImp2,false,getNomeMunUF2);
        }
    });
    
    function setSliderAno(){
        $("#ano1").simpleSlider("setValue", ano1_);
        $("#ano2").simpleSlider("setValue", ano2_);
    }
    
    function setAno(){
         $("#ano1").bind("slider:changed", function (event, data) {              
                switch($("#ano1").val()){
                    case "2010":
                        ano1 = 3
                        break;
                    case "2000":
                        ano1 = 2;
                        break;
                    case "1991":
                        ano1 = 1;
                        break;
                }
                dados();
            });

            $("#ano2").bind("slider:changed", function (event, data) {
                switch($("#ano2").val()){
                    case "2010":
                        ano2 = 3
                        break;
                    case "2000":
                        ano2 = 2;
                        break;
                    case "1991":
                        ano2 = 1;
                        break;
                }
                dados();
            });  
    }
    
    function getUrl(){
        municipio1 = '<?=$ids[0]?>';
        municipio2 = '<?=$ids[1]?>';
        ano1 = '<?=$idAnos[0]?>';
        ano2 = '<?=$idAnos[1]?>';
        ano1_ = '<?=$Anos[0]?>';
        ano2_ = '<?=$Anos[1]?>';
        espacialidade1 = '<?=$Espac[0]?>';
        espacialidade2 = '<?=$Espac[1]?>';
        nome_municipio1 = '<?=$NomesMunEst[0]?>';
        nome_municipio2 = '<?=$NomesMunEst[1]?>';
        nomeMunImp1 = '<?=$NomesMunImp[0]?>';
        nomeMunImp2 = '<?=$NomesMunImp[1]?>';
    }
        
    function getNomeMunUF1(nome,k, espac){
        nome_municipio1 = retira_acentos(nome);
        municipio1 = k;
        espacialidade1 = espac;
        dados();
    }
      
    function getNomeMunUF2(nome,k, espac){
        nome_municipio2 = retira_acentos(nome);
        municipio2 = k;
        espacialidade2 = espac;
        dados();
    }
            
    function imprimir(){
        //Ano1
        if(ano1 == 3)
            anoa1 = '2010';
        else if(ano1 == 2)
            anoa1 = '2000';
        else if(ano1 == 1)
            anoa1 = '1991';
        //Ano2
        if(ano2 == 3)
            anoa2 = '2010';
        else if(ano2 == 2)
            anoa2 = '2000';
        else if(ano2 == 1)
            anoa2 = '1991';
        show_shadow1 = getshow_shadow1();
        show_shadow2 = getshow_shadow2();
        
        if((espacialidade1 == '0' || espacialidade1 == undefined) && (espacialidade2 == '0' || espacialidade2 == undefined)){
            print_url = path_dir+'arvore_print/nulo/nulo';
        }
        
        else if((espacialidade1 == '0' || espacialidade1 == undefined) && (espacialidade2 != '0')){
            print_url = path_dir+'arvore_print/nulo/'+espacialidade2+"/"+nome_municipio2+'_'+anoa2+'_'+show_shadow2; 
        }
        
        else if(espacialidade1 != '0' && (espacialidade2 == '0' || espacialidade2 == undefined)){
            print_url = path_dir+'arvore_print/'+espacialidade1+"/"+nome_municipio1+'_'+anoa1+'_'+show_shadow1+'/nulo';
        }
        
        else{
           print_url = path_dir+'arvore_print/'+espacialidade1+"/"+nome_municipio1+'_'+anoa1+'_'+show_shadow1+'/'+espacialidade2+"/"+nome_municipio2+'_'+anoa2+'_'+show_shadow2; 
        }
        
        
//        if(municipio1 == '0' && municipio2 == '0'){
//            print_url = path_dir+'arvore_print/nulo';
//        }
//        else if(municipio1 != '0' && municipio2 == '0'){
//            print_url = path_dir+'arvore_print/'+espacialidade1+"/"+nome_municipio1+'_'+ano1+'_ideal/'+espacialidade2+"/"+nome_municipio2+'_'+ano2+'_ideal';
//        }
//        
//        else if(show_shadow1 == true && show_shadow2 == true)
//            print_url = path_dir+'arvore_print/'+espacialidade1+"/"+nome_municipio1+'_'+ano1+'_'+show_shadow1+'/'+espacialidade2+"/"+nome_municipio2+'_'+ano2+'_ideal';
//        else if(show_shadow1 == true && show_shadow2 == false)
//            print_url = path_dir+'arvore_print/'+espacialidade1+"/"+nome_municipio1+'_'+ano1+'_ideal/'+espacialidade2+"/"+nome_municipio2+'_'+ano2+'_nulo';
//        else if(show_shadow1 == false && show_shadow2 == false)
//            print_url = path_dir+'arvore_print/'+espacialidade1+"/"+nome_municipio1+'_'+ano1+'_nulo/'+espacialidade2+"/"+nome_municipio2+'_'+ano2+'_nulo';
//        else if(show_shadow1 == false && show_shadow2 == true)
//            print_url = path_dir+'arvore_print/'+espacialidade1+"/"+nome_municipio1+'_'+ano1+'_nulo/'+espacialidade2+"/"+nome_municipio2+'_'+ano2+'_ideal';

        window.open(print_url, '_blank');
    }
                                                
</script>
<div class="contentPages">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage" style="font-size: 40pt; width: 100%;">Árvore do IDHM</div>
                Selecione dois municípios ou estados e compare o IDHM através do gráfico abaixo
                <div style="margin-top: -69px; float: right; width: 144px;">
                    <a onclick="imprimir()" style="cursor: pointer;" target="_blank">
                        <div class="btn_print" data-original-title='Imprimir Árvore IDHM' id="toolTipPrintDown" style="margin-top: 19px; margin-left: 52px;" id='toolTipPrint'>
                            <button type="button" class="gray_button big_bt" >
                                <img src="img/icons/print_gray.png"/> 
                            </button>
                        </div>
                    </a>
                </div> 
            </div>
        </div>
        <div class="idhContainer1" id="idh_search1"></div>
        <div class="idhContainer2" id="idh_search2"></div>
            <div id="container">
                <div id="viz"></div>
                    <div class="sliderDivFatherHdi" style="width: 373px;">
                <div class="sliderDivIn_idh"><input type='text' id="ano1" data-slider="true" data-slider-values="1991,2000,2010" data-slider-equal-steps="true" data-slider-snap="true" data-slider-theme="volume" ></div>
                        <div class='labels' style="margin-left: 91px;">
                            <label style="margin-left: 31px;">1991</label>
                            <label class="midLabel" style="margin-left: 30px;">2000</label>
                            <label style="margin-left: -26px;">2010</label>
                        </div>
                    </div>
                    <div class="sliderDivFatherHdi" style="margin-left: 53px; width: 372px;">
                <div class="sliderDivIn_idh"><input type='text' id="ano2" data-slider="true" data-slider-values="1991,2000,2010" data-slider-equal-steps="true" data-slider-snap="true" data-slider-theme="volume" ></div>
                <div class='labels' style="margin-left: 113px;">
                            <label style="margin-left: 10px;">1991</label>
                            <label class="midLabel" style="margin-left: 33px;">2000</label>
                            <label style="margin-left: -29px;">2010</label>
                </div>
                    </div>
                <div style="width: 100%;">
                    <div style="width: 50%; float: left; ">
                        <p style="float: left; margin-top: 10px; margin-left: 157px;"><input type="checkbox" name='show_shadow1' style="margin-right: 5px; margin-bottom: 5px;" id="check1""/>Mostrar IDHM Ideal</p>
                        <div class="table_city1" id="table_city" style="display: block; margin-top: 53px; width: 383px;">      
                        </div>
                    </div>
                    <div style="width: 50%; float: right">
                        <p style="float: left; margin-left: 157px; margin-top: 10px;"><input type="checkbox" name='show_shadow2' style="margin-right: 5px; margin-bottom: 5px; "/>Mostrar IDHM Ideal</p>
                        <div class="table_city2" id="table_city" style="display: block; margin-top: 70px; float: right;">      
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div style='font-size: 11px;'> Inspirado por: <a href="http://hdr.undp.org/en/humandev/lets-talk-hd/2011-02/" target='_blank' style="color: blue;">HDI Tree</a>. The Media Laboratory - Massachusetts Institute of Technology (MIT), Center for International Development - Harvard University, Department of Art and Design - Northeastern University.</div>
        </div>
    </div>
</div>
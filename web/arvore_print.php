<!-- CSS -->
<link rel="stylesheet" type="text/css" href="hdi/css/smoothness/jquery-ui-1.8.12.custom.css" media="screen" />
<link rel="stylesheet" type="text/css" href="hdi/css/main.css" media="screen" />

<!-- Raphael Library -->
<script type="text/javascript" src="hdi/js/raphael.js"></script>
<script type="text/javascript" src="hdi/js/raphael.serialize.js"></script>

<!-- Javascript for HDI  --> 
<script type="text/javascript" src="hdi/js/hdi_tree_print.js"></script>
<!-- Problema --------------------------------------------------------  -->
<script type="text/javascript" src="hdi/js/hdi_onload.js"></script>

<?php 
    include_once './hdi/getMunicipios.php'; 
    if($municipio1Arvore != '0' || $municipio2Arvore != '0'){
        $arvore = new Arvore($municipio1Arvore, $municipio2Arvore);
        $ids = $arvore->getIds();
        $idAnos = $arvore->getidAnos();
        $Anos = $arvore->getAnos();
        $NomesMun = $arvore->getNomesMun();
        $Ideal = $arvore->getIdeal();
        $Espac = $arvore->getEspac();
        $Uf = $arvore->getUf();
    }
    
   else if($municipio1Arvore == 'nulo' && $municipio2Arvore == 'nulo'){
       $ids = array('0','0');
        $idAnos = array('0','0');
        $Anos = array('0','0');
        $NomesMun = array('0','0');
        $Ideal = array('0','0');
        $Espac = array('0','0');
        $Uf = array('0','0');
    }
?>

<script type="text/javascript">
    var municipio1 = 0;
    $(document).ready(function() {
        municipio1 = '<?=$ids[0]?>';
        municipio2 = '<?=$ids[1]?>';
        ano1 = '<?=$idAnos[0]?>';
        ano2 = '<?=$idAnos[1]?>';
        show_shadow1 = '<?=$Ideal[0]?>';
        show_shadow2 = '<?=$Ideal[1]?>';
        espacialidade1 = '<?=$Espac[0]?>';
        espacialidade2 = '<?=$Espac[1]?>';
        uf1 = '<?=$Uf[0]?>';
        uf2 = '<?=$Uf[1]?>';
        
        if(municipio1 == 'nulo'){
            municipio1 = 0;
        }
        if(municipio2 == 'nulo'){
            municipio2 = 0;
        }
        setshow_shadow1(show_shadow1, show_shadow2);
        dados();
        javascript:self.print();
    });                              
</script>
<div class="contentPages">
    <div class="idhContainer1_print">
        <?php 
            if($ids[0] != 0){
                if($Espac[0] == 'municipio')
                    echo $NomesMun[0].' ('.$Uf[0].')';
                else if($Espac[0] == 'estado')
                    echo $NomesMun[0];
            }
            
        ?>
    </div>
    <div class="idhContainer2_print">
        <?php 
            if($ids[1] != 0){
                if($Espac[1] == 'municipio')
                    echo $NomesMun[1].' ('.$Uf[1].')'; 
                else if($Espac[1] == 'estado')
                    echo $NomesMun[1];
            }
        ?>
    </div>
    <div id="container">
        <div id="viz"></div>
        <div class="containerAno">
            <div class="ano_mun1">
                <?php 
                    if($Anos[0] != 0)
                        echo '<b id="arvore_ano1"></b> '.$Anos[0]; 
                ?>
            </div>
            <div class="ano_mun2">
                <?php 
                    if($Anos[1] != 0)
                    echo '<b id="arvore_ano2"></b> '.$Anos[1]; 
                ?>
            </div>
        </div>
        <div style="width: 100%;">
            <div style="width: 50%; float: left; ">
                <div class="table_city1" id="table_city" style="display: block; margin-top: 53px; width: 383px;">      
            </div>
            </div>
            <div style="width: 50%; float: right">
                <div class="table_city2" id="table_city" style="display: block; margin-top: 112px; float: right;"></div>
            </div>
        </div>
        <div class="clear"></div>
        <div style='font-size: 11px;'><span id="arvore_inspiradoPor"> Inspirado por:</span> <a href="http://hdr.undp.org/en/humandev/lets-talk-hd/2011-02/" target='_blank' style="color: blue;">HDI Tree</a>. The Media Laboratory - Massachusetts Institute of Technology (MIT), Center for International Development - Harvard University, Department of Art and Design - Northeastern University.</div>
    </div>
</div>

<script type="text/javascript">
     $("#arvore_ano1").html(lang_mng.getString("arvore_ano1"));
     $("#arvore_ano2").html(lang_mng.getString("arvore_ano2"));
     $("#arvore_inspiradoPor").html(lang_mng.getString("arvore_inspiradoPor"));
</script>

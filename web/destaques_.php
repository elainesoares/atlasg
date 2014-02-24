<?php
//    $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
//    $gets = explode("/",$url);
//    $pag = $gets[3];
//    
//    $server = $_SERVER['SERVER_NAME']; 
//    $endereco = $_SERVER ['REQUEST_URI'];
//    $url = $server.$endereco;
//	$separator = (explode('/', $url));
	//echo $separator[4];
    //$separator = spliti ("/", $url, 5);
        
        
        
    class Destaque{
        private $pdf;
        private $imagem;
        const path = 'destaque/';
        const largura = 210;
        const altura = 297;
        
        private $largura_p;
        private $altura_p;
        private $margin;
        public function __construct($link_pdf,$link_imagem,$proporcao,$margin = 5) {
            $this->pdf = $link_pdf;
            $this->imagem = $link_imagem;
            
            $this->largura_p = self::largura * $proporcao;
            $this->altura_p = self::altura * $proporcao;
            $this->margin = $margin;
        }
        
        public function draw(){
            echo '<a class="a-img-destaque" target="_blank" href="'.self::path.'pdf/'.$this->pdf .'"><img src="'. self::path.'imagem/'.$this->imagem .'" style="width:'.$this->largura_p.'px;height:'.$this->altura_p.'px;margin:'.$this->margin.'px" class="img-polaroid img-destaques" /></a>';
        }
    }
    $destaques = array();
    $destaques[]  = new Destaque("FactSheetAtlasBrasil2013__ Metodologia.pdf", "FactSheetAtlasBrasil2013__ Metodologia_0001.jpg",0.7);
    $destaques[]  = new Destaque("FactSheetAtlasBrasil2013_Faixas_DH.pdf", "FactSheetAtlasBrasil2013_Faixas_DH_0001.jpg",0.7);
    $destaques[]  = new Destaque("FactSheetAtlasBrasil2013_IDHM_Brasil.pdf", "FactSheetAtlasBrasil2013_IDHM_Brasil_0001.jpg",0.7);
    $destaques[]  = new Destaque("FactSheetAtlasBrasil2013_Educacao.pdf", "FactSheetAtlasBrasil2013_Educação_0002.jpg",0.7);
    $destaques[]  = new Destaque("FactSheetAtlasBrasil2013_Longevidade_e_Renda.pdf", "FactSheetAtlasBrasil2013_Longevidade_e_Renda_0001.jpg",0.7);
?>
<style>
    .defaltWidthContent{
        overflow: visible;
    }
    .tr-holder td{
	text-align: center;
	padding-bottom: 10px;
	font-weight: bold;
    }
</style>
<script>
    $(document).ready(function(){
        $(".img-destaques").hover(function(){
            $(this).css("z-index","100");
        }, function(){
            $(this).css("z-index","50");
        })
    });
</script>
<div class="contentPages" style="min-height: 600px;">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage" id='destaques_title'></div> 
            </div>
        </div> 
        <div style='text-align: center;margin-left: auto;margin-right: auto; width: 854px'>
	    <table>
		<tr class="tr-holder">
		    <td id='destaques_metodologia'></td>
		    <td id='destaques_faixas_idhm'></td>
		    <td id='destaques_idhmBrasil'></td>
		    <td id='destaques_educacao'></td>
		    <td id='destaques_longRenda'></td>
		</tr>
		<tr>
		    <?php
			foreach($destaques as $destaque){
			    echo "<td>";
			    $destaque->draw();
			    echo "</td>";
			}
		    ?>
		</tr>
	    </table>
        </div>
    </div>
    
</div>

<script type='text/javascript'>
	$("#destaques_title").html(lang_mng.getString("destaques_title"));
	$("#destaques_metodologia").html(lang_mng.getString("destaques_metodologia"));
	$("#destaques_faixas_idhm").html(lang_mng.getString("destaques_faixas_idhm"));
	$("#destaques_idhmBrasil").html(lang_mng.getString("destaques_idhmBrasil"));
	$("#destaques_educacao").html(lang_mng.getString("destaques_educacao"));
	$("#destaques_longRenda").html(lang_mng.getString("destaques_longRenda"));
</script>



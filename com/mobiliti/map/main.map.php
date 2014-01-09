<!DOCTYPE html>
<html>
<head>
	<title>Teste do Mapa</title>
	<script type="text/javascript" src="<?php echo $path_dir ?>js/jquery-1.7.2.min.js"></script>
	
	
	<!-- Incluir no Main definitivo -->
	<link href="<?php echo $path_dir ?>css/smoothness/jquery-ui-1.10.1.custom.css" rel="stylesheet">
	<script src="<?php echo $path_dir ?>js/jquery-ui-1.10.1.custom.min.js"></script>
	
	
	<script type="text/javascript">


	function iniciar_mapa(){
        
        /*
        public static $ESP_NACIONAL = 1;
        public static $ESP_MUNICIPAL = 2;
        public static $ESP_REGIONAL = 3;
        public static $ESP_ESTADUAL = 4;
        public static $ESP_UDH = 5;
        public static $ESP_REGIAOMETROPOLITANA = 6;
        public static $ESP_REGIAODEINTERESSE = 7;
        
        
        
                
        public static $FILTRO_MUNICIPIO = 1;
        public static $FILTRO_ESTADO = 2;
        public static $FILTRO_REGIAO = 3;
        public static $FILTRO_UDH = 4;
        public static $FILTRO_MICROREGIAO = 5;
        public static $FILTRO_REGIAOMETROPOLITANA = 6;
        public static $FILTRO_REGIAODEINTERESSE = 7;
        */   

  
       var con = document.URL.replace("http://localhost/ipea/mapa/",""); 
       var url = JSON.parse('<?php echo URL::urlToJson(str_replace("/ipea/", "", $_SERVER['REQUEST_URI'])); ?>');
       
       map_build(url.mapa);
                        
}
</script>
	
</head>
<body>

<h1>Mapa module</h1>
<button onclick="iniciar_mapa();">Gerar pela URL</button>
<div style="width:800px;height:800px; margin:0 auto;background-color:#ccc;position:relative;">
 <?php include("ui.map.php"); ?>
</div>

</body>
</html>
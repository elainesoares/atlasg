<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') 
{
    header("Location: {$path_dir}404");
}

ob_start("ob_gzhandler");

include_once("../../../config/config_path.php");

include("./Map.class.php");
include("./MapResponse.class.php");

include_once("../../../config/conexao.class.php");

include_once("../consulta/Consulta.class.php");
include_once("../util/PublicMethods.class.php");
include_once("../util/Color.php");


#instanciando o objeto
$ocon = new Conexao();

#chamada ao metodo open que abra a conexao

$omap = new Map($ocon, MAP_IMG_PATH, MAP_IMG_URL,MAP_FILE_ZERO);

// É necessário colocar o import da função antes de utilizá-la
include_once("../util/protect_sql_injection.php");

/* =================== LER VALORES DA REQUISIÇÃO =============================== */

/* define as dimensões do mapa */
$omap->setHeight((int)$_POST["height"]);
$omap->setWidth((int)$_POST["width"]);

$e = (int)$_POST["e"];

$l = array();
if(isset($_POST["l"]))
{
   $l = explode(",", $_POST["l"]);
}

$i = (int)$_POST["i"];
$a = (int)$_POST["a"];

$istool = $_POST["istool"];

//As variáveis abaixo não são utilizadas em consultas SQL
$extent = explode(" ", $_POST["extent"]);
$zoom_extent =  $_POST['zoom_extent'];


$quantil_id = $_POST["quantil_id"];

/* =================== LIMPA REQUISIÇÃO ======================================== */

$_GET = null;
$_POST = null;
$_REQUEST = null;

/* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */


if($e == Consulta::$ESP_REGIAODEINTERESSE)
{
    $l = $omap->findCitiesRI($l);
}


/*Se houver necessidade de zoom sobre o mapa*/
/*também verifica necessidade de exibição das bordas das cidades*/
if($zoom_extent != "null")
{
    $ext = explode(" ", $zoom_extent);

    $coord0 = PublicMethods::click2map((int)$ext[0], (int)$ext[3], $extent, (int)$omap->getWidth(), (int)$omap->getHeight());
    $coord1 = PublicMethods::click2map((int)$ext[2], (int)$ext[1], $extent, (int)$omap->getWidth(), (int)$omap->getHeight());
   
    $ext_zoom = array($coord0[0], $coord0[1], $coord1[0], $coord1[1]);
    
    $omap->setExtent($ext_zoom);
}
else
{   
    //faz o zoom automático
    if(sizeof($l) > 0 && sizeof($l) < 5000 && $istool == "false")
    {
        $places = "";
        foreach ($l as $value) 
        {
            $places = $places . $value . ",";
        }
        $places = substr($places, 0, -1);
        
        if($e == Consulta::$ESP_MUNICIPAL || $e == Consulta::$ESP_REGIAODEINTERESSE)
        {
            $query = "SELECT ST_Extent(the_geom) as bbox FROM public.municipio where id in ($places);";
        }  
        else if($e == Consulta::$ESP_ESTADUAL)
        {
            $query = "SELECT ST_Extent(the_geom) as bbox FROM public.estado where id in ($places);";
        }
        
        
        $link = $ocon->open();
        $res = pg_query($link, $query) or die("Nao foi possivel executar a consulta!");

        $row = pg_fetch_array($res, null, PGSQL_ASSOC);
        $bbox = $row["bbox"];
       
        $bbox = str_replace ( "BOX(" , "" , $bbox);
        $bbox = str_replace ( ")" , "" , $bbox);
        $bbox = str_replace ( "," , " " , $bbox);

        $extent = explode(" ", $bbox);
    }

    $omap->setExtent($extent);
}


/* mostar ou não bordas*/
$oextent = $omap->getExtent();
$minx = abs($oextent["minx"]);
$maxx = abs($oextent["maxx"]);

/*Pelo fato do brasil estar a esquerda de Greenwich o minx é maior que maxx*/
$skip_click = true;
$omap->setTheGeom(Map::$T_GEOM_LIGHT);
$delta_x = $minx  - $maxx;
$pan_step = 2;
if($delta_x <= MAP_DELTA_X)
{
    $omap->setTheGeom(Map::$T_GEOM_HEAVY);
    $omap->setBoundaries(true);
    $pan_step = 0.1;
    $skip_click = false;
}


/******************************* */
// Mecanismo de consulta do mapa
/******************************* */



$omap->setQuantilID($quantil_id);
$omap->setSpatiality($e);


if($e == Consulta::$ESP_REGIAODEINTERESSE){
    $quantil_id = $omap->selectByRegioesDeInteresse($l,$i,$a);
}
else if($e == Consulta::$ESP_ESTADUAL){
    $quantil_id = $omap->selectByStates($l,$i,$a);
}   
else if($e == Consulta::$ESP_MUNICIPAL){
    $quantil_id = $omap->selectByCities($l,$i,$a);
}


/********************************/
//mostra as bordas dos estados
$omap->buildStatesBondaries();
/********************************/


/******************************/
$map_result = $omap->saveMap();
/********************************/

/* gera a saída para o controle ui do mapa */
$image_url = $map_result["map"];
if($i == 0)
    $legend_url = "tmp/zero.gif";
else
    $legend_url = $map_result["legend"];
$extent_to_html = $oextent["minx"] . " " . $oextent["miny"] . " " . $oextent["maxx"] . " " . $oextent["maxy"];

$response = new MapResponse($image_url, $legend_url, $extent_to_html,NULL,$pan_step,$quantil_id,$skip_click);

echo $response->getJSON();

?>
<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') 
{
    header("Location: {$path_dir}404");
}

include_once("../../../config/conexao.class.php");
include_once("../../../config/config_path.php");
include_once("../util/PublicMethods.class.php");
include_once("../consulta/Consulta.class.php");


// É necessário colocar o import da função antes de utilizá-la
include_once("../util/protect_sql_injection.php");

/* =================== LER VALORES DA REQUISIÇÃO =============================== */
$spac   = (int)$_POST['spac'];
$extent = explode(" ",$_POST["extent"]);
$height = (int)$_POST["height"];
$width  = (int)$_POST["width"];
$indc   = (int)$_POST['indc'];
$year   = (int)$_POST['year'];
$local  = (int)$_POST['local'];

/* =================== LIMPA REQUISIÇÃO ======================================== */
$_GET = null;
$_POST = null;
$_REQUEST = null;
/* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */
$ocon = new Conexao();
$link = $ocon->open();

$query = "";
switch($spac)
{

    case  Consulta::$ESP_MUNICIPAL:
         $query = "SELECT m.id, m.nome as title, m.the_geom, ROUND(v.valor,3) AS valor, e.uf as estado, lower( replace(m.nome,' ','-') || '_' || e.uf ) as perfil FROM municipio m INNER JOIN ESTADO e ON m.fk_estado = e.id INNER JOIN valor_variavel_mun v ON m.id = v.fk_municipio WHERE  v.fk_ano_referencia =" . $year. " and v.fk_variavel = " . $indc ." AND m.id = $local ;";                   
         break;
    case Consulta::$ESP_ESTADUAL:
         $query = "SELECT e.id, e.nome as title, e.the_geom, ROUND(v.valor,3) AS valor FROM estado e INNER JOIN valor_variavel_estado v ON e.id = v.fk_estado WHERE  v.fk_ano_referencia =" . $year. " and v.fk_variavel = " . $indc ." AND e.id = $local ;";         
         break;
}


$result = array();
$result["id"] = 0;

if( $spac && $year && $indc)
{
    $res = pg_query($link, $query) or die("Nao foi possivel executar a consulta!");

    $result_array = pg_fetch_array($res, null, PGSQL_ASSOC);
    $result["id"] = $result_array["id"];     
    $result["title"] = $result_array["title"]; 
    $result["value"] = $result_array["valor"];
    if(isset($result_array["estado"]))$result["estado"] = $result_array["estado"]; 
    if(isset($result_array["perfil"]))$result["perfil"] = $result_array["perfil"];
}


if($result["id"])
{
   
        $map = ms_newMapObj(MAP_FILE_SELECTION);
        $map->name = "Seleção de região";
        $map->status = MS_ON;
        $map->units = MS_METERS;
        $map->height = $height;
        $map->width = $width;

        //configura local onde as imagens serão salvas
        $webMaps = $map->web;

        $webMaps->imagepath = MAP_IMG_PATH;
        $webMaps->imageurl = MAP_IMG_URL;
 
        //extensão padrão
        $map->setExtent($extent[0], $extent[1], $extent[2], $extent[3]);

        
        $layer = ms_newLayerObj($map);
        $layer->name = "";
        $layer->type = MS_SHAPE_POLYGON;
        $layer->status = MS_ON;
        
        
        
        $con = new Conexao();
        $ht = $con->getHost();
        $db = $con->getNameBd();
        $pt = $con->getPort();
        $us = $con->getUser();
        $ps = $con->getPassword();


        $layer->setConnectionType(MS_POSTGIS);
        $layer->connection = "dbname=$db host=$ht port=$pt user=$us password=$ps sslmode=disable";
        
        
        $id = $result["id"];
        
        switch($spac)
        {
            case  Consulta::$ESP_MUNICIPAL:
                 $squery = "SELECT id, the_geom FROM municipio  WHERE id = $id ";
                 break;
            case Consulta::$ESP_ESTADUAL:
                 $squery = "SELECT id, the_geom FROM estado WHERE id = $id ";
                 break;
        }
        
                
        $layer->data = "the_geom from ($squery) as nova_tabela USING UNIQUE id USING srid=4326";

        $class = ms_newClassObj($layer);
        $class->setExpression("([id] > 0)");
        
        $style = ms_newStyleObj($class);
        $style->outlinecolor->setRGB(MAP_HIGHLIGHT_COLOR_RED,MAP_HIGHLIGHT_COLOR_GREEN,MAP_HIGHLIGHT_COLOR_BLUE);
        $style->width = 3;

        $image = $map->draw();
        $result["selection"] = $image->saveWebImage();
 
}


$result["idh"] = null;
$result["longevidade"] = null;
$result["renda"] = null;
$result["educacao"] = null;


if($result["id"])
{
    $squery_idh = "";
    $squery_lon = "";
    $squery_ren = "";
    $squery_edc = "";

    switch($spac)
    {
        case Consulta::$ESP_UDH:
             $squery = "";
             break;
        case  Consulta::$ESP_MUNICIPAL:
             $squery_idh = "SELECT valor FROM valor_variavel_mun WHERE fk_municipio = " . $result["id"] . " AND fk_ano_referencia = $year AND fk_variavel = " . INDICADOR_IDH . ";";
             $squery_lon = "SELECT valor FROM valor_variavel_mun WHERE fk_municipio = " . $result["id"] . " AND fk_ano_referencia = $year AND fk_variavel = " . INDICADOR_LONGEVIDADE . ";";
             $squery_ren = "SELECT valor FROM valor_variavel_mun WHERE fk_municipio = " . $result["id"] . " AND fk_ano_referencia = $year AND fk_variavel = " . INDICADOR_RENDA . ";";
             $squery_edc = "SELECT valor FROM valor_variavel_mun WHERE fk_municipio = " . $result["id"] . " AND fk_ano_referencia = $year AND fk_variavel = " . INDICADOR_EDUCACAO . ";";
             break;
        case  Consulta::$ESP_MICROREGIAO:
             $squery = "";
             break;
        case Consulta::$ESP_MESOREGIAO:
             $squery = "";
             break;
        case Consulta::$ESP_REGIAOMETROPOLITANA:
             $squery = "";
             break;
        case Consulta::$ESP_ESTADUAL:
             $squery_idh = "SELECT valor FROM valor_variavel_estado WHERE fk_estado = " . $result["id"] . " AND fk_ano_referencia = $year AND fk_variavel = " . INDICADOR_IDH . ";";
             $squery_lon = "SELECT valor FROM valor_variavel_estado WHERE fk_estado = " . $result["id"] . " AND fk_ano_referencia = $year AND fk_variavel = " . INDICADOR_LONGEVIDADE . ";";
             $squery_ren = "SELECT valor FROM valor_variavel_estado WHERE fk_estado = " . $result["id"] . " AND fk_ano_referencia = $year AND fk_variavel = " . INDICADOR_RENDA . ";";
             $squery_edc = "SELECT valor FROM valor_variavel_estado WHERE fk_estado = " . $result["id"] . " AND fk_ano_referencia = $year AND fk_variavel = " . INDICADOR_EDUCACAO . ";";
             break;
        case Consulta::$ESP_REGIONAL:
             $squery = "";
             break;
        case Consulta::$ESP_PAIS:   
             $squery = "";
             break;
        case Consulta::$ESP_REGIAODEINTERESSE:
            $squery = "";
            break;
    }

    $res_idh = pg_query($link, $squery_idh);
    $res_lon = pg_query($link, $squery_lon);
    $res_ren = pg_query($link, $squery_ren);
    $res_edc = pg_query($link, $squery_edc);

    $result_array_idh = pg_fetch_array($res_idh, null, PGSQL_ASSOC);
    $result_array_lon = pg_fetch_array($res_lon, null, PGSQL_ASSOC);
    $result_array_ren = pg_fetch_array($res_ren, null, PGSQL_ASSOC);
    $result_array_edc = pg_fetch_array($res_edc, null, PGSQL_ASSOC);


    $result["idh"] = $result_array_idh["valor"];
    $result["longevidade"] = $result_array_lon["valor"];
    $result["renda"] = $result_array_ren["valor"];
    $result["educacao"] = $result_array_edc["valor"];   
    
}

echo json_encode($result);

?>

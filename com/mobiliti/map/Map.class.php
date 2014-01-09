<?php
/* * ***************************
  class: map.class
  author: Reinaldo Aparecido Rocha Filho
 * *************************** */

    session_start();


include_once("../consulta/Consulta.class.php");
include_once("../util/Color.php");
include_once("Quantil.php");

class Map {
    
  
    private $quantil;
    private $quantil_id;
    
    
    public static $T_GEOM_LIGHT = "the_geom_light";
    public static $T_GEOM_HEAVY =  "the_geom";
    
    private static $MAX_DIMENSION = 1500;
    private $spatiality = "";
    private $classes = array();
    private $con;
    private $link;
    private $map;
    private $mapHeight = 800;
    private $mapWidth = 1000;
    private $loadedClasses =  array();
    private $boundaries =  false;
    private $the_geom  = 0;

    public function __construct($con, $imgpath, $imgurl,$mapfile) 
    {

        $this->con = $con;

        //configura map
        $this->map = ms_newMapObj($mapfile);
        $this->map->name = "Atlas do Desenvolvimento Humando Municipal";
        $this->map->status = MS_ON;
        $this->map->units = MS_METERS;

        //configura local onde as imagens serão salvas
        $webMaps = $this->map->web;

        $webMaps->imagepath = $imgpath;
        $webMaps->imageurl = $imgurl;

        $this->link = $con->open();
        
        $this->quantil = new Quantil();
        $this->quantil->setLink($this->link);
        
        $this->makeBrazilUnselected();
    }
    
    
    public function setTheGeom($val) {
        $this->the_geom = $val;
    }
    
    public function setQuantilID($val) {
        $this->quantil_id = $val;
    }

    public function getExtent(){
        $rct = $this->map->extent;
        return array("minx" => $rct->minx , "miny" => $rct->miny, "maxx" => $rct->maxx, "maxy" => $rct->maxy);
    }
    
    public function setBoundaries($val) {
        $this->boundaries = $val;
    }

    public function setHeight($num) {
        if ($num > 0 && $num < Map::$MAX_DIMENSION)
            $this->mapHeight = $num;
    }

    public function getHeight() {
        return $this->mapHeight;
    }

    public function setWidth($num) {
        if ($num > 0 && $num < Map::$MAX_DIMENSION)
            $this->mapWidth = $num;
    }

    public function getWidth() {
        return $this->mapWidth;
    }

    public function getSpatiality() {
        return $this->spatiality;
    }

    public function setSpatiality($spatiality) {
        $this->spatiality = $spatiality;
    }
    

    public function setExtent($ext){
         $this->map->setExtent($ext[0],$ext[1],$ext[2],$ext[3]);
    }

    private function createLayer($classitem) 
    {

        $layer = ms_newLayerObj($this->map);
        $layer->name = "";
        $layer->type = MS_SHAPE_POLYGON;
        $layer->status = MS_ON;
        $layer->classitem = $classitem;
        
        return $layer;
    }
    
    
    public function findCitiesRI($interesse) 
    {
       $arr = array();
       
       if(sizeof($interesse) <= 0)return $arr;
       
        $to_search = "";
        foreach ($interesse as $value) {
            $to_search = $to_search . $value . ",";
        }
        $to_search = substr($to_search, 0, -1); 
        
        $query = "  SELECT DISTINCT m.id FROM municipio m " .
                   "  INNER JOIN regiao_interesse_has_municipio ri ON m.id = ri.fk_municipio " .
                   "  WHERE ri.fk_regiao_interesse in ($to_search);";
        
        
        $result = pg_query($this->link, $query) or die("Nao foi possivel executar a consulta!");
        
        while($row = pg_fetch_array($result)){ $arr[] = $row["id"]; }
       
        return $arr;
    }
   
    

    public function selectByRegioesDeInteresse($interesse, $in, $an) 
    {
        return $this->selectByCities($interesse, $in, $an); 
    }
     
    public function selectByStates($states, $in, $an) 
    {
        
        if(sizeof($states) <= 0)return 0;
        
        $layer = $this->createLayer("valor");
        $this->buildPostGISConnection($layer);

       
        $states_to_search = "";
        foreach ($states as $value) {
            $states_to_search = $states_to_search . $value . ",";
        }
        $states_to_search = substr($states_to_search, 0, -1);

 
       $query = "SELECT e.id, e.the_geom, ROUND(v.valor,3) AS valor FROM estado e INNER JOIN valor_variavel_estado v on e.id = v.fk_estado WHERE fk_estado in ($states_to_search) and v.fk_ano_referencia = $an and v.fk_variavel = $in ";              
       
       
       $quartis_info = "SELECT COUNT(v.valor) as count ,ROUND(MAX(v.valor),3) as max ,ROUND(MIN(v.valor),3) as min FROM valor_variavel_estado v WHERE fk_estado in ($states_to_search) and v.fk_ano_referencia = $an and v.fk_variavel = $in;";              
       $quartis_data = "SELECT ROUND(v.valor,3) AS valor FROM valor_variavel_estado v WHERE fk_estado in ($states_to_search) and v.fk_ano_referencia = $an and v.fk_variavel = $in ORDER BY valor;";              

       
       $layer->data = "the_geom from ($query) as nova_tabela USING UNIQUE id USING srid=4326";
        
       if($this->quantil_id == "make")
       {
            //$this->quantil_id = $this->quantil->buildQuartil ($quartis_info, $quartis_data);
            $this->quantil_id = $this->quantil->buildQuintil($quartis_info, $quartis_data, $this->getDecimalLength($in), $in, $an, Consulta::$ESP_ESTADUAL);
            $this->loadQuantil ($layer, $this->quantil_id);
       }
       else if($this->quantil_id != "")
           $this->loadQuantil ($layer, $this->quantil_id);
       else
           $this->loadClasses($layer, $in, $an,  Consulta::$ESP_ESTADUAL);
        
       return $this->quantil_id;
        
    }
    
    public function selectByCities($cities, $in, $an) 
    {
        if(sizeof($cities) <= 0)return 0;
        
        $layer = $this->createLayer("valor");
        $this->buildPostGISConnection($layer);

        
        $cities_to_search = "";
        foreach ($cities as $value) {
            $cities_to_search = $cities_to_search . $value . ",";
        }
        $cities_to_search = substr($cities_to_search, 0, -1);

        $query = "SELECT m.id, m." . $this->the_geom . ", ROUND(v.valor,3) as valor FROM municipio m INNER JOIN valor_variavel_mun v ON m.id = v.fk_municipio WHERE fk_municipio in ($cities_to_search) and v.fk_ano_referencia = $an and v.fk_variavel = $in ";              
        
        $quartis_info = "SELECT COUNT(v.valor) as count ,ROUND(MAX(v.valor),3) as max ,ROUND(MIN(v.valor),3) as min FROM valor_variavel_mun v WHERE fk_municipio in ($cities_to_search) and v.fk_ano_referencia = $an and v.fk_variavel = $in;";              
        $quartis_data = "SELECT ROUND(v.valor,3) AS valor FROM valor_variavel_mun v WHERE fk_municipio in ($cities_to_search) and v.fk_ano_referencia = $an and v.fk_variavel = $in ORDER BY valor;";              
        
       
        $layer->data = $this->the_geom  . " from ($query) as nova_tabela USING UNIQUE id USING srid=4326";
        
        if($this->quantil_id == "make")
        {
            //$this->quantil_id = $this->quantil->buildQuartil ($quartis_info, $quartis_data);
            $this->quantil_id = $this->quantil->buildQuintil ($quartis_info, $quartis_data, $this->getDecimalLength($in), $in, $an, Consulta::$ESP_MUNICIPAL);
            $this->loadQuantil ($layer, $this->quantil_id);
        }
        else if($this->quantil_id != "")
            $this->loadQuantil ($layer, $this->quantil_id);
        else
            $this->loadClasses($layer, $in, $an, Consulta::$ESP_MUNICIPAL);
        
        return $this->quantil_id;
        
    }
  
    private function loadClasses($layer, $in, $an, $espc)
    {
 
        if(sizeof($this->loadedClasses) > 0)
        {
            foreach ($this->loadedClasses as $objcls) 
            {
          
                $layer_class = ms_newClassObj($layer);
                $layer_class->setExpression($objcls["expression"]); 
                $class_style = ms_newStyleObj($layer_class);

                $color = $objcls["color"];
                $class_style->color->setRGB($color->getRed(), $color->getGreen(), $color->getBlue());
                
                
                if($this->boundaries)
                {
                    $outcolor = $objcls["outcolor"];
                    $class_style->outlinecolor->setRGB($outcolor->getRed(), $outcolor->getGreen(), $outcolor->getBlue());
                    $class_style->width = $objcls["width"];
                }
               
            }
        }
        else
        {
                //procura pelo grupo mais espcífico (ano e fk_variavel)
                $query_group = "SELECT id FROM classe_grupo WHERE fk_ano_referencia = $an AND fk_variavel = $in AND espacialidade = $espc;";
                $res = pg_query($this->link, $query_group) or die("Nao foi possivel executar a consulta!");
                $query_row = pg_fetch_row($res, null, PGSQL_ASSOC);
                
                if(!$query_row){
                    //procura pelo grupo geral da variável definida na consulta
                    $query_group = "SELECT id FROM classe_grupo WHERE fk_ano_referencia IS NULL and fk_variavel = $in AND espacialidade = $espc;";
                    $res = pg_query($this->link, $query_group) or die("Nao foi possivel executar a consulta!");
                    $query_row = pg_fetch_row($res, null, PGSQL_ASSOC);
                    
                    //procura pelo grupo mais geral de todos
                    if(!$query_row){
                        //procura pelo grupo geral da variável definida na consulta
                        $query_group = "SELECT id FROM classe_grupo WHERE fk_ano_referencia IS NULL and fk_variavel IS NULL;";
                        $res = pg_query($this->link, $query_group) or die("Nao foi possivel executar a consulta!");
                        $query_row = pg_fetch_row($res, null, PGSQL_ASSOC);
                    }
                }
                
 
                $group_id = $query_row["id"];

                if($group_id)
                {
                    $query_classe = "SELECT nome, maximo, minimo, cor_preenchimento, cor_linha, largura_linha FROM classe WHERE fk_classe_grupo = $group_id ORDER BY id DESC;";
                    $res = pg_query($this->link, $query_classe) or die("Nao foi possivel executar a consulta!");

                    while ($row_classe = pg_fetch_row($res, null, PGSQL_ASSOC)) {

                        $layer_class = ms_newClassObj($layer);
                        $layer_class->name = $row_classe["nome"];
                        $layer_class->setExpression("(([valor] >= ". $row_classe["minimo"] . ") AND ([valor] <= " . $row_classe["maximo"] . "))");

                        $class_style = ms_newStyleObj($layer_class);


                        $color = new Color(0, 0, 0);
                        $color->setHexColor($row_classe["cor_preenchimento"]);
                        $class_style->color->setRGB($color->getRed(), $color->getGreen(), $color->getBlue());

                        $outcolor = new Color(0, 0, 0);
                        $outcolor->setHexColor($row_classe["cor_linha"]);
                        
                        if($this->boundaries)
                        {
                           $class_style->outlinecolor->setRGB($outcolor->getRed(), $outcolor->getGreen(), $outcolor->getBlue());
                           $class_style->width = $row_classe["largura_linha"];
                        }
                
                        array_push($this->loadedClasses, array("expression" => $layer_class->getExpressionString(), "color" => $color, "outcolor" => $outcolor, "width" => $class_style->width));

                    }

                }
            
        }
    }
    
    
    
    private function loadQuantil($layer)
    {     
        foreach ($_SESSION["QUANTIL_CLASS"] as $json) 
        {
            $obj = json_decode($json);

            $layer_class = ms_newClassObj($layer);
            $layer_class->name = $obj->name;
            $layer_class->setExpression("(([valor] >= ". $obj->min . ") AND ([valor] <= " . $obj->max . "))");
            
            $class_style = ms_newStyleObj($layer_class);

            $color = new Color(0, 0, 0);
            $color->setHexColor($obj->color);
            $class_style->color->setRGB($color->getRed(), $color->getGreen(), $color->getBlue());

            $outcolor = new Color(0,0,0);
            $outcolor->setHexColor(BORDA_CIDADE);
      
            
            if($this->boundaries)
            {
               $class_style->outlinecolor->setRGB($outcolor->getRed(), $outcolor->getGreen(), $outcolor->getBlue());
               $class_style->width = 0.35;
            }

            array_push($this->loadedClasses, array("expression" => $layer_class->getExpressionString(), "color" => $color, "outcolor" => $outcolor, "width" => $class_style->width));
        }
    }

    private function buildUnselectedClass($layer) 
    {

        $class = ms_newClassObj($layer);
        $class->setExpression("([id] > 0)");
        $style = ms_newStyleObj($class);
        $style->width = 1;
        $style->color->setRGB(189, 189, 189);
        $style->outlinecolor->setRGB(189, 189, 189);
    }

    private function makeBrazilUnselected() 
    {

        $layer = ms_newLayerObj($this->map);
        $layer->name = "Brasil descelecionado";
        $layer->type = MS_SHAPE_POLYGON;
        $layer->status = MS_ON;
        $layer->classitem = "id";

        $this->buildPostGISConnection($layer);

        $query = "select p.id, p.the_geom from pais p where p.id = 103";
        $layer->data = "the_geom from ($query) as nova_tabela USING UNIQUE id USING srid=4326";

        $this->buildUnselectedClass($layer);
    }

    private function buildPostGISConnection($layer) 
    {

        $ht = $this->con->getHost();
        $db = $this->con->getNameBd();
        $pt = $this->con->getPort();
        $us = $this->con->getUser();
        $ps = $this->con->getPassword();


        $layer->setConnectionType(MS_POSTGIS);
        $layer->connection = "dbname=$db host=$ht port=$pt user=$us password=$ps sslmode=disable";
    }

    /**
      name: saveMap
      desc: salva as imagens no caminho especificado no construtor da classe e rotorna um array com o mapa e a legenda.
      return: Array = { map => "map_file.gif", legend => "legend_file_.gif"}
     * */
    public function saveMap() 
    {

        $oLegenda = $this->map->legend;

        $this->map->height = $this->mapHeight;
        $this->map->width = $this->mapWidth;


        //gera o mapa final 
        $legend_image = $this->map->drawLegend();
        $legend_url = $legend_image->saveWebImage();

        $image = $this->map->draw();
        $image_url = $image->saveWebImage();

        return array("map" => $image_url, "legend" => $legend_url);
    }
    
    
    /**
      name: getStatesBondaries
      desc: constroí as bordas dos estados
     
     * */
    public function buildStatesBondaries()
    {
        
        $layer = $this->createLayer("id");
        $this->buildPostGISConnection($layer);

        $query = "select e.id, e.the_geom from estado e";
        $layer->data = "the_geom from ($query) as nova_tabela USING UNIQUE id USING srid=4326";
        
        $class = ms_newClassObj($layer);
        $class->setExpression("([id] > 0)");
        $style = ms_newStyleObj($class);
        
        if($this->boundaries)
            $style->width = 3;
        else
            $style->width = 1;
        
        /*$style->color->setRGB(189, 189, 189);*/
        $c = new Color(0, 0, 0);
        $c->setHexColor(BORDA_ESTADO);
        $style->outlinecolor->setRGB($c->getRed(), $c->getGreen(), $c->getBlue());
    }
    
    
    private function getDecimalLength($indc)
    {
        $decimais_sql = "SELECT decimais FROM variavel WHERE id = $indc;";
        $r_decimais = pg_query($this->link, $decimais_sql) or die("Nao foi possivel executar a consulta!");
        $decimais_obj = pg_fetch_object($r_decimais);


        if($decimais_obj != null)
        {
            $decimais = ($decimais_obj->decimais != null) ? (int)$decimais_obj->decimais : 0;
            return $decimais;
        }
        else
        {
            return 0;
        }
    }
    
    
}
?>
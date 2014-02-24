<?php
   if(!isset($_SESSION)) 
   { 
        session_start(); 
   }

class Quantil 
{
    //put your code here
    private $link;
    
    public function setLink($link)
    { 
        $this->link = $link;
    }
    
    
    public function buildQuartil($quartis_info, $quartis_data)
    {    
        
        $id = uniqid('', true);
        
        //pega os dados de valor e as infos sobre os dados
        $r_quartil_data = pg_query($this->link, $quartis_data) or die("Nao foi possivel executar a consulta!");
        $r_quartil_info = pg_query($this->link, $quartis_info) or die("Nao foi possivel executar a consulta!");
         
        $quartil_obj = pg_fetch_object($r_quartil_info, 0);
        $valor_dta = array();
        while($row = pg_fetch_array($r_quartil_data)){ $valor_dta[] = $row["valor"]; }
        
        $resultado = array();
        
        //CONSTRUÇÃO DO QUARTIL
        
        //primeiro quartil
        $pri_qrts = round(.25 *  $quartil_obj->count);
        $name = $quartil_obj->min . " a " . $valor_dta[$pri_qrts];
        $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$quartil_obj->min\", \"max\":\"$valor_dta[$pri_qrts]\", \"color\":\"#FF0000\"}";
        array_push($resultado, $class_object);
        
         //segundo quartil
        if(($quartil_obj->count % 2) == 0)
            $seg_qrts = ($valor_dta[sizeof($valor_dta)/2] + $valor_dta[(sizeof($valor_dta)/2) + 1]) / 2;
        else
            $seg_qrts = $valor_dta[(sizeof($valor_dta)/2) + 1];
  
        $name = $valor_dta[$pri_qrts] . " a " . $seg_qrts;
        $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$pri_qrts]\", \"max\":\"$seg_qrts\", \"color\":\"#FFFF00\"}";
        array_push($resultado, $class_object);
         
        //terc quartil
        $terc_qrts = round(.75 *  $quartil_obj->count);
        $name =  $seg_qrts  . " a " . $valor_dta[$terc_qrts];
        $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$seg_qrts\", \"max\":\"$valor_dta[$terc_qrts]\", \"color\":\"#00FF00\"}";
        array_push($resultado, $class_object);
        
        //final
        $name =  $valor_dta[$terc_qrts]  . " a " . $quartil_obj->max;
        $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$terc_qrts]\", \"max\":\"$quartil_obj->max\", \"color\":\"#00FFFF\"}";
        array_push($resultado, $class_object);
        
       
        $_SESSION["QUANTIL_CLASS"] = $resultado;
        
        return $id;
    }
    
   
    
    private function obtercor_do_banco($in, $an, $espc)
    {
        $c = json_decode(QUINTIL_COLORS);
       //procura pelo grupo mais espcífico (ano e fk_variavel)
        $query_group = "SELECT id FROM classe_grupo WHERE fk_ano_referencia = $an AND fk_variavel = $in AND espacialidade = $espc;";
        $res = pg_query($this->link, $query_group) or die("Nao foi possivel executar a consulta!");
        $query_row = pg_fetch_row($res, null, PGSQL_ASSOC);

        if(!$query_row)
        {
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
            $query_classe = "SELECT  cor_preenchimento FROM classe WHERE fk_classe_grupo = $group_id ORDER BY id ASC;";
            $res = pg_query($this->link, $query_classe) or die("Nao foi possivel executar a consulta!");
            
            $index = 0;
            while ($row_classe = pg_fetch_row($res, null, PGSQL_ASSOC))
            {
                if(isset($c->colors[$index])) $c->colors[$index] = $row_classe["cor_preenchimento"];
                $index++;
            }

        }
        
        return $c;
    }
    
    
    
    public function buildQuintil($quartis_info, $quartis_data, $dec, $ind , $an, $espc)
    {    

        
        $id = uniqid('', true);
        $c = $this->obtercor_do_banco($ind, $an, $espc);
        
        //pega os dados de valor e as infos sobre os dados
        $r_quartil_data = pg_query($this->link, $quartis_data) or die("Nao foi possivel executar a consulta!");
        $r_quartil_info = pg_query($this->link, $quartis_info) or die("Nao foi possivel executar a consulta!");
        
        
        $r_limites = pg_query($this->link, "SELECT minimo, maximo_e, maximo_m FROM variavel WHERE id = $ind;") or die("Nao foi possivel executar a consulta!");
        $limites = pg_fetch_object($r_limites);
       
        if($limites->minimo == NULL)$limites->minimo = 0;
        if($limites->maximo_e == NULL)$limites->maximo_e = 1000000;
        if($limites->maximo_m == NULL)$limites->maximo_m = 1000000;
        
        $quartil_obj = pg_fetch_object($r_quartil_info, 0);
        $valor_dta = array();
        while($row = pg_fetch_array($r_quartil_data))
        {
            if($row["valor"] != -1)$valor_dta[] = $row["valor"]; 
        }
        
        $resultado = array();
        $count = sizeof($valor_dta);
        //CONSTRUÇÃO DO QUINTIL
        
        if($count == 0)
        {
            $_SESSION["QUANTIL_CLASS"] = array_reverse($resultado);
            return $id;
        } 
        
        if($count <= 5)
        {
            $name = $limites->minimo . " a " . number_format($quartil_obj->max,$dec,",",".");
            $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$limites->minimo\", \"max\":\"$quartil_obj->max\", \"color\":\"" . $c->colors[4] . "\"}";
            array_push($resultado, $class_object);
            $_SESSION["QUANTIL_CLASS"] = array_reverse($resultado);
            return $id;
        }
        
        //primeiro quartil
        $pri_qrts = round(.20 *  $count);
        if(isset($valor_dta[$pri_qrts]))
        {
            $name = $limites->minimo . " a " . number_format($valor_dta[$pri_qrts],$dec,",",".");
            $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$limites->minimo\", \"max\":\"$valor_dta[$pri_qrts]\", \"color\":\"" . $c->colors[0] . "\"}";
            array_push($resultado, $class_object);
        }
        
        
        //segundo quartil
        $sec_qrts = round(.40 *  $count);
        if(isset($valor_dta[$pri_qrts]) && $valor_dta[$sec_qrts])
        {
            $name = number_format($valor_dta[$pri_qrts],$dec,",",".") . " a " . number_format($valor_dta[$sec_qrts],$dec,",",".");
            $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$pri_qrts]\", \"max\":\"$valor_dta[$sec_qrts]\", \"color\":\"" . $c->colors[1] . "\"}";
            array_push($resultado, $class_object);
        }
        
        
        //terceiro quartil
        $ter_qrts = round(.60 *  $count);
        if(isset($valor_dta[$sec_qrts]) && $valor_dta[$ter_qrts])
        {
            $name = number_format($valor_dta[$sec_qrts],$dec,",",".") . " a " . number_format($valor_dta[$ter_qrts],$dec,",",".");
            $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$sec_qrts]\", \"max\":\"$valor_dta[$ter_qrts]\", \"color\":\"" . $c->colors[2] . "\"}";
            array_push($resultado, $class_object);
        }
        
        
        //quarto quartil
        $qrt_qrts = round(.80 *  $count);
        if(isset($valor_dta[$ter_qrts]) && $valor_dta[$qrt_qrts])
        {
            $name = number_format($valor_dta[$ter_qrts],$dec,",",".") . " a " . number_format($valor_dta[$qrt_qrts],$dec,",",".");
            $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$ter_qrts]\", \"max\":\"$valor_dta[$qrt_qrts]\", \"color\":\"" . $c->colors[3] . "\"}";
            array_push($resultado, $class_object);
        }
        
        //final
        if(isset($valor_dta[$qrt_qrts]))
        {
              $name = number_format($valor_dta[$qrt_qrts],$dec,",",".") . " a " . number_format($quartil_obj->max,$dec,",",".");
              $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$qrt_qrts]\", \"max\":\"$quartil_obj->max\", \"color\":\"" . $c->colors[4] . "\"}";
              array_push($resultado, $class_object);
        }
        

                  
        $_SESSION["QUANTIL_CLASS"] = array_reverse($resultado);
        
        return $id;
    }
    
    
    
    
    public function buildAndReturnQuintil($quartis_info, $quartis_data, $dec, $ind, $espc)
    {    
        
        $id = uniqid('id', true);
        $c = json_decode(QUINTIL_COLORS);
        
        //pega os dados de valor e as infos sobre os dados
        $r_quartil_data = pg_query($this->link, $quartis_data) or die("Nao foi possivel executar a consulta!");
        $r_quartil_info = pg_query($this->link, $quartis_info) or die("Nao foi possivel executar a consulta!");
        
        $r_limites = pg_query($this->link, "SELECT minimo, maximo_e, maximo_m FROM variavel WHERE id = $ind;") or die("Nao foi possivel executar a consulta!");
        $limites = pg_fetch_object($r_limites);
       
        if($limites->minimo == NULL)$limites->minimo = 0;
        if($limites->maximo_e == NULL)$limites->maximo_e = 1000000;
        if($limites->maximo_m == NULL)$limites->maximo_m = 1000000;
          
        $quartil_obj = pg_fetch_object($r_quartil_info, 0);
        $valor_dta = array();
        while($row = pg_fetch_array($r_quartil_data)){
            if( $row["valor"] != -1)$valor_dta[] = $row["valor"]; 
        }
        
        $resultado = array();
        if(sizeof($valor_dta) == 0) return $resultado;
        
        
       //CONSTRUÇÃO DO QUINTIL 
       try 
       {
           //primeiro quartil
           $pri_qrts = round(.20 *  $quartil_obj->count);
           if(isset($valor_dta[$pri_qrts]))
           {
                $name = $limites->minimo . " a " . number_format($valor_dta[$pri_qrts],$dec,",",".");
                $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$limites->minimo\", \"max\":\"$valor_dta[$pri_qrts]\", \"color\":\"" . $c->colors[0] . "\"}";
                array_push($resultado, $class_object);
           }
            
           //segundo quartil
           $sec_qrts = round(.40 *  $quartil_obj->count);
           if(isset($valor_dta[$pri_qrts]) && isset($valor_dta[$sec_qrts]))
           {
                $name = number_format($valor_dta[$pri_qrts],$dec,",",".") . " a " . number_format($valor_dta[$sec_qrts],$dec,",",".");
                $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$pri_qrts]\", \"max\":\"$valor_dta[$sec_qrts]\", \"color\":\"" . $c->colors[1] . "\"}";
                array_push($resultado, $class_object);
           }

           //terceiro quartil
           $ter_qrts = round(.60 *  $quartil_obj->count);
           if(isset($valor_dta[$sec_qrts]) && isset($valor_dta[$ter_qrts]))
           {
                $name = number_format($valor_dta[$sec_qrts],$dec,",",".") . " a " . number_format($valor_dta[$ter_qrts],$dec,",",".");
                $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$sec_qrts]\", \"max\":\"$valor_dta[$ter_qrts]\", \"color\":\"" . $c->colors[2] . "\"}";
                array_push($resultado, $class_object);
           }
           
           //quarto quartil
           $qrt_qrts = round(.80 *  $quartil_obj->count);
           if(isset($valor_dta[$ter_qrts]) && isset($valor_dta[$qrt_qrts]))
           {
                $name = number_format($valor_dta[$ter_qrts],$dec,",",".") . " a " . number_format($valor_dta[$qrt_qrts],$dec,",",".");
                $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$ter_qrts]\", \"max\":\"$valor_dta[$qrt_qrts]\", \"color\":\"" . $c->colors[3] . "\"}";
                array_push($resultado, $class_object); 
           }

           //final
           if($espc == Consulta::$ESP_MUNICIPAL)
           {
               if(isset($valor_dta[$qrt_qrts]))
               {
                     $name = number_format($valor_dta[$qrt_qrts],$dec,",",".") . " a " . number_format($limites->maximo_m,$dec,",",".");
                     $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$qrt_qrts]\", \"max\":\"$limites->maximo_m\", \"color\":\"" . $c->colors[4] . "\"}";
                     array_push($resultado, $class_object);
               }
           }
           else if($espc == Consulta::$ESP_ESTADUAL)
           {
               if(isset($valor_dta[$qrt_qrts]))
               {
                     $name = number_format($valor_dta[$qrt_qrts],$dec,",",".") . " a " . number_format($limites->maximo_e,$dec,",",".");
                     $class_object = "{\"id\":\"$id\", \"name\":\"$name\", \"min\":\"$valor_dta[$qrt_qrts]\", \"max\":\"$limites->maximo_e\", \"color\":\"" . $c->colors[4] . "\"}";
                     array_push($resultado, $class_object);
               }
           }
         
        }
        catch (Exception $e) {
            return $resultado;
        }
   
        return $resultado;
    }
    
}

?>

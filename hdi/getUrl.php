<?php
    if($aleatorio == true && $municipio1Arvore == 0 && $municipio2Arvore == 0){
        //Conexão ao banco de dados
        $minhaConexao = new Conexao();
        $con = $minhaConexao->open();
        
        //gerando municipios aleatorios
        $ids = array(rand(1, 5565), rand(1, 5565));
        //Pegando nome do município 1
        $query1 = "SELECT m.nome, e.uf FROM municipio as m, estado as e WHERE m.id = {$ids[0]} AND m.fk_estado = e.id";
        $res = @pg_query($con, $query1) or die("Nao foi possivel executar a consulta!");
        while ($linha = pg_fetch_array($res)){ 
               $NomesMun[0] = $linha[0];
               $Uf[0] = $linha[1];
        }
        
        //Pegando nome do município 2
        $query2 = "SELECT m.nome, e.uf FROM municipio as m, estado as e WHERE m.id = {$ids[1]} AND m.fk_estado = e.id";
        $res = @pg_query($con, $query2) or die("Nao foi possivel executar a consulta!");
        while ($linha2 = pg_fetch_array($res)) {
               $NomesMun[1] = $linha2[0];
               $Uf[1] = $linha2[1];
        }
        
        $NomesMunImp = setNomeImp($NomesMun, $Uf);

        $NomesMun = retira_acentos($NomesMun);
        
        $NomesMunEst = minJunt($NomesMun, $Uf);
        $Espac = array('municipio', 'municipio');
        $Ideal = array('nulo', 'nulo');
        $idAnos = array('3', '3');
        $Anos = array('2010', '2010');
    }
    if($municipio1Arvore != '0' || $municipio2Arvore != '0'){
        $arvore = new Arvore($municipio1Arvore, $municipio2Arvore);
        $ids = $arvore->getIds();
        $idAnos = $arvore->getidAnos();
        $Anos = $arvore->getAnos();
        $NomesMun = $arvore->getNomesMun();
        $Uf = $arvore->getUf();
        $NomesMunImp = setNomeImp($NomesMun, $Uf);
        $NomesMun = retira_acentos($NomesMun);
        $NomesMunEst = minJunt($NomesMun, $Uf);
        $Ideal = $arvore->getIdeal();
        $Espac = $arvore->getEspac();
    }

    else if($municipio1Arvore == 0 && $municipio2Arvore == 0 && $aleatorio == false){
        $ids = array('0','0');
        $idAnos = array('0','0');
        $Anos = array('0','0');
        $NomesMun = array('0','0');
        $Ideal = array('0','0');
        $Espac = array('0','0');
        $Uf = array('0','0');
        $NomesMunEst = array('0','0');
        $NomesMunImp = array('0','0');
    }
    
    function minJunt($NomesMun, $Uf){
        //Transformando para minúsculas
        $NomesMun[0] = strtolower($NomesMun[0]);
        $NomesMun[1] = strtolower($NomesMun[1]);
        $Uf[0] = strtolower($Uf[0]);
        $Uf[1] = strtolower($Uf[1]);

        //Juntando município e estado
        $NomesMunEst[0] = $NomesMun[0].'_'.$Uf[0];
        $NomesMunEst[1] = $NomesMun[1].'_'.$Uf[1];
        return $NomesMunEst;
    }
    
    function retira_acentos($NomesMun){
       $clear_array = array( "á" => "a" , "é" => "e" , "í" => "i" , "ó" => "o" , "ú" => "u" ,
            "à" => "a" , "è" => "e" , "ì" => "i" , "ò" => "o" , "ù" => "u" ,
            "ã" => "a" , "õ" => "o" , "â" => "a" , "ê" => "e" , "î" => "i" , "ô" => "o" , "û" => "u" , "ç" => "c" , "ü" => "u",
            "Á" => "A" , "É" => "E" , "Í" => "I" , "Ó" => "O" , "Ú" => "U",
            "À" => "A" , "È" => "E" , "Ì" => "I" , "Ò" => "O" , "Ù" => "U",
            "Ã" => "A" , "Õ" => "O" , "Â" => "A" , "Ê" => "E" , "Î" => "I" , "Ô" => "O" , "Û" => "U" , "Ç" => "C" , "Ü" => "U", " " => "-"); 
       
        //Tirando acentos do municipio 1
        foreach($clear_array as $key=>$val){
            $NomesMun[0] = str_replace($key, $val, $NomesMun[0]);
        } 
        
        //Tirando acentos do municipio 2
        foreach($clear_array as $key=>$val){
            $NomesMun[1] = str_replace($key, $val, $NomesMun[1]);
        }
        
        return $NomesMun;
    }
    
    function setNomeImp($NomesMun, $Uf){
        $NomesMunImp[0] = $NomesMun[0].' ('.$Uf[0].')';
        $NomesMunImp[1] = $NomesMun[1].' ('.$Uf[1].')';
        return $NomesMunImp;
    }
?>

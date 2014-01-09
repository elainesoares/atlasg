<?php    if ($pagNext == null || $pagNext == "") {
        include "views/index.php";
    } else {
        $var1 = $pagNext;
        if(isset($gets[2])){
            if(isset($gets[3])){
                if(isset($gets[4]))
                    $var1($gets[2],$gets[3],$gets[4]);
                else
                    $var1($gets[2],$gets[3]);
            }else{
                $var1($gets[2]);
            }
        }
        else
            $var1();
    }
    
    function limpar_cache(){
        $files = scandir(MOBILITI_PACKAGE . "preconsultas/consultas");
        foreach ($files as $key => $v) {
            if ($key < 2)
                continue;
            unlink(MOBILITI_PACKAGE . "preconsultas/consultas/".$v);
        }
        header('location: ../admin');
    }
    
    function getIDsPorNome(){
        
        
        $es = "Rondônia
a|Acre
a|Amazonas
a|Roraima
a|Pará
a|Amapá
a|Tocantins
a|Maranhão
a|Piauí
a|Ceará
a|Rio Grande do Norte
a|Paraíba
a|Pernambuco
a|Alagoas
a|Sergipe
a|Bahia
a|Minas Gerais
a|Espírito Santo
a|Rio de Janeiro
a|São Paulo
a|Paraná
a|Santa Catarina
a|Rio Grande do Sul
a|Mato Grosso do Sul
a|Mato Grosso
a|Goiás
a|Distrito Federal
";header('Content-Type: text/html; charset=utf-8');
        $bd = new bd();
        $ex = explode('a|', $es);
        foreach($ex as $k=>$v){
            $v = trim($v);
            $sql = "select id from estado where nome ILIKE '$v'";
            $arr = $bd->ExecutarSQL($sql);
            echo $arr[0]['id']."<br />";
        }
        die();
        $bd = new bd();
        $ex = explode("a|",$var);
        foreach($ex as $v){
            $v = trim($v);
            $sql = "select id as id from variavel where sigla ILIKE '$v'";
            $arr = $bd->ExecutarSQL($sql);
            echo $arr[0]['id']."<br />";
        }
        
        
        return;
        $sql = "select id from municipio order by geocodmun6";
        $arr = $bd->ExecutarSQL($sql);
        foreach($arr as $v){
            echo $v['id']."<br />";
        }
        
        
        return;
        $ex = explode('a',$var);
        foreach($ex as $v){
            $v = pg_escape_string(trim($v));
            $sql = "select id from municipio where substring(geocodmun::text from 0 for 7) = '$v'";
            $arr = $bd->ExecutarSQL($sql);
            echo $arr[0]['id']."<br />";
        }
    }
    
    
    function rank($var=196,$ano = 3,$mun='estado'){
        
//        $var = 197;
//        $ano = 3;
        if($var == 196)
            $filed = "posicao_idh";
        if($var == 197)
            $filed = "posicao_idhr";
        if($var == 198)
            $filed = "posicao_idhl";
        if($var == 199)
            $filed = "posicao_idhe";
        if($mun == 'municipio'){
            $sql = "select e.nome, b.$filed as f,c.valor from rank as b
                INNER JOIN municipio as e on b.fk_municipio = e.id 
                INNER JOIN valor_variavel_mun as c on b.fk_municipio = c.fk_municipio 
                WHERE c.fk_ano_referencia = b.fk_ano_referencia and c.fk_variavel = $var and b.fk_ano_referencia = $ano
                ORDER BY valor desc";
        }elseif($mun=='estado' ||$mun==''){
            $sql = "select e.nome, b.$filed as f,c.valor from rank_estado as b
                INNER JOIN estado as e on b.fk_estado = e.id 
                INNER JOIN valor_variavel_estado as c on b.fk_estado = c.fk_estado 
                WHERE c.fk_ano_referencia = b.fk_ano_referencia and c.fk_variavel = $var and b.fk_ano_referencia = $ano
                ORDER BY valor desc";
        }else{
            die("terceiro parametro n'ao aceito.");
        }
        $bd = new bd();
        $arr = $bd->ExecutarSQL($sql);
        echo "<table class='table'>";
        $counter = 1;
        $counter_dif = 0;
        $last_v = 0;
        ?>
<thead>
<th>Nome</th>
<th>Posição da Planilha</th>
<th>Valor da variavel <?php echo $var ?></th>
<th>Contador Normal</th>
<th>Posição certa</th>
<th>Validação</th>
</thead>
<?
        foreach($arr as $k){
            extract($k);
            if($last_v != $valor){
                $counter_dif = $counter;
            }else{
//                $counter_dif = $counter;
            }
            echo "<tr>";
            echo "<td>$nome</td>";
            echo "<td>$f</td>";
            echo "<td>$valor</td>";
            echo "<td>$counter</td>";
            echo "<td>$counter_dif</td>";
            if($counter_dif != $f)
                echo "<td>2ERRO</td>";
            else
                echo "<td>OK</td>";
            echo "</tr>";
            $last_v = $valor;
            $counter ++;
        }
        echo "</table>";
        
        return;
        
//        
//        $es = "Rondônia
//a|Acre
//a|Amazonas
//a|Roraima
//a|Pará
//a|Amapá
//a|Tocantins
//a|Maranhão
//a|Piauí
//a|Ceará
//a|Rio Grande do Norte
//a|Paraíba
//a|Pernambuco
//a|Alagoas
//a|Sergipe
//a|Bahia
//a|Minas Gerais
//a|Espírito Santo
//a|Rio de Janeiro
//a|São Paulo
//a|Paraná
//a|Santa Catarina
//a|Rio Grande do Sul
//a|Mato Grosso do Sul
//a|Mato Grosso
//a|Goiás
//a|Distrito Federal
//";header('Content-Type: text/html; charset=utf-8');
//        $bd = new bd();
//        $ex = explode('a|', $es);
//        foreach($ex as $k=>$v){
//            $v = trim($v);
//            $sql = "select id from estado where nome ILIKE '$v'";
//            $arr = $bd->ExecutarSQL($sql);
//            echo $arr[0]['id']."<br />";
//        }
//        
//        
//        
//        die();
        
        $lines = file("config/$file");
        $insert = array();
        $bd = new bd();
        //$sql = "INSERT INTO rank(fk_municipio,posicao_idh,posicao_e_idh,posicao_idhe,posicao_e_idhe,posicao_idhr,posicao_e_idhr,posicao_idhl,posicao_e_idhl,fk_ano_referencia) VALUES ";
        $sql = "INSERT INTO rank_estado(fk_estado,posicao_idh,posicao_idhe,posicao_idhr,posicao_idhl,fk_ano_referencia) VALUES ";
        foreach($lines as $linha){
            $l = explode(';', $linha);
            foreach($l as $k=>$tttt){
                $l[$k] = str_replace('.', '', $l[$k]);
            }
            $insert[] = "(".implode(',',$l).",$ano)";
        }
        $sql .= implode(',', $insert);
//        $bd->insert($sql);
        die($sql);
    }
    function rewrite_config() {
        $lines = file("config/config_path.php");
        foreach ($lines as $key =>$linha) {
            if (strpos($linha, "=") && strpos($linha, ";") && strpos($linha, "$")) {
                $linha = str_replace(" ", "", $linha);
                $startsAt1 = strpos($linha, "$") + strlen("$");
                $endsAt1 = strpos($linha, "=", $startsAt1);
                $result1 = substr($linha, $startsAt1, $endsAt1 - $startsAt1);
                $newText = stripslashes($_POST[$result1]);
                $lines[$key] = replaceTags("=", ";", $newText, $lines[$key]);
            }
            if (strpos($linha, "define") && strpos($linha, ";")) {
                $linha = str_replace(" ", "", $linha);
                $startsAt1 = strpos($linha, "define(") + strlen("define(");
                $endsAt1 = strpos($linha, ",", $startsAt1);
                $result1 = substr($linha, $startsAt1, $endsAt1 - $startsAt1);

                $result1 = str_replace("'", "", $result1);
                $result1 = str_replace('"', "", $result1);
                
                $newText = stripslashes($_POST[$result1]);
                
                $part = explode(",",$lines[$key]);
                $result = $part[0].",".$newText.");";
                
                $lines[$key] = $result;
            }
        }
        $fp = fopen('config/config_path.php', 'w');
        foreach($lines as $linha){
            $linha = str_replace("\n", "", $linha);
            fwrite($fp, $linha."\n");
        }
        fclose($fp);
        header("location: ../admin");
    }
    function get_n($n){
        if($n == "" || !isset($n)){
            return 0;
        }
        return str_replace(',', ".", $n);
    }
    function regerar_rank() {
        $bd = new bd();
        
//        $var  = "ESPVIDA;FECTOT;MORT1;MORT5;RAZDEP;SOBRE40;SOBRE60;T_ENV;E_ANOSESTUDO;T_ANALF11A14;T_ANALF15A17;T_ANALF15M;T_ANALF18A24;T_ANALF18M;T_ANALF25A29;T_ANALF25M;T_ATRASO_0_BASICO;T_ATRASO_0_FUND;T_ATRASO_0_MED;T_ATRASO_1_BASICO;T_ATRASO_1_FUND;T_ATRASO_1_MED;T_ATRASO_2_BASICO;T_ATRASO_2_FUND;T_ATRASO_2_MED;T_FBBAS;T_FBFUND;T_FBMED;T_FBPRE;T_FBSUPER;T_FLBAS;T_FLFUND;T_FLMED;T_FLPRE;T_FLSUPER;T_FREQ0A3;T_FREQ11A14;T_FREQ15A17;T_FREQ18A24;T_FREQ25A29;T_FREQ4A5;T_FREQ4A6;T_FREQ5A6;T_FREQ6;T_FREQ6A14;T_FREQ6A17;T_FREQFUND1517;T_FREQFUND1824;T_FREQFUND45;T_FREQMED1824;T_FREQMED614;T_FREQSUPER1517;T_FUND11A13;T_FUND12A14;T_FUND15A17;T_FUND16A18;T_FUND18A24;T_FUND18M;T_FUND25M;T_MED18A20;T_MED18A24;T_MED18M;T_MED19A21;T_MED25M;T_SUPER25M;CORTE1;CORTE2;CORTE3;CORTE4;CORTE9;GINI;PIND;PINDCRI;PMPOB;PMPOBCRI;PPOB;PPOBCRI;PREN10RICOS;PREN20;PREN20RICOS;PREN40;PREN60;PREN80;PRENTRAB;R1040;R2040;RDPC;RDPC1;RDPC10;RDPC2;RDPC3;RDPC4;RDPC5;RDPCT;RIND;RMPOB;RPOB;THEIL;CPR;EMP;P_AGRO;P_COM;P_CONSTR;P_EXTR;P_FORMAL;P_FUND;P_MED;P_SERV;P_SIUP;P_SUPER;P_TRANSF;REN0;REN1;REN2;REN3;REN5;RENOCUP;T_ATIV;T_ATIV1014;T_ATIV1517;T_ATIV1824;T_ATIV18M;T_ATIV2529;T_DES;T_DES1014;T_DES1517;T_DES1824;T_DES18M;T_DES2529;THEILtrab;TRABCC;TRABPUB;TRABSC;T_AGUA;T_BANAGUA;T_DENS;T_LIXO;T_LUZ;AGUA_ESGOTO;PAREDE;T_CRIFUNDIN_TODOS;T_FORA4A5;T_FORA6A14;T_FUNDIN_TODOS;T_FUNDIN_TODOS_MMEIO;T_FUNDIN18MINF;T_M10A14CF;T_M15A17CF;T_MULCHEFEFIF014;T_NESTUDA_NTRAB_MMEIO;T_OCUPDESLOC_1;T_RMAXIDOSO;T_SLUZ;HOMEM0A4;HOMEM10A14;HOMEM15A19;HOMEM20A24;HOMEM25A29;HOMEM30A34;HOMEM35A39;HOMEM40A44;HOMEM45A49;HOMEM50A54;HOMEM55A59;HOMEM5A9;HOMEM60A64;HOMEM65A69;HOMEM70A74;HOMEM75A79;HOMEMTOT;HOMENS80;MULH0A4;MULH10A14;MULH15A19;MULH20A24;MULH25A29;MULH30A34;MULH35A39;MULH40A44;MULH45A49;MULH50A54;MULH55A59;MULH5A9;MULH60A64;MULH65A69;MULH70A74;MULH75A79;MULHER80;MULHERTOT;PEA;PEA1014;PEA1517;PEA18M;peso1;PESO1114;PESO1113;PESO1214;peso13;PESO15;peso1517;PESO1524;PESO1618;PESO18;Peso1820;PESO1824;Peso1921;PESO25;peso4;peso5;peso6;PESO610;Peso617;PESO65;PESOM1014;PESOM1517;PESOM15M;PESOM25M;pesoRUR;pesotot;pesourb;PIA;PIA1014;PIA1517;PIA18M;POP;POPT;I_ESCOLARIDADE;I_FREQ_PROP;IDHM;IDHM_E;IDHM_L;IDHM_R";
//        
//        
//        $ex = explode(';',$var);
//        
//        $result = array();
//        
//        foreach($ex as $v){
//            $v = trim($v);
//            $sql = "select id from variavel where sigla ILIKE '$v'";
//            $arr = $bd->ExecutarSQL($sql);
//            echo $arr[0]['id'].",$v<br />";
//        }
        
//        $result = array();
//        foreach($ex as $v){
//            $v = trim($v);
//            $sql = "select id from municipio where substring(geocodmun::text from 0 for 7) = '$v'";
//            $arr = $bd->ExecutarSQL($sql);
//            if(count($arr) > 1){
//                die("ERROOOOOOOOOO: $v");
//            }
//            if($c > 10) die();
//            echo $arr[0]['id']."<br />";
//            $result[] = $arr[0]["id"];
//            $c++;
//        }
        
        
        
//        $lines = file("config/rank_save.csv");
//        $insert = array();
//        $sql = "INSERT INTO rank(fk_municipio,posicao_idh,posicao_e_idh,posicao_idhe,posicao_e_idhe,posicao_idhr,posicao_e_idhr,posicao_idhl,posicao_e_idhl) VALUES ";
//        foreach($lines as $linha){
//            $insert[] = "(".implode(',',explode(';', $linha)).")";
//        }
//        $sql .= implode(',', $insert);
//        $bd->insert($sql);
//        die($sql);
        die();
        return;
        $bd = new bd();
        $ALL = array();
        $bd->insert("TRUNCATE TABLE rank");
        $SQL1 = "SELECT fk_municipio,valor,fk_variavel FROM valor_variavel_mun
                                  WHERE fk_variavel IN (196) and fk_ano_referencia = 3 ORDER BY valor_variavel_mun.valor desc";
        $SQLR = "SELECT fk_municipio,valor,fk_variavel FROM valor_variavel_mun
                                  WHERE fk_variavel IN (197) and fk_ano_referencia = 3 ORDER BY valor_variavel_mun.valor desc";
        $SQLL = "SELECT fk_municipio,valor,fk_variavel FROM valor_variavel_mun
                                  WHERE fk_variavel IN (198) and fk_ano_referencia = 3 ORDER BY valor_variavel_mun.valor desc";
        $SQLE = "SELECT fk_municipio,valor,fk_variavel FROM valor_variavel_mun
                                  WHERE fk_variavel IN (199) and fk_ano_referencia = 3 ORDER BY valor_variavel_mun.valor desc";
        $arr = $bd->ExecutarSQL($SQL1);
        $last = "";
        $posicao = 0;
        foreach ($arr as $key => $v) {
            $v["valor"] = cutNumber($v["valor"], 3, ',', '');
            if ($last != $v["valor"])
                $posicao++;
            $last = $v["valor"];
            $ALL[$v["fk_municipio"]][$v["fk_variavel"]] = $posicao;
        }
        $arr = $bd->ExecutarSQL($SQLR);
        $last = "";
        $posicao = 0;
        foreach ($arr as $key => $v) {
            $v["valor"] = cutNumber($v["valor"], 3, ',', '');
            if ($last != $v["valor"])
                $posicao++;
            $last = $v["valor"];
            $ALL[$v["fk_municipio"]][$v["fk_variavel"]] = $posicao;
        }
        $arr = $bd->ExecutarSQL($SQLL);
        $last = "";
        $posicao = 0;
        foreach ($arr as $key => $v) {
            $v["valor"] = cutNumber($v["valor"], 3, ',', '');
            if ($last != $v["valor"])
                $posicao++;
            $last = $v["valor"];
            $ALL[$v["fk_municipio"]][$v["fk_variavel"]] = $posicao;
        }
        $arr = $bd->ExecutarSQL($SQLE);
        $last = "";
        $posicao = 0;
        foreach ($arr as $key => $v) {
            $v["valor"] = cutNumber($v["valor"], 3, ',', '');
            if ($last != $v["valor"])
                $posicao++;
            $last = $v["valor"];
            $ALL[$v["fk_municipio"]][$v["fk_variavel"]] = $posicao;
        }


        for ($i = 1; $i <= 27; $i++) {
            $SQL = "SELECT fk_municipio,valor,fk_variavel FROM valor_variavel_mun
                        INNER JOIN municipio as m ON (fk_municipio = m.id)
                        WHERE fk_variavel IN (196) and fk_ano_referencia = 3 AND m.fk_estado = $i order by valor";
            $arr = $bd->ExecutarSQL($SQL);
            $last = "";
            $posicao = 0;
            foreach ($arr as $key => $v) {
                $v["valor"] = cutNumber($v["valor"], 3, ',', '');
                if ($last != $v["valor"])
                    $posicao++;
                $last = $v["valor"];
                $ALL[$v["fk_municipio"]][$v["fk_variavel"] . "e"] = $posicao;
            }

            $SQL = "SELECT fk_municipio,valor,fk_variavel FROM valor_variavel_mun
                        INNER JOIN municipio as m ON (fk_municipio = m.id)
                        WHERE fk_variavel IN (197) and fk_ano_referencia = 3 AND m.fk_estado = $i order by valor";
            $arr = $bd->ExecutarSQL($SQL);
            $last = "";
            $posicao = 0;
            foreach ($arr as $key => $v) {
                $v["valor"] = cutNumber($v["valor"], 3, ',', '');
                if ($last != $v["valor"])
                    $posicao++;
                $last = $v["valor"];
                $ALL[$v["fk_municipio"]][$v["fk_variavel"] . "e"] = $posicao;
            }

            $SQL = "SELECT fk_municipio,valor,fk_variavel FROM valor_variavel_mun
                        INNER JOIN municipio as m ON (fk_municipio = m.id)
                        WHERE fk_variavel IN (198) and fk_ano_referencia = 3 AND m.fk_estado = $i order by valor";
            $arr = $bd->ExecutarSQL($SQL);
            $last = "";
            $posicao = 0;
            foreach ($arr as $key => $v) {
                $v["valor"] = cutNumber($v["valor"], 3, ',', '');
                if ($last != $v["valor"])
                    $posicao++;
                $last = $v["valor"];
                $ALL[$v["fk_municipio"]][$v["fk_variavel"] . "e"] = $posicao;
            }

            $SQL = "SELECT fk_municipio,valor,fk_variavel FROM valor_variavel_mun
                        INNER JOIN municipio as m ON (fk_municipio = m.id)
                        WHERE fk_variavel IN (199) and fk_ano_referencia = 3 AND m.fk_estado = $i order by valor";
            $arr = $bd->ExecutarSQL($SQL);
            $last = "";
            $posicao = 0;
            foreach ($arr as $key => $v) {
                $v["valor"] = cutNumber($v["valor"], 3, ',', '');
                if ($last != $v["valor"])
                    $posicao++;
                $last = $v["valor"];
                $ALL[$v["fk_municipio"]][$v["fk_variavel"] . "e"] = $posicao;
            }
            echo $i . "<br />";
        }

        $SQL2 = array();
        foreach ($ALL as $key => $v) {
            $SQL2[] = "({$key},{$v[196]},{$v[197]},{$v[198]},{$v[199]},{$v["196e"]},{$v["197e"]},{$v["198e"]},{$v["199e"]})";
        }
        $s = join(",", $SQL2);
        $SQL = "INSERT INTO rank(fk_municipio,posicao_idh,posicao_idhr,posicao_idhl,posicao_idhe,posicao_e_idh,posicao_e_idhr,posicao_e_idhl,posicao_e_idhe) values $s";
        die($SQL);
        //$bd->insert($SQL);
        //header("location: ../admin");
    }

    function cutNumber($num, $casas_decimais, $decimal = ',', $milhar = '.') {
    //        $num = str_replace(".", $decimal, $num);
        $ex = explode('.', $num);
        if (strlen($ex[1]) >= $casas_decimais) {
            return substr($num, 0, strpos($num, '.') + $casas_decimais + 1);
        } else {
            $con = "";
            for ($diff = $casas_decimais - strlen($ex[1]); $diff > 0; $diff--) {
                $con .= "0";
            }
            return $num . $con;
        }
    }

    function replaceTags($startPoint, $endPoint, $newText, $source) {
        return preg_replace('#(' . preg_quote($startPoint) . ')(.*)(' . preg_quote($endPoint) . ')#si', '$1' . $newText . '$3', $source);
    }
    
    function replace_content_inside_delimiters($start, $end, $new, $source) {
        return preg_replace('#('.preg_quote($start).')(.*)('.preg_quote($end).')#si', '$1'.$new.'$3', $source);
    }
    
    
    function regerar_dados(){
        $lines = file("config/Export1.csv");
        $c = 0;
        $vars = array();
        $SQL = array();
        echo "INSERT INTO valor_variavel_mun(fk_ano_referencia, fk_municipio,fk_variavel,valor) VALUES ";
        foreach($lines as $linha){
            if($c == 0){
                $c++;
                $exp = explode(";",$linha);
                foreach($exp as $var){
                    $vars[] = $var;
                }
                continue;
            }
            $l = explode(";",$linha);
            $t = 0;
            foreach($l as $m){
                if($t == 0){
                    $t++;
                    continue;
                }
                $SQL[] = "(3,{$l[0]},$vars[$t],".get_n($m).")";
                $t++;
            }
            echo(implode(",",$SQL));
            echo ",";
            $SQL = array();
            $c++;
        }
    }

?>

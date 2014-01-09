<?php
    function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = object_to_array($value);
            }
            return $result;
        }
        return $data;
    }

    function download_send_headers($filename) {
        header('Content-Type: text/html; charset=utf-8');
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    download_send_headers("AtlasIDHM2013_DadosSelecionados.csv");
    if (isset($_POST["form2_lugares"])) {
        $json_lugares = utf8_decode($_POST["form2_lugares"]);
        echo $json_lugares;
    } elseif (isset($_POST["crossdata"])) {
        $arr = object_to_array(json_decode(urldecode($_POST["crossdata"])));
        require_once 'config/conexao.class.php';
        require_once MOBILITI_PACKAGE.'util/protect_sql_injection.php';
        require_once MOBILITI_PACKAGE."consulta/bd.class.php";
        require_once MOBILITI_PACKAGE."consulta/Consulta.class.php";
        require_once MOBILITI_PACKAGE."tabela/Tabela.class.php";
        $ObjConsulta = Consulta::tableParse($arr['vars']);
        foreach($ObjConsulta as $obj){
            $tab = new Tabela($obj, LIMITE_EXIBICAO_TABELA, false, true);
            $tab->DrawTabela();
        }
        if(is_array(Tabela::$JSONSaved)){
            foreach(Tabela::$JSONSaved as $key){
                foreach($key as $key2=>$val2){
                    $result[$key2] = $val2;
                }
            }
        }
        echo "sep = ,\n";
        foreach($result as $val){
            echo "Lugares,";
            foreach($arr["vars"]["indicadores"] as $val){
                if($val[1] == 1991)
                    echo utf8_decode(Tabela::$JSONSavedIndicadores[$val[0]]["nomecurto"])." (1991),";
                elseif($val[1] == 2000)
                    echo utf8_decode(Tabela::$JSONSavedIndicadores[$val[0]]["nomecurto"])." (2000),";
                else
                    echo utf8_decode(Tabela::$JSONSavedIndicadores[$val[0]]["nomecurto"])." (2010),";
            }
            echo "\n";
            break;
        }
        foreach($result as $val){
            if(isset($val["uf"]))
                echo utf8_decode($val["nome"])." ({$val["uf"]}),";
            else
                echo utf8_decode($val["nome"]).",";
                
            foreach($arr["vars"]["indicadores"] as $v){
                $id = "";
                if($v[1] == "1991")
                    $id = $v[0]."_1";
                elseif($v[1] == "2000")
                    $id = $v[0]."_2";
                else
                    $id = $v[0]."_3";
                
                echo $val["vs"][$id]["v"].",";
            }
            echo "\n";
        }
    }
?>
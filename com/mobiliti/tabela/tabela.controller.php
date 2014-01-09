<?php   
    require_once "../../../config/config_path.php";
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: {$path_dir}404");
    }
    ini_set( "display_errors", 0);
    ob_start("ob_gzhandler");
    require_once '../../../config/conexao.class.php';
    require_once MOBILITI_PACKAGE.'util/protect_sql_injection.php';
    require_once "../consulta/bd.class.php";
	require_once "../consulta/man_bd.class.php";
    require_once "../consulta/Consulta.class.php";
    require_once "Tabela.class.php";
     
    $result = array();
    $json_lugares = array();
    $json_lugares[] = array("e"=>"10","ids"=>"103");
    foreach($_POST['json_lugares'] as $key){
        $json_lugares[] = $key;
    }
    $json_indicadores = $_POST['json_indicadores'];
    $count_lugs = (int) $_POST['count_lugs'];
    $nome = PublicMethods::nameJson($json_lugares, $json_indicadores);
    $arrayConsulta = PublicMethods::TranslateTabela($json_lugares,$json_indicadores);
    $count_f = 0;
    $count_f += $count_lugs;
    $count_f += count($arrayConsulta["espacialidade"]["municipal"]["municipio"]);
    $count_f += count($arrayConsulta["espacialidade"]["estadual"]["estado"]);
    $count_f *= count($arrayConsulta["indicadores"]);
    if($count_f >= JS_LIMITE_TELA && $count_f < JS_LIMITE_DOWN){
        $res = array();
        $res["vars"] = $arrayConsulta;
        $res["download"] = true;
        echo json_encode($res);
        die();
    }elseif($count_f >= JS_LIMITE_DOWN){
        $result["erro"] = 99;
        $result["msg"] = "Atenção: sua consulta superou o limite de (112000) células na tabela.<br />
Acesse \"Download\" e baixe todos os dados do Atlas Brasil 2013.";
        $json = json_encode($result);
        echo $json;
        die();
    }
    if(file_exists('../preconsultas/consultas/'.$nome.".txt.gz")){
//        $SQL = "UPDATE cache_tabela SET
//                acessos = acessos+1,
//                ultimo_acesso = now() WHERE nome LIKE '$nome'
//                ";
//        $bd = new bd();
//        $bd->insert($SQL);
        $myFile = '../preconsultas/consultas/'.$nome.".txt.gz";
       
	   //metricas();
	   
        $fh = gzopen ($myFile, 'r');
        $theData = gzpassthru($fh);
        die();
    }
    if(isset($_POST['json_search_names']))
        $searchName = true;
    else{
        $searchName = false;
    }
    $varReturn = false;
    if(isset($_POST['dataBring']) && $_POST['dataBring'] == "var_only"){       
        $varReturn = true;
    }
    
    $ObjConsulta = Consulta::tableParse($arrayConsulta);
    $x = 1;
    foreach($ObjConsulta as $obj){
        $tab = new Tabela($obj, LIMITE_EXIBICAO_TABELA, $varReturn, $searchName);
        $tab->DrawTabela();
        $x++;
    }     
    if(!$varReturn){
        if(is_array(Tabela::$JSONSaved)){
            foreach(Tabela::$JSONSaved as $key){
                foreach($key as $key2=>$val2){
                    $result[$key2] = $val2;
                }
            }
        }
    }
    else{
        foreach(Tabela::$JSONSaved as $key){
            foreach($key as $key2=>$val2){
                $result[] = $val2;
            }
        }
    }
    $result['nomevariaveis'] = Tabela::$JSONSavedIndicadores;
	
    $json = json_encode($result);
    $file = '../preconsultas/consultas/'.$nome.".txt.gz";
    $handle = fopen($file, 'w'); //or die("erro");
    $stringData = gzencode($json,9);
    fwrite($handle, $stringData);
    fclose($handle);
    echo $json;

//    $SQL = "INSERT into cache_tabela(nome,data_criacao,ultimo_acesso) values ('{$nome}',now(),now())";
//    $bd = new bd();
//    $bd->insert($SQL);
	
	//metricas();
	
	function metricas(){
	
		$mbd = new mbd();
		$json_lugares = $_POST['json_lugares'];
		$json_indicadores = $_POST['json_indicadores'];
		$lugares = "";
		$navegador = getNav();

		if(sizeof($json_lugares)==1){
		$SQL = "INSERT INTO metricas(espacialidade_mun, indicadores, data_consulta, navegador, versao_navegador, os, ip) ".
		"VALUES ('".$json_lugares[0]["ids"]."','$json_indicadores',now(),'".$navegador[0]."','".$navegador[1]."','".getOs()."','".$_SERVER['REMOTE_ADDR']."')";
		}
		
		if(sizeof($json_lugares)==3){
		$SQL = "INSERT INTO metricas(espacialidade_mun, espacialidade_est, espacialidade_area_tematica, indicadores, data_consulta, navegador, versao_navegador, os, ip) ".
		"VALUES ('".$json_lugares[0]["ids"]."','".$json_lugares[1]["ids"]."','".$json_lugares[2]["ids"]."','$json_indicadores',now(),'".$navegador[0]."','".$navegador[1]."','".getOs()."','".$_SERVER['REMOTE_ADDR']."')";
		}

		$mbd->insert($SQL);
		
	}
	
	function getNav(){
		/*$agent = $_SERVER['HTTP_USER_AGENT'];
		if ( strstr($agent, "Opera") ) return "Opera";
		else if ( strstr($agent, "Firefox") ) return "Mozilla Firefox";
		else if ( strstr($agent, "Chrome") ) return "Google Chrome";
		else if ( strstr($agent, "Safari") ) return "Safari";
		else if ( strstr($agent, "MSIE") ) return "Internet Explorer";*/
		
		$var = $_SERVER['HTTP_USER_AGENT'];
        $info['browser'] = "OTHER";
        
        $browsers['Chrome']             = 'Chrome';
		$browsers['Firebird']           = 'Firebird';
		$browsers['Firefox']            = 'Firefox';
		$browsers['Internet Explorer']  = 'Internet Explorer';
		$browsers['Konqueror']          = 'Konqueror';
		$browsers['Lynx']               = 'Lynx';
		$browsers['mobilexplorer']      = 'Mobile Explorer'; // M�vel
		$browsers['Mobile Safari']      = 'Mobile Safari'; // M�vel
		$browsers['MSIE']               = 'Internet Explorer';
		$browsers['Netscape']           = 'Netscape';
		$browsers['OmniWeb']            = 'OmniWeb';
		$browsers['Opera']              = 'Opera';
		$browsers['operamini']          = 'Opera Mini'; // M�vel
		$browsers['opera mini']         = 'Opera Mini'; // M�vel
		$browsers['Phoenix']            = 'Phoenix';
		$browsers['Safari']             = 'Safari';
		$nav[] = '';
		if (is_array($browsers)) {
			foreach ($browsers as $ua => $browser) {
				if (preg_match("|".preg_quote($ua).".*?([0-9\.]+)|i", trim($_SERVER['HTTP_USER_AGENT']), $versao)) {
					//return $browser.' '.$versao[1];
					$nav[0] = $browser;
					$nav[1] = $versao[1];
					//break;
					return $nav;
				}
			}
		}
		//return 'Browser Desconhecido';
		$nav[0] = 'Browser Desconhecido';
		$nav[1] = '';
		return $nav;
	}
	
	function getOs(){
		/**
		* Windows...
		*/
		$sistemas_operativos['win95']              = 'Windows 95';
		$sistemas_operativos['windows 95']         = 'Windows 95';
		$sistemas_operativos['win98']              = 'Windows 98';
		$sistemas_operativos['windows 98']         = 'Windows 98';
		$sistemas_operativos['winnt']              = 'Windows NT';
		$sistemas_operativos['winnt4.0']           = 'Windows NT 4.0';
		$sistemas_operativos['windows nt 4.0']     = 'Windows NT 4.0';
		$sistemas_operativos['win 9x 4.90']        = 'Windows Me';
		$sistemas_operativos['windows me']         = 'Windows Me';
		$sistemas_operativos['windows nt 5.0']     = 'Windows 2000';
		$sistemas_operativos['windows nt 5.1']     = 'Windows XP';
		$sistemas_operativos['windows nt 5.2']     = 'Windows 2003';
		$sistemas_operativos['windows nt 6.0']     = 'Windows Vista';
		$sistemas_operativos['windows nt 6.1']     = 'Windows 7';
		$sistemas_operativos['windows nt 6.2']     = 'Windows 8';
		/**
		* Linux...
		*/
		$sistemas_operativos['linux']              = 'Linux';
		$sistemas_operativos['linux i686']         = 'Linux i686';
		$sistemas_operativos['linux i586']         = 'Linux i586';
		$sistemas_operativos['linux i486']         = 'Linux i486';
		$sistemas_operativos['linux i386']         = 'Linux i386';
		$sistemas_operativos['linux ppc']          = 'Linux PPC';
		/**
		* Unix...
		*/
		$sistemas_operativos['unix']               = 'Unix';
		/**
		* Mac...
		*/
		$sistemas_operativos['mac']               = 'Mac';
		$sistemas_operativos['macintosh']         = 'Macintosh';
		$sistemas_operativos['Mac OS X']          = 'Mac OS X';
		$sistemas_operativos['Mac 10']            = 'Mac OS X';
		$sistemas_operativos['Mac OS X 10_4']     = 'Mac OS X Tiger';
		$sistemas_operativos['Mac OS X 10_5']     = 'Mac OS X Leopard';
		$sistemas_operativos['Mac OS X 10_5_2']   = 'Mac OS X Leopard';
		$sistemas_operativos['Mac OS X 10_5_3']   = 'Mac OS X Leopard';
		$sistemas_operativos['PowerPC']           = 'Mac PPC';
		$sistemas_operativos['PPC']               = 'Mac PPC';
		/**
		* So M�veis...
		*/
		$sistemas_operativos['Android']           = 'Android';
		$sistemas_operativos['iphone']            = 'iPhone';
		$sistemas_operativos['elaine']            = 'Palm';
		$sistemas_operativos['palm']              = 'Palm';
		$sistemas_operativos['series60']          = 'Symbian S60';
		$sistemas_operativos['symbian']           = 'Symbian';
		$sistemas_operativos['SymbianOS']         = 'Symbian OS';
		$sistemas_operativos['windows ce']        = 'Windows CE';
		/**
		* BSD...
		*/
		$sistemas_operativos['freebsd']           = 'Free BSD';
		$sistemas_operativos['openbsd']           = 'Open BSD';
		$sistemas_operativos['netbsd']            = 'Net BSD';
		$sistemas_operativos['dragonflybsd']      = 'DragonFly BSD';
		/**
		* Solaris...
		*/
		$sistemas_operativos['solaris']           = 'Solaris';
		
		if (is_array($sistemas_operativos)) {
			foreach ($sistemas_operativos as $ua => $sistemas_operativo) {
				if (preg_match("|".preg_quote($ua)."|i", trim($_SERVER['HTTP_USER_AGENT']))) {
					return $sistemas_operativo;
				}
			}
		}
		return 'Sistema Operacional Desconhecido';
	}
?>

<?php
require_once "config/config_path.php";
require_once "config/config_gerais.php";
require_once MOBILITI_PACKAGE."/consulta/bd.class.php";
include_once './config/conexao.class.php';

$bd = new bd();
ob_start();
include_once 'controller.php';

$title = "Consulta";
$content = ob_get_contents();
ob_end_clean();
include "web/base.php";
?> 
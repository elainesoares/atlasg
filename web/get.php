<?php
    $url_completa = $_SERVER['REQUEST_URI'];
    $url_completa = str_replace("espacialidade", "esp",$url_completa);
    $url_completa = str_replace("regiao", "reg",$url_completa);
    $url_completa = str_replace("estado", "uf",$url_completa);
    $url_completa = str_replace("municipio", "mun",$url_completa);
    $url_completa = str_replace("microregiao", "mic",$url_completa);
    $url_completa = str_replace("udh", "udh",$url_completa);
    $url_completa = str_replace("filtro", "fil",$url_completa);
    $url_completa = str_replace("indicadores", "ind",$url_completa);

    $gets = explode("/",$url_completa);
    var_dump($gets);
    $tipoApresentacao = $gets[2];
?>
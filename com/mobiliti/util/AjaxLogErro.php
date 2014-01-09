<?php
    var_dump($_POST);
    $file = "log.txt";
    $handle = fopen($file, 'a') or die("erro");
    $stringData = "{$_POST["file"]},{$_POST["linha_inicial"]},{$_POST["linha_final"]},{$_POST["e"]},{$_POST["navegador"]},".date(" d/m/Y G:i:s")."\r\n";
    echo $stringData;
    fwrite($handle, $stringData,strlen($stringData));
    fclose($handle);
?>

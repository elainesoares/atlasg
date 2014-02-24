<?php
/**
 * Esse arquivo verifica as configurações básicas necessárias para o 
 * funcionamento correto do sistema Atlas 2013.
 * @author Andre Castro
 * @version 1.0
 */

include './config/conexao.class.php';

echo "<h1>Página de Testes - Atlas Brasil 2013</h1><hr><br>";

$clsconexao = new Conexao();
$clsconexao->open();
$get = explode("/",$_SERVER["REQUEST_URI"]);

echo "<b><u>Informações:</u></b><br><br>";

echo "- Navegador reconhecido:<br>" 
      .$_SERVER['HTTP_USER_AGENT']."<br><br>";

//echo "- Dominio configurado (\config\config_path.php):<br>"
//        .$dominio . "<br><br>";
//
//echo "- Base configurada (\config\config_path.php):<br>"
//        .$base . "<br><br>";
//
//echo "- Url atual (dominio/base): <br>"
//       .$_SERVER['SERVER_NAME']."/".$get[1]."<br><br>";

echo "- Caminho do projeto atual:<br>" 
      .BASE_ROOT . "<br>
          <font color='blue'>
          Este caminho precisa ser válido (nome da pasta)</font><br>";

//$filename = 'teste.txt';
if (is_writable(BASE_ROOT)) {
    echo "<font color='green'>*Possui permissão de escrita</font><br>";
} else {
    echo "<font color='red'>*Não possui permissão de escrita</font><br>";
}

echo "<br>A pasta (". BASE_ROOT . "tmp/) necessita de permissão de escrita.<br>";
if (is_writable(BASE_ROOT . "tmp/")) {
    echo "<font color='green'>*Possui permissão de escrita</font><br>";
} else {
    echo "<font color='red'>*Não possui permissão de escrita</font><br>";
}

echo "<br><b><u>Verificação automática:</u></b><br>";

$statusDominioUrl = 0;
$statusNomeUrl = 0;
$statusBD = 0;

$dominioUrl = "http://" . $_SERVER['SERVER_NAME'];
if ($dominio != $dominioUrl){
    echo "<br><font color='red'><b>- Configuração de URL (dominio) - ERRO:<br>
        Detalhe:</b> a variável dominio do arquivo (\config\config_path.php) é <b>$dominio</b><br> e
        o dominio atual na URL é <b>http://" . $_SERVER['SERVER_NAME'] . "</b> - NÃO CORRESPONDEM</font><br>";
    $statusDominioUrl = 0;
}else{
    echo "<br><font color='green'><b>- Configuração de URL (dominio) - OK</b>:
        Domínio na URL Atual: [http://" . $_SERVER['SERVER_NAME'] . "]</font><br>";
    $statusDominioUrl = 1;
}

if ($base != $get[1]){
    echo "<br><font color='red'><b>- Configuração de URL (nome projeto) - ERRO:<br>
        Detalhe:</b> a variável base do arquivo (\config\config_path.php) é <b>$base</b><br> e
        o nome atual na URL é <b>$get[1]</b> - NÃO CORRESPONDEM</font><br>";
    $statusNomeUrl = 0;
}else{
    echo "<br><font color='green'><b>- Configuração de URL (nome projeto) - OK</b>:
        Nome na URL Atual: [/" . $get[1] . "/]</font><br>";
    $statusNomeUrl = 1;
}

$statusBD2 = $clsconexao->statusCon($statusBD);
$clsconexao->close();

$site = $dominio ."/". $base . "/";
if ($statusDominioUrl == 1 && $statusNomeUrl == 1 && $statusBD2 == 1)
    echo "<br><br><h3><font color='blue'><b>Atlas configurado - </b><a href='$site'>[TENTE NOVAMENTE]</font></h3></a>"

?>
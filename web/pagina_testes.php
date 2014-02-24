<?php ob_start(); 

/**
 * Esse arquivo verifica as configurações básicas necessárias para o 
 * funcionamento correto do sistema Atlas 2013.
 * @author Andre Castro
 * @version 1.0
 */

include './config/conexao.class.php';
?>
<div class="contentPaginaNaoEncontrada">
       <!--<div class="containerPaginaNaoEncontrada">-->
           <div class="areaImagemErro">
                <img src="./img/icons/error_interrogacao.png"/>
           </div>
           <div class="p_PaginaTestes">
               <p class="p1_ptestes">Página de Testes - Atlas Brasil 2013</p>
               <p class="p2_ptestes">Esta página tem como objetivo auxiliar na configuração correta do sistema</p>
               <p class="p_ptestes"><u>Informações:</u></p>
               <!--<p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Dados de acesso ao banco não são válidos.</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Usuário de acesso ao banco não tem permissão.</p>
               <p class="p_erromotivo"><img src="./img/icons/arrow_right.png" style="width: 20px; height: 20px;"/>Servidor de banco de dados indisponível.</p>-->
<?php 

$clsconexao = new Conexao();
$clsconexao->open();
$get = explode("/",$_SERVER["REQUEST_URI"]);

//echo "<b><u>Informações:</u></b><br><br>";

echo "<p class='p_erromotivo'><img src='./img/icons/arrow_right.png'
    style='width: 20px; height: 20px;'/>Navegador reconhecido:<br>" 
      .$_SERVER['HTTP_USER_AGENT']."</p>";

//echo "- Dominio configurado (\config\config_path.php):<br>"
//        .$dominio . "<br><br>";
//
//echo "- Base configurada (\config\config_path.php):<br>"
//        .$base . "<br><br>";
//
//echo "- Url atual (dominio/base): <br>"
//       .$_SERVER['SERVER_NAME']."/".$get[1]."<br><br>";

echo "<p class='p_erromotivo'><img src='./img/icons/arrow_right.png'
    style='width: 20px; height: 20px;'/>
    Caminho do projeto atual:" 
      .BASE_ROOT . "<br>
          <font color='blue'>
          Este caminho precisa ser válido (nome da pasta)</font><br>";

//$filename = 'teste.txt';
if (is_writable(BASE_ROOT)) {
    echo "<font color='green'>*Possui permissão de escrita</font>";
} else {
    echo "<font color='red'>*Não possui permissão de escrita</font>";
}

echo "<p class='p_erromotivo'><img src='./img/icons/arrow_right.png'
    style='width: 20px; height: 20px;'/>
    A pasta (". BASE_ROOT . "tmp/) necessita de permissão de escrita.<br>";

if (is_writable(BASE_ROOT . "tmp/")) {
    echo "<font color='green'>*Possui permissão de escrita</font></p>";
} else {
    echo "<font color='red'>*Não possui permissão de escrita</font></p>";
}

echo "<br><p class='p_ptestes'><u>Verificação automática:</u></p><br>";
//echo "<br><b><u>Verificação automática:</u></b><br>";

$statusDominioUrl = 0;
$statusNomeUrl = 0;
$statusBD = 0;

$dominioUrl = "http://" . $_SERVER['SERVER_NAME'];
if ($dominio != $dominioUrl){
    echo "<font color='red'><b><p class='p_erromotivo'><img src='./img/icons/arrow_right.png'
    style='width: 20px; height: 20px;'/>
    Configuração de URL (dominio) - ERRO:<br>
        Detalhe:</b> a variável dominio do arquivo (\config\config_path.php) é <b>$dominio</b><br> e
        o dominio atual na URL é <b>http://" . $_SERVER['SERVER_NAME'] . "</b> - NÃO CORRESPONDEM</font></p><br>";
    $statusDominioUrl = 0;
}else{
    echo "<font color='green'><b><p class='p_erromotivo'><img src='./img/icons/arrow_right.png'
    style='width: 20px; height: 20px;'/>
    Configuração de URL (dominio) - OK:</b>
        Domínio na URL Atual: [http://" . $_SERVER['SERVER_NAME'] . "]</font></p>";
    $statusDominioUrl = 1;
}

if ($base != $get[1]){
    echo "<font color='red'><b><p class='p_erromotivo'><img src='./img/icons/arrow_right.png'
    style='width: 20px; height: 20px;'/>
    Configuração de URL (nome projeto) - ERRO:<br>
        Detalhe:</b> a variável base do arquivo (\config\config_path.php) é <b>$base</b><br> e
        o nome atual na URL é <b>$get[1]</b> - NÃO CORRESPONDEM</font></p>";
    $statusNomeUrl = 0;
}else{
    echo "<font color='green'><b><p class='p_erromotivo'><img src='./img/icons/arrow_right.png'
    style='width: 20px; height: 20px;'/>
    Configuração de URL (nome projeto) - OK:</b>
        Nome na URL Atual: [/" . $get[1] . "/]</font></p>";
    $statusNomeUrl = 1;
}

$statusBD2 = $clsconexao->statusCon($statusBD);
$clsconexao->close();

$site = $dominio ."/". $base . "/";
if ($statusDominioUrl == 1 && $statusNomeUrl == 1 && $statusBD2 == 1)
    echo "<br><br>
                <p class='p1_ptestes'>Atlas Brasil 2013 Configurado 
                <a href='$site'>[Acesse Aqui]</font>
                       </a></p>";

echo "</div><div class='clear'></div></div>";

    $title = 'Página de Testes - Atlas Brasil 2013';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
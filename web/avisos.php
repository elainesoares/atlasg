<?php
    ob_start();
?>
<div class="contentPages" style="min-height: 500px;">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage">Notas</div>
            </div>			
        </div>
		As seguintes variáveis do Atlas Brasil 2013 estão com dados preliminares para determinados anos:<br/><br/>
		<table border="0" CELLSPACING="1" CELLPADDING="8">
	<tr align="rigth" bgcolor="#CFCFCF">
		<td ><b>SIGLA</b></td>
		<td><b>NOME CURTO</b></td>
		<td><b>ANO</b></td>
	</tr>
	<tr>
		<td>T_ANALF11A14</td>
		<td>Taxa de analfabetismo - 11 a 14 anos</td>
		<td>1991 e 2000</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>T_ANALF15A17</td>
		<td>Taxa de analfabetismo - 15 a 17 anos</td>
		<td>1991 e 2000</td>
	</tr>
		<tr>
		<td>T_ANALF15M</td>
		<td>Taxa de analfabetismo - 15 anos ou mais</td>
		<td>1991 e 2000</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>T_ANALF18A24</td>
		<td>Taxa de analfabetismo - 18 a 24 anos</td>
		<td>1991 e 2000</td>
	</tr>
	<tr>
		<td>T_ANALF18M</td>
		<td>Taxa de analfabetismo - 18 anos ou mais</td>
		<td>1991 e 2000</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>T_ANALF25A29</td>
		<td>Taxa de analfabetismo - 25 a 29 anos</td>
		<td>1991 e 2000</td>
	</tr>
	<tr>
		<td>T_ANALF25M</td>
		<td>Taxa de analfabetismo - 25 anos ou mais</td>
		<td>1991 e 2000</td>
	</tr>
	 <tr bgcolor="#E8E8E8">
		<td>T_AGUA</td>
		<td>% da população em domicílios com água encanada</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>RENOCUP</td>
		<td>Rendimento médio dos ocupados - 18 anos ou mais</td>
		<td>2000</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>T_CRIFUNDIN_TODOS</td>
		<td>% de crianças em domicílios em que ninguém tem fundamental completo</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO1114</td>
		<td>População de 11 a 14 anos</td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO1113</td>
		<td>População de 11 a 13 anos</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO1214</td>
		<td>População de 12 a 14 anos</td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO13</td>
		<td>População de 1 a 3 anos</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO1517</td>
		<td>População de 15 a 17 anos</td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO1524</td>
		<td>População de 15 a 24 anos</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO1618</td>
		<td>População de 16 a 18 anos</td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO18</td>
		<td>População de 18 anos ou mais</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>Peso1820</td>
		<td>População de 18 a 20 anos</td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>Peso1824</td>
		<td>População de 18 a 24 anos</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>Peso1921</td>
		<td>População de 19 a 21 anos</td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO4</td>
		<td>População de 4 anos</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO5</td>
		<td>População de 5 anos</td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO6</td>
		<td>População de 6 anos</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO610</td>
		<td>População de 6 a 10 anos</td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>Peso617</td>
		<td>População de 6 a 17 anos</td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESOM1517</td>
		<td>Mulheres de 15 a 17 anos</td>
		<td>1991</td>
	</tr>
</table><br>

    </div>
    
</div>

<?php
    $title = "Notas";
    $meta_title = 'Notas do Atlas de Desenvolvimento Humano 2013';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>


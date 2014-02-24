<?php
    ob_start();
?>
<div class="contentPages" style="min-height: 500px;">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage" id="notas_title"></div>
            </div>			
        </div>
                <span id="notas_texto"></span><br/><br/>
		<table border="0" CELLSPACING="1" CELLPADDING="8">
	<tr align="rigth" bgcolor="#CFCFCF">
		<td id="notas_sigla"><b></b></td>
		<td id="notas_nomecurto"><b></b></td>
		<td id="notas_ano"><b></b></td>
	</tr>
	<tr>
		<td>T_ANALF11A14</td>
		<td id="notas_analf11a14"></td>
		<td id="notas_199120001"></td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>T_ANALF15A17</td>
		<td id="notas_analf15a17"></td>
		<td id="notas_199120002"></td>
	</tr>
		<tr>
		<td>T_ANALF15M</td>
		<td id="notas_analf15m"></td>
		<td id="notas_199120003"></td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>T_ANALF18A24</td>
		<td id="notas_analf18a24"></td>
		<td id="notas_199120004"></td>
	</tr>
	<tr>
		<td>T_ANALF18M</td>
		<td id="notas_analf18m"></td>
		<td id="notas_199120005"></td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>T_ANALF25A29</td>
		<td id="notas_analf25a29"></td>
		<td id="notas_199120006"></td>
	</tr>
	<tr>
		<td>T_ANALF25M</td>
		<td id="notas_analf25m"></td>
		<td id="notas_199120007"></td>
	</tr>
	 <tr bgcolor="#E8E8E8">
		<td>T_AGUA</td>
		<td id="notas_agua"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>RENOCUP</td>
		<td id="notas_renocup"></td>
		<td>2000</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>T_CRIFUNDIN_TODOS</td>
		<td id="notas_crifundinTodos"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO1114</td>
		<td id="notas_peso1114"></td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO1113</td>
		<td id="notas_peso1113"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO1214</td>
		<td id="notas_peso1214"></td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO13</td>
		<td id="notas_peso13"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO1517</td>
		<td id="notas_peso1517"></td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO1524</td>
		<td id="notas_peso1524"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO1618</td>
		<td id="notas_peso1618"></td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO18</td>
		<td id="notas_peso18"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>Peso1820</td>
		<td id="notas_peso1820"></td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>Peso1824</td>
		<td id="notas_peso1824"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>Peso1921</td>
		<td id="notas_peso1921"></td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO4</td>
		<td id="notas_peso4"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO5</td>
		<td id="notas_peso5"></td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>PESO6</td>
		<td id="notas_peso6"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESO610</td>
		<td id="notas_peso610"></td>
		<td>1991</td>
	</tr>
	<tr bgcolor="#E8E8E8">
		<td>Peso617</td>
		<td id="notas_peso617"></td>
		<td>1991</td>
	</tr>
	<tr>
		<td>PESOM1517</td>
		<td id="notas_pesom1517"></td>
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

<script>
    $(document).ready(function(){        
        $("#notas_title").html(lang_mng.getString("notas_title"));
        $("#notas_texto").html(lang_mng.getString("notas_texto"));
        $("#notas_sigla").html(lang_mng.getString("notas_sigla"));
        $("#notas_nomecurto").html(lang_mng.getString("notas_nomecurto"));
        $("#notas_ano").html(lang_mng.getString("notas_ano"));
        $("#notas_analf11a14").html(lang_mng.getString("notas_analf11a14"));
        $("#notas_analf15a17").html(lang_mng.getString("notas_analf15a17"));
        $("#notas_analf15m").html(lang_mng.getString("notas_analf15m"));
        $("#notas_analf18a24").html(lang_mng.getString("notas_analf18a24"));
        $("#notas_analf18m").html(lang_mng.getString("notas_analf18m"));
        $("#notas_analf25a29").html(lang_mng.getString("notas_analf25a29"));
        $("#notas_analf25m").html(lang_mng.getString("notas_analf25m"));
        $("#notas_agua").html(lang_mng.getString("notas_agua"));
        $("#notas_renocup").html(lang_mng.getString("notas_renocup"));
        $("#notas_crifundinTodos").html(lang_mng.getString("notas_crifundinTodos"));
        $("#notas_peso1114").html(lang_mng.getString("notas_peso1114"));
        $("#notas_peso1113").html(lang_mng.getString("notas_peso1113"));
        $("#notas_peso1214").html(lang_mng.getString("notas_peso1214"));
        $("#notas_peso13").html(lang_mng.getString("notas_peso13"));
        $("#notas_peso1517").html(lang_mng.getString("notas_peso1517"));
        $("#notas_peso1524").html(lang_mng.getString("notas_peso1524"));
        $("#notas_peso1618").html(lang_mng.getString("notas_peso1618"));
        $("#notas_peso18").html(lang_mng.getString("notas_peso18"));
        $("#notas_peso1820").html(lang_mng.getString("notas_peso1820"));
        $("#notas_peso1824").html(lang_mng.getString("notas_peso1824"));
        $("#notas_peso1921").html(lang_mng.getString("notas_peso1921"));
        $("#notas_peso4").html(lang_mng.getString("notas_peso4"));
        $("#notas_peso5").html(lang_mng.getString("notas_peso5"));
        $("#notas_peso6").html(lang_mng.getString("notas_peso6"));
        $("#notas_peso610").html(lang_mng.getString("notas_peso610"));
        $("#notas_peso617").html(lang_mng.getString("notas_peso617"));
        $("#notas_pesom1517").html(lang_mng.getString("notas_pesom1517"));
        $("#notas_199120001").html(lang_mng.getString("notas_199120001"));
        $("#notas_199120002").html(lang_mng.getString("notas_199120002"));
        $("#notas_199120003").html(lang_mng.getString("notas_199120003"));
        $("#notas_199120004").html(lang_mng.getString("notas_199120004"));
        $("#notas_199120005").html(lang_mng.getString("notas_199120005"));
        $("#notas_199120006").html(lang_mng.getString("notas_199120006"));
        $("#notas_199120007").html(lang_mng.getString("notas_199120007"));
    });
</script>
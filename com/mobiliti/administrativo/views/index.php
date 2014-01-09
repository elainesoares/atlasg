
<style>
    .tb_admin td{
        vertical-align: top;
    }
</style>
<div id="content">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage">Painel Administrativo</div>
            </div>
        </div>
    </div>
    <div class="linhaDivisoria"></div>
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#consulta">Cache Consulta</a></li>
        <li><a href="#mng-quintil">Gerênciar Quintil</a></li>
        <li><a href="#ranking">Ranking</a></li>
        <li><a href="#configgeral">Configurações Geral</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="consulta">
           <?php include MOBILITI_PACKAGE.'administrativo/views/includes/cache.php'; ?>
        </div>
        <div class="tab-pane" id="mng-quintil"> <?php include MOBILITI_PACKAGE."administrativo/quintil/main.quintil.php"; ?> </div>
        <div class="tab-pane" id="ranking">
            <button class="btn" onclick="regerarrank();">Regerar Rank</button>
            <br /><br /><br />
        </div>
        <div class="tab-pane" id="configgeral">
            <form action='admin/rewrite_config' method="post">
                <table class='tb_admin'>
                    <tr>
                        <td>Tipo</td><td>Nome</td><td>Valor</td><td>Descrição</td>
                    </tr>
                <?php 
                    $lines = file("config/config_path.php");
                    foreach($lines as $linha){
                        if(strpos($linha, "=") && strpos($linha, ";") && strpos($linha, "$")){
                            $desc = "";
                            if(strpos($linha,"#descricao:")){
                                $t = explode("#descricao:", $linha);
                                $desc = $t[1];
                            }
                            $linha = str_replace(" ", "", $linha);
                            $startsAt1 = strpos($linha, "$") + strlen("$");
                            $endsAt1 = strpos($linha, "=", $startsAt1);
                            $result1 = substr($linha, $startsAt1, $endsAt1 - $startsAt1);

                            $startsAt2 = strpos($linha, "=") + strlen("=");
                            $endsAt2 = strpos($linha, ";", $startsAt2);
                            $result2 = substr($linha, $startsAt2, $endsAt2 - $startsAt2);

                            $result2 = str_replace("'", '"', $result2);
                            
                            echo "<tr><td>Variável &nbsp;&nbsp;&nbsp;</td><td>";
                            echo $result1;
                            echo "</td><td><input type='text' name='$result1'  value='$result2' /></td><td>$desc</td></tr>";
                        }
                        if(strpos($linha, "define") && strpos($linha, ";")){
                            $desc = "";
                            if(strpos($linha,"#descricao:")){
                                $t = explode("#descricao:", $linha);
                                $desc = $t[1];
                            }
                            $linha = str_replace(" ", "", $linha);
                            $startsAt1 = strpos($linha, "define(") + strlen("define(");
                            $endsAt1 = strpos($linha, ",", $startsAt1);
                            $result1 = substr($linha, $startsAt1, $endsAt1 - $startsAt1);

                            $result1 = str_replace("'", "", $result1);
                            $result1 = str_replace('"', "", $result1);

                            $startsAt2 = strpos($linha, ",") + strlen(",");
                            $endsAt2 = strpos($linha, ");", $startsAt2);
                            $result2 = substr($linha, $startsAt2, $endsAt2 - $startsAt2);

                            $result2 = str_replace("'", '"', $result2);

                            echo "<tr><td>Constant &nbsp;&nbsp;&nbsp;</td><td>";
                            echo $result1;
                            echo "</td><td><input type='text' name='$result1'  value='$result2' /></td><td>$desc</td></tr>";
                        }
                    }
//                    $fp = fopen('config/config_path.php', 'w');
//                    foreach($lines as $linha){
//                        fwrite($fp, $linha."\n");
//                    }
//                    fclose($fp);
                ?>
                </table>

                <input class="btn btn-success" type="submit" value='Salvar' />
            </form>
            <br /><br /><br />
        </div>
    </div>

    <script>
        $(function () {
            $('#myTab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            })
        })
        
        function regerarrank(){
            loadingHolder.show("Recalculando...");
            location.href="admin/regerar_rank";
        }
    </script>
</div>
    <script src="com/mobiliti/grafico/linhas/grafico-linhas.builder.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        var dataGraficoLinhas = [["Ano","25e","10e","18e","21e","7e","9e","3e","17e","16e","6e","2e","12e","22e","13e","14e","20e","15e","11e","27e","8e","24e","26e","4e","1e","19e","5e","23e"],["1991",0.588154,0.55876,0.621813,0.578361,0.594315,0.633375,0.624142,0.661056,0.594258,0.566024,0.592466,0.624787,0.625769,0.601718,0.578531,0.59711,0.639802,0.646755,0.651644,0.574248,0.628411,0.616914,0.601268,0.572195,0.611159,0.566701,0.623657],["2000",0.588154,0.55876,0.621813,0.578361,0.594315,0.633375,0.624142,0.661056,0.594258,0.566024,0.592466,0.624787,0.625769,0.601718,0.578531,0.59711,0.639802,0.646755,0.651644,0.574248,0.628411,0.616914,0.601268,0.572195,0.611159,0.566701,0.623657],["2010",0.588154,0.55876,0.621813,0.578361,0.594315,0.633375,0.624142,0.661056,0.594258,0.566024,0.592466,0.624787,0.625769,0.601718,0.578531,0.59711,0.639802,0.646755,0.651644,0.574248,0.628411,0.616914,0.601268,0.572195,0.611159,0.566701,0.623657]] ;
        google.load('visualization', '1', {'packages':['corechart']});
        //google.setOnLoadCallback(drawChartBolha);

        function drawChartLinha(j) {
            var data = google.visualization.arrayToDataTable(j);
            //A Escala feita pelos designers tinha cores bem diferentes. 
            //Teríamos que encontrar uma forma de definir uma cor específica para cada variação.
            //Mas como o eixo de cor pode receber qualquer tipo de índice, é melhor setar apenas a cor máxima e mínima e deixar a API montar a escala de cores intermediárias.

            var options = {
              title: 'Gráfico 4 Indicadores',
              width: 800,
              height: 800
              };



            var chart = new google.visualization.LineChart(document.getElementById('chartlinha'));
            chart.draw(data, options);
        }
    </script>
    <div id="chartlinha" width="800" height="800"></div>
    <div class="clear"></div>

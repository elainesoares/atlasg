    <script src="com/mobiliti/grafico/grafico-dispersao.builder.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        var dataGraficoDispersao = [

              //Ordem dos campos é: ID, EixoX, EixoY, Cor, Tamanho da Bola.
              //Os dados precisam chegar neste formato.

              [ 'Lugar' ,'idhm', 'ihmrenda',     'populacao',    'idhmeducacao'],
              [ 'Montes Claros', 0.712,   0.60,          200000,         0.800],
              [ 'Brasilinha', 0.812,   0.70,          300000,         0.600],
              [ 'Espinosa', 0.822,   0.80,          400000,         0.700],
              [ 'Anta', 0.709,   0.65,          500000,         0.750],
              [ 'Sapucaia', 0.645,   0.75,          600000,         0.850],
              [ 'BH', 0.911,   0.85,          700000,         0.890],
              [ 'Terremoc', 0.812,   0.50,          800000,         0.920],
              [ 'Janauba', 0.512,   0.68,          900000,         0.990],

              [ 'Brasilia', 0.912,   0.92,          1000000,         0.850 ]

              ];
        google.load('visualization', '1', {'packages':['corechart']});
        //google.setOnLoadCallback(drawChartBolha);

        function drawChartBolha() {
            var data = google.visualization.arrayToDataTable(dataGraficoDispersao);
            //A Escala feita pelos designers tinha cores bem diferentes. 
            //Teríamos que encontrar uma forma de definir uma cor específica para cada variação.
            //Mas como o eixo de cor pode receber qualquer tipo de índice, é melhor setar apenas a cor máxima e mínima e deixar a API montar a escala de cores intermediárias.

            var options = {
              title: 'Gráfico 4 Indicadores',
              colorAxis: {colors: ['green', 'blue']},

              width: 900,
              height: 800
              };



            var chart = new google.visualization.BubbleChart(document.getElementById('chartdispersao'));
            chart.draw(data, options);
        }
    </script>
    <div id="chartdispersao"></div>
    <div class="clear"></div>
    <?php
        
    ?>

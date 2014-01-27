/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//function GraficoLinhas(){
//    this.data;
//    this.consultar = function (eixoX){
//        loadingHolder.show("Carregando dados...");
//        $.ajax({
//            type: 'post',
//            url:'com/mobiliti/grafico/linhas/grafico-linhas.controller.php',
//            data:{'json_lugares':geral.getLugares(),'indicador' : eixoX},
//            success: function(retorno){
//                this.data = jQuery.parseJSON(retorno);
//                dataGraficoDispersao = this.data;
//                drawChartLinha(dataGraficoDispersao);
//                loadingHolder.dispose();
//            }
//        });
//    }
//}
//
////============================================================================
////cast
////============================================================================
//    var graficoLinhas = new GraficoLinhas();

google.load('visualization', '1', {
    'packages':['corechart']
});
google.setOnLoadCallback(drawChart);

function drawChart(){
//    console.log('drawChart');
    //    console.log('Nome: '+obj['nome'][0]);
    //    console.log('Ano: '+obj['ano'][0]);
//    console.log('Valor: '+obj['valor'][0]);
//    console.log('Valor: '+obj['valor'][1]);
    var data = new google.visualization.DataTable();
    for (i = 0; i < 1; i ++){
        //        console.log('for 1');
        data.addColumn('number', 'Ano');
        for(j = 0; j < obj['nome'].length; j++){
            //            console.log('for 2');
//                        console.log('obj[nome]: '+obj['nome'][j]);
            data.addColumn('number', obj['nome'][j]);
        }
    }
    
    var tam = obj['nome'].length;
    var linhas = obj['valor'].length;
//    console.log('Tamanho nome: '+obj['nome'].length);
//    console.log('Tamanho valor: '+obj['valor'].length);
//    console.log(obj['ano'][1]);
    data.addRows(3);
//    console.log('tam: '+tam);
    var k = 0;
    var c = 0;
    for(i = 0; i < 3; i++){
//        console.log('k, 0, obj[ano][c] = '+ k+', '+0+', '+obj['ano'][c]);
        data.setValue(k, 0, Number(obj['ano'][c]));
        for(var j = 1; j <= tam; j++){
//            console.log('k = '+k);
            //            console.log('i = '+i);
            //            console.log('j = '+j);
            //            console.log('passei ');
            data.setValue(i, j, Number(obj['valor'][c]));
//            console.log('i, j, obj[valor][k] = '+ i+', '+j+', '+obj['valor'][c]);
            c++;
        }
        k++;
       
    }
    
    var options = {
    	curveType: 'function',	//Deixa a curva mais 'lisa'
    	legend: {position: 'none'}, 	//Retira a legenda
    	hAxis: {direction: 1, textPosition: 'out', gridlines: {count: 3}, minorGridlines: {count: 2}, viewWindow: {max: 2010, min: 1991}, format:'####', axAlternation: 4, minValue: 1991, maxValue: 2010},
        chartArea:{left:70,top:10,width:"85%",height:"90%"},
        axisTitlesPosition: 'none',
        colors: ['red', 'blue']
//        width: 400,
        //height: 900,
        //selectionMode: 'multiple',          // Allow multiple simultaneous selections.
        //tooltip: {trigger: 'selection'},    // Trigger tooltips on selections
        //aggregationTarget: 'category',      // Group selections by x-value.
//        
//        chartArea: {
//            left:100,
//            top: 10,
//            width:"60%"
//        },
//        legend: null,
        //focusTarget: 'datum',
        //hAxis: {direction: 1, textPosition: 'out', gridlines: {count: 3}, minorGridlines: {count: 2}, viewWindow: {max: 2010, min: 1991}},
        //lineWidth: 2,
        //vAxis: {format: '#.###'},
        //reverseCategories: true
        //pointSize: 5
    };

    // Create and draw the visualization.
    var chart = new google.visualization.LineChart(document.getElementById('chart_divLinha'));
    chart.draw(data, options); 
}

google.load('visualization', '1', {'packages':['corechart']});
google.setOnLoadCallback(drawChartBolha);

function drawChartBolha(){
//    console.log('drawChartBolha');
//    console.log('obj[0][0]: '+obj[0][0]);
    var cont = 0;
    //var i = 0;
    var array_graphic = new Array();
    var array_table = new Array();
    var data = new google.visualization.DataTable();
    for(cont = 0; cont < 1; cont++){
        data.addColumn('string', 'Lugar');
        for(var j=0; j < obj[0].length - 1; j++){
            data.addColumn('number', obj[0][j+1]);
        }
    }
    
   var tam = obj.length;
    data.addRows(tam-1);
//    console.log(tam);
    for(var i=0; i < (tam-1); i++){
//        console.log('i: '+i);
        var k = 0;
        for(var j=0; j < 5; j++){
            data.setValue(i, j, obj[i+1][k]);
            k++;
        }
    }
    
     var options = {
          colorAxis: {/*colors: ['red', 'blue'],*/ legend: {position: 'none'}},
          axisTitlesPosition: 'none',
          chartArea:{left:70,top:10,width:"85%",height:"90%"},
          enableInteractivity: true,
          hAxis: {gridlines: {count: 5}},
          sortBubblesBySize: true,
          tooltip: {trigger: 'focus'}
          //explorer: {actions: ['dragToZoom', 'rightClickToReset']}
          
//          backgroundColor: 'pink',
//          hAxis: {title: 'Life Expectancy'},
//          vAxis: {title: 'Fertility Rate'}
        };
    
    var chart = new google.visualization.BubbleChart(document.getElementById('chart_div'));
    
    chart.draw(data, options);
}
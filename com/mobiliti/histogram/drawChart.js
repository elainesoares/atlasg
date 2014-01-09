google.load('visualization', '1', {packages:['corechart']});
google.setOnLoadCallback(visualization);

function visualization(){
    var cont = 0;
    var i = 0;
    var array_graphic = new Array();
    var array_table = new Array();
    array_table.push('Amplitude');
    
    if(window.histogram_value == undefined) return;
    
    array_table.push(window.histogram_value.nomeVariavel);
    
    for(i = 0; i < window.histogram_value.qtdPrintMedia; i++) {
        array_table = new Array();
        array_table.push(window.histogram_value.amplitude[i])
        array_table.push(window.histogram_value.valor[i]);
        array_graphic.push(array_table);
    }
    var data = google.visualization.arrayToDataTable(array_graphic);
    var chart = new google.visualization.ColumnChart(document.getElementById(window.histogram_value.ne));
    chart.draw(data,window.histogram_value.options);
}



//google.load("visualization", "1", {packages:["corechart"]});
//google.setOnLoadCallback(drawChart);
  
function graficoFrequenciaEscolar() {
    var dados = new google.visualization.DataTable(); 
    var array_freq_esc_mun = Array();
    var array_freq_esc_uf = Array(); 
    var array_freq_esc_pais = Array();

    array_freq_esc_mun   = jQuery.parseJSON($("#freq_esc_mun").val());
    array_freq_esc_uf = jQuery.parseJSON($("#freq_esc_uf").val()); 
    array_freq_esc_pais = jQuery.parseJSON($("#freq_esc_pais").val());
	
    var array_freq_esc_4a6 = Array();
    var array_freq_esc_12a14 = Array(); 
    var array_freq_esc_16a18 = Array();
    var array_freq_esc_19a21 = Array();
		
    for(i=0;i<array_freq_esc_mun.length;i++)
    {
        if('T_FREQ4A6' == array_freq_esc_mun[i].sigla ){
            array_freq_esc_4a6.push(array_freq_esc_mun[i]);
            array_freq_esc_4a6.push(array_freq_esc_uf[i]);
            array_freq_esc_4a6.push(array_freq_esc_pais[i]);       
        }
        if('T_FUND12A14' == array_freq_esc_mun[i].sigla){
            array_freq_esc_12a14.push(array_freq_esc_mun[i]);
            array_freq_esc_12a14.push(array_freq_esc_uf[i]);
            array_freq_esc_12a14.push(array_freq_esc_pais[i]);
        }
        if('T_FUND16A18' == array_freq_esc_mun[i].sigla){
            array_freq_esc_16a18.push(array_freq_esc_mun[i]);
            array_freq_esc_16a18.push(array_freq_esc_uf[i]);
            array_freq_esc_16a18.push(array_freq_esc_pais[i]);
        }
        if('T_MED19A21' == array_freq_esc_mun[i].sigla){
            array_freq_esc_19a21.push(array_freq_esc_mun[i]);
            array_freq_esc_19a21.push(array_freq_esc_uf[i]);
            array_freq_esc_19a21.push(array_freq_esc_pais[i]);
        }
    }
    dados.addColumn('string','Data');
    dados.addRows(4);            
		
    dados.addColumn('number',array_freq_esc_mun[0].nome);
    dados.addColumn('number','Estado: ' + array_freq_esc_uf[0].nomeestado);
    dados.addColumn('number','Brasil');
		
		
    dados.setValue(0,0,array_freq_esc_4a6[0].nomecurto);
    dados.setValue(1,0,array_freq_esc_12a14[0].nomecurto);
    dados.setValue(2,0,array_freq_esc_16a18[0].nomecurto);
    dados.setValue(3,0,array_freq_esc_19a21[0].nomecurto);
		
    for(i=0;i<array_freq_esc_4a6.length;i++){
        dados.setValue(0,i+1,Number(array_freq_esc_4a6[i].valor));
        dados.setValue(1,i+1,Number(array_freq_esc_12a14[i].valor));
        dados.setValue(2,i+1,Number(array_freq_esc_16a18[i].valor));
        dados.setValue(3,i+1,Number(array_freq_esc_19a21[i].valor));
    }
	
     var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    
    formatter.format(dados, 1);
    formatter.format(dados, 2);
    formatter.format(dados, 3);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('chartFrequenciaEscolar'));
    chart.draw(dados, {
        legend: {
            alignment: 'center'
        },
        series: {
            0: {
                color:'#FF66CC'
            },
            1: {
                color: '#9AC0CD'
            },
            2: {
                color: '#2E8B57'
            }
        },
        width: 800, 
        height: 600,
        title:"Fluxo Escolar por Faixa Etária - Brasil, "+array_freq_esc_uf[0].nomeestado+", "+array_freq_esc_mun[0].nome+" - 2010"
	
    });
}
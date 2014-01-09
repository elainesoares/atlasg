function graficoFrequenciaEscolarDe15a17Anos() {

    var dados = new google.visualization.DataTable(); 
    var array = Array();
    var array_freq_esc_15a17_nome = Array();
    var array_freq_esc_15a17_valor = Array();
                
    array = jQuery.parseJSON($("#freq_esc_15a17").val());    

    var name_freq = array[0].nome;

    dados.addColumn('string','Data');
    dados.addRows(array.length);
    dados.addColumn('number',name_freq);
                
    var taxa_frequencia;
                
    for(i=0;i<array.length;i++)
    {
        if('T_FLMED' == array[i].sigla){
            taxa_frequencia = array[i].valor;
        } 
        else if('T_FREQ15A17' == array[i].sigla ){
            array_freq_esc_15a17_nome.push("Não Frequenta");
            array_freq_esc_15a17_valor.push(100 - array[i].valor);
        }
    }
                
    for(i=0;i<array.length;i++)
    {
        
        if('T_ATRASO_0_MED' == array[i].sigla){
            array_freq_esc_15a17_nome.push("No Ensino Médio Sem Atraso"); 
            array_freq_esc_15a17_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_ATRASO_1_MED' == array[i].sigla){
            array_freq_esc_15a17_nome.push("No Ensino Médio Com Um ano de Atraso"); 
            array_freq_esc_15a17_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_ATRASO_2_MED' == array[i].sigla){
            array_freq_esc_15a17_nome.push("No Ensino Médio Com Dois ano de Atraso"); 
            array_freq_esc_15a17_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_FREQFUND1517' == array[i].sigla){
            array_freq_esc_15a17_nome.push("Frequentando o Fundamental");
            array_freq_esc_15a17_valor.push(array[i].valor);
        }
        else if('T_FREQSUPER1517' == array[i].sigla){
            array_freq_esc_15a17_nome.push("Frequentando o Curso Superior");
            array_freq_esc_15a17_valor.push(array[i].valor);
        }
    }
    var valor = 100;
                
    for(i=0;i<array_freq_esc_15a17_nome.length;i++)
    {
        valor = valor - array_freq_esc_15a17_valor[i];
    }         
    array_freq_esc_15a17_nome.push("Outros");
    array_freq_esc_15a17_valor.push(valor);
          
    for(i=0;i<array_freq_esc_15a17_nome.length;i++){
        dados.setValue(i,0,array_freq_esc_15a17_nome[i] + " (" +number_format(array_freq_esc_15a17_valor[i], 2, ',', '.')+"%)");
        dados.setValue(i,1,Number(array_freq_esc_15a17_valor[i]));

    }
    var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    formatter.format(dados,1);
          
    var chart = new google.visualization.PieChart(document.getElementById('chart_freq_15a17'));
    chart.draw(dados, {
        hAxis: {
            minValue: 0,
            maxValue: 100
        },slices: [{color: '#F4A460'}, {color: '#CAE1FF'}, {color: '#A4D3EE'}, {color: '#4F94CD'}, {color: '#4682B4'}, {color: '#F5DEB3'},{color: 'orange'}],
        pieSliceText: 'value',
        width: 800, 
        height: 500,
        title:"Frequência escolar de 15 a 17 anos - 2010"
    });
                
}
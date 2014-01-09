function graficoFaixaEtaria2000() {
    var dados = new google.visualization.DataTable();
    var array_masc = Array();
    var array_femin = Array();
    var array_idade = new Array();
                
    array_idade.push('0 a 4','5 a 9','10 a 14','15 a 19','20 a 24','25 a 29',
        '30 a 34','35 a 39','40 a 44','45 a 49','50 a 54','55 a 59','60 a 64',
        '65 a 69','70 a 74','75 a 79','80 e +');

                
    array_masc = jQuery.parseJSON($("#piram_masc_2000").val());
    array_femin =jQuery.parseJSON($("#piram_fem_2000").val()); 
    var populacao = jQuery.parseJSON($("#piram_total_2000").val());            

    dados.addColumn('string','Data');
    dados.addRows(array_idade.length);
    dados.addColumn('number','Homens');
    dados.addColumn('number','Mulheres');
                
                
    //------------------------------MASC-----------------------------------------//                
    for(i=0;i<array_masc.length;i++){
                    
        if(array_masc[i].sigla == 'HOMEM0A4')
        {
            dados.setValue(0,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM5A9')
        {
            dados.setValue(1,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM10A14')
        {
            dados.setValue(2,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM15A19')
        {
            dados.setValue(3,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM20A24')
        {
            dados.setValue(4,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM25A29')
        {
            dados.setValue(5,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM30A34')
        {
            dados.setValue(6,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM35A39')
        {
            dados.setValue(7,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM40A44')
        {
            dados.setValue(8,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM45A49')
        {
            dados.setValue(9,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM50A54')
        {
            dados.setValue(10,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM55A59')
        {
            dados.setValue(11,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM60A64')
        {
            dados.setValue(12,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM65A69')
        {
            dados.setValue(13,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM70A74')
        {
            dados.setValue(14,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM75A79')
        {
            dados.setValue(15,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMENS80')
        {
            dados.setValue(16,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }

    }
    //---------------FEMININO-----------------------------------------//  
   
    for(i=0;i<array_idade.length;i++){
        dados.setValue(i,0,array_idade[i]);
    }
    for(i=0;i<array_femin.length;i++){
                    
        if(array_femin[i].sigla == 'MULH0A4')
        {
            dados.setValue(0,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH5A9')
        {
            dados.setValue(1,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH10A14')
        {
            dados.setValue(2,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH15A19')
        {
            dados.setValue(3,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH20A24')
        {
            dados.setValue(4,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH25A29')
        {
            dados.setValue(5,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH30A34')
        {
            dados.setValue(6,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH35A39')
        {
            dados.setValue(7,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH40A44')
        {
            dados.setValue(8,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH45A49')
        {
            dados.setValue(9,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH50A54')
        {
            dados.setValue(10,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH55A59')
        {
            dados.setValue(11,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH60A64')
        {
            dados.setValue(12,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH65A69')
        {
            dados.setValue(13,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH70A74')
        {
            dados.setValue(14,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH75A79')
        {
            dados.setValue(15,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULHER80')
        {
            dados.setValue(16,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        
    }

    var chart = new google.visualization.BarChart(document.getElementById('chart_piram_2000'));
 
     var options = {
        isStacked: true,
        hAxis: {
            format : ';',
            minValue: -10,
            maxValue: 10
        },
        vAxis: {
            direction: -1
        },
        width: 800,
        height: 450,
        chartArea:{left: 150,top: 30}
    };
    
    var formatter = new google.visualization.NumberFormat({
        negativeParens: true,
        suffix: '%'
    });
    
    formatter.format(dados, 1);
    formatter.format(dados, 2);
    
    chart.draw(dados, options);
}
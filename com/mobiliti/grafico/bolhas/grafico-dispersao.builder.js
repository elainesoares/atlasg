function GraficoDispersao(){
    this.data;
    this.consultar = function (eixoX, ano_eixoX, eixoY, ano_eixoY, eixoSize, ano_eixoSize, eixoColor, ano_eixoColor){
        //loadingHolder.show("Carregando dados...");
        json = JSON.parse('[{"id":'+eixoX+',"eixo":"X","ano":'+ano_eixoX+'},{"id":'+eixoY+',"eixo":"Y","ano":'+ano_eixoY+'},{"id":'+eixoSize+',"eixo":"Size","ano":'+ano_eixoSize+'},{"id":'+eixoColor+',"eixo":"Color","ano":'+ano_eixoColor+'}]');
        $.ajax({
            type: 'post',
            url:'com/mobiliti/grafico/bolhas/grafico-dispersao.controller.php',
            data:{'json_lugares':geral.getLugaresPorEspacialidadeAtiva(),'json_indicadores' : json},
            success: function(retorno){
                this.data = jQuery.parseJSON(retorno);
                dataGraficoDispersao = this.data;
                drawChartBolha();
                loadingHolder.dispose();
            }
        });
    }
}

//==============================================================================
//cast
//==============================================================================
/*
var graficoDispersao = new GraficoDispersao();
graficoDispersao.consultar(196,1,197,1,196,2,197,2);
*/
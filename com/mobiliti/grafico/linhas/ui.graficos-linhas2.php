<?php 
    require_once "./config/config_path.php"; 
    require_once "./config/config_gerais.php";
?>
<script src="com/mobiliti/grafico/linhas/grafico-linhas.builder.js"></script>
<script type="text/javascript">
    /*****************************
     script: ui.grafico.dispersao2.php
     author: Elaine Soares Moreira
     *****************************/
    var linha_indc_selector;
    var indicadorLocalL;

    var local;
    var linha_indc;

    var linha_a = 3;
    var ___first_time_year_ = true;
    var linha_e = null;
    var linha_l = null;
    //    var bolha_i = null;
    var linha_i = new Array();
    var linha_i_name = new Array();
    var obj;
    var eixoy = new Array();
    var eixox = new Array();
    var eixotam = new Array();
    var eixocor = new Array();
    var eixo;
    var _indicadores;
    var _locais;

    $(document).ready(function()
    {
//        console.log('ready');
        $('#local_box3').load('com/mobiliti/componentes/local/local_grafLinhas.html', function()
        {
//            console.log('local_box3');
            localL = new SeletorLocalG();
            localL.startLocal(listenerLocalGrafBolha, "local_box3", false);
            linha_indc = new LocalSelectorG();
            localL.setButton(linha_indc.html('uilinhalocal_selector'))
            linha_indc.startSelector(true, "uilinhalocal_selector", linha_indcator_selector_linha, "right", "linhaEditIndicador", true);
        });
        $('#box_indicador').load('com/mobiliti/componentes/local_indicador/indicador6.html', function()
        {
//            console.log('box_indicador');
            indicadorLocalL = new SeletorIndicadorG();
            indicadorLocalL.startLocal(listenerLocalIndicadoresLinha, "box_indicador", false, false);
            try {
                linha_indc_selector = new IndicatorSelectorG();
                indicadorLocalL.setButton(linha_indc_selector.html('linhaEditIndicador', 'Y'));
                linha_indc_selector.startSelector(false, "linhaEditIndicador", seletor_indicadorL, "right", false, "uilinhalocal_selector", true, false);
            } catch (e) {
////                //erro
            }
        });

        linha_init();
    });

    function linha_init() {
//        console.log('linha_init');
        $('.nav-tabs').button();
        $("#linha_year_slider").bind("slider:changed", linha_year_slider_listener);

        linha_loading(false);
    }
    //    
    function linha_year_slider_listener(event, data) {
//        console.log('linha_year_slider_listener');
        if (___first_time_year_)
        {
            ___first_time_year_ = false;
            return;
        }

        if (data.value === 1991)
            linha_a = 1;
        else if (data.value === 2000)
            linha_a = 2;
        else if (data.value === 2010)
            linha_a = 3;

        linha_use_interpolation = true;

        //-----------------------------------
        // Muda todos os anos dos indicadores
        //-----------------------------------
        _indicadores = geral.getIndicadores();
        for (var i = 0; i < _indicadores.length; i++) {
            geral.updateIndicador(i, linha_a);
        }
        //        if((linha_i[0] != undefined && linha_i[1] != undefined && linha_i[2] != undefined && linha_i[3] != undefined) &&(_locais != undefined)){
        linha_load(linha_e, linha_l, linha_i, linha_a);
        //        }
    }

    function linha_loading(status)
    {
//        console.log('linha_loading');
        if (status)
            $("#uilinhaloader").show();
        else
            $("#uilinhaloader").hide();
    }

    function linha_load(e, l, i, a)
    {
//        console.log('linha_load');
//        console.log('Espacialidade: '+e);
//        console.log('Locais: '+l);
//        console.log('indicador'+i);
//        console.log('ano: '+a);
        if(i != 0){
            linha_loading(true);
        }

        var linha_data = new Object();

        //define os ids
        linha_data['e'] = e; // espacialidade
        if (l.length)
            linha_data['l'] = l.toString(); // array de locais em modo texto     
        else
            linha_data['l'] = new Array(0);
        linha_data['i'] = i.toString(); // indicador
        linha_data['a'] = a; // ano

//        linha_e = e;
//        linha_l = l;
//        linha_i = i;
//        linha_a = a;
        //        teste = <? //= $path_dir;  ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo $path_dir ?>com/mobiliti/grafico/linhas/grafico-linhas.controller.php",
            data: linha_data,
            success: linha_response
        });
    }

    function linha_response(data, textStatus, jqXHR)
    {
//        console.log('linha_response');
        if (textStatus === "success") {
//            console.log('success');
            var ano_result_to_fill = '';
            if (linha_a === 1)
                ano_result_to_fill = "1991";
            else if (linha_a === 2)
                ano_result_to_fill = "2000";
            else if (linha_a === 3)
                ano_result_to_fill = "2010";

            $("#linha_year_slider").simpleSlider("setValue", ano_result_to_fill);

//            $("#p_indicador").val(linha_i_name);
//            $("#p_ano").val(ano_result_to_fill);


            obj = $.parseJSON(data);
//            console.log(obj['ano'][01]);
//            console.log(obj['nome'][0]);]);
//            console.log(obj['valor'][1]);
//            console.log(obj['nome'][0]);
            drawChart();
        }
    }

    function listenerLocalGrafLinha(lugares) {
//        console.log('listenerLocalGrafLInha');
        geral.setLugares(lugares);
    }
    function linha_indcator_selector_linha(array) {
//        console.log('linha_indcator_selector_linha');
        localL.setItensSelecionados(array);
    }

    function listenerLocalIndicadoresLinha(indicadores) {
//        console.log('listenerLocalIndicadoresLinha');
        geral.setIndicadores(indicadores);
        linha_indc_selector.refresh();
    }

    function linha_listener_lugar(event, obj)
    {
//        console.log('linha_listener_lugar');
        localL.refresh();
        linha_indc.refresh();
        dispacth_linha_evt();
    }

    function seletor_indicadorL(obj) {
//        console.log('seletor_indicadorL');
        geral.setIndicadores(obj);
        indicadorLocalL.refresh();

    }

    function linha_listener_indicador(event, obj)
    {
//        console.log('linha_listener_indicador');
        quantil_id = "";

        if (event === "changetab")
        {
            geral.removeIndicadoresDuplicados();
        }

        indicadorLocalL.refresh();
        linha_indc_selector.refresh();

        //        //-----------------------------------
        //        // Muda todos os anos dos indicadores
        //        //-----------------------------------
        _indicadores = geral.getIndicadores();
        for (var i = 0; i < _indicadores.length; i++) {
            geral.updateIndicador(i, linha_a);
        }

        //Ao Mudar de aba disparar o dispacth_linha_evt somente uma vez!
        if (event !== "changetab")
            dispacth_linha_evt(true);
    }

    function dispacth_linha_evt()
    {
//        console.log('dispacth_linha_evt');
        // limpa todos os argumentos
        linha_i_name = "";
        linha_i = 0;
        linha_e = 0;
        linha_l = new Array();

        //obtem os locais e espacialidade
        var _locais = geral.getLugaresPorEspacialidadeAtiva();

        if (!(_locais === undefined || _locais === null || _locais === ""))
        {
            linha_e = _locais.e;
            //locais a serem exibidos no mapa ou destacadas
            for (var i = 0; i < _locais.l.length; i++)
            {
                var _lugar = _locais.l[i];
                if (_lugar)
                {
                    if (_lugar.c)
//                        console.log('ID Lugar: '+_lugar.id);
                        linha_l.push(_lugar.id);
                }
            }
        }

        //obtem o indicador
        var _indicadores = geral.getIndicadores();
        if (!(_indicadores === undefined || _indicadores === null || _indicadores === ""))
        {
            for (var i = 0; i < _indicadores.length; i++)
            {
                var _indicador = _indicadores[i];

                //if (_indicador.c)
                //{
                linha_i = _indicador.id;
//                    console.log('linha_i: '+linha_i);
                linha_a = _indicador.a;
//                    console.log('linha_a: '+linha_a);

                //nome completo
                linha_i_name = _indicador.desc;
//                    console.log('linha_i_name: '+linha_i_name);
                break;
                //}
            }
        }

        linha_load(linha_e, linha_l, linha_i, linha_a);
    }

</script>
<div>
    <table>
        <tr style="padding: 0; margin: 0; border: 0;">
            <td id="local_box3" style="padding: 0; margin: 0; border: 0;"></td>
            <td rowspan="3" style="padding: 0; margin: 0; border-left: 1px solid #ccc; vertical-align: top;">
                <table>
                    <tr style="">
                        <td colspan="4">
                            <div id="box_indicador" data-original-title='Selecione o indicador que representará o Eixo Y, no gráfico.' style="margin-left:52px; width: 57px; float: left; height: 507px; "></div>
                            <div id="chart_divLinha" style='width: 600px; height: 500px; margin-left: 80px;'>
                                <img id="uilinhaloader" src="img/map/ajax-loader.gif" style="background-color: transparent; margin-top: 250px; margin-left: 270px;" />
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
<!--        <tr>
            <td>
                <div id="box_indicador" data-original-title='' style="margin-left:52px; width: 57px; float: left; height: 507px; "></div>
            </td>
        </tr>-->
    </table>
</div>


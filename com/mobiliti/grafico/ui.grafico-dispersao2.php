<?php require_once "./config/config_path.php"; ?>
<script src="com/mobiliti/grafico/grafico-dispersao.builder2.js"></script>
<script type="text/javascript">
    /*****************************
     script: ui.grafico.dispersao2.php
     author: Elaine Soares Moreira
     *****************************/
    var bolha_indc_selector = new Array();
    var indicadorLocal = new Array();
    
    var local;
    var bolha_indc;
    
    var bolha_a = 3;
    var ___first_time_year_ = true;
    var bolha_e = null;
    var bolha_l = null;
//    var bolha_i = null;
    var bolha_i = new Array();
    var bolha_i_name = new Array();
    var obj;
    var eixoy = new Array();
    var eixox = new Array();
    var eixotam = new Array();
    var eixocor = new Array();
    var eixo;
    var _indicadores;
    var _locais;
    var bolha;
    
    $(document).ready(function()
    {
//        console.log('ready');
        $('#local_box2').load('com/mobiliti/componentes/local/local_graf.html', function()
        {
//            console.log('local_box2');
            local_ = new SeletorLocal();
            local_.startLocal(listenerLocalGrafBolha, "local_box2", false);
            bolha_indc = new LocalSelector();
            local_.setButton(bolha_indc.html('uibolhalocal_selector'))
            bolha_indc.startSelector(true, "uibolhalocal_selector", bolha_indcator_selector_bolha, "right", "bolhaEditIndicador", true);
        });
        $('#box_indicador_eixoX').load('com/mobiliti/componentes/local_indicador/indicador3.html', function()
        {
//            console.log('box_indicador_eixoX');
            indicadorLocal[1] = new SeletorIndicador();
            indicadorLocal[1].startLocal(listenerLocalIndicadoresBolha, "box_indicador_eixoX", false, 'x');
            try {
                bolha_indc_selector[1] = new IndicatorSelector();
                indicadorLocal[1].setButton(bolha_indc_selector[1].html('bolhaEditIndicadorx', 'X'));
                bolha_indc_selector[1].startSelector(false, "bolhaEditIndicadorx", seletor_indicador_, "right", false, "uibolhalocal_selector", true, 'x');
            } catch (e) {
////                //erro
            }
        });
        $('#box_indicador_eixoY').load('com/mobiliti/componentes/local_indicador/indicador2.html', function()
        {
//            console.log('box_indicador_eixoY');
            indicadorLocal[0] = new SeletorIndicador();
            indicadorLocal[0].startLocal(listenerLocalIndicadoresBolha, "box_indicador_eixoY", false, 'y');
            try {
                bolha_indc_selector[0] = new IndicatorSelector();
                indicadorLocal[0].setButton(bolha_indc_selector[0].html('bolhaEditIndicadory', 'Y'));
                bolha_indc_selector[0].startSelector(false, "bolhaEditIndicadory", seletor_indicador_, "right", false, "uibolhalocal_selector", true, 'y');
            } catch (e) {
////                //erro
            }
        });
        $('#box_indicador_Cor').load('com/mobiliti/componentes/local_indicador/indicador5.html', function()
        {
//            console.log('box_indicador_Cor');
            indicadorLocal[3] = new SeletorIndicador();
            indicadorLocal[3].startLocal(listenerLocalIndicadoresBolha, "box_indicador_Cor", false, 'cor');
            try {
                bolha_indc_selector[3]= new IndicatorSelector();
                indicadorLocal[3].setButton(bolha_indc_selector[3].html('bolhaEditIndicadorcor', 'Cor'));
                bolha_indc_selector[3].startSelector(false, "bolhaEditIndicadorcor", seletor_indicador_, "right", false, "uibolhalocal_selector", true, 'cor');
            } catch (e) {
////                //erro
            }
        });
         $('#box_indicador_Tamanho').load('com/mobiliti/componentes/local_indicador/indicador4.html', function()
        {
//            console.log('box_indicador_Tamanho');
            indicadorLocal[2] = new SeletorIndicador();
            indicadorLocal[2].startLocal(listenerLocalIndicadoresBolha, "box_indicador_Tamanho", false, 'tam');
            try {
                bolha_indc_selector[2] = new IndicatorSelector();
                indicadorLocal[2].setButton(bolha_indc_selector[2].html('bolhaEditIndicadortam', 'Tamanho'));
                bolha_indc_selector[2].startSelector(false, "bolhaEditIndicadortam", seletor_indicador_, "right", false, "uibolhalocal_selector", true, 'tam');
            } catch (e) {
////                //erro
            }
        });
        
        bolha_init();
    });
    
    function bolha_init(){
//        console.log('bolha_init');
        $('.nav-tabs').button();
        $("#bolha_year_slider").bind("slider:changed", bolha_year_slider_listener);

        bolha_loading(false);
    }
//    
    function bolha_year_slider_listener(event, data){
//        console.log('2 - bolha_year_slider_listener');
        if (___first_time_year_)
        {
            ___first_time_year_ = false;
            return;
        }
        
        if (data.value === 1991)
            bolha_a = 1;
        else if (data.value === 2000)
            bolha_a = 2;
        else if (data.value === 2010)
            bolha_a = 3;
        
//        console.log('bolha_a: '+bolha_a);

        //-----------------------------------
        // Muda todos os anos dos indicadores
        //-----------------------------------
        _indicadores = geral.getIndicadores();
        for (var i = 0; i < _indicadores.length; i++){
            geral.updateIndicador(i, bolha_a);
        }
//        console.log('bolha_i: '+bolha_i);
        if((bolha_i[0] != undefined && bolha_i[1] != undefined && bolha_i[2] != undefined && bolha_i[3] != undefined) &&(_locais != undefined)){
            bolha_load(bolha_e, bolha_l, bolha_i, bolha_a);
        }
    }
    
    function bolha_loading(status)
    {
//        console.log('bolha_loading');
        if (status)
            $("#uibolhaloader").show();
        else
            $("#uibolhaloader").hide();
    }
    
    function bolha_load(e, l, i, a)
    {
//        console.log('bolha_load');

        bolha_loading(true);

        var bolha_data = new Object();

        //define os ids
        bolha_data['e'] = e; // espacialidade
        if (l.length)
            bolha_data['l'] = l.toString(); // array de locais em modo texto     
        else
            bolha_data['l'] = new Array(0);
        bolha_data['i'] = i.toString(); // indicador
        bolha_data['a'] = a; // ano

        bolha_e = e;
        bolha_l = l;
        bolha_i = i;
        bolha_a = a;
//        console.log('bolha_a: '+bolha_a);
//        teste = <?=$path_dir; ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo $path_dir ?>com/mobiliti/grafico/grafico-dispersao.controller.php",
            data: bolha_data,
            success: bolha_response
        });
    }
    
    function bolha_response(data, textStatus, jqXHR)
    {
//        console.log('bolha_response');
        if (textStatus === "success"){
            var ano_result_to_fill = '';
            if (bolha_a === 1)
                ano_result_to_fill = "1991";
            else if (bolha_a === 2)
                ano_result_to_fill = "2000";
            else if (bolha_a === 3)
                ano_result_to_fill = "2010";

            $("#bolha_year_slider").simpleSlider("setValue", ano_result_to_fill);

            $("#p_indicador").val(bolha_i_name);
            $("#p_ano").val(ano_result_to_fill);


            obj = $.parseJSON(data);
            drawChartBolha();

        }
    }
    
    function listenerLocalGrafBolha(lugares){
//        console.log('listenerLocalGrafBolha');
        geral.setLugares(lugares);
    }    
    function bolha_indcator_selector_bolha(array){
//        console.log('bolha_indcator_selector_bolha');
        local_.setItensSelecionados(array);
    }
    
    function listenerLocalIndicadoresBolha(indicadores){
//        console.log('listenerLocalIndicadoresBolha');
        geral.setIndicadores(indicadores);
        eixo = geral.getEixo();
        if(eixo == undefined || eixo == ''){
            eixo = 0;
        }
//        console.log('eixo 1: '+eixo);
        bolha_indc_selector[eixo].refresh();
    }
    
    function bolha_listener_lugar(event, obj)
    {
//        console.log('bolha_listener_lugar');
//        quantil_id = "";
        local_.refresh();
        bolha_indc.refresh();
        dispacth_bolha_evt();
    }
    
    function seletor_indicador_(obj){
//        console.log('seletor_indicador_');
        geral.setIndicadores(obj);
        eixo = geral.getEixo();
        if(eixo == undefined || eixo == ''){
            eixo = 0;
        }
//        console.log('eixo 2: '+eixo);
        indicadorLocal[eixo].refresh();

    }
    
    function bolha_listener_indicador(event, obj)
    {
//        console.log('bolha_listener_indicador');
        quantil_id = "";

        if (event === "changetab")
        {
            geral.removeIndicadoresDuplicados();
        }
        
        eixo = geral.getEixo();
        if(eixo == undefined || eixo == ''){
            eixo = 0;
        }
//        console.log('EIXO: '+eixo);
//        console.log('eixo 3: '+eixo);

        indicadorLocal[eixo].refresh();
        bolha_indc_selector[eixo].refresh();

//        //-----------------------------------
//        // Muda todos os anos dos indicadores
//        //-----------------------------------
        _indicadores = geral.getIndicadores();
//        console.log('Tamanho_indicadores: '+_indicadores.length);
        for (var i = 0; i < _indicadores.length; i++){
            geral.updateIndicador(i, bolha_a);
        }

         //Ao Mudar de aba disparar o dispacth_bolha_evt somente uma vez!
        if (event !== "changetab")
            dispacth_bolha_evt(true);
    }

    function dispacth_bolha_evt()
    {
//        console.log('dispacth_bolha_evt');
        // limpa todos os argumentos
        bolha_i_name = new Array();
        bolha_e = 0;
        bolha_l = new Array();

        //limpa a seleção

        //obtem os locais e espacialidade
        _locais = geral.getLugaresPorEspacialidadeAtiva();
//        console.log('locais => '+_locais);

        if (!(_locais === undefined || _locais === null || _locais === ""))
        {
            bolha_e = _locais.e;
            //locais a bem exibidos no mapa ou destacadas
            for (var i = 0; i < _locais.l.length; i++)
            {
                var _lugar = _locais.l[i];
                if (_lugar)
                {
                    if (_lugar.c)
                        bolha_l.push(_lugar.id);
                }
            }
        }

        //obtem o indicador
        _indicadores = geral.getIndicadores();
//        console.log(_indicadores.length);
        bolha_i[eixo] = _indicadores[0].id;
//        console.log('_indicadores.id: '+_indicadores[0].id);
        bolha_a = _indicadores[0].a;
//        console.log('bolha_a: '+bolha_a);
        bolha_i_name[eixo] = _indicadores[0].desc;
//        console.log('bolha_i[0]: '+bolha_i[0]);
//        console.log('bolha_i[1]: '+bolha_i[1]);
//        console.log('bolha_i[2]: '+bolha_i[2]);
//        console.log('bolha_i[3]: '+bolha_i[3]);
        
        var k = 1;
        
        if((bolha_i[0] != undefined && bolha_i[1] != undefined && bolha_i[2] != undefined && bolha_i[3] != undefined) && (_locais != undefined)){
//            alert('teste');
           
            for(var i = 0; i < 4; i++){
//                console.log('bolha_i[i]: '+bolha_i[i]);
                for(var j = i+1; j < 4; j++){
//                    console.log('bolha_i[j]: '+bolha_i[j]);
//                    alert(bolha_i[i]+ '=='+ bolha_i[j]);
                    if(bolha_i[i] == bolha_i[j]){
                        alert('Selecione Indicadores diferentes para cada Eixo.');
//                        console.log('igual');
//                        document.getElementById('myModal').style.display = 'block';
                        bolha = false;
                        break;
                    }
                    else{
                        bolha = true;
//                        document.getElementById('myModal').display = "none";
                    }
                }
                
                if(bolha == false){
                    break;
                }
            }
//            alert(bolha);
            if(bolha == true){
                bolha_load(bolha_e, bolha_l, bolha_i, bolha_a);
            }
        }
        
    }

</script>

<!-- conteúdo do popover -->
<div id="uimap_popover" style="display: none; margin: 0; padding: 0;" data-container="body"> 
    <span id="uimap_popover_espc_name" style="font-weight: bold;">NO_NAME</span><br/>
    <span id="uimap_popover_value">VALUE</span> 
</div>
<div style="height: 664px;">
    <table>
        <tr style="padding: 0; margin: 0; border: 0;">
            <td id="local_box2" style="padding: 0; margin: 0; border: 0;"></td>
            <td rowspan="3" style="padding: 0; margin: 0; border-left: 1px solid #ccc; vertical-align: top;">
                <table style='height: 823px;'>
                    <tr>
                         <td >
                             <div style="margin-top: 20px; margin-left: 30px;">
                                 <!--<img src="img/icons/gradiente.png" style="float: left;">-->
                                <div id="box_indicador_Cor" data-original-title='Selecione o indicador que representará a cor das Bolinhas no gráfico.' style="width: 281px; float: left; height: 34px; "></div>
                            </div>
                        </td>
                        <td>
                            <div style="margin-top: 20px;">
                                 <!--<img src="img/icons/size.png" style="float: left; margin-left: 10px; margin-top: -11px;">-->
                                <div id="box_indicador_Tamanho" data-original-title='Selecione o indicador que representará o Tamanho da Bolinha, no gráfico.' style="width: 311px; float: left; height: 33px;"> </div>
                            </div>
                        </td>
                    </tr>
<!--                    <tr>
                        
                    </tr>-->
                    <tr style="">
                        <td colspan="4">
                            <div style="width:681px; height:507px;">
                                <div id="box_indicador_eixoY" data-original-title='Selecione o indicador que representará o Eixo Y, no gráfico.' style="margin-left:52px; width: 57px; float: left; height: 507px; ">Teste</div>
                                <!--<div id="myModal" class="modal_video hide" tabindex="-1" role="dialog" data-toggle="modal" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">Selecione Indicadores diferentes para cada Eixo</div>-->
                                <div id="chart_div" style='margin-left: 77px; width: 603px; height: 559px; margin-top: -40px; padding-top: -40px;'>
                                    <img id="uibolhaloader" src="img/map/ajax-loader.gif" style="background-color: transparent; margin-top: 250px; margin-left: 270px;" />
                                </div>
                                <div id="box_indicador_eixoX" data-original-title='Selecione o indicador que representará o Eixo X, no gráfico.' style="width: 604; float: left; height: 55px; margin-left: 79px;"> </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tstyle="background: wheat">
            <td style="margin:0px; padding: 0px; border: 0px; border-right: 1px solid #ccc;">
                <span style="font-weight: bold; display:block; margin-left:24px; width:44px; margin-top: 311px;">ANO</span>
                <div>
                    <div class='labels'>
                        <span class="one">1991</span>
                        <span class="two">2000</span>
                        <span class="tree">2010</span>
                    </div>
                </div>
                <div class="sliderDivFather">
                    <div class="sliderDivIn">
                        <input type='text' id="bolha_year_slider" data-slider="true" data-slider-values="1991,2000,2010" data-slider-equal-steps="true" data-slider-snap="true" data-slider-theme="volume" />
                    </div>    
                </div>
            </td>
            <td  style="margin:0px; padding: 0px; border: 0px;" ></td>
        </tr>
    </table>
</div>


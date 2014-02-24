<script src="com/mobiliti/tabela/builder.tabela.js" type="text/javascript" charset="utf-8"></script>
<script>
  
  
    
    var local2;
    var map_indc2;
    var tab_indc;
    
 
    $(document).ready(function(){
        
        
        
        $("#espac_on_table").html(lang_mng.getString("mapa_espacialidade").toUpperCase());
        $("#selec_tb_01").html(lang_mng.getString("selecionar"));
        
        $("#limparTodasLinhasTabela").html(lang_mng.getString("limpar_lugares"));
        $("#limparTodosIndices").html(lang_mng.getString("limpar_indicadores"));
        
        
        
        
        
        
        map_indc2 = new LocalSelector();

        map_indc2.startSelector(true,"uimapindicator_selector_tabela",
        map_indcator_selector_tabela,"right","uiindicator_selector_tabela_mult");
        
        tab_indc = new IndicatorSelector();
        html = tab_indc.html("uiindicator_selector_tabela_mult");
        $("#calloutIndicadores").append(html);
        $("#calloutIndicadores").append("<h5>" + lang_mng.getString("mapa_indicadores").toUpperCase()  + "</h5>");
        tab_indc.startSelector(true,"uiindicator_selector_tabela_mult",tab_indcator_selector_tabela,"bottom",true,"uimapindicator_selector_tabela");

        
    });
    
    
    
    
    function listnerTabelaIndicadores(obj)
    {
        //tabela_build('');
    }
    
    function tab_indcator_selector_tabela(array){
        geral.setIndicadores(array);
        tabela_build();
    }
   
    function listnerTabelaLocal(e, obj)
    {
        if(e == "changetab" || e == "reloadList"){
            // geral.removeIndicadoresExtras();
            map_indc2.refresh();
            lug = geral.getLugares();
            t = true;
            for(var i in lug){
                try
                {
                    if(lug[i].l.length != 0){
                        t = false;
                        break;
                    }
                }catch(e){
                    
                }
            }
            if(t){
                fillEnptyTabela();
                loadingHolder.dispose();
                return;
            }
            tabela_build('');
        }
     }
    
    
    function map_indcator_selector_tabela(array)
    {
     
    }
    
</script>
        <div id="lugaresTabela22"></div>
<div id="containerTabela">
    <div id="tabelaPlace">
        <div class="titleTable">
            <div id="lugaresTabela"></div>

            <div class="titleLugares"  onclick=''>
                <div id="uimapindicator_selector_tabela" style="float: right; margin-right: 0px;">
                    <div class="divCallOutLugares">
                        <button id="selec_tb_01" type="button" class="blue_button big_bt dropdown selector_popover" data-toggle="dropdown" rel="popover" style="margin-right: 31px !important; font-size: 14px; height: 34px;"></button>
                          <!--<button class="btn btn-primary dropdown selector_popover" data-toggle="dropdown" rel="popover" style="margin-right: 30px;">Selecionar</button>-->
                        </div>
                </div>
                <h5 id="espac_on_table"></h5>
            </div>
            <div class="titleIndices" id="calloutIndicadores">
                
            </div>
            
        </div>
        <div id="localTabelaConsulta"></div>
        <div style="clear: both"></div>
    </div>
        <div class="bottomLugares">
            <button class="gray_button big_bt" id="limparTodasLinhasTabela" onclick="limparTodasLinhasTabela()" type="button" style="font-size: 14px; height: 32px;">Limpar Lugares</button>
        </div>
        <div class="bottomIndices">
            <button class="gray_button big_bt" id="limparTodosIndices" onclick="limparTodosIndices()" type="button" style="margin-left: 30px; font-size: 14px; height: 32px;">Limpar Indicadores</button>
        </div>
    <div id="loadingTabela"></div>
</div>
<script src="com/mobiliti/tabela/builder.tabela.js" type="text/javascript" charset="utf-8"></script>
<script>
  
  
    
    var local2;
    var map_indc2;
    
 
    $(document).ready(function(){
        // $('#lugaresTabela').load('com/mobiliti/componentes/local/local_btn.html',function()
        // {
        //     local2 = new SeletorLocal();
        //     local2.startLocal(listenerLocalTabela,"lugaresTabela",false);

        //     map_indc2 = new LocalSelector();

        //     map_indc2.startSelector(true,"uimapindicator_selector_tabela",map_indcator_selector_tabela,"right");
            
        // });
        
        map_indc2 = new LocalSelector();

        map_indc2.startSelector(true,"uimapindicator_selector_tabela",
        map_indcator_selector_tabela,"right");
    });
    
    
    
    
    function listnerTabelaIndicadores(obj)
    {
        //tabela_build('');
    }
    
   
    function listnerTabelaLocal(e, obj)
    {
        if(e == "changetab" || e == "reloadList"){
            map_indc2.refresh();
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
        Carregando tabela para impressão...
    </div>
</div>
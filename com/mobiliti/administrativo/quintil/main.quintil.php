<?php

include_once("/com/mobiliti/consulta/Consulta.class.php");
include_once("/config/conexao.class.php");

$ocon = new Conexao();

$query = "SELECT id, nomecurto FROM variavel WHERE exibir_na_consulta IS TRUE;" ;
$res = pg_query($ocon->open(), $query) or die("Nao foi possivel executar a consulta!");
?>

<style type="text/css">
    
    .cls_table
    {
        width: 600px;
    }
    
    .ipt_value
    {
         width: 100px;
         height: 100%;
    }

</style>



<script type="text/javascript">
    
  var qindex = -1;
  var qarray;
  var qrqst = null;
  
  $(document).ready(function() 
  {
      $("#ind_sel").change(ind_sel_change);
      $("#btn_quintil").click(btn_quintil_click);
      $("#btn_load_class").click(btn_load_class_click);

      define_msg("");
  });
  
  function ind_sel_change(e)
  {
      //alert(e);
      //nada por enquanto
  }
  
  function btn_quintil_click(e)
  {
     define_msg("");
     var request = build_request_quintil();
     if(request == null)
     {
         define_msg("Atenção! Selecione o indicador, ano e espacialidade para realizar a operação.");
         return 0;
     }
      
     if(request.indicador == "all" || request.indicador == "idh")
     {
         multiple_request(request);
         return 0;
     }
     
     set_status(true);
     $.ajax({
        type: "POST",
        url: "<?php echo $path_dir ?>com/mobiliti/administrativo/quintil/gerador.php",
        data: request,
        success: quintil_response
      });
  }
  
  
  
  
  function quintil_response(data, textStatus, jqXHR)
  {
      if(textStatus === "success")
      {
          var response = $.parseJSON(data);
          define_msg(response.msg); 
      }
      else
      {
            define_msg('Erro!! Impossível executar operação.'); 
      }
      
      set_status(false);
  }
  
  function build_request_quintil()
  {

      var idc = $("#ind_sel").val();
      var ano = $("#ano_sel").val();
      var spc = $("#espc_sel").val();
      
      
      
      if(idc == "none" || ano == "none" || spc == "none")
      {
          return null;
      }
      else
      {
          var request = new Object();
          request.indicador = idc;
          request.ano = ano;
          request.espacialidade = spc;
      }
      return request;
  }
  
 
  function btn_load_class_click(e)
  {
     define_msg("");
     var request = build_request_quintil();
     if(request == null)
     {
         define_msg("Atenção! Selecione o indicador, ano e espacialidade para carregar as classes.");
         return 0;
     }
     
     
     if(request.indicador == "all")
     {
         define_msg("Atenção! Opção todos os indicadores não está disponível para essa operação.");
         return 0;
     }
     

     set_status(true);
     $.ajax({
        type: "POST",
        url: "<?php echo $path_dir ?>com/mobiliti/administrativo/quintil/class_loader.php",
        data: request,
        success: class_loader_response
      });
  }
  
  function class_loader_response(data, textStatus, jqXHR)
  {
      if(textStatus === "success")
      {
          build_table($.parseJSON(data)); 
      }
      else
      {
            define_msg('Erro!! Impossível executar operação.'); 
      }
      
      set_status(false);
  }
  
  
  function set_status(loading)
  {
      if(loading)
      {
          $("#btn_load_class").addClass("disabled");
          $("#btn_quintil").addClass("disabled");
          $("#qntloader").show();  
      }
      else
      {
          $("#btn_load_class").removeClass("disabled");
          $("#btn_quintil").removeClass("disabled");
          $("#qntloader").hide();
      }
  }
  
  function define_msg(msg)
  {
      $("#class_area").hide(); 
      if(msg == "")
          $("#msg_area").hide(); 
      else
          $("#msg_area").show(); 
      $("#msg_area").text(msg); 
  }
  
  
  function multiple_request(rqst)
  {
      qindex = 0;
      qarray = new Array();
      
        
      if(rqst.indicador == "all")
      {
            $("#ind_sel option").each(function( index ) 
            {
              if($(this).val() != "none" && $(this).val() != "all" && $(this).val() != "idh")
              {
                  qarray.push($(this).val());
              }
            });
      }
      else if(rqst.indicador == "idh")
      {
          $("#ind_sel option").each(function( index ) 
          {
              if($(this).val() != "none" && $(this).val() != "all" && $(this).val() != "idh")
              {
                 var v =  $(this).val();
                 
                 if(v != JS_INDICADOR_EDUCACAO && v != JS_INDICADOR_IDH && v != JS_INDICADOR_LONGEVIDADE  && v != JS_INDICADOR_RENDA)
                 {
                    qarray.push(v);
                 }
              }
          });
      }
      
      if(qarray.length > 0)
      {
          set_status(true);
          qrqst = rqst;
          qrqst.indicador = qarray[qindex];
          $.ajax({
             type: "POST",
             url: "<?php echo $path_dir ?>com/mobiliti/administrativo/quintil/gerador.php",
             data: qrqst,
             success: m_quintil_response
          });
      }
      else
      {
          define_msg("Não existe indicadores no banco!");
      }
      
  }
  
  
  function m_quintil_response(data, textStatus, jqXHR)
  {
      if(textStatus === "success")
      {
          var response = $.parseJSON(data);
          define_msg(response.msg + " | Indicador número: " + (qindex + 1) + " de um total de " + qarray.length + " indicadores"); 
      }
      else
      {
            define_msg('Erro!! Impossível executar operação.'); 
      }
      
      qindex++;
      if(qindex < qarray.length)
      {
          qrqst.indicador = qarray[qindex];
          $.ajax({
             type: "POST",
             url: "<?php echo $path_dir ?>com/mobiliti/administrativo/quintil/gerador.php",
             data: qrqst,
             success: m_quintil_response
          });
      }
      else
      {
        set_status(false);  
      }
      
  }
  
  function build_table(data)
  {
      var row = "";
      var html = "<table id=\"table_class_obj\" class=\"cls_table\" border=\"1\">";
      html += "<tr><th>c_id</th><th>cg_id</th><th>cor</th><th>nome</th><th>m&iacute;nimo</th><th>m&aacute;ximo</th></tr>"; 
      
      var flag = false;
      
      $(data).each(function(index, obj) 
      {
          flag = !flag;
          var row = "<tr class=\"row_data\">";
          row += "<td id=\"row_c_id_"+ index +"\" >" + obj.c_id + "</td><td id=\"row_cg_id_"+ index +"\" >" + obj.cg_id + "</td>";
          row += "<td><div id=\"row_color_id_"+ index +"\" class=\"colorSelector\"><div style=\"background-color: " + obj.cor_preenchimento + "\"></div></div></td>";
          row += "<td><input id=\"row_nome_id_"+ index +"\" class=\"ipt_value\" value=\"" + obj.nome + "\" /></td>";
          row += "<td><input id=\"row_min_id_"+ index +"\" class=\"ipt_value\"  value=\"" + obj.minimo + "\" /></td>";
          row += "<td><input id=\"row_max_id_"+ index +"\" class=\"ipt_value\"  value=\"" + obj.maximo + "\" /></td>";

          html += row;
      });
    
      html += "</table>";
      
      if(flag)
      {
          html += "<br/><button id=\"btn_update_class\" class=\"btn\"  style=\"font-size: 14px; height: 34px;\" >Atualizar</button>"; 
          html += "<button id=\"btn_delete_class\" class=\"btn\"  style=\"font-size: 14px; height: 34px;\" >Excluir Quintil</button>"; 
      }
      
      $("#class_area").html(html);
      $("#btn_update_class").click(btn_update_class_click);
      $("#btn_delete_class").click(btn_delete_class_click);
      
      $(data).each(function(index, obj) 
      {
         $('#row_color_id_' + index).ColorPicker({
            color: obj.cor_preenchimento, 
            onShow: function (colpkr) {
                    $(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    $(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    $("#row_color_id_" + index + " div").css('backgroundColor', '#' + hex);
            }
         });
      });
      
      
      $("#class_area").show();
      
      return 0;
  }
  
  
  function btn_update_class_click(e)
  {
      var request = new Object();
      var arr = new Array();
      var rows = $("#table_class_obj tr.row_data"); // skip the header row
      
      
      rows.each(function(index) {
          
            var obj = new Object();
            obj.c_id  = $("#row_c_id_"  + index).text();
            obj.cg_id = $("#row_cg_id_" + index).text();
            obj.color = rgb2hex($("#row_color_id_" + index + " div").css('backgroundColor'));
            obj.min   = $("#row_min_id_" + index).val();
            obj.max   = $("#row_max_id_" + index).val();
            obj.nome  = $("#row_nome_id_" + index).val();
            arr.push(obj);
      });
      
      request.data =  arr;
     
      set_status(true);
      $.ajax({
            type: "POST",
            url: "<?php echo $path_dir ?>com/mobiliti/administrativo/quintil/class_update.php",
            data: request,
            success: update_response
      });
      
  }
  
  function update_response(data, textStatus, jqXHR)
  {
      set_status(false);
      if(textStatus === "success")
      {
          var response = $.parseJSON(data);
          define_msg(response.msg); 
      }
      else
      {
            define_msg('Erro!! Impossível atualizar dados.'); 
      }
      
  }
  
  
    var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

    //Function to convert hex format to a rgb color
    function rgb2hex(rgb) {
     rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
     return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }

    function hex(x) {
      return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
    }
 
 
   function btn_delete_class_click(e)
   {
        define_msg("");
        var request = build_request_quintil();
        if(request == null)
        {
            define_msg("Atenção! Selecione o indicador, ano e espacialidade para carregar as classes.");
            return 0;
        }


        if(request.indicador == "all")
        {
            define_msg("Atenção! Opção todos os indicadores não está disponível para essa operação.");
            return 0;
        }


        set_status(true);
        $.ajax({
           type: "POST",
           url: "<?php echo $path_dir ?>com/mobiliti/administrativo/quintil/class_delete.php",
           data: request,
           success: class_delete_response
         });
      
  }
  
  
  function class_delete_response(data, textStatus, jqXHR)
  {
      set_status(false);
      if(textStatus === "success")
      {
          var response = $.parseJSON(data);
          define_msg(response.msg); 
      }
      else
      {
            define_msg('Erro!! Impossível remover dados.'); 
      }
  }
  
</script>




<div style="position: relative;  width: 100%; min-height: 800px; height: 100%;">
    
<div style="position: relative; height: 70px; width: 100%;">
<label for="ind_sel">Indicador</label>
<select id="ind_sel" style="width: 100%;"> 
   <option  value="none" selected="selected" disabled="disabled">Selecione...</option>
   <option value="all">TODOS</option>
   <option value="idh">TODOS - Exceto IDH</option>
   <?php 
      while ($data = pg_fetch_object($res)) 
      {
   ?>
      <option value="<?php echo $data->id; ?>"><?php echo $data->nomecurto; ?></option>
   <?php
      } 
   ?>
</select>
</div>

<?php 
$query = "SELECT id, label_ano_referencia as ano FROM ano_referencia;";
$res = pg_query($ocon->open(), $query) or die("Nao foi possivel executar a consulta!");
?>

<div style="position: relative; display: block; width: 100%;">
<label for="ano_sel">Ano</label>
<select id="ano_sel">
  <option  value="none" selected="selected" disabled="disabled">Selecione...</option>
  <option value="all">TODOS</option>
  <?php 
    while ($data = pg_fetch_object($res)) 
    {
  ?>
    <option value="<?php echo $data->id; ?>"><?php echo $data->ano; ?></option>
  <?php
    } 
  ?>
</select>
</div>

<div style="position: relative; display: block; width: 100%;">
<label for="espc_sel">Espacialidade</label>
<select id="espc_sel">
  <option  value="none" selected="selected" disabled="disabled">Selecione...</option>
<!--  <option value="all">TODOS</option>-->
  <option value="<?php echo Consulta::$ESP_MUNICIPAL; ?>">MUNICIPAL</option>
  <option value="<?php echo Consulta::$ESP_ESTADUAL; ?>">ESTADUAL</option>
</select>
</div>    
    
    
    
<div style="position: relative; float: left; display: block; width: 100%;">
    <button id="btn_quintil" class="btn"  style="font-size: 14px; height: 34px;" >Gerar Quintil</button> 
    <button id="btn_load_class" class="btn"  style="font-size: 14px; height: 34px;" >Carregar Classe</button> 
</div>

<div style="position: relative; float: left; display: block; height: 100%; width: 100%;">
    
    <div id="msg_area" class="alert" style="position: relative; margin-left: auto; margin-right: auto; top: 50px; width: 600px"> 
    
    </div> 
    
    <div id="class_area" style="position: relative; margin-left: auto; margin-right: auto; top: 50px; width: 600px"> 

    </div>
    
    <img id="qntloader" src="img/map/ajax-loader.gif"  style="display: none; position: absolute; top: 10px; left: 50%; background-color: transparent;" />
</div>
    

</div>

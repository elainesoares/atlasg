<?php
header('Content-Type: text/html; charset=utf-8');
ob_start();
?>


<script type="text/javascript">
    $(document).ready(function() 
    {
        javascript:self.print();
    });
</script>

<style type="text/css"> 
    
  .map-title-print 
  {
    line-height: 23pt;
    position: relative;
    float: left;
    font-size: 30pt;
    font-family: Passion One;
    width: 900px;
    padding: 0px;
    margin: 0px;
    border: 0px;
  }
  
  .data_impressao
  {
     
    position: relative;
    float: right;
    padding: 0px;
    margin: 0px;
    border: 0px;
  }
  
</style>


<hr/>
<span class="map-title-print"><?php echo $_POST["p_ano"]; ?> - <?php echo $_POST["p_indicador"]; ?></span>
<span class="data_impressao"> <?php echo date("d/m/y"); ?> </span>        
<?php if(strlen($_POST["p_indicador"]) > 45){ echo "<br/><br/>"; } ?>
<br/>
<hr/>


<div style="position:relative; top:0px; left:0px; padding: 0px; margin: 0px; border: 0px;">
    <img style="position:relative; left:150px;" src="<?php echo $_POST["p_map"]; ?>" />
    <div style="position:absolute; top:0; left:0;">
         <img style="position:relative; left:150px; <?php if( $_POST["p_selection"] == ""){ echo "display:none;"; } ?>"  src="<?php echo $_POST["p_selection"]; ?>" />
    </div> 
</div>

<br/>

<div style="position: relative; float: left; margin-left: 150px; height: 150px; " >
    <span style="font-weight: bold; margin-left: 5px;">  LEGENDA </span><br/>
    <img src="<?php echo $_POST["p_legend"]; ?>"  /> 
</div>



<div style="position: relative; float: right; margin-right: 150px;" >
<span  style="<?php if( $_POST["p_selection"] == ""){ echo "display:none;"; } ?> position: relative; float: right;" >
    <span style="font-weight: bold;">  <?php echo $_POST["p_title" ]; ?>: </span> 
    <span style="font-weight: bold; color: #00ADEE;">  <?php echo $_POST["p_nome_local"]; ?> </span>
</span> <br/>
<div style="<?php if( $_POST["p_selection"] == ""){ echo "display:none;"; } ?> position: relative; float: right; background-color: white; height: 125px; width: 120px;">
    <table style="position: absolute; left:10px; top: 0px; width: 100px; border:0px; margin:0px; padding: 0px;"> 
        <tbody>
                <tr>
                   <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:left; color:#808080; font-weight: bold;"> 
                        IDHM
                   </td>
                   <td id="miniperfil_idh" style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">
                      <?php echo $_POST["p_value_idh"]; ?>
                   </td>
                 </tr>

                 <tr>
                     <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px;"> 
                         <img style="height: 32px; width: 32px;" src="img/map/idh_longevidade.png" alt="Longevidade" /> 
                     </td>

                     <td id="miniperfil_longevidade" style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">
                         <?php echo $_POST["p_value_longevidade"]; ?>
                     </td>
                 </tr>

                 <tr>
                     <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px;" > 
                          <img style="height: 32px; width: 32px;" src="img/map/idh_renda.png" alt="Renda" /> 
                     </td>

                     <td id="miniperfil_renda" style="border:0; height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">
                         <?php echo $_POST["p_value_renda"]; ?>
                     </td>
                 </tr>

                 <tr>
                     <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px;"> 
                          <img style="height: 32px; width: 32px;" src="img/map/idh_educacao.png" alt="Educação"/> 
                     </td>

                     <td id="miniperfil_educacao" style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">
                         <?php echo $_POST["p_value_educacao"]; ?>
                     </td>
                 </tr>
            </tbody>  
    </table>
</div>
</div>

    
<?php

$title = 'Impressão do Mapa';
$title_print = 'Mapa';
$content = ob_get_contents();
ob_end_clean();
include "web/base.php";
?>


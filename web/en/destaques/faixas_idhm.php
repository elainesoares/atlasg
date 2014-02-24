<div class="destaqueFaixasIDHM">
    <div class="titleDestaqueAzul">MHDI CATEGORIES</div>
    <div class="titleDestaque floatLeft">EVOLUTION OF HUMAN DEVELOPMENT IN THE BRAZILIAN MUNICIPALITIES</div>

    <img src="<?php echo "./img/destaques/".$_SESSION['lang']."/mapas_idhm.png"?>" class="mapas1Faixas">
    <div class="direita1Faixas floatLeft">
        <img src="<?php echo "./img/destaques/".$_SESSION['lang']."/faixas_idh.png"?>" >
        <h4>% of the municipalities by MHDI categories</h4>
        Brazil:<br />
        <img src="<?php echo "./img/destaques/".$_SESSION['lang']."/seta.png"?>" class="setaList"><b>High</b> and <b>Medium</b><br />
        <span class="marginLeft20">74% of the municipalities</span>
        <p>
            <img src="./img/destaques/en/seta.png" class="setaList"><b>Low</b> and <b>Very Low</b><br />
            <span class="marginLeft20">25% of the municipalities</span>
        </p>
        <p>
            <img src="./img/destaques/en/seta.png" class="setaList"><b>Very Low</b><br />
            <span class="marginLeft20">- 1991: 85.8% of the municipalities</span><br />
            <span class="marginLeft20">- 2010: 0.6% of the municipalities</span>
        </p>


        Regions:<br />
        <div class="floatLeft">
            <span class="marginLeft20">South</span><br />
            <img src="./img/destaques/en/seta.png" class="setaList"><b>High </b> (65%)<br />
            <p>
                <span class="marginLeft20">Southeast</span><br />
                <img src="./img/destaques/en/seta.png" class="setaList"><b>High</b> (52%)<br />
            </p>
        </div>
        <div class="floatRight" style="margin-right: 100px;">
            <span class="marginLeft20">Central-West</span><br />
            <img src="./img/destaques/en/seta.png" class="setaList"><b>Medium</b> (57%)<br />
            <p>
                <span class="marginLeft20">North</span><br />
                <img src="./img/destaques/en/seta.png" class="setaList"><b>Medium</b> (50%)<br />
            </p>
        </div>
        <div class="clear"></div>
        <p style="margin-left: 109px;">
            <span style="margin-left: 20px;">Northeast</span><br />
            <img src="./img/destaques/en/seta.png" class="setaList"><b>Low</b> (61%)<br />
        </p>
        <p style="margin-top: 30px">
            <span>South, Southeast and Central-West:</span><br />
            <img src="./img/destaques/en/seta.png" class="setaList">None of the municipalities is in the <b>Very Low</b> category<br />
        </p>
        <p>
            <span>North and Northeast:</span><br />
            <img src="./img/destaques/en/seta.png" class="setaList">None of the municipalities is in the <b>Very High</b> category<br />
        </p>
    </div>

    <div class="floatRight" style="width: 468px;">
        <img src='./img/destaques/en/mapa2010.png'>
        <img src='./img/destaques/en/tabela_idh.png'>
    </div>
    <div class="clear"></div>

    <div class="floatLeft marginTop20" style="width: 380px;">
        <img src='./img/destaques/en/tabela_municipio_idhm.png'>
        <img src='./img/destaques/en/circulos_coloridos.png' class="marginTop30">
    </div>
    <div class="floatRight mapa2Faixas marginTop20" >
        <img src='./img/destaques/en/mapa2_idhm.png'>
    </div>
    <div class='clear'></div>
    
    <div class="marginAuto marginTop30 textoCentralFaixa">
        <li>The difference between the highest and lowest municipal MHDIs in the 
            country increased from 0.577, in 1991, to 0.612, in 2000 but, receded to 0,444 in 2010.
        </li><br />
        <li>In the last two decades, the largest reduction in the difference between 
            the highest and the lowest performance between the MHDI components of 
            the municipalities was observed in MHDI Longevity, where the difference 
            decreased from 0.377 in 1991 to 0.222 in 2010.
        </li>
    </div>
    
    <div class="marginTop40 quadroAzulFaixas">
        <h4>Federative Units (FUs) in 2010</h4>
        <div class="floatLeft" style='width: 400px;'>
            <p>
                - <b>Federal District</b> (Distrito Federal) has the highest MHDI (0,824),
                the only FU with <b>Very High Human Development</b>.
            </p>
            <p>
                - <b>São Paulo</b> (0.783) and <b>Santa Catarina</b> (0.774) have the second-highest 
                MHDI values and are located in the High Human Development category.
            </p>
            <p>
                - The <b>Federal District</b> also has the highest MHDI Income (0.863), 
                in MHDI Education (0.742) and MHDI Longevity (0.873) in the country.
            </p>
        </div>
        <div class="floatRight marginTop20" style='width: 423px;'>
            <p>
                - <b>Alagoas</b> (0.631) and <b>Maranhão</b> (0.639) are the FUs with the lowest MHDI values in the country.
            </p>
            <p>
                - The difference between the highest and the lowest MHDI among the 
                FUs fell from 0.259, in 1991, to 0.254, in 2000, and reached 0.193 in 2010.
            </p>
        </div>
            
    </div>
</div>

<?php
    $title2 = $lang_mng->getString("destaques_titleAbaFaixasIDHM");
    $meta_title2 = $lang_mng->getString("destaques_metaTitleFaixasIDHM");
    $meta_description2 = $lang_mng->getString("destaques_metaDescricaoFaixasIDHM");
?>
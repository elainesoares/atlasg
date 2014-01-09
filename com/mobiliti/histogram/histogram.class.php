<?php
    class Histogram{
        private $idIndicador;
        private $idAno;
        private $espacialidade;
        private $sqlValorVariaveis;
        private $arrayConsulta;
        private $conexao;
        private $xmax;
        private $xmin;
        private $quantidade;
        private $quantidadeClasse;
        private $amplitudeTotal;
        private $amplitudeDaClasse;
        private $media;
        private $mediana;
        private $desvioPadrao;
        private $assimetria;
        private $curtose;
        
        
        /**
        name: construct
        desc: seta os valores de idIndicador, idAno e espacialidade

        arg: $indicador
        desc: id do indicador

        arg: $ano
        desc: id do ano

        arg: $espacialidade
        desc: valor da espacialidade
        **/
        public function __construct($indicador, $ano, $espacialidade, $con) {
            $this->idIndicador = $indicador;
            $this->idAno = $ano;
            $this->espacialidade = $espacialidade;
            $this->conexao = $con;
        }
        
        
        /**
        name: selectByRegions
        desc: seleciona as variáveis referentes ás regiões

        arg: $regions
        desc: array com os ids regiões

        arg: $in
        desc: id do indicador

        arg: $an
        desc: id do ano
        **/
        public function selectByRegions($regions, $in, $an) { //regiao, indicador e ano
            if(sizeof($regions) <= 0)return 0;

            foreach ($regions as $value) {
                $regions_to_search = $regions_to_search . $value . ",";
            }
            $regions_to_search = substr($regions_to_search, 0, -1);
            $sql = "";
            
            if ($this->espacialidade == Consulta::$ESP_REGIONAL){
                $sql = "SELECT r.id, v.valor 
                        FROM regiao r 
                        INNER JOIN valor_variavel_regiao v 
                        ON r.id = v.fk_regiao 
                        WHERE fk_regiao in($regions_to_search) AND v.fk_ano_referencia = $an AND v.fk_variavel = $in ";
            }
            else if($this->espacialidade == Consulta::$ESP_ESTADUAL){
                $sql = "SELECT e.id, v.valor 
                        FROM estado e 
                        INNER JOIN valor_variavel_estado v on e.id = v.fk_estado 
                        INNER JOIN regiao r 
                        ON r.id = e.fk_regiao 
                        WHERE fk_regiao in($regions_to_search) AND v.fk_ano_referencia = $an AND v.fk_variavel = $in ";              
            }
            else if ($this->espacialidade == Consulta::$ESP_MUNICIPAL) {
                $sql = "SELECT m.id, v.valor 
                        FROM municipio m 
                        INNER JOIN valor_variavel_mun v 
                        ON m.id = v.fk_municipio 
                        INNER JOIN estado e 
                        ON e.id = m.fk_estado 
                        INNER JOIN regiao r 
                        ON r.id = e.fk_regiao 
                        WHERE e.fk_regiao in($regions_to_search) AND v.fk_ano_referencia = $an AND v.fk_variavel = $in ";              
            }
           $this->sqlValorVariaveis = $sql;
        }
        
        public function selectByStates($states, $in, $an) {
            if(sizeof($states) <= 0)return 0;
            
            foreach ($states as $value) {
                $states_to_search = $states_to_search . $value . ",";
            }
            
            $states_to_search = substr($states_to_search, 0, -1);

            $sql = "";

            if($this->espacialidade == Consulta::$ESP_ESTADUAL){
                $sql = "SELECT e.id, v.valor 
                        FROM estado e 
                        INNER JOIN valor_variavel_estado v 
                        ON e.id = v.fk_estado 
                        WHERE fk_estado in ($states_to_search) AND v.fk_ano_referencia = $an AND v.fk_variavel = $in ";              
            }
            if($this->espacialidade == Consulta::$ESP_MUNICIPAL) {            
                $sql = "SELECT m.id, v.valor 
                        FROM municipio m 
                        INNER JOIN valor_variavel_mun v 
                        ON m.id = v.fk_municipio 
                        INNER JOIN estado e 
                        ON e.id =  m.fk_estado 
                        WHERE fk_estado in ($states_to_search) AND v.fk_ano_referencia = $an AND v.fk_variavel = $in ";              
            }
            $this->sqlValorVariaveis = $sql;
        }
        
     public function selectByCities($cities, $in, $an) {
        foreach ($cities as $value) {
            $cities_to_search = $cities_to_search . $value . ",";
        }
        $cities_to_search = substr($cities_to_search, 0, -1);

        $sql = "";
        
        if ($this->espacialidade == Consulta::$ESP_MUNICIPAL) {            
            $sql = "SELECT m.id, v.valor 
                    FROM municipio m 
                    INNER JOIN valor_variavel_mun v 
                    ON m.id = v.fk_municipio 
                    WHERE fk_municipio in ($cities_to_search) AND v.fk_ano_referencia = $an AND v.fk_variavel = $in ";              
        }
        $this->sqlValorVariaveis = $sql;

    }
        
        //============================================================//
        //Faz a consulta passando para a variavel ArrayConsulta
	//$ConexaoLink: Recebe a vari�vel de conex�o ao banco de dados
        //============================================================//
        public function ConsultarSQL($con){
            $link = $con->open();
            $resultado = pg_query($link, $this->sqlValorVariaveis);
            $row = pg_fetch_row($resultado, null, PGSQL_ASSOC);
            while ($linha = pg_fetch_array($resultado)){
                $linha['id'];
                $this->arrayConsulta[] = ($linha[0] != "") ? $linha[0] : 0;
            }
            sort($this->arrayConsulta);
        }
        
        //============================================================//
        //Calcular a quantidade de classes por Sturges
        //============================================================//
        public function kSturges(){
            if($this->quantidade > 600){
                $this->quantidadeClasse = 35; 
            }
            elseif($this->quantidade > 100){
                $this->quantidadeClasse = 21; 
            }
            elseif($this->quantidade > 20){
                $this->quantidadeClasse = 11; 
            }
            elseif($this->Quantidade > 10){
                $this->quantidadeClasse = 5; 
            }
            else{
                $this->quantidadeClasse = round(1 + 3.3 * log($this->quantidade,10));
            }
        } 
        
        //============================================================//
        //Calcular a amplitude do conjunto de dados
        //============================================================//
        public function lAmplitude(){
            $this->amplitudeTotal = $this->xmax - $this->xmin;
        }
        
        //============================================================//
        //Calcular a amplitude(largura) da classe
        //============================================================//
        public function hAmplitude(){
            $this->amplitudeDaClasse = $this->amplitudeTotal/$this->quantidadeClasse;
        }
        
        public function getFunctions($con){
            $this->ConsultarSQL($con);
            $iN = count($this->arrayConsulta);
            $this->xmax = $this->arrayConsulta[$iN - 1];
            $this->xmin = $this->arrayConsulta[0];
            $this->quantidade = $iN;
            //$this->fNomeVariavel($con);
            //$this->fLabelAno($ConexaoLink);
            $this->kSturges();
            $this->lAmplitude();
            $this->hAmplitude();
        }

        //============================================================//
        //Calcular a amplitude do conjunto de dados
        //============================================================//
        public function mMedia($arrColuna,$arrLinha){
            $x = 0;
            foreach($this->arrayConsulta as $key=>$val){
                $x += $this->arrayConsulta[$key];
            }
            
            $this->media = $x / $this->quantidade;
            
            $x = 0;
            
            foreach($arrColuna as $key=>$val){
                if($arrLinha[$key] == 0)
                    continue;
                $k = $arrColuna[$key] - $this->media;
                $x += bcpow($k,2,3);
            }
            $this->desvioPadrao = sqrt($x/$this->quantidade);
            
            $q = count($this->arrayConsulta);
            
            if($q % 2 == 0){
                $meio = ($q / 2);
                $meio2 = $meio+1;
                $this->mediana = ($this->arrayConsulta[$meio] + $this->arrayConsulta[$meio2])/2;
            }
            else{
                $this->mediana = $this->arrayConsulta[($q + 1)/2];
            }
            
            $moda = 3 *($this->media - $this->mediana);
            $this->assimetria = $moda/$this->desvioPadrao;
            $fac = array();
            $a = 0;
            foreach($arrLinha as $key=>$val){
                $a += $arrLinha[$key];
                $fac[] = $a;
            }
            $e1 = ($this->quantidade/4);
            $e1_V = 0;
            for($x = 0; $x < count($fac); $x++){
                if($fac[$x] >= $e1){
                    $e1_V = $x;
                    break;
                }
            }
            $f = $this->amplitudeDaClasse;
            $somatorio = $e1;
            $somatorioAnterior = isset($fac[$e1_V - 1]) ? $fac[$e1_V - 1] : 0;
            $frequenciaSimples = $arrLinha[$e1_V];
            $numDaColuna = str_replace(",", ".", $arrColuna[$e1_V]);
            $limiteInferio =  $numDaColuna  - ($this->amplitudeDaClasse/2);
            $q1 = $limiteInferio + (($somatorio - $somatorioAnterior)*$f)/$frequenciaSimples;
            $e1 = ((3*$this->quantidade)/4);
            $e1_V = 0;
            for($x = 0; $x < count($fac); $x++){
                if($fac[$x] >= $e1){
                    $e1_V = $x;
                    break;
                }
            }
            $somatorio = $e1;
            $somatorioAnterior = isset($fac[$e1_V - 1]) ? $fac[$e1_V - 1] : 0;
            $frequenciaSimples = $arrLinha[$e1_V];
            $numDaColuna = str_replace(",", ".", $arrColuna[$e1_V]);
            $limiteInferio =  $numDaColuna  - ($this->amplitudeDaClasse/2);
            $q3 = $limiteInferio + (($somatorio - $somatorioAnterior)*$f)/$frequenciaSimples;
            
            $e1 = ((10*$this->quantidade)/100);
            $e1_V = 0;
            for($x = 0; $x < count($fac); $x++){
                if($fac[$x] >= $e1){
                    $e1_V = $x;
                    break;
                }
            }
            $somatorio = $e1;
            $somatorioAnterior = isset($fac[$e1_V - 1]) ? $fac[$e1_V - 1] : 0;
            $frequenciaSimples = $arrLinha[$e1_V];
            $numDaColuna = str_replace(",", ".", $arrColuna[$e1_V]);
            $limiteInferio =  $numDaColuna  - ($this->amplitudeDaClasse/2);
            $p10 = $limiteInferio + (($somatorio - $somatorioAnterior)*$f)/$frequenciaSimples;
            
            $e1 = ((90*$this->quantidade)/100);
            $e1_V = 0;
            for($x = 0; $x < count($fac); $x++){
                if($fac[$x] >= $e1){
                    $e1_V = $x;
                    break;
                }
            }
            $somatorio = $e1;
            $somatorioAnterior = isset($fac[$e1_V - 1]) ? $fac[$e1_V - 1] : 0;
            $frequenciaSimples = $arrLinha[$e1_V];
            $numDaColuna = str_replace(",", ".", $arrColuna[$e1_V]);
            
            $limiteInferio =  $numDaColuna  - ($this->amplitudeDaClasse/2);
            $p90 = $limiteInferio + (($somatorio - $somatorioAnterior)*$f)/$frequenciaSimples;
            if($p90 - $p10 != 0)
                $c = ($q3 - $q1)/(2*($p90 - $p10));
            else
                $c = "-";
            
            $this->curtose = $c;
        }
        
        public function ImprimirBtn(){
            $url = str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]);
            $url[strlen($url)-1] != "/" ? $url[strlen($url)] = "/": "";
        }

        
        //============================================================//
        //Pegar o nome da regi�o
        //============================================================//
        public function getNomeArea($Area,$ID = 0,$ConexaoLink = null/*,$Estado = false*/){
            if(!$Estado){
                switch ($Area){
                    case 'MR':
                        $tabela = "microregiao";
                        $this->NomeAreaPesquisa = 'Todos os munic�pios da microregi�o ';
                        break;
                    case 'ES':
                        $tabela = "estado";
                        $this->NomeAreaPesquisa = 'Todos os munic�pios do estado ';
                        break;
                    case 'RE':
                        $tabela = "regiao";
                        $this->NomeAreaPesquisa = 'Todos os munic�pios da regi�o ';
                        break;
                    case 'AL':
                        $this->NomeAreaPesquisa = "Todos os munic�pios do Brasil";
                        return;
                        break;
                }
					
                $SQL = "SELECT nome 
                        FROM $tabela
                        WHERE id = {$ID} LIMIT 1";
                $Resultado = pg_query($ConexaoLink, $SQL);
                if ($linha = pg_fetch_array($Resultado)){
                    $this->NomeAreaPesquisa = $this->NomeAreaPesquisa . $linha[0];
                }
            }
            else{
                switch ($Area){
                    case 'RE':
                        $tabela = "regiao";
                        $this->NomeAreaPesquisa = 'Todos os Estados da regi�o ';
                        break;
                    case 'AL':
                        $this->NomeAreaPesquisa = "Todos os Estados do Brasil";
                        return;
                        break;
                }
                $SQL = "SELECT nome 
                        FROM $tabela
                        WHERE id = {$ID} LIMIT 1";
                $Resultado = pg_query($ConexaoLink, $SQL);
                if ($linha = pg_fetch_array($Resultado)){
                    $this->NomeAreaPesquisa = $this->NomeAreaPesquisa . $linha[0];
                }
            }
    }

        //============================================================//
        //Densenha a tablea
        //============================================================//
        /*public function DrawTabela()
        {
            echo "<table class='data'  width='500px'>
                    <thead id='relatorio_thead'>
                        <tr>
                            <th rowspan='' style='width:90px;text-align:center'>Observa��es</th>
                            <th colspan='' style='width:90px;text-align:center'>M�nimo</th>
                            <th colspan='' style='width:90px;text-align:center'>Mediana</th>
                            <th colspan='' style='width:90px;text-align:center'>M�ximo</th>
                            <th colspan='' style='width:90px;text-align:center'>Amplitude</th>
                            <th colspan='' style='width:90px;text-align:center'>M�dia</th>
                            <th colspan='' style='width:90px;text-align:center'>Desvio Padr�o</th>
                            <th colspan='' style='width:90px;text-align:center'>Assimetria</th>
                            <th colspan='' style='width:90px;text-align:center'>Curtose</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style='text-align:center;width:90px;'>{$this->Quantidade}</td>
                            <td style='text-align:center;width:90px;'>{$this->Xmin}</td>
                            <td style='text-align:center;width:90px;'>{$this->Mediana}</td>
                            <td style='text-align:center;width:90px;'>{$this->Xmax}</td>
                            <td style='text-align:center;width:90px;'>{$this->AmplitudeTotal}</td>
                            <td style='text-align:center;width:90px;'>".number_format($this->Media,3,',','')."</td>
                            <td style='text-align:center;width:90px;'>".number_format($this->DesvioPadrao,2,',','')."</td>
                            <td style='text-align:center;width:90px;'>".number_format($this->Assimetria,2,',','')."</td>
                            <td style='text-align:center;width:90px;'>".number_format($this->Curtose,2,',','')."</td>   
                        </tr>
                    </tbody>
                  </table><br />";
        }*/

        //============================================================//
        //Gera o sql da consulta do estado
        //============================================================//
        /*public function GerarSQLQuantidadeBarrasEstado($Local,$Valor = 0)
        {
            switch ($Local)
            {
                case 'RE':
                    $SQL = "SELECT valor FROM valor_variavel_estado
                            INNER JOIN estado ON (estado.id = valor_variavel_estado.fk_estado) and (estado.fk_regiao = $Valor)
                            WHERE 
                                    fk_variavel = ".$this->IDVariavel." AND
                                    fk_ano_referencia = ".$this->IDAno;
                break;
                case 'AL':
                    $SQL = "SELECT valor FROM valor_variavel_estado
                            INNER JOIN estado ON (estado.id = valor_variavel_estado.fk_estado)
                            WHERE 
                                    fk_variavel = ".$this->IDVariavel." AND
                                    fk_ano_referencia = ".$this->IDAno;
                break;
            }
            $this->SQLValorVariaveis = $SQL;
        }*/
        
    //============================================================//
    //Pega o nome curto da vari�vel da pesquisa
    //$ConexaoLink: Recebe a vari�vel de conex�o ao banco de dados
    //============================================================//
    public function fNomeVariavel($con){
       // echo 'Indicador: '.$this->idIndicador.'<br />';
        $sql = "SELECT nomelongo 
                FROM variavel
                WHERE id = {$this->idIndicador} 
                LIMIT 1";
        //echo 'sql: '.$sql.'<br />';
        $resultado = pg_query($con, $sql);
        if ($linha = pg_fetch_array($resultado)){
	$this->nomeVariavel = $linha['nomelongo'];
	//echo 'Nome Variavel: '.$this->nomeVariavel.'<br />';
        }
    }
    //============================================================//
    //Busca a Label do ano
    //$ConexaoLink: Recebe a vari�vel de conex�o ao banco de dados
    //============================================================//
    public function fLabelAno($ConexaoLink){
        $SQL = "SELECT label_ano_referencia 
                FROM ano_referencia
                WHERE id = {$this->IDAno} 
                LIMIT 1";
        $Resultado = pg_query($ConexaoLink, $SQL);
        if ($linha = pg_fetch_array($Resultado))
            $this->LabelAno = $linha[0];
    }

    //============================================================//
    //Desenha o histograma, se n�o definir o elemento ele desenha 
    //onde chamar o metodo.
    //============================================================//
    public function DrawHistograma($ElementoDestinoDoDesenho = null){
        is_null($ElementoDestinoDoDesenho) ? $nomeElemento = "chart_div" : $nomeElemento = $ElementoDestinoDoDesenho;
        $tempArray = $this->arrayConsulta;
        $pontoMedio = $this->xmin;
        $inicialBarra = $this->xmin;
        for($i = 0; $i < $this->quantidadeClasse;$i++){
            $pontoMedio += $this->amplitudeDaClasse / 2;
            $printMedia[] = number_format($pontoMedio,3,",","");
            $pontoMedio += $this->amplitudeDaClasse / 2;
            $contador = 0;
            foreach($tempArray as $key=>$val){
                if($tempArray[$key] >= $inicialBarra  && $tempArray[$key] < $pontoMedio){
                    $contador++;
                }
                if($tempArray[$key] > $pontoMedio)
                    break;
            }
            $printCidadesa[] = $contador;
            $inicialBarra += $this->amplitudeDaClasse;
        }
        $diff = $this->quantidade - array_sum($printCidadesa);
        $printCidadesa[count($printCidadesa)-1]+=$diff;
        if(empty($printCidadesa)){
            //echo "<br><br><p id='relatorio_caption'>Nenhum registro foi encontrado para esta consulta.</p>";
            return;
        }
        $this->mMedia($printMedia,$printCidadesa);
        $data2 = array();
        foreach($printMedia as $key=>$val){
	$amplitude[] = $printMedia[$key];
	$valor[] = $printCidadesa[$key];
        }
        $data2['amplitude'] = $amplitude;
        $data2['valor'] = $valor;
        $data3['options'] =  array(
                            /*'title' => "",*/
                            'hAxis' => array(
                                       /*'title' => 'Histograma',*/
                                       'legend' => array('position' => 'top'),
                                       'titleTextStyle' => array(
                                                            'color' => 'black',
                                                            'minTextSpacing' => 70
					),
			),
                            'legend' => array(
                                            'position' => 'none'
                                        ),
                            'bar' => array('groupWidth' => '90%'),
                            'chartArea' => array('width' => 900,
                                                'heigth' => 200)
		);
			
        $data3['ne'] = $nomeElemento;
        //echo $this->nomeVariavel;
        $data3['nomeVariavel'] = $this->nomeVariavel;
        $qtd = count($printMedia);
        $data3['qtdPrintMedia'] = $qtd;
        return ($data2+$data3);
    }
        
        
        //============================================================//
        //da print nas op��es 
        //============================================================//
        /*public function PritOptions($Sufixo)
        {
            $Title = "{$this->NomeVariavel} ({$this->LabelAno}) - {$this->NomeAreaPesquisa}";
            is_null($ElementoDestinoDoDesenho) ? $NomeElemento = "chart_div" : $NomeElemento = $ElementoDestinoDoDesenho;
            
            $TempArray = $this->ArrayConsulta;
            $PontoMedio = $this->Xmin;
            $InicialBarra = $this->Xmin;
            //die(var_dump($TempArray));
            for($i = 0; $i < $this->QuantidadeClasse;$i++)
            {
                $PontoMedio += $this->AmplitudeDaClasse / 2;
                $PrintMedia[] = number_format($PontoMedio,2,",","");
                $PontoMedio += $this->AmplitudeDaClasse / 2;
                $Contador = 0;
                foreach($TempArray as $key=>$val)
                {
                    if($TempArray[$key] >= $InicialBarra  && $TempArray[$key] < $PontoMedio)
                    {
                        $Contador++;
                    }
                    if($TempArray[$key] > $PontoMedio)
                        break;
                }
                $PrintCidadesa[] = $Contador;
                $InicialBarra += $this->AmplitudeDaClasse;
            }
            $diff = $this->Quantidade - array_sum($PrintCidadesa);
            $PrintCidadesa[count($PrintCidadesa)-1]+=$diff;
            if(empty($PrintCidadesa))
            {
                echo "<br><br><p id='relatorio_caption'>Nenhum registro foi encontrado para esta consulta.</p>";
                return;
            }
            $Retorno .= "var data$Sufixo = google.visualization.arrayToDataTable([";
                $Retorno .= "\r\n";
            $Retorno .= "['Amplitude', '{$this->NomeVariavel}']";
                $Retorno .= "\r\n";
            foreach($PrintMedia as $key=>$val)
            {
                $Retorno .= ",['{$PrintMedia[$key]}', {$PrintCidadesa[$key]}]";
                $Retorno .= "\r\n";
            }
            $Retorno .= "]);var options$Sufixo = {";
                $Retorno .= "\r\n";
            $Retorno .= "    title: '$Title',";
                $Retorno .= "\r\n";
            $Retorno .= "    hAxis: {title: 'Histograma', titleTextStyle: {color: 'black'},minTextSpacing : 70}, legend:{position: 'none'},bar : {groupWidth:'70%'},
                chartArea : {width:900}
                
    ";
                $Retorno .= "\r\n";
            $Retorno .= "  };
                var chart = new google.visualization.ColumnChart(document.getElementById('char1'));
               chart.draw(data$Sufixo, options$Sufixo);";
            echo $Retorno;
        }*/
        
    }
?>
<?php
	class Tabela_URL
	{
            public static function leURL($url)
            {
                    $arrayEsp = array();
                    $url = explode("/",$url);
                    for($i = 0; $i < count($url); $i++)
                            {
                                    if($url[$i] == 'filtro')
                                    {

                                            $arrayEsp.array_push($arrayEsp, $url[$i-1]);
                                    }
                            }
                    var_dump($arrayEsp);
                    return $arrayEsp;
            }
	}

	class ConsultaTabela
	{
		private $espacialidade;

		private $filtros;

		public function __construct($espacialidade,$filtros) {
                    $this->espacialidade =  $espacialidade;  
                    $this->filtros = $filtros;   
                }
	}

	class Filtro
	{
	    private $tipo;
	    private $valores;

	    public function __construct($tipo,$valores) {
         	$this->tipo =  $tipo;  
         	$this->valores = $valores;   
            }
	}

	class Valor
	{
            private $nome;
	    private $valor;

	    public function __construct($nome,$valor) {
         	$this->nome =  $nome;  
         	$this->valor = $valor;   
            }
	}
?>
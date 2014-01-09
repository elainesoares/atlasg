<?php
$comPath = BASE_ROOT . "/com/mobiliti//";
require_once BASE_ROOT . 'config/config_path.php';
require_once $comPath . "consulta/bd.class.php";
require_once $comPath . "util/protect_sql_injection.php";
require_once $comPath . "display/Block.class.php";
require_once $comPath . "display/BlockTabela.class.php";
define("PATH_DIRETORIO", $path_dir);

/**
 * Description of Perfil
 *
 * @author Lorran, 
 *   Andre Castro (versão 2)
 */
class PerfilPrint extends bd {

    private $UrlNome;
    private $UrlUf;
    private $nome;
    private $uf;
    private $ufCru;
    private $id;
    private $estado;
    private $nomeCru;
    private $data = array();
    private $locale;

    public function __construct($municipio) {
        parent::__construct();

        if ($municipio == null || $municipio == "") {
            
        }

        $divisao = explode('_', $municipio);
        $this->nomeCru = $divisao[0];
        $this->ufCru = $divisao[1] ;
        $stringTratada = cidade_anti_sql_injection(str_replace('-', ' ', $divisao[0]));
        $stringUfTratada = cidade_anti_sql_injection(str_replace('-', ' ', $divisao[1]));
        $this->UrlNome = $stringTratada;
        $this->UrlUf = $stringUfTratada;
        $this->read();
    }
    public function drawNome() {
        echo "<div class='perfil-title-print'><hr>Perfil do Município de<br><br> 
            " . $this->nome . ", " . strtoupper($this->uf) . "
            <img id='uiperfilloader' src='img/map/ajax-loader.gif' background-color: transparent;' />
            
            <hr></div>
            <div align='right' style='margin-top:-12%;'>".date("d/m/Y")." - Pág 1 de 14</div>";
    }

    public function drawMap() {
        echo '<div class="perfil-map-div"><div id="mapaPerfil"></div></div>';
    }

    public function drawArrows() {
        ?>
        <div class="pArrowLeft"></div>
        <div class="pArrowRight"></div>
        <?php
    }

    public function __destruct() {
        parent::__destruct();
    }

   public function drawBoxes() {
        TextBuilder::$bd = new bd();

        $carac_mun = PerfilPrint::getCaracteristicas(TextBuilder::$idMunicipio); //IDHM
        $pop = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESOTOT"); //IDHM_R
        $micro_meso = PerfilPrint::getMicroMeso(TextBuilder::$idMunicipio, "PESOTOT"); //IDHM_R
        $idhm = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM"); //IDHM_R

        $tabela = new BlockTabela("Caracterização do território", 2, 4);
        //$tabela->setManual("link", $path_dir."atlas/tabela/nulo/mapa/municipal/filtro/municipio/{$this->nomeCru}/indicador/idhm-2010");
        $tabela->addBox("Área", str_replace(".",",",$carac_mun[0]["area"]). " km²");
        $tabela->addBox("IDHM 2010", str_replace(".",",",number_format($idhm[2]["valor"], 3)));
        $tabela->addBox("Faixa do IDHM", TextBuilder::getSituacaoIDH($idhm[2]["valor"]));
        $tabela->addBox("População (Censo 2010)", $pop[2]["valor"] . " hab.");

        $tabela->addBox("Densidade demográfica", str_replace(".",",",$carac_mun[0]["densidade"]) . " hab/km²");
        $tabela->addBox("Ano de instalação", $carac_mun[0]["anoinst"]);
        $tabela->addBox("Microrregião", $micro_meso[0]["micro"]);
        $tabela->addBox("Mesorregião", $micro_meso[0]["meso"]);
        $tabela->draw();
    }

    static function getCaracteristicas($municipio) {

        $SQL = "SELECT altitude, anoinst, densidade, area, distancia_capital
                     FROM municipio
                     WHERE municipio.id = $municipio";

        return TextBuilder::$bd->ExecutarSQL($SQL, "getCaracteristicas");
    }

    static function getMicroMeso($municipio) {

        $SQL = "SELECT microrregiao.nome as micro, mesorregiao.nome as meso FROM municipio
                    INNER JOIN microrregiao ON fk_microregiao = microrregiao.id
                    INNER JOIN mesorregiao ON fk_mesorregiao = mesorregiao.id
                    WHERE municipio.id  = $municipio";

        return TextBuilder::$bd->ExecutarSQL($SQL, "getMicroMeso");
    }

    private function read() {
        $SQL = "SELECT municipio.nome, uf, municipio.id, estado.nome as nomeestado, (ST_AsGeoJSON(municipio.the_geom)) as locale FROM municipio 
                    INNER JOIN estado ON (municipio.fk_estado = estado.id)
                     WHERE sem_acento(municipio.nome) ILIKE '{$this->UrlNome}' AND (uf ILIKE '{$this->ufCru}' OR sem_acento(estado.nome) ILIKE '%{$this->UrlUf}%') LIMIT 1";

        $results = parent::ExecutarSQL($SQL);
        $this->nome = $results[0]["nome"];
        $this->uf = $results[0]["uf"];
        $this->id = $results[0]["id"];
        $this->estado = $results[0]["nomeestado"];
        $this->locale = $results[0]["locale"];
    }

    public function getCityId() {
        return $this->id;
    }

    public function getCityName() {
        return $this->nome;
    }
    
    public function getUfName() {
        return $this->uf;
    }

    public function drawMenu() {
        ?>
        <div class="pmainMenuTop">
            <!--            <div class="pmainMenuTopCenter">-->
            <ul class="pmainMenuTopUl">
                <li><a class="perfilMenu" io-pos="0" io="caracterizacao" >CARACTERIZAÇÃO</a></li>
                <li><a class="perfilMenu" io-pos="1" io="idh" >IDH</a></li>
                <li><a class="perfilMenu" io-pos="2" io="demografia" >DEMOGRAFIA</a></li>
                <li><a class="perfilMenu" io-pos="3" io="educacao" >EDUCAÇÃO</a></li>
                <li><a class="perfilMenu" io-pos="4" io="renda" >RENDA</a></li>
                <li><a class="perfilMenu" io-pos="5" io="trabalho" >TRABALHO</a></li>
                <li><a class="perfilMenu" io-pos="6" io="habitacao" >HABITAÇÃO</a></li>
                <li><a class="perfilMenu" io-pos="7" io="vulnerabilidade" >VULNERABILIDADE</a></li>                        
            </ul>
            <!--            </div>-->
        </div>
        <?php
    }

    public function drawScriptsMaps() {
        ?>
        <script type="text/javascript">
            var map;
            currentFeature_or_Features = null;

            var pol_style = {
                strokeColor: "#FF0000",
                strokeOpacity: 0.75,
                strokeWeight: 0.5,
                fillColor: "#FF0000",
                fillOpacity: 0.30
            };

            //aqui se fosse fazer a bolinha de capital
            var capital_style = {
                icon: "img/capital_icon.png"
            };

            var infowindow = new google.maps.InfoWindow();

            function init() {

                map = new google.maps.Map(document.getElementById('mapaPerfil'), {
                    zoom: 5,
                    center: new google.maps.LatLng(-20, -50),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                ///////////////////////////
                // Segundo argumento recebe um estilo, se precisar
                showFeature(city_ex, pol_style);
                ///////////////////////////
            }
            function clearMap() {
                if (!currentFeature_or_Features)
                    return;
                if (currentFeature_or_Features.length) {
                    for (var i = 0; i < currentFeature_or_Features.length; i++) {
                        if (currentFeature_or_Features[i].length) {
                            for (var j = 0; j < currentFeature_or_Features[i].length; j++) {
                                set_map_of(currentFeature_or_Features[i][j], true);
                            }
                        }
                        else {
                            set_map_of(currentFeature_or_Features[i], true);
                        }
                    }
                } else {
                    set_map_of(currentFeature_or_Features, true);
                }
                if (infowindow.getMap()) {
                    infowindow.close();
                }
            }
            google.maps.Polygon.prototype.my_getBounds = function() {
                var bounds = new google.maps.LatLngBounds()
                this.getPath().forEach(function(element, index) {
                    bounds.extend(element)
                })
                return bounds
            }
            function set_map_of(object, remove) {
                object.setMap(remove ? null : map);
                if (!remove) {
                    map.fitBounds(object.my_getBounds());
                }
                ;
            }
            function showFeature(geojson, style) {
                clearMap();
                currentFeature_or_Features = new GeoJSON(geojson, style || null);

                if (currentFeature_or_Features.type && currentFeature_or_Features.type == "Error") {
                    return false; //Aqui temos um erro!
                }
                if (currentFeature_or_Features.length) {
                    for (var i = 0; i < currentFeature_or_Features.length; i++) {
                        if (currentFeature_or_Features[i].length) {
                            for (var j = 0; j < currentFeature_or_Features[i].length; j++) {
                                set_map_of(currentFeature_or_Features[i][j]);
                                if (currentFeature_or_Features[i][j].geojsonProperties) {
                                    setInfoWindow(currentFeature_or_Features[i][j]);
                                }
                            }
                        }
                        else {
                            set_map_of(currentFeature_or_Features[i])
                        }
                    }
                } else {
                    set_map_of(currentFeature_or_Features);
                    if (currentFeature_or_Features.geojsonProperties) {
                        setInfoWindow(currentFeature_or_Features);
                    }
                }
            }

            $(document).ready(function() {
                init();
            });

            google.maps.event.addListener(map, 'load', function() {
                alert("garregou");
            });
        </script>
    <?php
    }

    public function drawScripts() {
        ?>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript">
            var baseUrl = "<?php echo $path_dir . "perfil_print/" ?>";
            var storedName = "";

            $(document).ready(function() {
                inputHandler.add($('#perfil_search'), 'andreteste', 2, false, getNomeMunUF);
            });

            function getNomeMunUF(nome) {
                storedName = nome;
            }

            function buscaPerfil() {
                RedirectSearch(storedName);
            }

            function RedirectSearch(nome) {
                window.location = baseUrl + nome;
            }
        </script>

        <script type="text/javascript">
            var city_ex = {"type": "Feature", "properties": {"name": "<?php echo $this->nome; ?>", "uf": "<?php echo $this->uf; ?>"}, "geometry":<?php echo $this->locale; ?>};
            var url = document.URL;
            var mPage = 0;
            var storedPages = new RegExp();

            //var bUrl = "</?php echo PATH_DIRETORIO . "perfil_print/{$this->nomeCru}_{$this->ufCru}" ?>";
            
            function perfil_loading(status)
            {
                if(status)
                    $("#uiperfilloader").show();
                else
                    $("#uiperfilloader").hide();
            }
            
            function _getUrl() {
            
            perfil_loading(true);
            
                iPage = 0;
                mPage = iPage;

                if (typeof storedPages[mPage] != "undefined") {
                    $("#MainContentPerfil").html(storedPages[mPage]);
                    return;
                }
                $.ajax({
                    type: 'post',
                    data: {page: iPage, city: "<?php echo $this->nomeCru . "_" . $this->ufCru; ?>"},
                    url: "com/mobiliti/display/controller/AjaxPaginaPerfilPrint.php",
                    success: function(r) {
                        storedPages[mPage] = r;
                        $("#MainContentPerfil").html(r);
                        
                        perfil_loading(false);
                    }
                });
            }
//            $(document).ready(function() {
//                splited = document.URL.split("</?php echo $this->nomeCru; ?>/");
//                $("[io='" + splited[1] + "']").parent("li").addClass("pmainMenuTopActive");
//                $(".perfilMenu").click(function() {
//                    history.pushState("", "Atlas Fase 3 ", bUrl + "/" + $(this).attr("io"));
//                    _getUrl();
//                })
//
//                $(".pArrowLeft").click(function() {
//                    if (mPage > 0) {
//                        mPage--;
//                        mUrl = bUrl + "/" + $("[io-pos='" + mPage + "']").attr("io");
//                        history.pushState("", "Atlas Fase 3 ", mUrl);
//                        _getUrl();
//                    }
//                });
//                $(".pArrowRight").click(function() {
//                    if (mPage < 7) {
//                        mPage++;
//                        mUrl = bUrl + "/" + $("[io-pos='" + mPage + "']").attr("io");
//                        history.pushState("", "Atlas Fase 3 ", mUrl);
//                        _getUrl();
//                    }
//                });
//            });
            _getUrl();


        </script>
        <?php
    }

}
?>

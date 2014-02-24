<?php
$comPath = BASE_ROOT . "/com/mobiliti//";
require_once BASE_ROOT . 'config/config_path.php';
require_once BASE_ROOT . 'config/config_gerais.php';
require_once $comPath . "consulta/bd.class.php";
require_once $comPath . "util/protect_sql_injection.php";
require_once $comPath . "display/Block.class.php";
require_once $comPath . "display/BlockTabela.class.php";

require_once $comPath . "display/controller/Formulas.class.php";

//require_once MOBILITI_PACKAGE . "display/controller/PerfilBuilder.class.php";

define("PATH_DIRETORIO", $path_dir);

/**
 * Description of Perfil
 *
 * @author Andre Castro (versão 2)
 */
class Perfil extends bd {

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

    
    public function getNomeCru(){
        return $this->nome . ", " . strtoupper($this->uf);
    }
    
    public function __construct($municipio) {
        parent::__construct();
        if ($municipio == null || $municipio == "") {
            
        }

        $divisao = explode('_', $this->retira_acentos($municipio));
        $this->nomeCru = $divisao[0];
        $stringTratada = cidade_anti_sql_injection(str_replace('-', ' ', $divisao[0]));
        $this->UrlNome = $stringTratada;

        if (sizeof($divisao) > 1) {
            $this->ufCru = $divisao[1];
            $stringUfTratada = cidade_anti_sql_injection(str_replace('-', ' ', $divisao[1]));
            $this->UrlUf = $stringUfTratada;
        }

        $this->read();
    }

    private function retira_acentos($texto) {
        $array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
            , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
        $array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
            , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C");
        return str_replace($array1, $array2, $texto);
    }

    public function drawNome() {
        if ($this->nome != null)
        //echo "<div class='perfil-title'>" . mb_convert_case($this->nome, MB_CASE_TITLE, "UTF-8") . ", " . strtoupper($this->uf) . "
            echo "<div class='perfil-title'>" . $this->nome . ", " . strtoupper($this->uf) . "
            <img id='uiperfilloader' src='img/map/ajax-loader.gif' background-color: transparent;' />
        </div>";
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

    public function drawBoxes($lang) {

        TextBuilder::$bd = new bd();
        TextBuilder_EN::$bd = new bd();
        TextBuilder_ES::$bd = new bd();

        $carac_mun = Perfil::getCaracteristicas(TextBuilder::$idMunicipio); //IDHM
        $pop = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "PESOTOT"); //IDHM_R
        $micro_meso = Perfil::getMicroMeso(TextBuilder::$idMunicipio, "PESOTOT"); //IDHM_R
        $idhm = TextBuilder::getVariaveis_table(TextBuilder::$idMunicipio, "IDHM"); //IDHM_R
        
        if ($lang == "pt"){
            $tabela = new BlockTabela("Caracterização do território", 2, 4);
            //$tabela->setManual("link", $path_dir."atlas/tabela/nulo/mapa/municipal/filtro/municipio/{$this->nomeCru}/indicador/idhm-2010");
            $tabela->addBox("Área", str_replace(".", ",", $carac_mun[0]["area"]) . " km²");
            $tabela->addBox("IDHM 2010", str_replace(".", ",", number_format($idhm[2]["valor"], 3)));
            $tabela->addBox("Faixa do IDHM", Formulas::getSituacaoIDH($idhm, $lang));
            $tabela->addBox("População (Censo 2010)", $pop[2]["valor"] . " hab.");

            $tabela->addBox("Densidade demográfica", str_replace(".", ",", $carac_mun[0]["densidade"]) . " hab/km²");
            $tabela->addBox("Ano de instalação", $carac_mun[0]["anoinst"]);
            $tabela->addBox("Microrregião", $micro_meso[0]["micro"]);
            $tabela->addBox("Mesorregião", $micro_meso[0]["meso"]);
        }
        else if ($lang == "en"){
            $tabela = new BlockTabela("Characterization of the territory", 2, 4);
            //$tabela->setManual("link", $path_dir."atlas/tabela/nulo/mapa/municipal/filtro/municipio/{$this->nomeCru}/indicador/idhm-2010");
            $tabela->addBox("Area", str_replace(".", ",", $carac_mun[0]["area"]) . " km²");
            $tabela->addBox("MHDI 2010", str_replace(".", ",", number_format($idhm[2]["valor"], 3)));
            $tabela->addBox("MHDI category", Formulas::getSituacaoIDH($idhm, $lang));
            $tabela->addBox("Population (Census of 2000)", $pop[2]["valor"] . " Inhabitants");

            $tabela->addBox("Population density", str_replace(".", ",", $carac_mun[0]["densidade"]) . " inhabitants/km²");
            $tabela->addBox("Year of Establishment", $carac_mun[0]["anoinst"]);
            $tabela->addBox("Microregion", $micro_meso[0]["micro"]);
            $tabela->addBox("Mesoregion", $micro_meso[0]["meso"]);
        }else if ($lang == "es"){
            $tabela = new BlockTabela("Caracterización del territorio", 2, 4);
            //$tabela->setManual("link", $path_dir."atlas/tabela/nulo/mapa/municipal/filtro/municipio/{$this->nomeCru}/indicador/idhm-2010");
            $tabela->addBox("Area", str_replace(".", ",", $carac_mun[0]["area"]) . " km²");
            $tabela->addBox("IDHM 2010", str_replace(".", ",", number_format($idhm[2]["valor"], 3)));
            $tabela->addBox("Nivel de IDHM", Formulas::getSituacaoIDH($idhm, $lang));
            $tabela->addBox("Población (censo 2010)", $pop[2]["valor"] . " hab.");

            $tabela->addBox("Densidad demográfica", str_replace(".", ",", $carac_mun[0]["densidade"]) . " hab/km²");
            $tabela->addBox("Año de fundación", $carac_mun[0]["anoinst"]);
            $tabela->addBox("Microrregión", $micro_meso[0]["micro"]);
            $tabela->addBox("Mesorregión", $micro_meso[0]["meso"]);
        }
        
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
                    WHERE sem_acento(municipio.nome) ILIKE '{$this->UrlNome}' AND (uf ILIKE '{$this->ufCru}' OR sem_acento(estado.nome) ILIKE '{$this->UrlUf}') LIMIT 1";
        $results = parent::ExecutarSQL($SQL);

        if (sizeof($results) > 0) {
            $this->nome = $results[0]["nome"];
            $this->uf = $results[0]["uf"];
            $this->id = $results[0]["id"];
            $this->estado = $results[0]["nomeestado"];
            $this->locale = $results[0]["locale"];
        }
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
        if ($this->nome != null) {
            ?>
            <div class="pmainMenuTop">
                <!--                    <div class="pmainMenuTopCenter">-->
                <ul class="pmainMenuTopUl">
                    <script type="text/javascript">
                        if (lang_mng.getString('lang_id') == "pt"){
                            document.write("<li><a href='"+document.URL+"#caracterizacao' class='perfilMenu' io-pos='0' io='caracterizacao'>CARACTERIZAÇÃO</a></li>");
                            document.write("<li><a href='"+document.URL+"#idh' class='perfilMenu' io-pos='1' io='idh'>IDH</a></li>");
                            document.write("<li><a href='"+document.URL+"#demografia' class='perfilMenu' io-pos='2' io='demografia'>DEMOGRAFIA</a></li>");
                            document.write("<li><a href='"+document.URL+"#educacao' class='perfilMenu' io-pos='3' io='educacao'>EDUCAÇÃO</a></li>");
                            document.write("<li><a href='"+document.URL+"#renda' class='perfilMenu' io-pos='4' io='renda'>RENDA</a></li>");
                            document.write("<li><a href='"+document.URL+"#trabalho' class='perfilMenu' io-pos='5' io='trabalho' >TRABALHO</a></li>");
                            document.write("<li><a href='"+document.URL+"#habitacao' class='perfilMenu' io-pos='6' io='habitacao' >HABITAÇÃO</a></li>");
                            document.write("<li><a href='"+document.URL+"#vulnerabilidade' class='perfilMenu' io-pos='7' io='vulnerabilidade' >VULNERABILIDADE</a></li>");                
                        }
                        else if (lang_mng.getString('lang_id') == "en"){
                            document.write("<li><a href='"+document.URL+"#caracterizacao' class='perfilMenu' io-pos='0' io='caracterizacao'>CHARACTERIZATION</a></li>");
                            document.write("<li><a href='"+document.URL+"#idh' class='perfilMenu' io-pos='1' io='idh'>MHDI</a></li>");
                            document.write("<li><a href='"+document.URL+"#demografia' class='perfilMenu' io-pos='2' io='demografia'>DEMOGRAPHY</a></li>");
                            document.write("<li><a href='"+document.URL+"#educacao' class='perfilMenu' io-pos='3' io='educacao'>EDUCATION</a></li>");
                            document.write("<li><a href='"+document.URL+"#renda' class='perfilMenu' io-pos='4' io='renda'>INCOME</a></li>");
                            document.write("<li><a href='"+document.URL+"#trabalho' class='perfilMenu' io-pos='5' io='trabalho' >LABOUR</a></li>");
                            document.write("<li><a href='"+document.URL+"#habitacao' class='perfilMenu' io-pos='6' io='habitacao' >HOUSING</a></li>");
                            document.write("<li><a href='"+document.URL+"#vulnerabilidade' class='perfilMenu' io-pos='7' io='vulnerabilidade' >VULNERABILITY</a></li>");                   
                        }
                        else if (lang_mng.getString('lang_id') == "es"){
                            document.write("<li><a href='"+document.URL+"#caracterizacao' class='perfilMenu' io-pos='0' io='caracterizacao'>CARACTERIZACIÓN</a></li>");
                            document.write("<li><a href='"+document.URL+"#idh' class='perfilMenu' io-pos='1' io='idh'>IDH</a></li>");
                            document.write("<li><a href='"+document.URL+"#demografia' class='perfilMenu' io-pos='2' io='demografia'>DEMOGRAFÍA</a></li>");
                            document.write("<li><a href='"+document.URL+"#educacao' class='perfilMenu' io-pos='3' io='educacao'>EDUCACIÓN</a></li>");
                            document.write("<li><a href='"+document.URL+"#renda' class='perfilMenu' io-pos='4' io='renda'>INGRESOS</a></li>");
                            document.write("<li><a href='"+document.URL+"#trabalho' class='perfilMenu' io-pos='5' io='trabalho' >TRABAJO</a></li>");
                            document.write("<li><a href='"+document.URL+"#habitacao' class='perfilMenu' io-pos='6' io='habitacao' >VIVIENDA</a></li>");
                            document.write("<li><a href='"+document.URL+"#vulnerabilidade' class='perfilMenu' io-pos='7' io='vulnerabilidade' >VULNERABILIDAD</a></li>");                   
                        }
                        </script>
                </ul>
                <!--                    </div>-->
            </div>
            <?php
        }
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
                var bounds = new google.maps.LatLngBounds();
                this.getPath().forEach(function(element, index) {
                    bounds.extend(element)
                });
                return bounds;
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
        </script>
        <?php
    }

    public function drawScripts() {
        ?>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=pt"></script>
        <script type="text/javascript">
             
            var lang = '<?=$_SESSION["lang"]?>';
            //var baseUrl = "</?php //echo PATH_DIRETORIO . "perfil/" ?>";
            //var baseUrl = "</?php echo PATH_DIRETORIO; ?>" + lang + "/perfil_m/";
            var baseUrl = "<?php echo PATH_DIRETORIO; ?>" + lang + "/perfil/";
            var storedName = "";
                    
            
            splited = document.URL.split(lang_mng.getString("lang_id")+"/perfil/");
            //splited = document.URL.split(lang_mng.getString("lang_id")+"/perfil_m/");

            $(document).ready(function() {
                inputHandler.add($('#perfil_search'), 'buscaPerfil', 2, "", false, getNomeMunUF);
            });

            function getNomeMunUF(nome) {
                nome = retira_acentos(nome);
                storedName = nome;
                buscaPerfil()
            }

            function buscaPerfil() {
                if ($("#buscaPerfil").attr("i") != 0){
                    RedirectSearch(storedName);
                }
                else if(storedName == ""){
                    document.getElementById('erroBusca').style.display= "block";
                }
                    
                document.getElementById('teste2').style.height = "370px";
                document.getElementById('perfil-title').style.padding.top = "98px";
                //alert("Selecione um município para continuar");
            }

            function RedirectSearch(nome) {
                window.location = baseUrl + retira_acentos(nome);
            }
            
            if (splited[1] === ""){
                $(document).ready(function() {

                    //I'm not doing anything else, so just leave
                    if (!navigator.geolocation)
                        return;
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        geocoder = new google.maps.Geocoder();
                        var latlng = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
                            
                        geocoder.geocode({'latLng': latlng}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                //Check result 0
                                var result = results[0];
                                //look for locality tag and administrative_area_level_1
                                var city = "";
                                var state = "";
                                for (var i = 0, len = result.address_components.length; i < len; i++) {
                                    var ac = result.address_components[i];
                                    if (ac.types.indexOf("locality") >= 0)
                                        city = ac.long_name;
                                    if (ac.types.indexOf("administrative_area_level_1") >= 0)
                                        state = ac.long_name;
                                }
                                //only report if we got Good Stuff
                                
                                if (city != '' && state != '') {
                                    var city_t = city.replace(" ", "-");
                                    var uf_t = state.replace(" ", "-");
                                    window.location = baseUrl + retira_acentos(city_t) + "_" + uf_t;
                                }
                            }
                        });

                    });
                })
            }

        </script>
        <script type="text/javascript">
            var city_ex = {"type": "Feature", "properties": {"name": "<?php echo $this->nome; ?>", "uf": "<?php echo $this->uf; ?>"}, "geometry":<?php echo $this->locale; ?>};
            //var url = document.URL;
            var mPage = 0;
            var storedPages = new RegExp();

            // var bUrl = "</?php echo PATH_DIRETORIO . "perfil/{$this->nomeCru}_{$this->ufCru}" ?>";
            function perfil_loading(status)
            {
                if(status)
                    $("#uiperfilloader").show();
                else
                    $("#uiperfilloader").hide();
            }

            function _getUrl() {      
                perfil_loading(true);
                //iPage = 0;
                       
                $.ajax({
                    type: 'post',
                    //data: {page: iPage, lang: lang_mng.getString('lang_id') ,city: "<\\?php echo $this->nomeCru . "_" . $this->ufCru; ?>"},
                    data: {lang: lang_mng.getString('lang_id'), city: "<?php echo $this->nomeCru . "_" . $this->ufCru; ?>"},
                    url: "com/mobiliti/display/controller/AjaxPaginaPerfil.php",
                    success: function(r) {
                        perfil_loading(false);             
                        storedPages[mPage] = r;
                        $("#MainContentPerfil").html(r);
                    }
                });
            }
            
            _getUrl();
        </script>
        <?php
    }

}
?>

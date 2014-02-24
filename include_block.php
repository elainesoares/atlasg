
            <script>

                $(document).ready(function()
                {
                    $('#imgTab1').tooltip({html:true});
                    $('#imgTab2').tooltip({html:true});
                    $('#imgTab6').tooltip({html:true, delay: 500});
                    $('#form1').tooltip({html:true, delay: 500});
                                            
                    $('#btnPrintMap').tooltip({html:true, delay: 500});
                    $('#btnPrintMap').hide();
                                            
                    limites = new LimiteTabela();
                    geral = new Geral(readyGo);
                                            
                    setTimeout(function(){
                        readyGo();
                        consulta();
                    },100);
                });
            </script>
            <div id="content">
                <div class="containerPage">
                    <div class="containerTitlePage">
                        <div class="titlePage">
                            <div class="titletopPage">Consulta</div>
                            <div class="iconAtlas">
                                <img src="./img/icons/table_gray.png" class="buttonDesabilitado">
                                <img src="./img/icons/brazil_gray.png" class="buttonDesabilitado">
                            </div>
                        </div>
                    </div>
                    <div id="alertTabela"></div>
                </div>

                <?php require_once 'web/download_navegadores.php'; ?>
            </div>

            <?php
            $title = "Consulta";
            $content = ob_get_contents();
            ob_end_clean();
            include "web/base.php";
            die();
            ?>
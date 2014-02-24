<div class="div-admin-1">
    <a class="btn btn-danger" onclick="limpar_cache()"><i class="icon-exclamation-sign icon-white"></i> Limpar Cache</a>
    <?php
    $files = scandir(MOBILITI_PACKAGE . "preconsultas/consultas");
    foreach ($files as $key => $v) {
        if ($key < 2)
            continue;
        echo "<div class='in-div-admin-1'>$v</div>";
    }
    ?>
</div>
<script>
    function limpar_cache(){
        if(confirm('Realmente deseja apagar todos arquivos?'))
            if(confirm('Sério mesmo?'))
                if(confirm('Apaga não moço! Continuar?'))
                    location.href='./admin/limpar_cache'
    }
</script>
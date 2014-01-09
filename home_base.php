<?php 
    ob_start(); 
    
    require_once "web/home.php";
    $title = 'Início';
    $meta_title = 'Atlas do Desenvolvimento Humano no Brasil 2013';
    $meta_description = 'O Atlas do Desenvolvimento Humano no Brasil 2013 é um banco de dados eletrônico feito com o objetivo de democratizar o acesso e aumentar a capacidade de análise sobre informações socioeconômicas relevantes dos municípios brasileiros e das Unidades da Federação. Baseado nos microdados dos censos de 1991 e de 2000 do IBGE (Fundação Instituto Brasileiro de Geografia e Estatística), este sistema disponibiliza informações sobre o Índice de Desenvolvimento Humano Municipal (IDH-M) e 124 outros indicadores georreferenciados de população, educação, habitação, longevidade, renda, desigualdade social e características físicas do território. Com uma navegação simples e auto-explicável, o Atlas permite ao usuário criar seus próprios instrumentos de análise sobre diversas dimensões do desenvolvimento humano, através de mapas temáticos, tabelas, gráficos, relatórios, ordenamento (rankings) de municípios e estados, e ferramentas estatísticas. Os resultados podem ser impressos ou exportados para serem trabalhados em outros programas, como planilhas eletrônicas, por exemplo.';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>

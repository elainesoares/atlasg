function number_format( number, decimals, dec_point, thousands_sep ) {
    // %        nota 1: Para 1000.55 retorna com precisão 1 no FF/Opera é 1,000.5, mas no IE é 1,000.6
    // *     exemplo 1: number_format(1234.56);
    // *     retorno 1: '1,235'
    // *     exemplo 2: number_format(1234.56, 2, ',', ' ');
    // *     retorno 2: '1 234,56'
    // *     exemplo 3: number_format(1234.5678, 2, '.', '');
    // *     retorno 3: '1234.57'
    // *     exemplo 4: number_format(67, 2, ',', '.');
    // *     retorno 4: '67,00'
    // *     exemplo 5: number_format(1000);
    // *     retorno 5: '1,000'
    // *     exemplo 6: number_format(67.311, 2);
    // *     retorno 6: '67.31'
 
    var n = number, prec = decimals;
    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep == "undefined") ? ',' : thousands_sep;
    var dec = (typeof dec_point == "undefined") ? '.' : dec_point;
 
    var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;
 
    var abs = Math.abs(n).toFixed(prec);
    var _, i;
 
    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;
 
        _[0] = s.slice(0,i + (n < 0)) +
              _[0].slice(i).replace(/(\d{3})/g, sep+'$1');
 
        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }
 
    return s;
}
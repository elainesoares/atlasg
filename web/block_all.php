<?php

$useragent = $_SERVER['HTTP_USER_AGENT'];

if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'IE';
} elseif (preg_match('|Opera/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'Opera';
} elseif (preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'Firefox';
} elseif (preg_match('|Chrome/([0-9\.]+)|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'Chrome';
} elseif (preg_match('|Safari/([0-9\.]+)|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'Safari';
} else {
    // browser not recognized!
    $browser_version = 0;
    $browser = 'other';
}
$version = $browser_version;
if (strpbrk($browser_version, ".")) {
    $sp = explode('.', $browser_version);
    $version = $sp[0];
}
foreach ($NavegadoresBloqueadosGERAL as $key => $v) {
    if ($browser == $key) {
        if ($v >= (int) $version) {
            include BASE_ROOT . 'web/comp_bloqueio.php';
            include("footer_print.php");
            die();
        }
    }
}
?>

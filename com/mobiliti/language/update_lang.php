<?php

session_start();


$_SESSION["lang"]  = $lang;

header("Location: {$path_dir}");

?>

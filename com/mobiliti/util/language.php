<?php


function home_has_lang($lang)
{
    $langs = explode("|", HOME_HAS_LANG);
    foreach ($langs as $value) 
    {
        if($value === $lang) return true;
    }
    return false;
}

function atlas_has_lang($lang)
{
    $langs = explode("|", ATLAS_HAS_LANG);
    foreach ($langs as $value) 
    {
        if($value === $lang) return true;
    }
    return false;
}

function destaque_has_lang($lang)
{
    $langs = explode("|", DESTAQUE_HAS_LANG);
    foreach ($langs as $value) 
    {
        if($value === $lang) return true;
    }
    return false;
}


function perfil_has_lang($lang)
{
    $langs = explode("|", PERFIL_HAS_LANG);
    foreach ($langs as $value) 
    {
        if($value === $lang) return true;
    }
    return false;
}

function arvore_has_lang($lang)
{
    $langs = explode("|", ARVORE_HAS_LANG);
    foreach ($langs as $value) 
    {
        if($value === $lang) return true;
    }
    return false;
}


function ranking_has_lang($lang)
{
    $langs = explode("|", RANKING_HAS_LANG);
    foreach ($langs as $value) 
    {
        if($value === $lang) return true;
    }
    return false;
}

function download_has_lang($lang)
{
    $langs = explode("|", DONWLOAD_HAS_LANG);
    foreach ($langs as $value) 
    {
        if($value === $lang) return true;
    }
    return false;
}

function consulta_has_lang($lang)
{
    $langs = explode("|", CONSULTA_HAS_LANG);
    foreach ($langs as $value) 
    {
        if($value === $lang) return true;
    }
    return false;
}

function buscaPerfil_has_lang($lang)
{
    $langs = explode("|", CONSULTA_HAS_LANG);
    foreach ($langs as $value) 
    {
        if($value === $lang) return true;
    }
    return false;
}


?>

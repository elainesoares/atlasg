/*!
 * HDI Tree Onload Functions
 *
 * Copyright (c) 2011 Alexander J. G. Simoes
 * Licensed under the MIT license:
 * (http://www.opensource.org/licenses/mit-license.php)
 *
 */

// Set Globals
var paper;
var paper_h = 425;
var paper_w = 850;

$(document).ready(function() {
    paper = setup("viz", paper_w, paper_h);
    refresh();

    // Build Trees based on selections
    $("select").change(dados);
    //$("select").change(refresh);
    $("input").change(refresh);
})
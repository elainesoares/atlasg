var da = new Array(8)
var da2 = new Array(8)
var da3 = new Array(8)
var ano1aux;
var ano2aux;
var estado1aux;
var estado2aux;
var municipio1aux;
var municipio2aux;
var show_shadow1 = 0;
var show_shadow2 = 0;

function setup(element, width, height){
    return Raphael(document.getElementById(element), width, height);
}

function setshow_shadow1(show_shadow1_, show_shadow2_){
    show_shadow1 = show_shadow1_;
    show_shadow2 = show_shadow2_;
}

function dados(){
    if(municipio1!=0){
        if(ano1!=ano1aux || municipio1!=municipio1aux){
            loading(true);
	ano1aux = ano1;
	municipio1aux = municipio1;
            if(espacialidade1 == 'municipio'){
                $.post("hdi/consulta.php",{ano:ano1,municipio:municipio1, espac: 'municipio'},function(valor){
                    da = valor.split("|");
                    refresh();
                })
            }
            else if(espacialidade1 == 'estado'){
                $.post("hdi/consulta.php",{ano:ano1,municipio:municipio1, espac: 'estado'},function(valor){
                    da = valor.split("|");
                    refresh();
                })
            }
        }
    }
    if(municipio2!=0){
        if(ano2!=ano2aux || municipio2!=municipio2aux){
	loading(false);
	ano2aux = ano2;
	municipio2aux = municipio2;
            if(espacialidade2 == 'municipio'){
                $.post("hdi/consulta.php",{ano:ano2,municipio:municipio2, espac: 'municipio'},function(valor){
                    da2 = valor.split("|");
                    refresh();
                })
            }
            else if(espacialidade2 == 'estado'){
                $.post("hdi/consulta.php",{ano:ano2,municipio:municipio2, espac: 'estado'},function(valor){
                    da2 = valor.split("|");
                    refresh();
                })
            }
        }
    }
}


function refresh(){
//    if(show_shadow1 == 0)
//        show_shadow1 = $('input[name="show_shadow1"]').is(":checked");
//    else if(show_shadow2 == 0)
//        show_shadow2 = $('input[name="show_shadow2"]').is(":checked");
    
    var show_labels1 = $('input[name="show_labels1"]').is(":checked");
    var show_labels2 = $('input[name="show_labels2"]').is(":checked");
    var show_labels3 = $('input[name="show_labels2"]').is(":checked");
    var show_outlines1 = $('input[name="show_outlines1"]').is(":checked");
    var show_outlines2 = $('input[name="show_outlines2"]').is(":checked");
    var show_outlines3 = $('input[name="show_outlines2"]').is(":checked");
    var show_shadow3 = $('input[name="show_shadow2"]').is(":checked");
    var show_data1 = $('input[name="show_data1"]').is(":checked");
    var show_data2 = $('input[name="show_data2"]').is(":checked");
    var show_data3 = $('input[name="show_data2"]').is(":checked");
    var show_labels1 = true;
    var show_labels2 = true;
    var show_labels3 = true;

    var data = {"left":{}, "right":{}, "mid":{}}
    if(da[1]){
        data.left = {
	"income": da[7] * 100,
	"health": da[5] * 100,
	"edu": da[3] * 100,
	"hdi": da[1] * 100,
        }
    } else {
        data.left = null;
    }
    
    
	
    if(da2[1]){
        data.right = {
	"income": da2[7] * 100,
	"health": da2[5] * 100,
	"edu": da2[3] * 100,
	"hdi": da2[1] * 100,
        }
    } else {
        data.right = null;
    }

    if(da3[1]){
        data.mid = {
	"income": da3[7] * 100,
	"health": da3[5] * 100,
	"edu": da3[3] * 100,
	"hdi": da3[1] * 100,
        }
    } else {
        data.mid = null;
    }

    paper.clear();

    draw_scale_lines();

    if(data["left"]){
        var indicies = get_index_order(data["left"]);
        if(show_shadow1 == 'true'){
	var shadow_indicies = get_index_order(data["left"]);
            for(var i = 0; i < shadow_indicies.length; i++){
                shadow_indicies[i].value = 100;
                if(shadow_indicies[i].secondary_branches){
                    for(var j = 0; j < shadow_indicies[i].secondary_branches.length; j++){
		shadow_indicies[i].secondary_branches[j].value = 50;
                    }
                }
	}
	build_tree({"hdi":100, "indicies":shadow_indicies, "left":true, "show_outlines":show_outlines1, "show_labels":show_labels1, "opacity": 0.35, "mid":false});
        }
        build_tree({"hdi":data["left"].hdi, "indicies":indicies, "left":true, "show_outlines":show_outlines1, "show_labels":show_labels1, "mid":false});
        set_table({"hdi":data["left"], "left":true});
        table_hdi({'income': data.left.income, 'health': data.left.health, 'edu': data.left.edu, 'hdi': data.left.hdi, 'pos': 'left'});
    }
    else {
        show_no_data(true);
        set_table({"left": true});
    }
		
    if(data["right"]){
		var indicies = get_index_order(data["right"]);
		if(show_shadow2 == 'true'){
			var shadow_indicies = get_index_order(data["right"]);
			for(var i = 0; i < shadow_indicies.length; i++){
				shadow_indicies[i].value = 100;
				if(shadow_indicies[i].secondary_branches){
					for(var j = 0; j < shadow_indicies[i].secondary_branches.length; j++){
						shadow_indicies[i].secondary_branches[j].value = 50;
					}
				}
			}
			build_tree({"hdi":100, "indicies":shadow_indicies, "left":false, "show_outlines":show_outlines2, "show_labels":show_labels2, "opacity": 0.35, "mid":false});
		}
		
		build_tree({"hdi":data["right"].hdi, "indicies":indicies, "left":false, "show_outlines":show_outlines2, "show_labels":show_labels2, "mid":false});
		set_table({"hdi":data["right"], "left":false});
                        table_hdi({'income': data.right.income, 'health': data.right.health, 'edu': data.right.edu, 'hdi': data.right.hdi, 'pos': 'right'});
	} else {
		show_no_data(false);
		set_table({"left":false});
	}

      
//	if(show_data1){
//		$(".tree_selection.l table").show();
//	} else {
//		$(".tree_selection.l table").hide();
//	}
//	if(show_data2){
//		$(".tree_selection.r table").show();
//	} else {
//		$(".tree_selection.r table").hide();
//	}
//	if(show_data3){
//		//$(".tree_selection.r table").show();
//	} else {
//		//$(".tree_selection.r table").hide();
//	}
	
}

function getshow_shadow1(){
    return show_shadow1;
}

function getshow_shadow2(){
    return show_shadow2;
}

function table_hdi(option){
    var data2 ={'left':{}, 'right':{}};
    if(option.pos == 'left'){
        data2.left = {
            'income': option.income/100,
            'health': option.health/100,
            'edu': option.edu/100,
            'hdi': option.hdi/100
        }
        data2.left.income = data2.left.income.toFixed(3);
        data2.left.health = data2.left.health.toFixed(3);
        data2.left.edu = data2.left.edu.toFixed(3);
        data2.left.hdi = data2.left.hdi.toFixed(3);
          $('.table_city1').html("<table class='city1' style='margin-bottom: 40px;'>"+
            "<tr class='tr_titulo' style='border-bottom: 1px solid #000'>"+
                "<td style='width: 253px; padding: 5px; font-weight: bold;' id='arvore_idhmComponentes'></td>"+
                "<td style='width: 100px; padding: 5px; font-weight: bold;' id='arvore_valores'></td>"+
            "</tr>"+
            "<tr class='tr_titulo' style='border-bottom: 1px solid #ccc'>"+
                "<td style='width: 100px; padding: 5px; font-weight: bold;' id='arvore_idhm'></td>"+
                "<td style='width: 100px; padding: 5px;'>"+ data2.left.hdi+"</td>"+
            "</tr>"+
            "<tr class='tr_titulo' style='border-bottom: 1px solid #ccc'>"+
                "<td style='width: 100px; padding: 5px; font-weight: bold; padding-right: 85px;' id='arvore_idhmRenda'></td>"+
                "<td style='width: 100px; padding: 5px;'>"+data2.left.income+"</td>"+
             "</tr>"+
             "<tr class='tr_titulo' style='border-bottom: 1px solid #ccc'>"+
                "<td style='width: 100px; padding: 5px; font-weight: bold;' id='arvore_idhmLongevidade'></td>"+
                "<td style='width: 100px; padding: 5px;'>"+data2.left.health+"</td>"+
             "</tr>"+
             "<tr class='tr_titulo' style='border-bottom: 1px solid #ccc'>"+
                "<td style='width: 100px; padding: 5px; font-weight: bold;' id='arvore_idhmEducacao'></td>"+
                "<td style='width: 100px; padding: 5px;'>"+data2.left.edu+"</td>"+
             "</tr>"+
          "</table>");
		  
		$("#arvore_idhmComponentes").html(lang_mng.getString("arvore_idhmComponentes"));
        $("#arvore_idhm").html(lang_mng.getString("arvore_idhm"));
        $("#arvore_idhmRenda").html(lang_mng.getString("arvore_idhmRenda"));
        $("#arvore_idhmLongevidade").html(lang_mng.getString("arvore_idhmLongevidade"));
        $("#arvore_idhmEducacao").html(lang_mng.getString("arvore_idhmEducacao"));
        $("#arvore_valores").html(lang_mng.getString("arvore_valores"));
    }
    if(option.pos == 'right'){
        data2.right = {
            'income': option.income/100,
            'health': option.health/100,
            'edu': option.edu/100,
            'hdi': option.hdi/100
        }
        data2.right.income = data2.right.income.toFixed(3);
        data2.right.health = data2.right.health.toFixed(3);
        data2.right.edu = data2.right.edu.toFixed(3);
        data2.right.hdi = data2.right.hdi.toFixed(3);
        
        $('.table_city2').html("<table class='city2' style='margin-bottom: 40px; width: 356px; margin-top: -59px;'>"+
            "<tr class='tr_titulo' style='border-bottom: 1px solid #000'>"+
                "<td style='width: 253px; padding: 5px; font-weight: bold;' id='arvore_idhmComponentes2'></td>"+
                "<td style='width: 100px; padding: 5px; font-weight: bold;' id='arvore_valores2'></td>"+
            "</tr>"+
            "<tr class='tr_titulo' style='border-bottom: 1px solid #ccc'>"+
                "<td style='width: 100px; padding: 5px; font-weight: bold;' id='arvore_idhm2'></td>"+
                "<td style='width: 100px; padding: 5px;' >"+ data2.right.hdi+"</td>"+
            "</tr>"+
            "<tr class='tr_titulo' style='border-bottom: 1px solid #ccc'>"+
                "<td style='width: 0px; padding: 5px; font-weight: bold; padding-right: 135px;' id='arvore_idhmRenda2'></td>"+
                "<td style='width: 100px; padding: 5px;'>"+data2.right.income+"</td>"+
             "</tr>"+
             "<tr class='tr_titulo' style='border-bottom: 1px solid #ccc'>"+
                "<td style='width: 100px; padding: 5px; font-weight: bold;'id='arvore_idhmLongevidade2'></td>"+
                "<td style='width: 100px; padding: 5px;'>"+data2.right.health+"</td>"+
             "</tr>"+
             "<tr class='tr_titulo' style='border-bottom: 1px solid #ccc'>"+
                "<td style='width: 100px; padding: 5px; font-weight: bold;' id='arvore_idhmEducacao2'></td>"+
                "<td style='width: 100px; padding: 5px;'>"+data2.right.edu+"</td>"+
             "</tr>"+
          "</table>");
		  
		$("#arvore_idhmComponentes2").html(lang_mng.getString("arvore_idhmComponentes2"));
        $("#arvore_idhm2").html(lang_mng.getString("arvore_idhm2"));
        $("#arvore_idhmRenda2").html(lang_mng.getString("arvore_idhmRenda2"));
        $("#arvore_idhmLongevidade2").html(lang_mng.getString("arvore_idhmLongevidade2"));
        $("#arvore_idhmEducacao2").html(lang_mng.getString("arvore_idhmEducacao2"));
        $("#arvore_valores2").html(lang_mng.getString("arvore_valores2"));
    }
}

function get_index_order(data){
    var branch_colors = {"eys": "#315ed8", "mys": "#69b7e4", "edu": "#3772e4", "health": "#d52d1b", "income": "#e1a000"};
    var secondary_indicies = new Array();
    secondary_indicies[secondary_indicies.length] = {value: data.mys, name:"MYS", color: branch_colors.mys}
    secondary_indicies[secondary_indicies.length] = {value: data.eys, name: "EYS", color: branch_colors.eys}
    secondary_indicies.sort(compareIndicies)
    var indicies = new Array();
    
    
   indicies[indicies.length] = {value: data.income, color: branch_colors.income, name: lang_mng.getString("arvore_renda"), secondary_branches: false}
   indicies[indicies.length] = {value: data.health, color: branch_colors.health, name: lang_mng.getString("arvore_longevidadetr"), secondary_branches: false}
    indicies[indicies.length] = {value: data.edu, color: branch_colors.edu, name: lang_mng.getString("arvore_edu"), secondary_branches: false}
     
    
    return indicies;
}

function draw_text(x, y, text, angle, rx, ry){
//    if(text != 'IDHM' && text != 'renda' && text != 'longevidade' && text != 'edu'){
//      text = text;
//      text = text.toFixed(3);
//    }

    var txt = paper.text(x, y, text);
    txt.attr("text-anchor", "start");
    txt.attr("fill", "#FFFFFF");
    txt.rotate(angle, rx, ry);
    return txt;
	
}

function draw_line(x, y, length, angle, rx, ry){
    var line = paper.path([["M", x, y], ["h", length]]);
    line.attr("stroke", "#FFFFFF");
    line.rotate(angle, rx, ry);
    return line;
	
}

function draw_scale_lines(){
    var lines = paper.set();
    var text = paper.set();
    for (var i = 1; i < 11; i++){
        var y_offset = paper_h - 20*i - 15;
        lines.push(paper.path([["M", 20, y_offset], ["H", paper_w]]))
        text.push(paper.text(10, y_offset, 10*i))
    }
    lines.attr("stroke", "#DDDDDD")
    text.attr("fill", "#AAAAAA")
	
}

function draw_branch(x, y, value, radius, color, opacity, rx, ry){
    var branch = paper.rect(x, y, value, value, radius)
    branch.attr("fill",  color);
    branch.attr("opacity",  opacity);
    branch.attr("stroke",  color);
    branch.attr("stroke-width",  10);
    branch.rotate(-45, rx, ry);
    return branch;	
}

function make_branch(x, y, index, radius, order, opacity, show_outlines, show_labels){
    var value = index["value"];
    var color = index["color"];
    var name = index["name"];
    var secondary_branches = index["secondary_branches"];
    var stroke_width = 10;
	
    // Distance from trunk
    offset = Math.sqrt((Math.pow(value, 2)) * 2);
    
    // White space between branches
    padding = 10;
    if(order == 0){
        x = x - offset - (padding/2) - (stroke_width/2);
        y = y - padding - (stroke_width/5);
    }
    else if(order == 1){
        x = x - (offset / 2);
        y = y - (offset / 2) - padding - stroke_width;
    }
    else {
        x = x + (padding/2) + (stroke_width/2);
        y = y - padding - (stroke_width/5);
    }
	
    // Draw Branch
    branch = draw_branch(x, y, value, radius, color, opacity, x, y);
    var line, top_txt, bot_txt, center_txt;
	
    if(show_labels){
        
        // Draw Line	
        line_x = x + (stroke_width / 2);
        line_y = y + (value / 2);
        line_length = value - stroke_width;
        line = draw_line(line_x, line_y, line_length, -45, x, y);
        
        // Write text
        top_txt_x = line_x;
        top_txt_y = line_y - 6;
        top_txt = draw_text(top_txt_x, top_txt_y, name, -45, x, y);
        top_txt.attr("font-weight", "bold");

        // Write text
        bot_txt_x = line_x;
        bot_txt_y = line_y + 6;
        bot_txt = draw_text(bot_txt_x, bot_txt_y, value.toFixed(1), -45, x, y);
		
        // Write center text
        var center_txt = draw_text(line_x, line_y, value.toFixed(1), -45, x, y);
        center_txt.hide();
		
        if(line.getBBox().width < top_txt.getBBox().width){
            line.hide();
            top_txt.hide();
            bot_txt.hide();
            center_txt.show();
            q = center_txt;
	if(center_txt.getBBox().width > (branch.getBBox().width - stroke_width)){
                center_txt.hide();
	}
        }

    }
    if(show_outlines && !show_labels) {
        branch.attr("fill", "#FFFFFF");
    }
    else if(show_outlines && show_labels){
        branch.attr("fill", "#FFFFFF");
        line.attr("stroke", color);
        top_txt.attr("fill",  color);
        bot_txt.attr("fill",  color);
        center_txt.attr("fill",  color);
    }
	
    for(var i = 0; i < secondary_branches.length; i++){
        var space_offset = 2;
        // Check if value is negative (for smaller countries)
        var sec_value = ((secondary_branches[i].value - ((stroke_width/2) + (space_offset/2))) < 0) ? secondary_branches[i].value : (secondary_branches[i].value - ((stroke_width/2) + (space_offset/2)));
        var sec_color = secondary_branches[i].color;
        var sec_name = secondary_branches[i].name;
		
        var sec_branch_x = x;
        var sec_branch_y = y - sec_value - stroke_width - space_offset;
		
        if(order == 2 && i == 1) {
            var sec_branch_x = x + value + stroke_width + space_offset;
	var sec_branch_y = y + secondary_branches[0].value + (stroke_width/2) + (space_offset/2);
        }
        else if(order == 2){
	var sec_branch_x = x + value + stroke_width + space_offset;
	var sec_branch_y = y;
        }
        else if(i == 1){
            var sec_branch_x = x + secondary_branches[0].value + (stroke_width/2) + (space_offset/2);
	var sec_branch_y = y - sec_value - stroke_width - space_offset;
        }
		
        var sec_branch = draw_branch(sec_branch_x, sec_branch_y, sec_value, radius, sec_color, opacity, x, y);	
        var sec_branch_line, sec_branch_bot_txt, sec_branch_top_txt, sec_branch_center_txt;
        
        if(show_labels){
	var sec_branch_line_x = sec_branch_x;
            var sec_branch_line_y = sec_branch_y + (sec_value / 2);
	var sec_branch_line_length = sec_value;
            var sec_branch_top_txt_x = sec_branch_line_x;
            var sec_branch_top_txt_y = sec_branch_line_y - 7;

	var sec_branch_bot_txt_x = sec_branch_line_x;
	var sec_branch_bot_txt_y = sec_branch_line_y + 7;

	var sec_branch_line = draw_line(sec_branch_line_x, sec_branch_line_y, sec_branch_line_length, -45, x, y)
	var sec_branch_top_txt = draw_text(sec_branch_top_txt_x, sec_branch_top_txt_y, sec_name, -45, x, y);
	sec_branch_top_txt.attr("font-weight", "bold");
	var sec_branch_bot_txt = draw_text(sec_branch_bot_txt_x, sec_branch_bot_txt_y, secondary_branches[i].value.toFixed(1), -45, x, y);	
	
            var sec_center_txt = draw_text(sec_branch_line_x, sec_branch_line_y, secondary_branches[i].value.toFixed(1), -45, x, y);
	sec_center_txt.hide();
			
	if(sec_branch_line.getBBox().width < sec_branch_top_txt.getBBox().width){
                sec_branch_line.hide();
                sec_branch_top_txt.hide();
                sec_branch_bot_txt.hide();
                sec_center_txt.show();
                if(center_txt.getBBox().width > (sec_branch.getBBox().width - stroke_width)){
                    sec_center_txt.hide();
                }
	}
			
        }
        if(show_outlines && !show_labels){
	sec_branch.attr("fill", "#FFFFFF");
        }
        else if(show_outlines && show_labels){
	sec_branch.attr("fill", "#FFFFFF");
	sec_branch_line.attr("stroke", sec_color);
	sec_branch_top_txt.attr("fill",  sec_color);
	sec_branch_bot_txt.attr("fill",  sec_color);
	sec_center_txt.attr("fill", sec_color);
        }
    }	
}

function make_trunk(w, h, x, y, r, color, opacity, show_outlines, show_labels){
    stroke_width = 10;
    var value = h;
    h = 2*h;
    h = h - stroke_width - r;
    
    var path = [	
        // LEFT BOTTOM ANGLE
        ["M", x, y - r - (stroke_width/2)], 
        ["s", 0, r, r, r],
        // BOTTOM
        ["h", w],
        // RIGHT BOTTOM ANGLE
        ["s", r, 0, r, -r],        
        // RIGHT
        ["v", -h],
        // RIGHT TOP ANGLE
        ["s", 0, -r, -r, -(r+r)],
        // RIGHT TOP LINE
        ["l", -((w/2) -r), -((w/2) -r)],
        // TOP CAP
        ["s",  -r, -r,  -(r*2), 0],
        // LEFT TOP LINE
        ["l", -((w/2) -r), ((w/2) -r)],
        // LEFT TOP ANGLE
        ["s", -r, r, -r, r+r],
        // Left Vertical (added for IE compatibility)
        ["v", h+1],
        // END
        ["z"]
    ];

    var total_height = r + h + (r*2.2) + ((w/2) -r) + r;
    var total_width = (w - 1) + (2 * r) + stroke_width;
    var trunk = paper.path(path);
    trunk.attr("fill", color);
    trunk.attr("stroke", color);
    trunk.attr("stroke-width", stroke_width);
    trunk.attr("opacity", opacity);
    var line_x = x + (total_width / 2) - r*4;
    var line_y = y - 10;
    var line_length = total_height - stroke_width/2 - 10;
    
    line = draw_line(line_x, line_y, line_length, -90, line_x, line_y)
    var top_txt_x = line_x - 6;
    var top_txt_y = line_y;
    top_txt = draw_text(top_txt_x, top_txt_y, lang_mng.getString("arvore_idhm4"), -90, top_txt_x, top_txt_y);
    top_txt.attr("font-weight", "bold");
    var bottom_txt_x = line_x + 7;
    var bottom_txt_y = line_y;
    bottom_txt = draw_text(bottom_txt_x, bottom_txt_y, value.toFixed(1), -90, bottom_txt_x, bottom_txt_y)
    if(show_outlines){
        trunk.attr("fill", "#FFFFFF");
        line.attr("stroke", color);
        top_txt.attr("fill",  color);
        bottom_txt.attr("fill",  color);
    }
    if(!show_labels){
        line.hide();
        top_txt.hide();
        bottom_txt.hide();
    }

    return Math.ceil(total_height);
}

function compareIndicies(a, b) {
	return a.value - b.value;
}

function build_tree(options){
    if(!options.opacity){
        options.opacity = 1;
    }
    
    var trunk_colors = {"trunk_edu": "#616873", "trunk_longevidade": "#79605f", "trunk_renda": "#7b735e"};
    var trunk_width = 25;
    var radius = 1;
    if(options.left){
        var trunk_position = (paper_w/4) - trunk_width;
        var base_branch_xposition = (paper_w/4) - (trunk_width / 2) + radius;
    }
    else {
        var trunk_position = (paper_w*(3/4)) - trunk_width;
        var base_branch_xposition = (paper_w*(3/4)) - (trunk_width / 2) + radius;
    }

    // Draw the trunk
    trunk_height = make_trunk(trunk_width, options.hdi, trunk_position, paper_h, radius, trunk_colors["trunk_"+options.indicies[2].name], options.opacity, options.show_outlines, options.show_labels);
    trunk_height = paper_h - trunk_height - radius;
	
    // Draw each of the leaves
    for(var i = 0; i < options.indicies.length; i++){
        make_branch(base_branch_xposition, trunk_height, options.indicies[i], radius, i, options.opacity, options.show_outlines, options.show_labels);
    }
}

function show_no_data(left){
	
	var trunk_position = paper_w * (3/4);
	if(left){
		var trunk_position = paper_w/4;
	}
	
	var txt = paper.text(trunk_position, paper_h - 12, lang_mng.getString("arvore_semdados"));
	txt.attr("fill", "black");
	txt.attr("font-size", 14);

}

function loading(left){
    var trunk_position = paper_w * (3/4);
    if(left){
        var trunk_position = paper_w/4;
    }
    paper.image("img/load.gif", trunk_position - 31, paper_h - 70, 40, 40);
}

function set_table(options){
	var table = $(".tree_selection.r")
	if(options.left){
		table = $(".tree_selection.l")
	}
	
	if(options.hdi){

		table.find("tr:eq(0) td:eq(0)").html(options.hdi.hdi.toFixed(2));
		table.find("tr:eq(2) td:eq(0)").html(options.hdi.income.toFixed(2));
		table.find("tr:eq(3) td:eq(0)").html(options.hdi.health.toFixed(2));
		table.find("tr:eq(4) td:eq(0)").html(options.hdi.edu.toFixed(2));
		
	} else {

		table.find("tr:eq(0) td:eq(0)").html("&mdash;");
		table.find("tr:eq(2) td:eq(0)").html("&mdash;");
		table.find("tr:eq(3) td:eq(0)").html("&mdash;");
		table.find("tr:eq(4) td:eq(0)").html("&mdash;");

	}
	
}
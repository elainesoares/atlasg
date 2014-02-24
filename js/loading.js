/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var loadingHolder = {
    show: function (text) {
        $("#contentLoading").css("top", "300px");
        $("#contentLoading").css("left",(($(document).innerWidth()/2)-($("#contentLoading").width()/2))+"px");
        $("#contentLoading").css("display","block");
        $("#contentLoading").css("text-align","center");
        $("#contentLoading").css("top", Math.max(0, (($(window).height() - $("#contentLoading").outerHeight()) / 2) + $(window).scrollTop()) + "px");
        $("#maskTransparent").css("display","block");
        $("#maskTransparent").css("width",$(document).innerWidth()+"px");
        $("#maskTransparent").css("height",$(document).innerHeight()+"px");
        $("#contentLoading #contentLoading-text").html(text);
    },
    dispose: function(){
        $("#contentLoading").fadeOut("slow");
        $("#maskTransparent").fadeOut("slow");
        //$("#maskTransparent").css("display","none");
    }
}




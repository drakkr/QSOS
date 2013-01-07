$(document).ready(function(){ 

    /*
     *   Crée un selecteur :contains insensible à la casse.
     */
    $.extend($.expr[":"], {
        "containsNC": function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    /*
     *   Affiche l'onglet courant dans la barre de navigation
     */
    $("#buttonBar li").removeClass("active");
    $("#buttonBar li:nth-child(1)").addClass("active");

    /*
     *   Cache les domaines fonctionnels
     *   Affiche le contenu du repertoire selectionné
     *   Complete le fil d'Ariane avec le chemin du dossier
     */
    $(".pack").live("click", function(){
        var text = $(".nameItem", this).text();
        $(".pack").addClass("hidden").hide();
        $("ul.breadcrumb li:first-child").css("color", "#3399ff");
        $("ul.breadcrumb").append('<li><span class="divider">/</span>' + text + '</li>');
        console.log(text);
        $.ajax({
        type: "POST",
        url: "bridge.php",
        data: "function=displayCategory&category=" + text,
        success: function(msg){
            $("#listCategory").html(msg);
        }
    });
    });

    /*
     *   Permet de ré-afficher toutes les catégories.
     */
    $("ul.breadcrumb li:first-child").live("click", function(){
        if($(".pack").is(":hidden") && !$("ul.breadcrumb").hasClass("modified")) {
            $(".pack").removeClass("hidden").fadeIn('fast');
            $(this).css("color", "black");
            $("ul.breadcrumb  li[id!='unRemovable']").remove();
            $("#searchInput").val('');
            $("#listCategory").empty();
        }else{
            location.reload();
        }
    });
    
    /*
     *   Affiche les domaines fonctionnels en fonction de la recherche
     */
    $("#searchInput").keyup(function(){
        var item = $(this).val().toLowerCase();
        var node;
        if(!$(".pack").hasClass("hidden")){
            if(item.length != "" && item.length > 1){
                node = $(".pack p:containsNC(" + item + ")");
                $(".pack").hide();
                node.parent().show();
            }else{
                $(".pack").show();
            }
        }else if($(".pack").hasClass("hidden")){
            if(item != "" && item.length > 1){
                node = $(".itemLine div:containsNC(" + item + ")");
                $(".itemLine").hide();
                node.parent().show();
            }else{
                $(".itemLine").show();
            }
        }
    });
    
    /*
     *  Display info on the evaluation
     */
    $(".openEval").live("click", function(){
        var file = $(this).parent().parent();
        var repo;
        if($(".info", file).is(":empty")){
            $(this).html("Close");
            if($(this).parent().parent().hasClass("master")){
                repo = "master";   
            }else if($(this).parent().parent().hasClass("incoming")){
                repo = "incoming";
            }
            $.ajax({
                type: "POST",
                url: "bridge.php",
                data: "function=openEval&repo="+ repo +"&file=" + file.attr('id'),
                success: function(msg){
                    $(".info", file).hide();
                    $(".info", file).html(msg).fadeIn('slow');
                }
            });
        }else{
            $(this).html("Open");
            $(".info", file).hide().empty();
        }
    });
    
    /*
     *  Display info on the template
     */
    $(".openTpl").live("click", function(){
        var file = $(this).parent().parent();
        var repo;
        if($(".info", file).is(":empty")){
            $(this).html("Close");
            if($(this).parent().parent().hasClass("master")){
                repo = "master";   
            }else if($(this).parent().parent().hasClass("incoming")){
                repo = "incoming";
            }
            $.ajax({
                type: "POST",
                url: "bridge.php",
                data: "function=openTpl&repo="+ repo +"&file=" + file.attr('id'),
                success: function(msg){
                    $(".info", file).hide();
                    $(".info", file).html(msg).fadeIn('slow');
                }
            });
        }else{
            $(this).html("Open");
            $(".info", file).hide().empty();
        }
    });
    
    /*
     *   Function : Upgrade an evaluation
     */
    $(".upgradeEval").live("click", function(){
        var id = $(this).parent().parent().attr('id');
        $(this).parent().hide();
        $(this).parent().parent().fadeOut('fast', function(){
            $(this).css("background-color", "#aaffbb");
            $(this).css("color", "#00bb22");
	    $(this).css("border-top", "1px solid #aaffbb");
            $(this).fadeIn('fast');
        });
        $.ajax({
            type: "POST",
            url: "bridge.php",
            data: "function=upgradeEval&file=" + id,
            success: function(msg){
            }
        });
        $("ul.breadcrumb").addClass("modified");
    });

    /*
     *   Function : Upgrade a template
     */
    $(".upgradeTpl").live("click", function(){
        var id = $(this).parent().parent().attr('id');
        $(this).parent().hide();
        $(this).parent().parent().fadeOut('fast', function(){
            $(this).css("background-color", "#aaffbb");
            $(this).css("color", "#00bb22");
	    $(this).css("border-top", "1px solid #aaffbb");
            $(this).fadeIn('fast');
        });
        $.ajax({
            type: "POST",
            url: "bridge.php",
            data: "function=upgradeTpl&file=" + id,
            success: function(msg){
            }
        });
        $("ul.breadcrumb").addClass("modified");
    });    
    
    /*
     *   Function : Delete an evaluation
     */
    $(".delEval").live("click", function(){
        var id = $(this).parent().parent().attr('id');
        var repo;
        $(this).parent().hide();
        $(this).parent().parent().fadeOut('fast', function(){
            $(this).css("background-color", "#ff9999");
            $(this).css("color", "#bb0022");
	    $(this).css("border-top", "1px solid #ff9999");
            $(this).fadeIn('fast');
        });
        if($(this).parent().parent().hasClass("master")){
            repo = "master";   
        }else if($(this).parent().parent().hasClass("incoming")){
            repo = "incoming";
        }
                console.log(repo);
	$.ajax({
	    type: "POST",
	    url: "bridge.php",
	    data: "function=delEval&repo=" + repo + "&file=" + id,
	    success: function(msg){
            }
        });
        $("ul.breadcrumb").addClass("modified");
    });
    
    /*
     *   Function : Delete a template
     */
    $(".delTpl").live("click", function(){
        var id = $(this).parent().parent().attr('id');
        var repo;
        $(this).parent().hide();
        $(this).parent().parent().fadeOut('fast', function(){
            $(this).css("background-color", "#ff9999");
            $(this).css("color", "#bb0022");
	    $(this).css("border-top", "1px solid #ff9999");
            $(this).fadeIn('fast');
        });
        if($(this).parent().parent().hasClass("master")){
            repo = "master";   
        }else if($(this).parent().parent().hasClass("incoming")){
            repo = "incoming";
        }
                console.log(repo);
	$.ajax({
	    type: "POST",
	    url: "bridge.php",
	    data: "function=delTpl&repo=" + repo + "&file=" + id,
	    success: function(msg){
            }
        });
        $("ul.breadcrumb").addClass("modified");
    });    
    
});
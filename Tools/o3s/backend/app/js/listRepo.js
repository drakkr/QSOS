$(document).ready(function(){
    
    /*
     *  Selectionne le bon onglet dans la navbar
     */
    $("#buttonBar li").removeClass("active");
    $("#buttonBar li:nth-child(2)").addClass("active");

    /*
     *  Affiche le contenu de incoming au chargement de la page
     */
    $.ajax({
    	type: "POST",
    	url: "bridge.php",
    	data: "function=checkout&repo=incoming&type=evaluations",
    	success: function(list){
    		$('#listContent').html(list);
    	}
    });

    /*
     *   Fonctions checkout
     */
    $("#list_repo").live("click", function(){
        var repo = $("#list_repo").val();
        var type = $("#list_type").val();
        $.ajax({
	    type: "POST",
	    url: "bridge.php",
	    data: "function=checkout&repo="+repo+"&type="+type,
	    success: function(list){
		$('#listContent').fadeOut('fast', function(){
		$('#listContent').html(list);
		$('#listContent').fadeIn('fast');
		});
	    }
       });
    });

    $("#list_type").live("click", function(){
        var repo = $("#list_repo").val();
        var type = $("#list_type").val();
        $.ajax({
	    type: "POST",
	    url: "bridge.php",
	    data: "function=checkout&repo="+repo+"&type="+type,
	    success: function(list){
		$('#listContent').fadeOut('fast', function(){
		$('#listContent').html(list);
		$('#listContent').fadeIn('fast');
		});
	    }
       });
    });    
    
    /*
     *   Cherche le motif dans les balises .name
     */
    $("#searchInput").keyup(function(){
	var item = $(this).val().toLowerCase();
	if(item != ""){
	    var node = $("div:containsNC(" + item + ")");
	    $("div.itemLine").hide();
	    node.parent().show();
	}else{
	    $("div.itemLine").show();
	}
    });
});
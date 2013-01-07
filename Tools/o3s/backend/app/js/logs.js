$(document).ready(function(){
    var repo = $("#list_repo").val();
    /*
     * Affiche l'onglet courant dans la navBar
     */
    $("#buttonBar li").removeClass("active");
    $("#buttonBar li:nth-child(3)").addClass("active");
    
    /*
     * Affiche les logs au chargement de la page
    */
    $.ajax({
        type: "POST",
        url: "bridge.php",
        data: "function=logs&repo="+repo,
        success: function(logs){
            $("#logs").html(logs);
        }
    });
    
    //Fonction affichage des logs
    $("#list_repo").live("click", function(){
        repo = $("#list_repo").val();
        $.ajax({
            type: "POST",
            url: "bridge.php",
            data: "function=logs&repo=" + repo,
            success: function(logs){
		$("#logs").html(logs);
            }
        });
    });
    
    /*
     * Permet de selectionner un commit dans le temps
     */
//    $(".alert").live("click", function(){
//	if($(this).hasClass("alert-success")){
//	    if($(".alert").hasClass("alert-info")){
//		$(".alert").removeClass("alert-info");
//		$(".alert").addClass("alert-success");
//	    }
//	    $(this).removeClass("alert-success");
//	    $(this).addClass("alert-info");
//	}else{
//	    $(".alert").removeClass("alert-info");
//	    $(".alert").addClass("alert-success");
//	}
//    });
});
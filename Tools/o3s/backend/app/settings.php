<?php require("header.php") ?>
<script type="text/javascript">
	$(".nav li").removeClass("active");
	$(".nav:nth-child(3) li:nth-child(3)").addClass("active");
	
	$(".promote").live("click", function(){
		var user = $(this).parent().parent();
		$.ajax({
			type: "POST",
			url: "bridge.php",
			data: "function=promote&&user=" + user.attr('id'),
        });
		user.fadeOut('fast', function(){
			$(this).css("background-color", "#aaffbb");
			$(this).css("color", "#00bb22");
			$(this).css("border-top", "1px solid #aaffbb");
			$(".promote", user).hide();
			$(this).fadeIn('fast');
		});
	});
</script>
		
<?php
if (isset($_SESSION['login'])){
	/*
	 *	Select the status (admin, moderator, user...)
	 */
	$sql = "SELECT status FROM users WHERE login=?";
    $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array($_SESSION['login']));
	$data = $sth->fetchAll();
	$status = $data[0][0];

	if($status == "admin" || $status == "moderator"){

		//Select informations about the user
		$sql = "SELECT login, mail, contributions, status FROM users ORDER BY status";
		$sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$data = $sth->fetchAll();
		
		echo "<h2>". TXT_SETTING_USER ." : </h2>";
		echo "<table id='user_list'>
			<tr id='head'><td>Login</td><td>Mail</td><td>Contributions</td><td>Role</td>";
			if($status == "admin"){ //if the user is an admin, he can promote others
				echo "<td>". TXT_SETTING_PROMOTE ."</td>";
			};
			
			//display "promote" option on simple users lines
			for($i=0; $i<sizeof($data); $i++){
				echo "<tr id='".$data[$i]['login']."'>";
				for($j=0; $j<4; $j++){
					echo "<td>". $data[$i][$j] ."</td>";
				}
				if($data[$i][3] == "user" && $status == "admin"){
					echo "<td><i class='icon-ok-sign promote'></i></td>";
				}elseif($status == "admin"){
					echo "<td></td>";
				}
				echo "</tr>";
			}
		echo "</table>";

	}else{
		echo "<div class='alert alert-success'>". TXT_UPLOAD_LOGIN ."</div>";   
	}
}else{
    echo "<div class='alert alert-success'>". TXT_UPLOAD_LOGIN ."</div>";   
}
?>

</div><!-- End of div.clearfix-->
</div><!-- End of div.well-->
</div><!-- End of div#mainWindow-->
</body>
</html>
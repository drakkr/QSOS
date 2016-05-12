<?php require("header.php"); ?>
<script type="text/javascript">
    $("#buttonBar li").removeClass("active");
    $("#buttonBar li:nth-child(4)").addClass("active");
</script>

<?php

if (isset($_SESSION['login'])){
    
    //Print the upload form
    echo '<form id="upload" method="post" enctype="multipart/form-data" action="upload.php">';
    echo '<h4>'. TXT_UPLOAD_SELECT .'</h4>';
    echo '<p class="input-append">';
    echo '<input class="btn-large" type="file" name="fichier">';
    echo '<input class="btn btn-success btn-upload" type="submit" name="upload" value="'. TXT_UPLOAD .'">';
    echo '</p>';
    echo '</form>';
    
    $login = $_SESSION['login'];
    if (isset($_FILES['fichier'])) {
      include("upload.inc");
      upload($_FILES['fichier'], $login);
    }

} else {
    echo "<div class='alert alert-success'>". TXT_UPLOAD_LOGIN ."</div>";   
}
?>
</div><!-- End of div.clearfix-->
</div><!-- End of div.well-->
</div><!-- End of div#mainWindow-->
</body>
</html>

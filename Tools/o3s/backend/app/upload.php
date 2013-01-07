<?php require("header.php"); ?>
<script type="text/javascript">
    $("#buttonBar li").removeClass("active");
    $("#buttonBar li:nth-child(4)").addClass("active");
</script>

<?php

function alertError($error) {
  echo "<div class='alert alert-error'>".$error."</div>";
}

function alertSuccess($error) {
  echo "<div class='alert alert-success'>".$success."</div>";
}

if (isset($_SESSION['login'])){
    
    //Print the upload form
    echo '<form id="upload" method="post" enctype="multipart/form-data" action="upload.php">';
    echo '<h4>'. TXT_UPLOAD_SELECT .'</h4>';
    echo '<p class="input-append">';
    echo '<input class="btn-large" type="file" name="fichier">';
    echo '<input class="btn btn-success btn-upload" type="submit" name="upload" value="'. TXT_UPLOAD .'">';
    echo '</p>';
    echo '</form>';
    
//     $re1='.*?';
//     $re2='(\\.)';
//     $re3='(qsos)';
//     $re4='(mm)';
    
    $login = $_SESSION['login'];

    include("upload.inc");
    upload($_FILES['fichier'], $login);

} else {
    echo "<div class='alert alert-success'>". TXT_UPLOAD_LOGIN ."</div>";   
}
?>
</div><!-- End of div.clearfix-->
</div><!-- End of div.well-->
</div><!-- End of div#mainWindow-->
</body>
</html>
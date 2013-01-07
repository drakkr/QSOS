<?php require("header.php"); ?>
<script src="./js/index.js"></script>

    <div class="pull-left">
        <ul class="breadcrumb">
            <li id="unRemovable"><?php echo TXT_REPO_CATEGORIES ?></li>
        </ul>
    </div>
            
    <!--Search Form-->
    <form class="pull-right">
        <div class="input-prepend">
        <span class="add-on"><i class="icon-search"></i></span><input type="text" id="searchInput" placeholder=<?php echo "'".TXT_REPO_SEARCH."'"?>>
        </div>
    </form>
</div><!--End of div.clearfix header.php-->

    <div class="clearfix">
        <div id="listCategory">
        </div>
    <?php
        /*
         *  Display app family
         */
        $tabDomaines = $bdd->query("SELECT DISTINCT qsosappfamily FROM evaluations ORDER BY qsosappfamily");
        
        while ($item = $tabDomaines->fetch()){
            $nbElements = $bdd->query("SELECT COUNT(*) FROM evaluations WHERE qsosappfamily='". $item['qsosappfamily'] ."'");
            $nbElements = $nbElements->fetch();

            echo "<div class='pack'>";
            echo "<div class='icon'><img src='./lib/img/folder_Faenza64.png' alt='" . $item['qsosappfamily'] . "'><div class='badgeNum'>". $nbElements[0] ."</div></div>";
            echo "<p class='nameItem'>" . $item['qsosappfamily'] . "</p>";
            echo "</div>";
        }
    ?>
   </div>
   
</div><!-- End of div.well-->
</div><!-- End of div#mainWindow-->
</body>
</html>
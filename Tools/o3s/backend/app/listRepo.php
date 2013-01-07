<?php require("header.php"); ?>
<script src="./js/index.js"></script>
<script src="./js/listRepo.js"></script>

    <div class="input-append pull-left controls controls-row">
        <!--Select the repo-->
        <select class="span2" id="list_repo" name="list_repo">
            <option value='master' >Master</option>
            <option value='incoming' selected='selected'>Incoming</option>
        </select>

        <!--Select the type-->
        <select class="span2" id="list_type" name="list_type">
            <option value='evaluations' selected='selected'>Evaluations</option>
            <option value='templates'>Templates</option>
        </select>
    </div>
        
    <!--Search form-->
    <form class="pull-right">
        <div class="input-prepend">
            <span class="add-on"><i class="icon-search"></i></span><input type="text" id="searchInput" placeholder=<?php echo "'".TXT_REPO_SEARCH."'"?> >
        </div>
    </form>
    </div> <!--End of div.clearfix header.php-->
    
    <div id="listContent">
    <!--Display all the files in the repo-->
    </div>
    
</div><!-- End of div.well-->
</div><!-- End of div#mainWindow-->
</body>
</html>
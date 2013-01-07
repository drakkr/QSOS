<?php require("header.php") ?>
<script src="./js/logs.js"></script>

    <!--Select the repository-->
    <span class="pull-left">
	<?php echo TXT_DISPLAY_LOGS;?>  
	<select class="span2" id="list_repo" name="list_repo">
	     <option value='master' selected='selected'>Master</option>
	    <option value='incoming'>Incoming</option>
	</select>
    </span>
    
    <div id="logs">
    </div>

</div><!-- End of div.clearfix-->
</div><!-- End of div.well-->
</div><!-- End of div#mainWindow-->
</body>
</html>
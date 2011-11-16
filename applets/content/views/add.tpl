
	<h1>Content &raquo; Add</h1>
	<hr/>
	<form action="/backend/process/applets/content/add" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name" maxlength="255"/>
		</p>
		<p>
    		* Create content as page? <small><em>(enables webpage URL, Title, and META data)</em></small><br/>
    		<label><input type="radio" name="page" value="1"/> Page</label>
    		<label><input type="radio" name="page" value="0" checked="checked"/> Other/miscellaneous</label>
		</p>
		<div id="page-panel" style="display:none;">
    		<p>
    			* URL:<br/>
    			http://{$smarty.server.SERVER_NAME}/<input type="text" name="url" maxlength="255"/>
    		</p>
    		<p>
    			Title:<br/>
    			<input type="text" name="title" style="width:95%;" maxlength="255"/>
    		</p>
    		<p>
    			META Keywords:<br/>
    			<input type="text" name="meta_keywords" style="width:95%;" maxlength="256"/>
    		</p>
    		<p>
    			META Description:<br/>
    			<textarea name="meta_description" style="width:95%;"></textarea>
    		</p>	
		</div>
		<p>
			* Content:<br/>
			<textarea name="content" class="wysiwyg" style="height:420px;"></textarea>
		</p>
		<p>
			<input type="submit" value="Save"/>
		</p>	
	</form>
    <script type="text/javascript">
    $(document).ready(function(){
        $('input[name=page]').change(function(){
            if ($(this).val() == '1') {
                $('#page-panel').show();
            } else {
                $('#page-panel').hide();
            }
        })
    });
    </script>
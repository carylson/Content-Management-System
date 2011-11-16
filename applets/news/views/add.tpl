	
	<h1>News &raquo; Add</h1>
	<hr/>
	<form action="/backend/process/applets/news/add" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name"/>
		</p>
		<p>
			* Title:<br/>
			<input type="text" name="title" style="width:95%;"/>
		</p>
		<p>
			Subtitle:<br/>
			<input type="text" name="subtitle" style="width:95%;"/>
		</p>
		<p>
			* Date:<br/>
			<input type="text" name="date" class="calendar"/>
		</p>
		<p>
    		* What type of news article?<br/>
    		<label><input type="radio" name="type" value="content" checked="checked"/> Create a news article page</label><br/>
    		<label><input type="radio" name="type" value="link"/> Link to a news article on another website</label>
		</p>
		<p id="type-link-panel" style="display:none;">
			* URL:<br/>
			<input type="text" name="url"/>
		</p>
		<p id="type-content-panel">
			* Content:<br/>
			<textarea style="width:100%; height:420px;" class="wysiwyg" name="content"></textarea>
		</p>
		<p>
			<input type="submit" value="Save"/>
		</p>	
	</form>
    <script type="text/javascript">
    $(document).ready(function(){
        $('input[name=type]').change(function(){
            $('#type-link-panel:visible, #type-content-panel:visible').hide();
            $('#type-'+$(this).val()+'-panel').show();
        });
    });
    </script>
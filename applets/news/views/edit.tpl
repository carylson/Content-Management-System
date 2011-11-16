	
	{$data = $sizzle->applets['news']->models['news']->fetch($sizzle->request[3])}
	<h1>News &raquo; Edit</h1>
	<hr/>
	<form action="/backend/process/applets/news/edit" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name" value="{$data['name']}"/>
		</p>
		<p>
			* Title:<br/>
			<input type="text" name="title" value="{$data['title']}" style="width:95%;"/>
		</p>
		<p>
			Subtitle:<br/>
			<input type="text" name="subtitle" value="{$data['subtitle']}" style="width:95%;"/>
		</p>
		<p>
			* Date:<br/>
			<input type="text" name="date" value="{$data['date']}" class="calendar"/>
		</p>
		<p>
    		* What type of news article?<br/>
    		<label><input type="radio" name="type" value="content"
    		{if empty($data['url'])}
    		checked="checked"
    		{/if}
    		/> Create a news article page</label><br/>
    		<label><input type="radio" name="type" value="link"
    		{if !empty($data['url'])}
    		checked="checked"
    		{/if}
    		/> Link to a news article on another website</label>
		</p>
		<p id="type-link-panel" 
		{if empty($data['url'])}
		style="display:none;"
		{/if}
		>
			* URL:<br/>
			<input type="text" name="url" value="{$data['url']}"/>
		</p>
		<p id="type-content-panel" 
		{if !empty($data['url'])}
		style="display:none;"
		{/if}
		>
			* Content:<br/>
			<textarea style="width:100%; height:420px;" class="wysiwyg" name="content">{$data['content']}</textarea>
		</p>
		<p>
			<input type="submit" value="Save"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
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

	{$data = $sizzle->applets['content']->models['content']->fetch($sizzle->request[3])}
	<h1>Content &raquo; Edit</h1>
	<hr/>
	<form action="/backend/process/applets/content/edit" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name" value="{$data['name']}" maxlength="255"/>
		</p>
		<p>
    		* Create content as page? <small><em>(enables webpage URL, Title, and META data)</em></small><br/>
    		<label><input type="radio" name="page" value="1"
    		{if !empty($data['url'])}
        		checked="checked"
    		{/if}
    		/> Page</label>
    		<label><input type="radio" name="page" value="0"
    		{if empty($data['url'])}
        		checked="checked"
    		{/if}
    		/> Other/miscellaneous</label>
		</p>
		<div id="page-panel"
		{if empty($data['url'])}
    		style="display:none;"
		{/if}
		>
    		<p>
    			URL:<br/>
    			http://{$smarty.server.SERVER_NAME}/<input type="text" name="url" value="{$data['url']}" maxlength="255"/>
    		</p>
    		<p>
    			Title:<br/>
    			<input type="text" name="title" value="{$data['title']}" style="width:95%;" maxlength="255"/>
    		</p>
    		<p>
    			META Keywords:<br/>
    			<input type="text" name="meta_keywords" value="{$data['meta_keywords']}" style="width:95%;" maxlength="255"/>
    		</p>
    		<p>
    			META Description:<br/>
    			<textarea name="meta_description" style="width:95%;">{$data['meta_description']}</textarea>
    		</p>
		</div>
		<p>
			* Content:<br/>
			<textarea name="content" class="wysiwyg" style="height:420px;">{$data['content']}</textarea>
		</p>
		<p>
			<input type="submit" value="Save"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
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
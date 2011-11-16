
	{$options = $sizzle->applets['content']->models['content']->fetchOptions()}
	<h1>Content &raquo; Options</h1>
	<hr/>
	<form action="/backend/process/applets/content/options" method="post">
		<p>
			Home page:<br/>
			<select name="homepage">
			{foreach $sizzle->applets['content']->models['content']->fetchPages() as $data}
				<option value="{$data['id']}"
				{if isset($options['homepage']) && $options['homepage'] == $data['id']}selected="selected"{/if}
				>{$data['name']}</option>
			{/foreach}
			</select>
		</p>
		<p>
			Error page:<br/>
			<select name="errorpage">
			{foreach $sizzle->applets['content']->models['content']->fetchPages() as $data}
				<option value="{$data['id']}"
				{if isset($options['errorpage']) && $options['errorpage'] == $data['id']}selected="selected"{/if}
				>{$data['name']}</option>
			{/foreach}
			</select>
		</p>
		<p>
			Default Title:<br/>
			<input type="text" name="title" value="{$options['title']}" style="width:100%;"/>
		</p>
		<p>
			Default META Keywords:<br/>
			<input type="text" name="meta_keywords" value="{$options['meta_keywords']}" style="width:100%;" maxlength="256"/>
		</p>
		<p>
			Default META Description:<br/>
			<textarea name="meta_description" style="width:100%;">{$options['meta_description']}</textarea>
		</p>
		<p>
			<input type="submit" value="Save"/>
		</p>	
	</form>
		

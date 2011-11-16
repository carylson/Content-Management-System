	
	{$data = $sizzle->applets['blog']->models['blog']->fetch($sizzle->request[3])}
	<h1>Blog &raquo; Edit</h1>
	<hr/>
	<form action="/backend/process/applets/blog/edit" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name" value="{$data['name']}"/>
		</p>
		<p>
			* Title:<br/>
			<input type="text" name="title" value="{$data['title']}" style="width:95%;"/>
		</p>
		<p>
			* Content:<br/>
			<textarea style="width:100%; height:420px;" class="wysiwyg" name="content">{$data['content']}</textarea>
		</p>
		<p>
			<input type="submit" value="Save"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
		</p>	
	</form>	
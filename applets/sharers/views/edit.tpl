	
	{$data = $sizzle->applets['sharers']->models['sharers']->fetch($sizzle->request[3])}
	<h1>Sharers &raquo; Edit</h1>
	<hr/>
	<form action="/backend/process/applets/sharers/edit" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name" value="{$data['name']}"/>
		</p>
		<p>
			* Code snippet:<br/>
			<textarea style="width: 100%; height: 200px;" name="content">{$data['content']}</textarea>
		</p>
		<p>
			<input type="submit" value="Save"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
		</p>	
	</form>

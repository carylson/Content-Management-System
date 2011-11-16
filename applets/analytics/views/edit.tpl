	
	{$data = $sizzle->applets['analytics']->models['analytics']->fetch($sizzle->request[3])}
	<h1>Analytics &raquo; Edit</h1>
	<hr/>
	<form action="/backend/process/applets/analytics/edit" method="post">
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

	
	{$data = $sizzle->applets['users']->models['users']->fetch($sizzle->request[3])}
	<h1>Users &raquo; Delete</h1>
	<hr/>
	<form action="/backend/process/applets/users/delete" method="post">
		<p>
			Are you sure you want to remove this item?
			<input type="submit" value="Delete"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
		</p>
	</form>

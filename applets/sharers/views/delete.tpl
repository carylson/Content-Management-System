	
	{$data = $sizzle->applets['sharers']->models['sharers']->fetch($sizzle->request[3])}
	<h1>Sharers &raquo; Delete</h1>
	<hr/>
	<form action="/backend/process/applets/sharers/delete" method="post">
		<p>
			Are you sure you want to remove this item?
			<input type="submit" value="Delete"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
		</p>
	</form>

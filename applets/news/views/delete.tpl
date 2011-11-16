	
	{$data = $sizzle->applets['news']->models['news']->fetch($sizzle->request[3])}
	<h1>News &raquo; Delete</h1>
	<hr/>
	<form action="/backend/process/applets/news/delete" method="post">
		<p>
			Are you sure you want to remove this item?
			<input type="submit" value="Delete"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
		</p>
	</form>

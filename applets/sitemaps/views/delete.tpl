	
	{$data = $sizzle->applets['sitemaps']->models['sitemaps']->fetch($sizzle->request[3])}
	<h1>Sitemaps &raquo; Delete</h1>
	<hr/>
	<form action="/backend/process/applets/sitemaps/delete" method="post">
		<p>
			Are you sure you want to remove this item?
			<input type="submit" value="Delete"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
		</p>
	</form>

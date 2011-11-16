	{$data = $sizzle->applets['galleries']->models['galleries']->fetch($sizzle->request[3])}
	<h1>Galleries &raquo; Edit</h1>
	<hr/>
    <link rel="stylesheet" type="text/css" href="/applets/galleries/views/galleries.css"/>
    <script type="text/javascript" src="/applets/galleries/views/galleries.js"></script>
    <script type="text/javascript"> var images = {$data['images']|json_encode}; </script>
	<form action="/backend/process/applets/galleries/edit" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name" value="{$data['name']}"/>
		</p>
		<p>
			* Images:<br/>
			<span class="uploadify"></span>
		</p>
		<p>
    		<span class="images"></span>
    		<div style="clear:left;"></div>
		</p>
		<p>
			<input type="submit" value="Save"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
		</p>	
	</form>	

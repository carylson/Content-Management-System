	
	<h1>Galleries &raquo; Add</h1>
	<hr/>
    <link rel="stylesheet" type="text/css" href="/applets/galleries/views/galleries.css"/>
    <script type="text/javascript" src="/applets/galleries/views/galleries.js"></script>
    <script type="text/javascript"> var images = []; </script>
	<form action="/backend/process/applets/galleries/add" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name"/>
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
		</p>	
	</form>

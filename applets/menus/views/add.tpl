
	<h1>Menus &raquo; Add</h1>
	<hr/>
	<link rel="stylesheet" type="text/css" href="/applets/menus/views/menu.css"/>
	<script type="text/javascript" src="/applets/menus/views/menu.js"></script>
	<script type="text/javascript">
	var menu = [];
	var pages = {$sizzle->applets['content']->models['content']->fetchPages()|json_encode};
	</script>
	<form action="/backend/process/applets/menus/add" method="post">
		<p>
			* Name:
			<input type="text" name="name"/>
		</p>
		<p>
			* Menu:
		</p>
		<div class="menu"></div>
		<p>
			<button type="submit">Save</button>
			<button type="button" class="insert">Insert menu item</button>
		</p>
	</form>
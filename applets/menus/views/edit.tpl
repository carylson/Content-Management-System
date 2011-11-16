
	{$data = $sizzle->applets['menus']->models['menus']->fetch($sizzle->request[3])}
	<h1>Menus &raquo; Edit</h1>
	<hr/>
	<link rel="stylesheet" type="text/css" href="/applets/menus/views/menu.css"/>
	<script type="text/javascript" src="/applets/menus/views/menu.js"></script>
	<script type="text/javascript">
	var menu = {$data['menu']|json_encode};
	var pages = {$sizzle->applets['content']->models['content']->fetchPages()|json_encode};
	</script>
	<form action="/backend/process/applets/menus/edit" method="post">
		<p>
			* Name:
			<input type="text" name="name" value="{$data['name']}"/>
		</p>
		<p>
			* Menu:
		</p>
		<div class="menu"></div>
		<p>
			<button type="submit">Save</button>
			<button type="button" class="insert">Insert menu item</button>
		</p>
		<input type="hidden" name="id" value="{$data['id']}"/>
	</form>

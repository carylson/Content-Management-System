
	<h1>Content &raquo; {$sizzle->request[2]|ucwords}</h1>
	<hr/>
	<form action="" method="post">
		<p>
			Please select an item to continue:
			<select name="title" class="jumptourl">
				<option value="0" selected="selected">Select</option>
				{foreach $sizzle->applets['content']->models['content']->fetchAll() as $data}
					<option value="{$sizzle->request[2]}/{$data['id']}">{$data['name']}</option>
				{/foreach}
			</select>
		</p>
	</form>

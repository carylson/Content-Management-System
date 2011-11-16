
	{$datas = $sizzle->applets['sharers']->models['sharers']->fetchAll()}
	<h1>Sharers &raquo; View All</h1>
	<hr/>
	{if count($datas) > 0}
		<table style="width:100%;">
			<tr>
				<th style="width:75px; text-align:center;">ID</th>
				<th style="width:auto; text-align:left;">Title</th>
				<th style="width:125px; text-align:center;">Actions</th>
			</tr>
			{foreach $datas as $data}
			<tr>
				<td align="center">#{$data['id']}</td>
				<td>{$data['name']}</td>
				<td align="center">
					<a href="edit/{$data['id']}">Edit</a> 
					<a href="delete/{$data['id']}">Delete</a>
				</td>
			</tr>
			{/foreach}
		</table>
	{else}
		<p>There are no sharers in the database.</p>
	{/if}


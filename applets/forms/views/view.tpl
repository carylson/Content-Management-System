{$forms = $sizzle->applets['forms']->models['forms']->fetchAll()}
<h1>Forms &raquo; View All</h1>
<hr/>
{if count($forms) > 0}
	<table style="width:100%;">
		<tr>
			<th style="width:75px; text-align:center;">ID</th>
			<th style="width:auto; text-align:left;">Title</th>
			<th style="width:125px; text-align:center;">Actions</th>
		</tr>
		{foreach $forms as $form}
		<tr>
			<td align="center">#{$form['id']}</td>
			<td>{$form['name']}</td>
			<td align="center">
				<a href="edit/{$form['id']}">Edit</a> 
				<a href="delete/{$form['id']}">Delete</a>
			</td>
		</tr>
		{/foreach}
	</table>
{else}
	<p>There are no forms in the database.</p>
{/if}
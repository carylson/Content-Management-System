
	{$contents = $sizzle->applets['content']->models['content']->fetchContent()}
	{$pages = $sizzle->applets['content']->models['content']->fetchPages()}
	{$options = $sizzle->applets['content']->models['content']->fetchOptions()}

	<h1>Content &raquo; View All</h1>
	<hr/>
	<h2>Pages</h2>
	{if count($pages) > 0}
		<table style="width:100%;">
			<tr>
				<th style="width:75px; text-align:center;">ID</th>
				<th style="width:auto; text-align:left;">Name</th>
				<th style="width:auto; text-align:left;">URL</th>
				<th style="width:125px; text-align:center;">Actions</th>
			</tr>
			{foreach $pages as $page}
				<tr>
					<td style="text-align:center;">#{$page['id']}</td>
					<td>{$page['name']}</td>
					<td>
    					/{$page['url']}
						{if $page['id'] == $options['homepage']}<small><em>(Home page)</em></small>{/if}
						{if $page['id'] == $options['errorpage']}<small><em>(Error page)</em></small>{/if}					
					</td>
					<td style="text-align:center;">
						<a href="edit/{$page['id']}">Edit</a> 
						<a href="delete/{$page['id']}">Delete</a>
					</td>
				</tr>
			{/foreach}
		</table>
	{else}
		<p>There are no pages in the database.</p>
	{/if}
	<br/>
	<h2>Other/Miscellaneous</h2>
	{if count($contents) > 0}
		<table style="width:100%;">
			<tr>
				<th style="width:75px; text-align:center;">ID</th>
				<th style="width:auto; text-align:left;">Name</th>
				<th style="width:125px; text-align:center;">Actions</th>
			</tr>
			{foreach $contents as $content}
				<tr>
					<td style="text-align:center;">#{$content['id']}</td>
					<td>{$content['name']}</td>
					<td style="text-align:center;">
						<a href="edit/{$content['id']}">Edit</a> 
						<a href="delete/{$content['id']}">Delete</a>
					</td>
				</tr>
			{/foreach}
		</table>
	{else}
		<p>There is no content in the database.</p>
	{/if}
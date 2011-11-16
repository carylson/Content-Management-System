	
	{$pages = $sizzle->applets['content']->models['content']->fetchPages()}
	<h1>Sitemaps &raquo; Add</h1>
	<hr/>
	<form action="/backend/process/applets/sitemaps/add" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name"/>
		</p>
		<p>
			* Sitemap:<br/>
    		<table>
    			<tr>
    				<th style="text-align:left;">Name</th>
    				<th style="text-align:left;">URL</th>
    				<th>&nbsp;</th>
    			</tr>
    			{foreach $pages as $page}
    			<tr>
    				<td>{$page['name']}</td>
    				<td>/{$page['url']}</td>
    				<td>
            			<label><input type="radio" name="sitemap[{$page['id']}]" value="1" checked="checked"/> Include</label>
            			<label><input type="radio" name="sitemap[{$page['id']}]" value="0"/> Exclude</label>
    				</td>
    			</tr>
    			{/foreach}
    		</table>
		</p>
		<p>
			<input type="submit" value="Save"/>
		</p>	
	</form>

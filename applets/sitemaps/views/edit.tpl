	
	{$data = $sizzle->applets['sitemaps']->models['sitemaps']->fetch($sizzle->request[3])}
	{$data['content'] = $data['content']}
	{$pages = $sizzle->applets['content']->models['content']->fetchPages()}
	<a href="http://{$smarty.server['SERVER_NAME']}/sitemap.xml/{$data['id']}" target="_blank" style="float:right; position:relative; top:10px;">View XML Sitemap</a>
	<h1>Sitemaps &raquo; Edit</h1>
	<hr/>
	<form action="/backend/process/applets/sitemaps/edit" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name" value="{$data['name']}"/>
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
            			<label><input type="radio" name="sitemap[{$page['id']}]" value="1"
            			{if isset($data['content'][$page['id']]) && $data['content'][$page['id']] == '1'}
            			checked="checked"
            			{/if}
            			/> Include</label>
            			<label><input type="radio" name="sitemap[{$page['id']}]" value="0"
            			{if !isset($data['content'][$page['id']]) || $data['content'][$page['id']] != '1'}
            			checked="checked"
            			{/if}
            			/> Exclude</label>
    				</td>
    			</tr>
    			{/foreach}
    		</table>
		</p>
		<p>
			<input type="submit" value="Save"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
		</p>	
	</form>	

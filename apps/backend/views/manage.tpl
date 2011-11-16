{extends file=$sizzle->apps['backend']->config->template}
{block name='content'}
	
	{if isset($applet) && isset($action)}
		{if isset($id)}
			{$sizzle->applets[$applet]->$action($id)}
		{else}
			{$sizzle->applets[$applet]->$action()}
		{/if}
	{else}
		<h1>Website Management</h1>
		<hr/>
		<p>Please select a website component to manage:</p>
		{foreach $sizzle->apps['backend']->config->managed_views as $k=>$v}
			<h2>{$k}</h2>
			{foreach $v as $k2=>$v2}
    			<a href="/backend/{$k2}/view" class="applet"
    			{if !$sizzle->apps['backend']->models['backend']->authorize($k2, 'view')}
                style="text-decoration: line-through;"
    			{/if}
    			>
    				<div><img src="/applets/{$k2}/icon.png" alt=""/></div>
    				<div class="title">{$sizzle->applets[$k2]->config->name}</div>
    			</a>
    		{/foreach}
    		<div style="clear:left;"></div>
		{/foreach}
	{/if}
	{literal}
	<script type="text/javascript">
	$(document).ready(function(){
		$('.applet').corner();
	});
	</script>
	{/literal}
	
{/block}
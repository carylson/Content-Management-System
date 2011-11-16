<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Sizzle! CMS</title>
		<link rel="icon" type="image/png" href="/templates/backend/images/favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="/templates/backend/styles/reset.css"/>
		<link rel="stylesheet" type="text/css" href="/templates/backend/styles/style.css"/>
		<link rel="stylesheet" type="text/css" href="/templates/backend/styles/misc.css"/>
		<link rel="stylesheet" type="text/css" href="/common/jquery.jqueryui/css/sizzle/jquery-ui-1.8.6.custom.css"/>
		<link rel="stylesheet" type="text/css" href="/common/jquery.uploadify/uploadify.css"/>
		<script type="text/javascript" src="/common/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="/common/jquery.jqueryui/js/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="/common/jquery.corner/jquery.corner.js"></script>		
		<script type="text/javascript" src="/common/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="/common/ckeditor/adapters/jquery.js"></script>
		<script type="text/javascript" src="/common/jquery.uploadify/swfobject.js"></script>
		<script type="text/javascript" src="/common/jquery.uploadify/jquery.uploadify.v2.1.4.min.js"></script>
		<script type="text/javascript" src="/common/jquery.json/json2.js"></script>
		<script type="text/javascript" src="/common/jquery.sizzle-dialog/jquery.sizzle-dialog.js"></script>
		<script type="text/javascript" src="/templates/backend/javascripts/backend.js"></script>
		<script type="text/javascript">
        var sizzle_dialog;
        var sizzle_helpers = {$sizzle->helpers|json_encode};
		</script>		
	</head>
	<body>
		<div class="header"></div>
		<div class="body">
			<div class="container">
				<a href="/backend" class="logo" style="text-transform:none;">Sizzle! CMS</a>
				<ul class="navigation">
					{if isset($smarty.session.sizzle_user) && $smarty.session.sizzle_user !== false}
    					<li {if (!isset($sizzle->request[1]) || ($sizzle->request[1] != 'support' && $sizzle->request[1] != 'upgrade'))}class="current"{/if}>
    						<a href="/backend/">Website Management <span style="font-size:10px; line-height:10px;">&#9660;</span></a>
                            <ul style="width:700px; padding: 20px 0 20px 20px;">
                    		{foreach $sizzle->apps['backend']->config->managed_views as $k=>$v}
                        		<h3>{$k}</h3>
                        		{foreach $v as $k2=>$v2}
                            		<li>
                                		<img src="/applets/{$k2}/icon.png" alt="" style="height:24px; float:right; margin-right:5px;"/>
                                		<a href="/backend/{$k2}/view"
                            			{if !$sizzle->apps['backend']->models['backend']->authorize($k2, 'view')}
                                            style="text-decoration: line-through;"
                            			{/if}
                                		>{$sizzle->applets[$k2]->config->name}</a>
                    					<ul>
                    					{foreach $v2 as $k3=>$v3}
                    						<li><a href="/backend/{$k2}/{$k3}"
                                			{if !$sizzle->apps['backend']->models['backend']->authorize($k2, $k3)}
                                                style="text-decoration: line-through;"
                                			{/if}
                    						>{$v3}</a></li>
                    					{/foreach}
                    					</ul>
                                    </li>
                        		{/foreach}
                    		{/foreach}
                    		</ul>
    					</li>
    					<li {if isset($sizzle->request[1]) && $sizzle->request[1] == 'support'}class="current"{/if}>
    						<a href="/backend/support">Support</a>
    					</li>
                        {if $current_version < $latest_version}
        					<li {if isset($sizzle->request[1]) && $sizzle->request[1] == 'upgrade'}class="current"{/if}>
        						<a href="/backend/upgrade">Upgrade</a>
        					</li>
    					{/if}
					{/if}
				</ul>
				{if !empty($sysmessage)}
					<p class="system-message">{$sysmessage}</p>
				{/if}
                {if $current_version < $latest_version}
                	<p class="upgrade-message">The version of Sizzle! CMS currently installed is out of date; a new version has been released. <a href="/backend/upgrade">Upgrade information &raquo;</a></p>
                {/if}
				{if !empty($message)}
					<p class="session-message">{$message}</p>
				{/if}
				<table style="width:100%;">
					<tr>
						<td class="content">
							{block name='content'}{/block}
							<p>&nbsp;</p>
							<p>&nbsp;</p>
						</td>
						<td style="width:20px;">&nbsp;</td>
						<td class="sidebar" style="width:250px;">
							<h2>Actions:</h2>
							<hr/>
							<ul>
        						<li><img src="/templates/backend/images/globe.png" alt="" style="height:24px; float:left; position:relative; left:-2px; top:-2px"/>Website: {$smarty.server.SERVER_NAME} <a href="http://{$smarty.server.SERVER_NAME}/" target="_blank">View</a></li>
    							{if isset($smarty.session.sizzle_user) && $smarty.session.sizzle_user !== false}
            						<li>
                						<img src="/templates/backend/images/user.png" alt="" style="height:24px; float:left; position:relative; left:-2px; top:-2px"/> Logged in as: {$smarty.session.sizzle_user['email']}
                						<span style="whitespace:no-wrap;"><a href="/backend/users/edit/{$smarty.session.sizzle_user['id']}">Edit</a> | <a href="/backend/logout">Logout</a></span>
            						</li>
        						{else}
            						<li><img src="/templates/backend/images/user.png" alt="" style="height:24px; float:left; position:relative; left:-2px; top:-2px"/> Not logged in!</li>
        						{/if}
							</ul>
							{if isset($smarty.session.sizzle_user) && $smarty.session.sizzle_user !== false}
								{if isset($sizzle->request[1])}
                            		{foreach $sizzle->apps['backend']->config->managed_views as $k=>$v}
                                		{foreach $v as $k2=>$v2}
                                    		{if $sizzle->request[1] == $k2}
            									<br/>
            									<h2>{$k2|ucwords}:</h2>
            									<hr/>
            									<ul class="actions">
                                					{foreach $v2 as $k3=>$v3}
                                						<li><a href="/backend/{$k2}/{$k3}">{$v3}</a></li>
                                					{/foreach}
            									</ul>
            									<br/>
                        					{/if}
                                		{/foreach}
                            		{/foreach}
								{/if}
							{/if}
							<p>&nbsp;</p>
							<p>&nbsp;</p>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="footer">
			<p>Sizzle! CMS version {$current_version}<br/>&copy; {$smarty.now|date_format:'%Y'} <a href="http://www.noein.com/">Noein Inc.</a>, All Rights Reserved.</p>
		</div>
        {if $sizzle->debug->mode}
    		{$sizzle->debug->injectDebugger()}
		{/if}
	</body>
</html>

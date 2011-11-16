{extends file=$sizzle->apps['backend']->config->template}
{block name='content'}

	<h1>Upgrade</h1>
	<hr/>
    {if $current_version < $latest_version}
    	<h3>Installed version: <span style="color:#999;">{$current_version|string_format:'%.1f'}</span><br/>Latest version: <span style="color:#999;">{$latest_version|string_format:'%.1f'}</span></h3>
    	<p>Upgrade to the latest version of Sizzle! CMS immediately; click the "upgrade" button below!</p>
    	<p><strong>Warning: Upgrading will undo any code customizations that you've made.</strong></p>
    	<form action="/backend/process/apps/backend/upgrade" method="post">
    		<p><input type="submit" value="Upgrade"/></p>
    	</form>
    	<p>&nbsp;</p>
    	<p>Please contact <a href="http://www.noein.com" target="_blank">Noein Inc.</a> or visit the <a href="http://www.thesizzlecms.com" target="_blank">Sizzle! CMS</a> website for more information.</p>
    	<script type="text/javascript">
    	$(document).ready(function(){
        	$('input[type="submit"]').click(function(){

                var html = '<p><strong>Warning: Upgrading will undo any code customizations that you\'ve made.</strong></p><p>Would you like to proceed with the upgrade?</p>';
                $.sizzleDialog('confirm', html, function(){
                    var url = '/backend/ajax/apps/backend/upgrade';
                    var data = $(this).serialize();
                    $.sizzleDialog('ajax', url, data, function(resultJson){
                        result = $.parseJSON(resultJson);
                        $.sizzleDialog('alert', '<p>'+result.message+'</p>');
                    });
                });

            	return false;
        	});
    	});
    	</script>
	{else}
    	<p>The version of Sizzle! CMS currently installed on this website, {$sizzle->config->version}, is up to date!  There are no upgrades at this time.</p>
	{/if}

{/block}
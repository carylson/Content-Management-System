	
	{$sizzle_user = $sizzle->applets['users']->models['users']->fetch($smarty.session.sizzle_user['id'])}
	{$data = $sizzle->applets['users']->models['users']->fetch($sizzle->request[3])}
	<h1>Users &raquo; Edit</h1>
	<hr/>
	<form action="/backend/process/applets/users/edit" method="post">
		<p>
			First Name:<br/>
			<input type="text" name="first_name" value="{$data['first_name']}" style="width:300px;"/>
		</p>
		<p>
			Last Name:<br/>
			<input type="text" name="last_name" value="{$data['last_name']}" style="width:300px;"/>
		</p>
		<p>
			* E-mail:<br/>
			<input type="text" name="email" value="{$data['email']}" style="width:500px;"/>
		</p>
		<p>
			* Password:<br/>
			<input type="password" name="password" value="{$data['password']}"/>
		</p>
		<p>
			* Password (confirm):<br/>
			<input type="password" name="password_confirm" value="{$data['password']}"/>
		</p>
		<p>
			Access:<br/>
			<table>
    			{foreach $sizzle->apps['backend']->config->managed_views as $k=>$v}
    				{foreach $v as $k2=>$v2}
    				<tr>
    					<td align="right">{$sizzle->applets[$k2]->config->name}:</td>
    					<td>
    						{foreach $v2 as $k3=>$v3}
    						<label>
        						<input type="checkbox" name="access[{$k2}][]" value="{$k3}"
        							{if isset($data['access'][$k2]) && in_array($k3, $data['access'][$k2])}
        				                checked="checked"
        				            {/if}
        				            {if !$sizzle->apps['backend']->models['backend']->authorize($k2, $k3)}
            				            disabled="disabled"
        				            {/if}
        						/> {$v3}
    						</label>
    						{/foreach}
    					</td>
    					<td align="right" style="width:100px;">
    				        {if !empty($sizzle_user['access'][$k2])}
        						<a href="#" class="rowtoggle">(Toggle)</a>
    						{/if}
    					</td>
    				</tr>
    				{/foreach}
    			{/foreach}
				<tr>
					<td colspan="3" align="right"><a href="#" class="alltoggle">(Toggle All)</a></td>
				</tr>
			</table>
		</p>
		<p>
			<input type="submit" value="Save"/>
			<input type="hidden" name="id" value="{$data['id']}"/>
		</p>	
	</form>
	{literal}
	<script type="text/javascript">
	$(document).ready(function(){
		$('.rowtoggle').click(function(){
			var checks = $(this).closest('tr').find(':input[type=checkbox]:not(:disabled)');
			toggleChecks(checks);
			return false;
		});
		$('.alltoggle').click(function(){
			var checks = $(this).closest('table').find(':input[type=checkbox]:not(:disabled)');
			toggleChecks(checks);
			return false;
		});
	});
	function toggleChecks(checks) {
		checks.each(function(){
			if ($(this).is(':checked')) {
				$(this).attr('checked', false);
			} else {
				$(this).attr('checked', true);
			}
		});
		return;
	}
	</script>
	{/literal}

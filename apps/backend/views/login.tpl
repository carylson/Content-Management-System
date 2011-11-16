{extends file=$sizzle->apps['backend']->config->template}
{block name='content'}
	<h1>Login</h1>
	<hr/>
	<form action="/backend/process/apps/backend/login" method="post">
		<p>
			E-mail Address:<br/>
			<input type="text" name="email"/>
		</p>
		<p>
			Password:<br/>
			<input type="password" name="password"/>
		</p>
		<p>
			<input type="submit" value="Login"/>
		</p>
	</form>
{/block}
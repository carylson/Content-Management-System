<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>{sizzle_meta type='title'}</title>
		<meta name="keywords" content="{sizzle_meta type='keywords'}"/>
		<meta name="description" content="{sizzle_meta type='description'}"/>
		<link rel="shortcut icon" href="/templates/backend/img/favicon.ico"/>
	</head>
	<body>
    	<h1>Hello, I'm Aaron Caryl</h1>
    	<h2>Web Developer/Software Engineer Extraordinaire</h2>
    	{sizzle_menu}
		{if isset($message)}
			<h4>{$message}</h4>
		{/if}
    	<p>You are here: {sizzle_breadcrumbs}</p>
    	{sizzle_content_by_url}
    	{sizzle_menu}
    	{sizzle_content id='5'}
        {sizzle_analytic}
        {if $sizzle->debug->mode}
    		{$sizzle->debug->injectDebugger()}
		{/if}
	</body>
</html>

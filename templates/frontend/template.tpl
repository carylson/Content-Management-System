<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>{sizzle_meta type='title'}</title>
		<meta name="keywords" content="{sizzle_meta type='keywords'}"/>
		<meta name="description" content="{sizzle_meta type='description'}"/>
		<link rel="shortcut icon" href="/templates/frontend/img/favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="/templates/frontend/css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="/templates/frontend/css/layout.css"/>
		<script type="text/javascript" src="/templates/frontend/js/jquery/jquery-1.5.2.min.js"></script>
		<script type="text/javascript" src="/templates/frontend/js/jquery.corner/jquery.corner.js"></script>
		<script type="text/javascript" src="/templates/frontend/js/jquery.imagePreloader/jquery.imagePreloader.js"></script>
		<script type="text/javascript" src="/common/jquery.uploadify/swfobject.js"></script>
		<script type="text/javascript" src="/common/jquery.uploadify/jquery.uploadify.v2.1.4.min.js"></script>
		<script type="text/javascript" src="/templates/frontend/js/inline.js"></script>
	</head>
	<body>
        <div class="colorful">
    		<div class="container">
    			<div class="header">
    				<a href="/" class="logo">
    					Hello, I'm Aaron Caryl
    					<span>Web Developer/Software Engineer Extraordinaire</span>
    				</a>
    				{sizzle_menu}
    			</div>
    			<div class="body">
    				<div class="col1-container">
    					<div class="col2-container">
    						<div class="col1">
    							<div class="content">
        							{if isset($message)}
        								<div class="session-message">{$message}</div>
        							{/if}
        							<div class="breadcrumbs">
            							You are here: {sizzle_breadcrumbs}
        							</div>
                					{sizzle_content_by_url}
    							</div>
    						</div>
    						<div class="col2">
    							<div class="sidebar">
    								<div class="icons">
    									<a href="/blog.xml" title="Subscribe to my blog feed"><img src="/templates/frontend/img/rss.png" alt=""/></a>
    									<a href="#" title="Add to favorites"><img src="/templates/frontend/img/favorite.png" alt=""/></a>
    									<a href="http://www.linkedin.com/in/aaroncaryl" target="_blank" title="View my LinkedIn profile"><img src="/templates/frontend/img/linkedin.png" alt=""/></a><br/>
    								</div>
                					{sizzle_content id='6'}
    								<div class="icons">
    									<img src="/templates/frontend/img/apple.png" alt=""/><br/>Made on a Mac :-)
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    				<div style="clear:left;"></div>
    			</div>
    			<div class="footer">
    				{sizzle_menu}
    				{sizzle_content id='5'}
    			</div>
    		</div>
		</div>
        {sizzle_analytic}
        {if $sizzle->debug->mode}
    		{$sizzle->debug->injectDebugger()}
		{/if}
	</body>
</html>

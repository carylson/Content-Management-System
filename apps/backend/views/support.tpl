{extends file=$sizzle->apps['backend']->config->template}
{block name='content'}

	<h1>Support</h1>
	<hr/>
    	<p>Please contact <a href="http://www.noein.com" target="_blank">Noein Inc.</a> or visit the <a href="http://www.thesizzlecms.com" target="_blank">Sizzle! CMS</a> website for all Sizzle! CMS support inquiries.</p>
	<br/>
	<h2>Frequently Asked Questions:</h2>
	<ol>
		{foreach $faqs as $faq}
			<li><a href="#q{$faq['faq_id']}">{$faq['title']}</a></li>
		{/foreach}
	</ol>
	<p>&nbsp;</p>
	<p><hr/></p>
	{foreach $faqs as $faq}
		<a id="q{$faq['faq_id']}"></a>
		<h3>{$faq['title']}</h3>
		<p>{$faq['content']}</p>
		<p><a href="#">Top</a></p>
		<p><hr/></p>
	{/foreach}

{/block}
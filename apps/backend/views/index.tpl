{extends file=$sizzle->apps['backend']->config->template}
{block name='content'}
	<h1>Home</h1>
	<hr/>
	<p>Welcome to <strong>Sizzle CMS</strong>, a customized website content management system created by <a href="http://www.noein.com/" target="_blank">Noein Inc.</a></p>
	<blockquote style="margin:20px 40px; color:#666; font-style:italic;">
	<p>"A Web Content Management System is content management system (CMS) software, implemented as a Web application, for creating and managing HTML content. It is used to manage and control a large, dynamic collection of Web material (HTML documents and their associated images). A WCMS facilitates content creation, content control, editing, and essential Web maintenance functions.</p>
	<p>The software provides authoring (and other) tools designed to allow users with little knowledge of programming languages or markup languages to create and manage content with relative ease, and can be viewed primarily as a Web-site maintenance tool for non-technical administrators."</p>
	<p>- <a href="http://en.wikipedia.org/wiki/Web_content_management_system">Wikipedia "web content management system"</a></p>
	</blockquote>
	<p>To begin management of your website, visit the <a href="/backend/manage">Website Management</a> page.</p>
	<p>If you are in need of assistance, or for all other support-related activities, visit the <a href="/backend/support">Support</a> page.</p>
{/block}
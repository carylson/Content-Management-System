	
	<h1>Blog &raquo; Add</h1>
	<hr/>
	<form action="/backend/process/applets/blog/add" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name"/>
		</p>
		<p>
			* Title:<br/>
			<input type="text" name="title" style="width:95%;"/>
		</p>
		<p>
			* Content:<br/>
			<textarea style="width:100%; height:420px;" class="wysiwyg" name="content"></textarea>
		</p>
		<p>
			<input type="submit" value="Save"/>
		</p>	
	</form>
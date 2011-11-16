
	{$data = $sizzle->applets['forms']->models['forms']->fetch($sizzle->request[3])}
	<h1>Forms &raquo; Edit</h1>
	<hr/>
	<link rel="stylesheet" type="text/css" href="/applets/forms/views/forms.css"/>
	<script type="text/javascript" src="/applets/forms/views/forms.js"></script>
	<script type="text/javascript"> var form = {$data['form']|json_encode}; </script>
    <form action="/backend/process/applets/forms/edit" method="post">
		<p>
			* Name:
			<input type="text" name="name" value="{$data['name']}"/>
		</p>
    	<p>
    		* To:
    		<input type="text" name="to" value="{$data['to']}"/>
    	</p>
    	<p>
    		* From:
    		<input type="text" name="from" value="{$data['from']}"/>
    	</p>
    	<p>
    		* Captcha:
    		<label><input type="radio" name="captcha" value="1"
    		{if $data['captcha']}
    		checked="checked"
    		{/if}
    		/> Yes</label>
    		<label><input type="radio" name="captcha" value="0"
    		{if !$data['captcha']}
    		checked="checked"
    		{/if}
    		/> No</label>
    	</p>
		<p>
			* Form fields:
		</p>
    	<p>
    		<select class="insert">
    			<option value="">Add a form field</option>
    			<option value="text">Text</option>
    			<option value="password">Password</option>
    			<option value="textarea">Textarea</option>
    			<option value="select">Select</option>
    			<option value="radios">Radios</option>
    			<option value="checkboxes">Checkboxes</option>
    			<option value="file">File</option>
    		</select>
    	</p>
    	<div class="form"></div>
    	<p>
    		<button type="submit">Save</button>
    	</p>
		<input type="hidden" name="id" value="{$data['id']}"/>
	</form>
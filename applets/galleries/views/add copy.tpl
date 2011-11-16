	
	<h1>Galleries &raquo; Add</h1>
	<hr/>
	<form action="/backend/process/applets/galleries/add" method="post">
		<p>
			* Name:<br/>
			<input type="text" name="name"/>
		</p>
		<p>
			* Images:<br/>
			<span class="uploadify" id="upload"></span>
		</p>
		<p>
    		<span class="images">No images have been added.</span>
		</p>
		<p>
			<input type="submit" value="Save"/>
		</p>	
	</form>

    <script type="text/javascript">
    $(document).ready(function() { 
    	bindUploader($('.uploadify'));
    });
    function bindUploader(elements) {
        elements.each(function() {
            var element = $(this);
        	element
            	.html('<input type="file" name="fileInput" id="fileInput'+element.attr('id')+'"/>')
            	.find('input[type="file"]')
            	.uploadify({
            		uploader: '/common/jquery.uploadify/uploadify.swf',
            		expressInstall: '/common/jquery.uploadify/expressInstall.swf',
            		script: '/common/jquery.uploadify/upload.php',
            		cancelImg: '/common/jquery.uploadify/cancel.png',
            		auto: true,
            		folder: '/uploads',
            		fileExt: '*.png;*.jpg;*.jpeg;*.JPEG;*.gif;*.bmp;*.tiff',
            		fileDesc: 'Select an image!',
            		onComplete: function(e, q, f, r, d) {
            			var html;
            			if (r != 'error') {
            				html = $('\
                    				<span>File uploaded successfully.</span>\
                    				<a href="/uploads/'+r+'" target="_blank">View</a>\
                    				<a href="#">Reset</a>\
                    				<input type="hidden" name="'+element.attr('id')+'" value="'+r+'"/>\
                    				');
            			} else {
            				html = $('\
                    				<span>Error.</span>\
                    				<a href="#">Reset</a>\
                    				');
            			}
            			element.html(html);
            			bindReset(element.find('a:last'));
            		}
            	});
        });
    }
    function bindReset(element) {
        element.click(function(){
            bindUploader(element.parent());
            return false;
        });
    }
    </script>
    
    var images = [];
    
    $(document).ready(function() { 

    	$(images).each(function(i){
    		insertItem(images[i]);
    	});

    	$('.images').sortable({
    		handle: 'a:first'
    	});

    	bindUploader($('.uploadify'));

    });
    
    function bindUploader(element) {
    	element
        	.html('<input type="file" name="fileInput" id="fileInput"/>')
        	.find('input[type="file"]')
        	.uploadify({
        		uploader: '/common/jquery.uploadify/uploadify.swf',
        		expressInstall: '/common/jquery.uploadify/expressInstall.swf',
        		script: '/common/jquery.uploadify/upload.php',
        		cancelImg: '/common/jquery.uploadify/cancel.png',
        		auto: true,
        		multi: true,
        		buttonText: 'UPLOAD',
        		folder: '/uploads/galleries',
        		fileExt: '*.png;*.jpg;*.jpeg;*.JPEG;*.gif;*.bmp;*.tiff',
        		fileDesc: 'Select an image!',
        		onComplete: function(e, q, f, r, d) {
        			var id = $('input[type="hidden"][name^="images["][name$="][image]"]').length;
        			var html;
        			if (r != 'error') {
            			insertItem({ image: r, caption: '', date: '' });
        			}
        		}
        	});
    }
    
    function bindEdit(element) {
        element.click(function(){
    		var buttons = [{
    	        text: 'Save',
    	        click: function() {
    		        element
        		        .closest('span')
        		        .find('input[name$="[caption]"]')
        		        .val(sizzle_dialog.find('input[name="caption"]').val());
    		        element
        		        .closest('span')
        		        .find('input[name$="[date]"]')
        		        .val(sizzle_dialog.find('input[name="date"]').val());
    		        $(this).dialog('close');
    	        }
    		}];
    		sizzle_dialog
    			.html('\
            	<table>\
            		<tr>\
            			<td style="vertical-align:middle; text-align:left; padding-right:10px;">\
                			<img src="'+element.closest('span').find('> a:last img').attr('src')+'" alt=""/>\
            			</td>\
            			<td style="vertical-align:middle; text-align:left;">\
            				<form action="" method="post">\
            					<p>Caption:<br/><input type="text" name="caption" value="'+element.closest('span').find('input[name$="[caption]"]').val()+'" style="width:250px;"/></p>\
            					<p>Date:<br/><input type="text" name="date" value="'+element.closest('span').find('input[name$="[date]"]').val()+'"/></p>\
            				</form>\
            			</td>\
            		</tr>\
            	</table>\
    			')
    			.dialog('option', { title: 'Edit Image Details:', buttons: buttons })
        		.dialog('open');
            return false;
        });
    }
    
    function bindDelete(element) {
        element.click(function(){
            $(this).closest('span').remove();
            if ($('.images > span').length <= 0) {
                $('.images').html('<span class="default">No images have been added.</span>');
            }
            return false;
        });
    }
    
    function insertItem(json) {
    	var id = $('input[type="hidden"][name^="images["][name$="][image]"]').length;
        var html = $('\
    	<span class="image">\
    		<a href="#" class="move">\
    			<img src="/templates/backend/images/magnet.png"/>\
    		</a>\
    		<a href="#" class="edit">\
    			<img src="/templates/backend/images/edit.png"/>\
    		</a>\
    		<a href="#" class="delete">\
    			<img src="/templates/backend/images/delete.png"/>\
    		</a>\
    		<a href="/uploads/galleries/'+json.image+'" target="_blank">\
    			<img src="/common/phpthumb/?src=/uploads/galleries/'+json.image+'&x=128&y=128" class="thumbnail"/>\
    		</a>\
    		<input type="hidden" name="images['+id+'][image]" value="'+json.image+'"/>\
    		<input type="hidden" name="images['+id+'][caption]" value="'+json.caption+'"/>\
    		<input type="hidden" name="images['+id+'][date]" value="'+json.date+'"/>\
    	</span>\
        ');
    	bindEdit(html.find('a:eq(1)'));
    	bindDelete(html.find('a:eq(2)'));
    	$('.images')
    		.append(html)
    		.find('.default')
    		.remove();
    }

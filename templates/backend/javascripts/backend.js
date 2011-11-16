$(document).ready(function(){
	
	/* 
	Corner the navigation links & form elements.
	*/
	
	$('.navigation > li, .actions li').corner('top');
	$('.navigation ul:first').corner('bottom');
	$('.system-message').corner();
	$('.upgrade-message').corner();
	$('input[type=submit]').button();

	/* 
	Zebra stripe the content tables.
	*/
	
	//$('.content table tr:not(:last) td').css('border-bottom', '1px dashed #eee');
	$('.content > table tr:odd td')
    	.css('background-color', '#eee')
    	.css('border-bottom', 'none');

	/* 
	Jump-to-url select-box helper-thing. 
	*/
	
	$('.jumptourl').change(function(){
		$(location).attr('href', $(this+':selected').val());
	});

	/* 
	Calendar helper-thing. 
	*/
	
	$('.calendar').datepicker({ dateFormat: 'yy-mm-dd' });
	
	/*
	Form input maxlength helper-thing.
	*/
	
	$('form input[maxlength]')
    	.keyup(function(){
        	$(this)
            	.siblings('.indicator:first')
            	.html('<small><em>(Characters remaining: '+($(this).attr('maxlength') - $(this).val().length)+')</em></small>');
    	})
    	.focus(function(){
        	var indicator = $(this).siblings('.indicator:first');
        	if (indicator.length == 0) {
            	$('<span class="indicator"><small><em>(Characters remaining: '+$(this).attr('maxlength')+')</em></small></span>')
                	.appendTo($(this).parent());
        	} else {
            	indicator
                	.html('<small><em>(Characters remaining: '+($(this).attr('maxlength') - $(this).val().length)+')</em></small>')
                	.css('display', 'inline');
        	}
    	})
    	.blur(function(){
        	$(this)
            	.siblings('.indicator:first')
            	.css('display', 'none');
    	});
	
	/* 
	WYSIWYG editor 
	Note: Loop shenanegains exist to allow (inline) height setting, per instance.
	*/
	
	$('.wysiwyg').each(function(){
		var height = $(this).css('height') ? parseInt($(this).css('height')) : 200 ;
		var editor = $(this).ckeditor({
			uiColor: '#eee',
			height: height,
			toolbar:
			[
				['Bold','Italic','Underline','Strike','Subscript','Superscript','Blockquote'],
				['NumberedList','BulletedList','Outdent','Indent'],
				['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
				'/',
				['Styles','Format','Font','FontSize'],
				['TextColor','BGColor'],
				['Cut','Copy','Paste','PasteText','PasteFromWord'],
				'/',
				['Link','Unlink','Anchor','Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],
				['sizzle'],
				['SpellChecker','Scayt'],
				['Source'],
				['Maximize'],
				['Undo','Redo']
			],
			filebrowserBrowseUrl: '/common/ajaxplorer/?external_selector_type=ckeditor&relative_path=/uploads',
			filebrowserImageBrowseUrl: '/common/ajaxplorer/?external_selector_type=ckeditor&relative_path=/uploads',
			filebrowserFlashBrowseUrl: '/common/ajaxplorer/?external_selector_type=ckeditor&relative_path=/uploads',
			filebrowserWindowWidth: '800',
			filebrowserWindowHeight: '490',
			entities: false,
            extraPlugins: 'sizzle'
		});
	});
	
	/* 
	Session message.
	*/
	
	if ($('.session-message').length > 0) {
		var html = $('.session-message').hide().html();
        $.sizzleDialog('alert', html);
	}
	
	/* 
	Enhanced form processing.
	*/

	$('form[action^="/backend/process"]').submit(function(){
    	var url = $(this).attr('action').replace('/backend/process', '/backend/ajax');
    	var data = $(this).serialize();
        $.sizzleDialog('ajax', url, data, function(resultJson){
            result = $.parseJSON(resultJson);
            var html = '<p>'+result.message+'</p>';
            if (result.redirect) {
                $.sizzleDialog('confirm', html, function(){
                    window.location = result.redirect;
                });
            } else {
                $.sizzleDialog('alert', html);
            }
        });
		return false;
	});

});
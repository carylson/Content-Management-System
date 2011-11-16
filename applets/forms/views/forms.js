function bindAddOption(element) {
    element
		.button({ icons: { primary: 'ui-icon-plus' }, text: false  })
        .click(function(){
			// Correct "sticky" button look; remove the ui-hover/focus classes!
			$(this).removeClass('ui-state-focus ui-state-hover');
    		insertOption(element.siblings('ol'));
            return false;
        });
}

function bindDrawerToggle(element) {
	element
		.button({ icons: { primary: 'ui-icon-wrench' }, text: false })
    	.click(function(){
			// Correct "sticky" button look; remove the ui-hover/focus classes!
			$(this).removeClass('ui-state-focus ui-state-hover');
			var drawer = $(this).closest('li').find('.drawer:first');
			if (drawer.is(':visible')) {
				// Close drawer.
				$(this)
					.closest('li')
					.find('.name:first')
					.removeClass('ui-corner-top')
					.addClass('ui-corner-all');
				drawer.animate({ opacity: 'hide', height: 'hide' }, 'fast');
			} else {
				// Open drawer.
				$(this)
					.closest('li')
					.find('.name:first')
					.removeClass('ui-corner-all')
					.addClass('ui-corner-top');
				drawer.animate({ opacity: 'show', height: 'show' }, 'fast');
			}
			return false;
		});
}

function bindInsertItem(element) {
	element.change(function(){
		insertItem({ type: element.val() }, $('.form'));
        // re-select the first element
		element
            .find('option:first')
            .attr('selected', 'selected');
		return false;
	});
}

function bindLabel(element) {
    element.change(function(){
        var type = element
        			.closest('li')
                    .find('input[name="type"]')
                    .val();
        element
			.closest('li')
			.find('.name')
			.text(type+': '+element.val());
    });
}

function bindRemove(element) {
	element
		.button({ icons: { primary: 'ui-icon-close' }, text: false  })
		.click(function(){
			// Correct "sticky" button look; remove the ui-hover/focus classes!
			$(this).removeClass('ui-state-focus ui-state-hover');
			// Removes the appropriate <li> element.
			var item = $(this).closest('li');
			item.animate({ opacity: 'hide', height: 'hide' }, 'fast', function(){ item.remove(); });
			return false;
		});
}

function bindReset(element) {
	// Note: built assuming a form reset element is being used; fn. doesn't reset values itself!
	element
		.button({ icons: { primary: 'ui-icon-arrowreturnthick-1-w' } })
		.click(function(){
			// Correct "sticky" button look; remove the ui-hover/focus classes!
			$(this).removeClass('ui-state-focus ui-state-hover')
			// Reset closest heading element!
            var type = element
            			.closest('li')
                        .find('input[name="type"]')
                        .val();
            var defaultLabel = element
            			.closest('li')
                        .find('input[name="label"]')[0]
                        .defaultValue;
            var label = type;
            if (defaultLabel.length > 0) {
                label += ': '+defaultLabel;
            }
            element
    			.closest('li')
    			.find('.name')
    			.text(label);
		});
}

function bindSave(element) {
	element
		.button({ icons: { primary: 'ui-icon-check' } })
		.click(function(){
			$('input[name^=form]').remove();
			parseForForm();
		});
}

function insertItem(json, appendTo) {
	
	//console.log(json);
	
	var value, options, selected;
	
	value = typeof json.label != 'undefined' ? json.label : '' ;
	var labelHtml = $('<div>Label: <input type="text" name="label" value="'+value+'"/></div>');

	value = typeof json.size != 'undefined' ? json.size : '' ;
	var sizeHtml = $('<div>Size: <select  name="size"></select></div>');
    options = { 'small': 'Small', 'medium': 'Medium', 'large': 'Large' };
    for (var i in options) {
        selected = i == value ? 'selected="selected"' : '' ;
        $('<option value="'+i+'" '+selected+'>'+options[i]+'</option>').appendTo(sizeHtml.find('select'));
    }

	value = typeof json.defaultvalue != 'undefined' ? json.defaultvalue : '' ;
	var defaultValueHtml = $('<div>Default Value: <input type="text" name="defaultvalue" value="'+value+'"/></div>');

	value = typeof json.maxlength != 'undefined' ? json.maxlength : '' ;
	var maximumLengthHtml = $('<div>Maximum Length: <input type="text" name="maxlength" maxlength="2" size="3" value="'+value+'"/> characters</div>');

	value = typeof json.instruction != 'undefined' ? json.instruction : '' ;
	var instructionHtml = $('<div>Instruction:<br/><textarea name="instruction" style="width:100%; height:50px;">'+value+'</textarea></div>');

	value = typeof json.required != 'undefined' ? json.required : '' ;
	var requiredHtml = $('<div>Required: <select name="required"></select></div>');
    options = { 0: 'No', 1: 'Yes' };
    for (var i in options) {
        selected = i == value ? 'selected="selected"' : '' ;
        $('<option value="'+i+'" '+selected+'>'+options[i]+'</option>').appendTo(requiredHtml.find('select'));
    }    

    var optionsHtml = $('<div>Options: <button type="button" class="add-option">Add Option</button><ol></ol></div>');
	if (typeof json.options != 'undefined') {
        $(json.options).each(function(i){
            insertOption(optionsHtml.find('ol'), json.options[i]);
        });
	}
	
	var ul = $('.form ul:first');
	if (ul.length <= 0) {
		ul = $('<ul></ul>').appendTo('.form');
	}
	
	var name = typeof json.label != 'undefined' ? json.type + ': ' + json.label : json.type ;
	
	var item = $('\
	<li class="ui-widget">\
		<div class="name ui-widget-header ui-corner-all">'+name+'</div>\
		<div class="drawer ui-widget-content ui-corner-bottom">\
			<form>\
            	<button type="reset">Reset</button>\
			</form>\
		</div>\
		<input type="hidden" name="type" value="'+json.type+'"/>\
		<div class="buttons">\
			<button type="button" class="toggle">Edit</button>\
        	<button type="button" class="remove">Remove</button>\
		</div>\
	</li>\
	');
	
	if (json.type == 'password') {
        item.find('form')
            .prepend(requiredHtml)
            .prepend(instructionHtml)
            .prepend(maximumLengthHtml)
            .prepend(defaultValueHtml)
            .prepend(sizeHtml)
            .prepend(labelHtml);
	} else if (json.type == 'textarea') {
        item.find('form')
            .prepend(requiredHtml)
            .prepend(instructionHtml)
            .prepend(defaultValueHtml)
            .prepend(sizeHtml)
            .prepend(labelHtml);
	} else if (json.type == 'select') {
        item.find('form')
            .prepend(optionsHtml)
            .prepend(requiredHtml)
            .prepend(instructionHtml)
            .prepend(labelHtml);
	} else if (json.type == 'radios') {
        item.find('form')
            .prepend(optionsHtml)
            .prepend(requiredHtml)
            .prepend(instructionHtml)
            .prepend(labelHtml);
	} else if (json.type == 'checkboxes') {
        item.find('form')
            .prepend(optionsHtml)
            .prepend(requiredHtml)
            .prepend(instructionHtml)
            .prepend(labelHtml);
	} else if (json.type == 'file') {
        item.find('form')
            .prepend(requiredHtml)
            .prepend(instructionHtml)
            .prepend(labelHtml);
	} else {
        item.find('form')
            .prepend(requiredHtml)
            .prepend(instructionHtml)
            .prepend(maximumLengthHtml)
            .prepend(defaultValueHtml)
            .prepend(sizeHtml)
            .prepend(labelHtml);
	}
	
	bindDrawerToggle(item.find('.toggle'));
	bindRemove(item.find('.remove'));
	bindReset(item.find('button[type="reset"]'));
	bindLabel(item.find('input[name="label"]'));
	bindAddOption(item.find('.add-option'));

	item.appendTo(ul);

}

function insertOption(appendTo, value) {
	if (typeof value == 'undefined') value = '';
    var html = $('<li><input type="text" name="options[]" value="'+value+'" size="10"/> <button type="button">Remove Option</button></li>')
	bindRemove(html.find('button'));
    appendTo.append(html);
}

function parseForForm() {
	// Iterate over the constructed form structure and create an equivalent hidden form array-element to POST.
	$('.form')
		.find('> ul > li')
		.each(function(i){
			// Set a "search radius" to make sure we're searching ONLY this <li>; no parents/children!
			var searchRadius = $(this)/* .find('> .drawer') */;
			var append = '';
			var fields = ['type', 'label', 'size', 'defaultvalue', 'maxlength', 'instruction', 'required'];
			// Append field data...
			$(fields).each(function(j){
    			if (searchRadius.find('[name="'+fields[j]+'"]').length > 0) {
                    append += '<input type="hidden" name="form['+i+']['+fields[j]+']" value="'+searchRadius.find('[name="'+fields[j]+'"]').val()+'"/>';
    			}
			});
			// Append field options data...
            if (searchRadius.find('input[name="options[]"]').length > 0) {
                searchRadius.find('input[name="options[]"]').each(function(j){
                    append += '<input type="hidden" name="form['+i+'][options]['+j+']" value="'+$(this).val()+'"/>';
                });
            }
			$(this).append(append);
		});
}

var form = [];
$(document).ready(function(){
	$(form).each(function(i){
		insertItem(form[i], $('.form'));
	});
	$('.form').sortable({
		placeholder: 'ui-state-highlight', 
		items: '> ul > li',
		start: function(){
			$('.ui-state-highlight').addClass('ui-corner-all');
		},
		revert: 125
	});
	bindInsertItem($('.insert'));
	bindSave($('button[type="submit"]'));
});

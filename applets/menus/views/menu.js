
function drawPageSelect(selected_id) {
	// Create a select element.
	var select = $('<select name="id"></select>')
		.append('<option value="" class="ignore-opt">Select</option>');
	// Iterate over the db "pages" array and append option elements.
	$(pages).each(function(i){
		var selected = pages[i].id == selected_id ? 'selected="selected"' : '' ;
		select.append('<option value="'+pages[i].id+'" '+selected+'>'+pages[i].name+'</option>');
	});
	// Return the html.  Note: wrap/parent/html needed to return entire select element.
	return select
		.wrap('<div></div>')
		.parent()
		.html();
}

function parseForNav(element, navIndex) {
	// Iterate over the constructed menu structure and create an equivalent hidden form array-element to POST.
	element
		.find('> ul > li')
		.each(function(i){
			// Set a "search radius" to make sure we're searching ONLY this <li>; no parents/children!
			var searchRadius = $(this).find('> .sort');
			var pageId, pageAltName, pageAltUrl;
			if (searchRadius.find('select[name="id"] option:not(.ignore-opt):selected').length) {
				pageId = searchRadius.find('select[name="id"] option:not(.ignore-opt):selected').val();
				pageAltName = '';
				pageAltUrl = '';
			} else {
				pageId = '';
				pageAltName = searchRadius.find('input[name="alt_name"]').val();
				pageAltUrl = searchRadius.find('input[name="alt_url"]').val();
			}
			var pageHidden = searchRadius.find('input[type="checkbox"]').is(':checked') ? true : false ;
			var append = '\
			<input type="hidden" name="menu'+navIndex+'['+i+'][id]" value="'+pageId+'"/>\
			<input type="hidden" name="menu'+navIndex+'['+i+'][alt_name]" value="'+pageAltName+'"/>\
			<input type="hidden" name="menu'+navIndex+'['+i+'][alt_url]" value="'+pageAltUrl+'"/>\
			<input type="hidden" name="menu'+navIndex+'['+i+'][hidden]" value="'+pageHidden+'"/>\
			';
			$(this).append(append);
			// Recurse!
			parseForNav($(this), navIndex+'['+i+'][children]');
		});
}

function bindSave(element) {
	element
		// Make it a ui-button.
		.button({ icons: { primary: 'ui-icon-check' } })
		// Remove any existing parse-related hidden array-elemenets, and parse!
		.click(function(){
			$('input[name^=menu]').remove();
			parseForNav($('.menu'), '');
		});
}

function bindInsertItem(element) {
	// Appends an empty <li> element to the current menu structure.
	element
		.button({ icons: { primary: 'ui-icon-plus' } })
		.click(function(){
			insertItem({ id: 0, alt_name: '', alt_url: '', hidden: false }, $('.menu'));
			$(this).removeClass('ui-state-focus ui-state-hover');
			return false;
		});
}

function bindItemDemote(element) {
	element
		// Make it a ui-button.
		.button({
			icons: { primary: 'ui-icon-triangle-1-w' },
			text: false
		})
		.click(function(){
			// Correct "sticky" button look; remove the ui-hover/focus classes!
			$(this).removeClass('ui-state-focus ui-state-hover');
			// Demotes the appropriate <li> element a(n) <ul> element.
			var item = $(this).closest('li');
			var destination = item.parents('li:first');
			if (destination.length <= 0) { return false; }
			item.insertAfter(destination);
			return false;
		});
}

function bindItemPromote(element) {
	element
		// Make it a ui-button.
		.button({
			icons: { primary: 'ui-icon-triangle-1-e' },
			text: false
		})
		.click(function(){
			// Correct "sticky" button look; remove the ui-hover/focus classes!
			$(this).removeClass('ui-state-focus ui-state-hover');
			// Premotes the appropriate <li> element a(n) <ul> element.
			var item = $(this).closest('li');
			var destination = item.prev('li');
			if (destination.length <= 0) { return false; }
			var ul = destination.find('ul:first');
			if (ul.length <= 0) { ul = $('<ul></ul>').appendTo(destination); }
			item.appendTo(ul);
			return false;
		});
}

function bindItemDrawerToggle(element) {
	element
		// Make it a ui-button.
		.button({
			icons: { primary: 'ui-icon-wrench' },
			text: false
		})
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

function bindPageSelect(element) {
	var fn = function(){
		// Set alt_name, alt_url element values to "".
		$(this)
			.closest('li')
			.find('input[name="alt_name"]:first, input[name="alt_url"]:first')
			.val('');
		// Change the heading element to reflect the selected page's name.
		$(this)
			.closest('li')
			.find('.name:first')
			.text($(this).find('option:not(.ignore-opt):selected').text());
	}
	// Bind to both events for both key & click effects, not just one or the other. :-)
	element
		.keyup(fn)
		.change(fn);
}

function bindPageAltName(element) {
	var fn = function(){
		// Set id element values to "".
		$(this)
			.closest('li')
			.find('select[name="id"] option:first')
			.attr('selected', 'selected');
		// Change the heading element to reflect the entered page's name.
		$(this)
			.closest('li')
			.find('.name:first')
			.text($(this).val());
	};
	// Bind to both events for both key & click effects, not just one or the other. :-)
	element
		.keyup(fn)
		.change(fn);
}

function bindItemReset(element) {
	// Note: built assuming a form reset element is being used; fn. doesn't reset values itself!
	element
		// Make it a ui-button.
		.button({ icons: { primary: 'ui-icon-arrowreturnthick-1-w' } })
		.click(function(){
			// Correct "sticky" button look; remove the ui-hover/focus classes!
			$(this).removeClass('ui-state-focus ui-state-hover');
			// Reset closest heading element!
            var alt_name = $(this)
        			.closest('li')
        			.find('input[name="alt_name"]')[0]
                    .defaultValue;
            var alt_url = $(this)
        			.closest('li')
        			.find('input[name="alt_url"]')[0]
                    .defaultValue;
        	var id
        	$(this)
    			.closest('li')
    			.find('select[name="id"] option')
    			.each(function(){
        			if ($(this)[0].defaultSelected) {
            			id = $(this).val();
        			}
    			});
        	if (alt_name != '' && alt_url != '') {
        		defaultLabel = alt_name;
        	} else {
        		$(pages).each(function(i){
        			if (pages[i].id == id) {
            			defaultLabel = pages[i].name;
        				return;
        			}
        		});
        	}
            var label = typeof defaultLabel != 'undefined' && defaultLabel.length > 0 ? defaultLabel : '<em>None</em>' ;
            $(this)
    			.closest('li')
    			.find('.name')
    			.html(label);
		});
}

function bindItemHide(element) {
	// Make it a ui-button.
	element
    	.button({
    		icons: { primary: 'ui-icon-cancel' },
    		text: false
    	});	
}

function bindItemRemove(element) {
	element
		// Make it a ui-button.
		.button({ icons: { primary: 'ui-icon-close' }, text: false })
		.click(function(){
			// Correct "sticky" button look; remove the ui-hover/focus classes!
			$(this).removeClass('ui-state-focus ui-state-hover');
			// Removes the appropriate <li> element.
			var item = $(this).closest('li');
			item.animate({ opacity: 'hide', height: 'hide' }, 'fast', function(){ item.remove(); });
			return false;
		});
}

// Create an insert index varibale to maintain a UID for each <li> element.
var insertIndex;
function insertItem(json, appendTo) {
	// Increment or create our UID variable.
	if (typeof insertIndex == 'undefined') { insertIndex = 0; } else { insertIndex++; }
	// Find or create a <ul> element to store child <li> elements.
	var ul = appendTo.find('ul:first');
	if (ul.length <= 0) {
		ul = $('<ul></ul>')
			.appendTo(appendTo);
	}
	// Hidden?
	var checked = json.hidden == 'true' ? 'checked="checked"' : '' ;
	// Create the dom structure for our new <li> element.
	var item = $('\
	<li class="ui-widget">\
    	<div class="sort">\
    		<div class="name ui-widget-header ui-corner-all"><em>None</em></div>\
    		<div class="drawer ui-widget-content ui-corner-bottom">\
    			<form>\
    				<div class="page-select">\
    					<p>Page: '+drawPageSelect(json.id)+'</p>\
    				</div>\
    				<div class="page-custom">\
        				<p>Or enter a custom page name &amp; URL:</p>\
    					<p>Alt. Name: <input type="text" name="alt_name" value="'+json.alt_name+'"/></p>\
    					<p>Alt. URL: <input type="text" name="alt_url" value="'+json.alt_url+'"/></p>\
    				</div>\
    				<button type="reset">Reset</button>\
    			</form>\
    		</div>\
    		<div class="buttons">\
    			<button type="button" class="demote">Demote</button>\
    			<button type="button" class="promote">Promote</button>\
    			<button type="button" class="toggle">Edit</button>\
    				<input type="checkbox" name="hidden'+insertIndex+'" id="checkbox'+insertIndex+'" '+checked+'/>\
    				<label for="checkbox'+insertIndex+'">Hide</label>\
    			<button type="button" class="remove">Remove</button>\
    		</div>\
    	</div>\
	</li>\
	');
	// Bind some events.
	bindItemDemote(item.find('.demote'));
	bindItemPromote(item.find('.promote'));
	bindItemDrawerToggle(item.find('.toggle'));
	bindPageSelect(item.find('select[name="id"]'));
	bindPageAltName(item.find('input[name="alt_name"]'));
	bindItemRemove(item.find('.remove'));
	bindItemHide(item.find('input[type="checkbox"]'));
	bindItemReset(item.find('button[type="reset"]'));
	// Change the heading element to reflect the selected page's name.
	if (json.alt_name != '' && json.alt_url != '') {
		item
			.find('.name')
			.text(json.alt_name);
	} else {
		$(pages).each(function(i){
			if (pages[i].id == json.id) {
				item
					.find('.name')
					.text(pages[i].name);
				return;
			}
		});
	}
	// Append the new <li> element to the menu structure!
	item.appendTo(ul);
	// Recurse!
	if (typeof json.children != 'undefined') {
		$(json.children).each(function(i){
			insertItem(json.children[i], item);
		});
	}
}

// Create a menu variable in global scope; we'll used this to accept a menu structure in editing.
var menu = [];

// Create an array of db pages in the global scope; other fns. will appreciate this. :-)
var pages = [];

$(document).ready(function(){
	// If we have a menu array, construct a navigaton structure!
	$(menu).each(function(i){
		insertItem(menu[i], $('.menu'));
	});
	// Initialize sortable(s).
	$('.menu').sortable({
		placeholder: 'ui-state-highlight', 
		items: 'li',
		toleranceElement: '> .sort',
		start: function(){
			// Corner the ui-highlight thing.
			$('.ui-state-highlight').addClass('ui-corner-all');
		},
		revert: 125
	});
	// Bind some events.
	bindInsertItem($('.insert'));
	bindSave($('button[type="submit"]'));
});
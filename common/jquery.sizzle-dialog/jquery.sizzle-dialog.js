(function($) {

    var dialog;
    var methods = {

        /*
        Create a sizzle-themed jQuery UI dialog with XYZ options.
        Options are whatever the jQuery UI dialog object accepts, including events.
        Return "this" for chaining!
        */

        init: function(options) {
            var settings = { autoOpen: false, modal: true, resizable: false };
            if (options) $.extend(settings, options);
        	dialog = $('<div></div>').css({ padding: '20px 40px', textAlign: 'center', minWidth: '200px' }).dialog(settings);
            return this;
        },

        /*
        Open a sizzle-themed jQuery UI dialog
        Return "this" for chaining!
        */

        open: function() {
            if (typeof dialog == 'undefined') methods.init();
            dialog.dialog('open');
            return this;
        },

        /*
        Close a sizzle-themed jQuery UI dialog
        Return "this" for chaining!
        */

        close: function() {
            if (typeof dialog == 'undefined') methods.init();
            dialog.dialog('close');
            return this;
        },

        /*
        Modify a sizzle-themed jQuery UI dialog's content and/or options.
        Return "this" for chaining!
        */

        update: function(content, options) {
            if (typeof dialog == 'undefined') methods.init();
            var settings = { width: 'auto', title: 'Notice:' };
            if (options) $.extend(settings, options);
            dialog.html(content).dialog('option', settings);
            dialog.dialog('option', { position: 'center' });
			$('.ui-dialog button:contains(\'Close\').ui-button').button({ icons: { primary: 'ui-icon-closethick' } });
			$('.ui-dialog button:contains(\'Continue\').ui-button').button({ icons: { primary: 'ui-icon-check' } });
            return this;
        },
        
        /*
        Alert; similar to the standard JavaScript alert().
        Return "this" for chaining!
        */

        alert: function(content, callback) {
            if (typeof dialog == 'undefined') methods.init();
            if (typeof callback != 'function') callback = function(){ methods.close(); };
            methods
                .update(
                    content,
                    {
                        title: 'Notice:',
                        buttons: [{
                            text: 'Close',
                            click: callback
                        }]
                    }
                )
                .open();
            return this;
        },
        
        /*
        Confirm; similar to the standard JavaScript confirm().
        Return "this" for chaining!
        */

        confirm: function(content, callback) {
            if (typeof dialog == 'undefined') methods.init();
            if (typeof callback != 'function') callback = function(){};
            methods
                .update(
                    content,
                    { 
                    	title: 'Notice:',
                    	buttons: [{
                        	text: 'Close',
                        	click: function() {
                				methods.close();
                			}
                    	}, {
                        	text: 'Continue',
                        	click: callback
                    	}]
                	}
            	)
                .open();
            return this;
        },
        
        /*
        Performs and handles a sizzle ajax request.
        Return "this" for chaining!
        */

        ajax: function(params, data, callback) {
            if (typeof dialog == 'undefined') methods.init();
            methods.update('<p>Processing...<br/><img src="/templates/backend/images/loading.gif" alt=""/></p>', { title: 'Processing...' }).open();
    		$.post(params, data, function(result){
				if (typeof callback == 'function') callback.apply(this, [result]);
				return this;
			});
        }

    };
    
    // Invoke a method or throw an error.
    $.extend({
        sizzleDialog: function(method) {
            if (methods[method]) {
                return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
            } else if (typeof method === 'object' || !method) {
                return methods.init.apply(this, arguments);
            } else {
                $.error('Method '+method+' does not exist on jQuery.sizzleDialog');
            }
        }
    });

})(jQuery);
CKEDITOR.plugins.add('sizzle', {   
    
    requires : ['richcombo'],
    init : function(editor) {
    
        var config = editor.config, lang = editor.lang.format;
        
        editor.ui.addRichCombo('sizzle', {
        
            label : 'Helpers',
            title :'Helpers',
            voiceLabel : 'Helpers',
            className : 'cke_format',
            multiSelect : false,
            panel : {
                css : [ config.contentsCss, CKEDITOR.getUrl(editor.skinPath+'editor.css') ],
                voiceLabel : lang.panelVoiceLabel
            },
            
            init : function() {
                this.startGroup('Select a helper:');
                var that = this;
                $.each(sizzle_helpers, function(helper_id, helper){
                    var value = '{'+helper_id;
                    $.each(helper['params'], function(param_id, param){
                        value += ' '+param_id+'='+param;
                    });
                    value += '}';
                    //that.add('value', 'drop_text', 'drop_label');
                    that.add(value, helper['name'], 'Click to insert!');
                });
            },
            
            onClick : function(value) {
                editor.focus();
                editor.fire('saveSnapshot');
                editor.insertHtml(value);
                editor.fire('saveSnapshot');
            }

        });
    
    }

});
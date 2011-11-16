(function($){

    var cache = [];
    $.imagePreloader = function(images, callback) {
    
        $(images).each(function(i){
            
            var image = $('<img/>');
            image
                .load(function(){
                    cache.push(image);
                    if (cache.length >= images.length) {
                        if ($.isFunction(callback)) {
                            callback();
                        }
                    }
                })
                .attr('src', images[i]);

        });
        
        return true;
    
    }

})(jQuery)
$(document).ready(function(){
    
    $('.menu, .col1, .session-message').corner('4px');
    
    $('.sizzle-content').css({
        position: 'relative',
        zIndex: 1000
    }).append('<div class="sizzle-content-overlay"></div>');

    $('.sizzle-content-edit').css({
        display: 'block',
        background: 'url(/templates/backend/images/sizzle-edit.png) 0 0 no-repeat',
        width: '54px',
        height: '24px',
        position: 'absolute',
        top: '5px',
        left: '5px',
        zIndex: 1002
    }).attr('title', 'Edit this content with Sizzle! CMS');

    $('.sizzle-content-overlay').css({
        position: 'absolute',
        top: '-5px',
        left: '-5px',
        zIndex: 1001,
        background: 'url(/templates/backend/images/sizzle-overlay.png) 0 0 repeat',
        display: 'none',
        opacity: '.5'
    });
    
    $('.sizzle-content-edit').hover(function(){
        var content = $(this).closest('.sizzle-content');
        var width = content.outerWidth();
        var height = content.outerHeight();
        content.find('> .sizzle-content-overlay:first').css({
            width: (width+10)+'px',
            height: (height+10)+'px'
        }).show();
    }, function(){
        var content = $(this).closest('.sizzle-content');
        content.find('> .sizzle-content-overlay:first').hide();
    });
    
});
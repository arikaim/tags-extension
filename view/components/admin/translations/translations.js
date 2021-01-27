'use strict';

$(document).ready(function() {
    $('.tags-dropdown').on('change',function(element) {
        var selected = $(this).dropdown('get value');    
        
        if (isEmpty(selected) == true) {
           arikaim.ui.hide('#translations_content');
        } else {
            arikaim.ui.show('#translations_content');

            arikaim.page.loadContent({
                id: 'translations_content',
                component: 'tags::admin.translations.items',
                params: { uuid: selected }
            },function(result) {
                initTagsForm();
            });  
        }            
    });
});
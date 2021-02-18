'use strict';

function applyTranslation(result) {
    $('#tags').val(result.fields['word']);
}

arikaim.component.onLoaded(function() {
    $('#choose_language').dropdown({
        onChange: function(value) {
            var uuid = $('#tag_form_content').attr('uuid');
            $('.translate-button').attr('language',value);

            arikaim.page.loadContent({
                id: 'tag_form_content',
                component: 'tags::admin.form',
                params: { 
                    uuid: uuid,
                    language: value 
                }
            },function(result) {
                tags.initTagsForm();
            });   
            $('#language').val(value);
        }
    }); 

    $('.tags-dropdown').on('change',function(element) {
        var selected = $(this).dropdown('get value');    
        $('#tag_form_content').attr('uuid',selected);
        
        if (isEmpty(selected) == true) {
           arikaim.ui.hide('#tag_content');
        } else {
            arikaim.ui.show('#tag_content');

            arikaim.page.loadContent({
                id: 'tag_form_content',
                component: 'tags::admin.form',
                params: { uuid: selected }
            },function(result) {
                tags.initTagsForm();
            });  
        }            
    });

    tags.initTagsForm();    
});
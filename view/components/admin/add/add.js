'use strict';

arikaim.component.onLoaded(function() {
    $('#choose_language').dropdown({
        onChange: function(value) {
            $('#language').val(value);
        }
    }); 

    arikaim.ui.form.onSubmit("#tags_form",function() {  
        var language = $('#choose_language').dropdown('get value');
        $('#language').val(language);
        return tags.add('#tags_form');
    },function(result) {
        arikaim.ui.form.clear('#tags_form');
        arikaim.ui.form.showMessage(result.message);        
    });
});

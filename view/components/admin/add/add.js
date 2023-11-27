'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit("#tags_form",function() {  
        return tags.add('#tags_form');
    },function(result) {
        arikaim.ui.form.clear('#tags_form');
        arikaim.ui.form.showMessage(result.message);        
    });
});

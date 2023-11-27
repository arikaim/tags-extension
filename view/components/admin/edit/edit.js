'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit("#tags_form",function() {  
        return tags.update('#tags_form');
    },function(result) {          
        arikaim.ui.form.showMessage(result.message);        
    });
});
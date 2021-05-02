'use strict';

arikaim.component.onLoaded(function() {
    $('#choose_language').dropdown({
        onChange: function(value) {
            options.save('tags.job.translate.language',value);
        }
    });
});
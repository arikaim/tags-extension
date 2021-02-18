'use strict';

arikaim.component.onLoaded(function() {
    $('#translate_tags_job').accordion({});

    $('#choose_language').dropdown({
        onChange: function(value) {
            options.save('tags.job.translate.language',value);
        }
    });

    $('#translate_tags_toggle').checkbox({
        onChecked: function(value) {         
            var uuid = $(this).attr('uuid');           
            jobs.enable(uuid,function(result) {
                jobs.load(uuid,'translate_tags_job',function(result) {                   
                });   
                arikaim.ui.show('#job_settings_form');        
            });
        },
        onUnchecked: function(value) {    
            var uuid = $(this).attr('uuid');
            jobs.disable(uuid,function(result) {
                jobs.load(uuid,'translate_tags_job',function(result) {                   
                });
                arikaim.ui.hide('#job_settings_form');
            });
        }
    });
});
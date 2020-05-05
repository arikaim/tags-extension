'use strict';

$(document).ready(function() {
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
                    $('#translate_tags_job').accordion({});
                });   
                arikaim.ui.show('#job_settings');        
            });
        },
        onUnchecked: function(value) {    
            var uuid = $(this).attr('uuid');
            jobs.disable(uuid,function(result) {
                jobs.load(uuid,'translate_tags_job',function(result) {
                    $('#translate_tags_job').accordion({});
                });
                arikaim.ui.hide('#job_settings');
            });
        }
    });
});
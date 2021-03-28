/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com 
 */
'use strict';

function TagsControlPanel() {
 
    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/tags/delete/' + uuid,onSuccess,onError);          
    };

    this.add = function(data, onSuccess, onError) {
        return arikaim.post('/api/admin/tags/add',data,onSuccess,onError);          
    };

    this.update = function(data, onSuccess, onError) {
        return arikaim.put('/api/admin/tags/update',data,onSuccess,onError);          
    };

    this.getList = function(query, onSuccess, onError) {
        return arikaim.get('/api/admin/tags/list/' + query,onSuccess,onError);          
    };
   
    this.loadAddTag = function(language) {
        arikaim.ui.setActiveTab('#add_tag','.tags-tab-item')   
        arikaim.page.loadContent({
            id: 'tags_content',
            component: 'tags::admin.add',
            params: { language: language }
        });          
    };

    this.initTagsForm = function() {
        arikaim.ui.form.onSubmit("#tags_form",function() {  
            var language = $('#choose_language').dropdown('get value');
            $('#language').val(language);

            return tags.update('#tags_form');
        },function(result) {          
            arikaim.ui.form.showMessage(result.message);        
        });
    }

    this.init = function() {           
        arikaim.ui.tab();
    };
}

var tags = new TagsControlPanel();

arikaim.component.onLoaded(function() {
    tags.init();
});
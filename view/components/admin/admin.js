/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com 
 */
'use strict';

function TagsControlPanel() {
    var self = this;

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/tags/admin/delete/' + uuid,onSuccess,onError);          
    };

    this.add = function(data, onSuccess, onError) {
        return arikaim.post('/api/tags/admin/add',data,onSuccess,onError);          
    };

    this.update = function(data, onSuccess, onError) {
        return arikaim.put('/api/tags/admin/update',data,onSuccess,onError);          
    };

    this.getList = function(query, onSuccess, onError) {
        return arikaim.get('/api/tags/admin/list/' + query,onSuccess,onError);          
    };
   
    this.loadAddTag = function(parent_id, language) {
        arikaim.ui.setActiveTab('#add_tag','.tags-tab-item')   
        arikaim.page.loadContent({
            id: 'tags_content',
            component: 'tags::admin.add',
            params: { language: language }
        });          
    };

    this.init = function() {           
        arikaim.ui.tab();
    };
}

var tags = new TagsControlPanel();

arikaim.page.onReady(function() {
    tags.init();
});
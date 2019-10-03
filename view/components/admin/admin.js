/**
 *  Arikaim
 *  @version    1.0  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license.html
 *  http://www.arikaim.com
 * 
 *  Extension: Tags
 *  Component: tags:admin
 */

function TagsControlPanel() {
    var self = this;

    this.delete = function(uuid,onSuccess,onError) {
        return arikaim.delete('/api/tags/admin/delete/' + uuid,onSuccess,onError);          
    };

    this.add = function(data,onSuccess,onError) {
        return arikaim.post('/api/tags/admin/delete/',data, onSuccess,onError);          
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
        $('#choose_language').dropdown({
            onChange: function(value) {
                arikaim.page.loadContent({
                    id: 'tab_content',
                    component: 'tags::admin.menu',
                    params: { language: value }
                });
            }
        }); 
    };
}

var tags = new TagsControlPanel();

arikaim.page.onReady(function() {
    tags.init();
});
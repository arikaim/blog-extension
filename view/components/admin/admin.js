/**
 *  Arikaim
 *  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 * 
 *  Extension: Category
 *  Component: category:admin
 */

function CategoryControlPanel() {
   
    var tab_item_element = '.tab-item';

    this.delete = function(uuid,onSuccess,onError) {
        return arikaim.delete('/api/category/' + uuid,onSuccess,onError);          
    };

    /**
     * 
     * @param string uuid 
     * @param int status  0 - disabled, 1 - active, toggle - toggle status value
     */
    this.setStatus = function(uuid,status,onSuccess,onError) {     
        var status_text = isEmpty(status) ? 'toggle' : status;         
        return arikaim.put('/api/category/status/'+ uuid + '/' + status_text,null,onSuccess,onError);      
    };

    this.loadList = function(element, parent_id, language, onSuccess) { 
        return arikaim.page.loadContent({
            id : element,
            component : 'category::admin.view.list',
            params: { parent_id: parent_id, language: language }
        },onSuccess);
    };

    this.edit = function(uuid) {
        $(tab_item_element).removeClass('active');
        $('#edit_button').addClass('active');
        arikaim.page.loadContent({
            id: 'tab_content',
            component: 'category::admin.category.edit',
            params: { uuid: uuid }
        });  
    };

    this.move = function(uuid,after_uuid,onSuccess,onError) {
        return arikaim.put('/api/category/move/'+ uuid + '/' + after_uuid,null,onSuccess,onError);      
    };

    this.init = function() {    
        $('.categories-list').accordion();

        $('#choose_language').dropdown({
            onChange: function(value) {
                arikaim.page.loadContent({
                    id: 'tab_content',
                    component: 'category::admin.view',
                    params: { language: value }
                });
            }
        }); 
        
        $(tab_item_element).off();
        $(tab_item_element).on('click',function() {
            $(tab_item_element).removeClass('active');
            $(this).addClass('active');
            var component_name = $(this).attr('component');
            var language = $(this).attr('language');
            arikaim.page.loadContent({
                id: 'tab_content',
                component: component_name,
                params: { language: language }
            });     
        });
    };
}

var category = new CategoryControlPanel();

arikaim.page.onReady(function() {
    category.init();
});
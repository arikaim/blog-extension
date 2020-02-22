/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function PagesControlPanel() {
    var self = this;

    this.init = function() {     
        arikaim.ui.button('.add-page',function(element) {
            return arikaim.page.loadContent({
                id: 'page_editor',           
                component: 'blog::admin.pages.add',
                params: {}
            });  
        });
    };

    this.showPagesList = function() {
        return arikaim.page.loadContent({
            id: 'pages_list',           
            component: 'blog::admin.pages.list'            
        },function(result) {
            self.initRows();
        });  
    };

    this.initRows = function() {
        var component = arikaim.component.get('blog::admin');
        var removeMessage = component.getProperty('messages.page.content');

        arikaim.ui.button('.edit-page',function(element) {
            var uuid = $(element).attr('uuid');

            return arikaim.page.loadContent({
                id: 'page_editor',           
                component: 'blog::admin.pages.editor',
                params: { uuid: uuid }
            },function(result) {
                $('#page_editor').attr('edit-page',uuid);
            });  
        });

        arikaim.ui.button('.delete-page',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(removeMessage,{ title: title });
            modal.confirmDelete({ 
                title: component.getProperty('messages.page.title'),
                description: message
            },function() {
                blogControlPanel.deletePage(uuid,function(result) {
                    $('#' + uuid).remove();  
                    var editUuid = $('#page_editor').attr('edit-page');           
                    if (editUuid == uuid) {
                        $('#page_editor').html("");
                    }                 
                });
            });
        });
    };
}

var pages = new PagesControlPanel();

arikaim.page.onReady(function() {
    pages.init();
    pages.initRows();
});
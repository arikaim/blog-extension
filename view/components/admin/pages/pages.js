/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function PagesView() {
    var self = this;

    this.init = function() {     
        this.loadMessages('blog::admin');

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
            var message = arikaim.ui.template.render(self.getMessage('page.content'),{ title: title });
            
            modal.confirmDelete({ 
                title: self.getMessage('page.title'),
                description: message
            },function() {
                blogControlPanel.deletePage(uuid,function(result) {
                    $('#' + uuid).remove();  
                    var editUuid = $('#page_editor').attr('edit-page');           
                    if (editUuid == uuid) {
                        $('#page_editor').html('');
                    }                 
                });
            });
        });
    };
}

var pagesView = createObject(PagesView,ControlPanelView);

arikaim.component.onLoaded(function() {
    pagesView.init();
    pagesView.initRows();
});
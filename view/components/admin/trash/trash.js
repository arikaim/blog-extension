/**
 *  Arikaim  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function TrashControlPanel() {
    var self = this;

    this.init = function() {
        this.loadMessages();

        arikaim.ui.button('.empty-trash',function(element) {       
            return modal.confirmDelete({ 
                title: self.getMessage('empty.title'),
                description: self.getMessage('empty.description') 
            },function() {         
                blogControlPanel.emptyTrash(function(result) {
                    self.loadRows();
                },function(error) {
                    arikaim.page.toastMessage({
                        class: 'error',
                        message: error
                    });
                });
            });               
        });
    };

    this.loadRows = function() {
        return arikaim.page.loadContent({
            id: 'items_rows',           
            component: 'blog::admin.trash.rows',
            params: { }
        },function(result) {
            self.initRows();
        }); 
    };

    this.initRows = function() {
        arikaim.ui.button('.restore-page',function(element) {   
            var uuid = $(element).attr('uuid');

            blogControlPanel.restorePage(uuid,function(result) {
                arikaim.ui.table.removeRow('#row_' + uuid,null,function(element) {
                    $('.trash-button').addClass('disabled');
                });
                arikaim.ui.table.removeRow('.page-' + uuid,null,function(element) {
                    $('.trash-button').addClass('disabled');
                });
                arikaim.page.toastMessage(result.message);                   
            },function(error) {
                arikaim.page.toastMessage({
                    class: 'error',
                    message: error
                });
            });
        });   

        arikaim.ui.button('.restore-post',function(element) {   
            var uuid = $(element).attr('uuid');

            blogControlPanel.restorePost(uuid,function(result) {
                arikaim.ui.table.removeRow('#row_' + uuid,null,function(element) {
                    $('.trash-button').addClass('disabled');
                });
                arikaim.page.toastMessage(result.message);                   
            },function(error) {
                arikaim.page.toastMessage({
                    class: 'error',
                    message: error
                });
            });
        });   
    };
}

var trashView = new createObject(TrashControlPanel,ControlPanelView);

arikaim.component.onLoaded(function() {
    trashView.init();
    trashView.initRows();
});

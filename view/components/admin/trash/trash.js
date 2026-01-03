/**
 *  Arikaim  
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function TrashControlPanel() {
    var self = this;

    this.init = function() {
        this.loadMessages();

        arikaim.ui.button('.empty-trash',function(element) {   
            arikaim.ui.getComponent('empty_trash_modal').open(function() {         
                    blogApi.emptyTrash(function(result) {
                        self.loadRows();
                    },function(error) {
                        arikaim.page.toastMessage({
                            class: 'error',
                            message: error
                        });
                    });
            });            
        });

        this.initRows();
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
        arikaim.ui.button('.restore-post',function(element) {   
            var uuid = $(element).attr('uuid');

            blogApi.restorePost(uuid,function(result) {
                arikaim.ui.table.removeRow('#row_' + uuid,null,function(element) {
                    $('.trash-button').addClass('disabled');
                });
                arikaim.ui.getComponent('toast').show(result.message);                       
            },function(error) {
                arikaim.ui.getComponent('toast').show(result.error);               
            });
        });   
    };
}

var trashView = createObject(TrashControlPanel,ControlPanelView);

arikaim.component.onLoaded(function() {
    trashView.init();   
});

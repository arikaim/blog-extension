/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */

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

    this.initRows = function() {
        arikaim.ui.button('.edit-page',function(element) {
            var uuid = $(element).attr('uuid');

            return arikaim.page.loadContent({
                id: 'page_editor',           
                component: 'blog::admin.pages.editor',
                params: { uuid: uuid}
            });  
        });
    };
}

var pages = new PagesControlPanel();

arikaim.page.onReady(function() {
    pages.init();
    pages.initRows();
});
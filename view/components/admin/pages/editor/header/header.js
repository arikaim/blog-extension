'use strict';

arikaim.component.onLoaded(function() { 
    $('#page_status').dropdown({
        onChange: function(value) {           
            var uuid = $(this).attr('uuid');         
            blogControlPanel.setPageStatus(uuid,value);
        }       
    });

    posts.init();

    /*
    arikaim.ui.button('.edit-page-name',function(element) {
        var uuid = $(element).attr('uuid');

        return arikaim.page.loadContent({
            id: 'page_editor',           
            component: 'blog::admin.pages.edit',
            params: { uuid: uuid }
        },function(result) {
            $('#page_editor').attr('edit-page',uuid);
        });  
    });
    */
   
});
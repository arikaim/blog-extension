'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.events.on('image.upload',function(result) {   
        var uuid = $('#blog_post').attr('uuid');

        blogControlPanel.updatePost({
            image_id: result.id,
            uuid: uuid
        },function(result) {
            return arikaim.page.loadContent({
                id: 'blog_post_edit_content',           
                component: 'blog::admin.posts.edit.image',
                params: { 
                    uuid: uuid                   
                }
            });  
        });
    },'blogImageUpload');   
});
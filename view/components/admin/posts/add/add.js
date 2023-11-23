'use strict';

arikaim.component.onLoaded(function() {
    $('#post_status').dropdown({});

    arikaim.ui.form.onSubmit("#editor_form",function() {  
        return blogApi.addPost('#editor_form',function(result) {
            return blogPostView.loadEditPost(result.uuid);
        });
    },function(result) {          
        arikaim.ui.form.showMessage(result.message);        
    });
});

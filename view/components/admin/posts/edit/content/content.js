'use strict';

arikaim.component.onLoaded(function() {
    
    $('#post_status').on('change', function() {
        var val = $(this).val();
        var uuid = $(this).attr('uuid');         
        
        blogApi.setPostStatus(uuid,val);         
    });
   
    arikaim.ui.form.onSubmit("#editor_form",function() {  
        return blogApi.updatePost('#editor_form',function(result) {
            blogPostView.updateItem(result.uuid);
        });
    },function(result) {          
        arikaim.ui.form.showMessage(result.message);        
    },function(error) {
        arikaim.ui.form.showErrors(error);        
    });
});
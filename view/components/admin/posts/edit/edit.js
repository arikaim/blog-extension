$(document).ready(function() {

    $('#post_status').dropdown({
        onChange: function(value) {           
            var uuid = $(this).attr('uuid');         
            blogControlPanel.setPostStatus(uuid,value);
        }       
    });

    arikaim.ui.form.onSubmit("#editor_form",function() {  
        return blogControlPanel.updatePost('#editor_form');
    },function(result) {          
        arikaim.ui.form.showMessage(result.message);        
    },function(error) {
        arikaim.ui.form.showErrors(error);        
    });
});
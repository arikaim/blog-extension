$(document).ready(function() {
    $('#post_status').dropdown({});

    arikaim.ui.form.onSubmit("#editor_form",function() {  
        return blogControlPanel.addPost('#editor_form');
    },function(result) {          
        arikaim.ui.form.showMessage(result.message);        
    });
});

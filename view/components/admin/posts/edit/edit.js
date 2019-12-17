$(document).ready(function() {
    arikaim.ui.form.onSubmit("#editor_form",function() {  
        return blogControlPanel.updatePost('#editor_form');
    },function(result) {          
        arikaim.ui.form.showMessage(result.message);        
    },function(error) {
        arikaim.ui.form.showErrors(error);        
    });
});
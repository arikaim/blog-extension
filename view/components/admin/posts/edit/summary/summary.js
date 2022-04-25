'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.ui.form.onSubmit("#post_summary_form",function() {  
        return blogControlPanel.updatePost('#post_summary_form');
    },function(result) {          
        arikaim.ui.form.showMessage(result.message);        
    },function(error) {
        arikaim.ui.form.showErrors(error);        
    });
});
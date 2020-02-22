'use strict';

$(document).ready(function() {
    arikaim.ui.form.onSubmit("#page_form",function() {  
        return blogControlPanel.addPage('#page_form');
    },function(result) {          
        arikaim.ui.form.showMessage(result.message);     
        pages.showPagesList();   
    });
});
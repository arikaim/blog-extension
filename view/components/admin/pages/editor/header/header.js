$(document).ready(function() {   
    $('.status-dropdown').dropdown({
        onChange: function(value) {           
            var uuid = $(this).attr('uuid');         
            blogControlPanel.setPageStatus(uuid,value);
        }       
    });
});
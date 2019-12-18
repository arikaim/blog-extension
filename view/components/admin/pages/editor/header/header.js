$(document).ready(function() {   
    $('#page_status').dropdown({
        onChange: function(value) {           
            var uuid = $(this).attr('uuid');         
            blogControlPanel.setPageStatus(uuid,value);
        }       
    });
});
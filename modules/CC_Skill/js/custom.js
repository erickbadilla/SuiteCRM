$(document).ready(function(){

    $('#delete_button').hide();
    $('.listViewButtons').find('input[value=Remove]').hide();
    $('#actionLinkTop').find('li').find('ul').find('#delete_listview_top').hide();

    
    var record_id= document.getElementsByName("record")[0].value;
    
    let data_send = {
        action : "get_info",
        record_id
      }
    
    $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=EmployeeInformationEntryPoint',
        dataType: 'json',
        data: data_send,
        success: function(resp) {
          if(resp > 0){
            $('#edit_button').hide();
            $("#name").parent().find('div').hide();
            $("#skill_type").parent().find('div').hide();
            $('#name'). attr('disabled','disabled');
            $('#skill_type'). attr('disabled','disabled');
          }
        }
      }); 
    
    });

    $('.inlineEditIcon').click(function(){
        toastr.options = {
            "positionClass": "toast-bottom-right",
          }

          var records= $(this).parent().find('a').attr('href');
          var record_id= records.substring(records.length - 36);
    
          let data_send = {
              action : "get_info",
              record_id
            }
          var button = $(this);
          $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=EmployeeInformationEntryPoint',
            dataType: 'json',
            data: data_send,
            success: function(resp) {
              if(resp > 0){
                button.remove();
                toastr.error('This Can Not be Edited', 'Oops!');
                return;
              }
            }
          }); 

      });
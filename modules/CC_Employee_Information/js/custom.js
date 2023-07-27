var id_employee="";
var professional = ";"
$(document).ready(function(){

    professional = document.getElementById('is_professional_service');
    
    if(window.location.search.length > 1 ){
        const urlParams = new URLSearchParams(window.location.search); 
        id_employee = urlParams.get('record');
    }else{
        let url = new URL($(".moduleTitle").find("h2").find("a").prop('href'))
        for(let [name, value] of url.searchParams) {
            if( name == 'record'); 
            id_employee = value;
          }
    }

    jQuery('.inlineEditIcon').click(function() {
        setTimeout(function(){ 
                    document.getElementById('is_professional_service').addEventListener('click', change_profesional);
            }, 500);
    });
    
    professional.addEventListener('click', change_profesional);
    
    });
    
    function change_profesional() {
      if(document.getElementById('is_professional_service').checked) {

        document.getElementById('remind_anniversary').checked=false;
        let data_send = {
            action : "changeAnniversary",
            anniversary : 0,
            id_employee
          }
      } else {
        document.getElementById('remind_anniversary').checked=true;
        let data_send = {
            action : "changeAnniversary",
            anniversary : 1,
            id_employee
          }
      }
    
      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=EmployeeInformationEntryPoint',
        dataType: 'json',
        data: data_send,
        success: function(resp) {
        
        }
      });
    
    }
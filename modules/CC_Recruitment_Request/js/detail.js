var tableActivity = "";

$(document).ready(function(){

    toastr.options = {
        "positionClass": "toast-bottom-right",
    }

    $("#subpanel_list").css("display","none");
    $("#groupTabs").css("display","none");
    getActionRecruitment();
    getDatagetRecruitmentRequest();

});


function getDatagetRecruitmentRequest(){
  
  let initial_data = {
    RecruitmentID : recruitmentID,
    action: "getRecruitmentRequest"
  }

  $.post(
    'index.php?entryPoint=GetRecruitmentRequestEntryPoint',
    initial_data,
    function (data) {
      let resp = data['results'][0]; 
      $("#lbl_account").html(resp.name_account);
      $("#lbl_project").html(resp.name_project);
      $("#lbl_position").html(resp.name_job_decription);
      $("#lbl_assigned").html(resp.assigned_to_name);
    },
    'json'
  )

}


function getActionRecruitment(){

    const data_Send = {
      "action":"getActionRecruitmentRequest",
      "IdRecruitmentRequest": recruitmentID
    };

    $.ajax({
      type: "POST",
      url: 'index.php?entryPoint=GetActionRecruitmentRequestEntryPoint',
      dataType: 'json',
      data: data_Send,
      success: function(resp) {

        // set widgets
        let data_widgets = resp['results']['widgets'][0];

        if(data_widgets){ 
            $("#lbl_candidates_registered").html(data_widgets.candidates_registered);
            $("#lbl_candidates_interviewed").html(data_widgets.candidates_interviewed);
            $("#lbl_candidates_rejected").html(data_widgets.candidates_rejected);
            $("#lbl_candidates_hired").html(data_widgets.candidates_hired);
        }

        if(tableActivity != ""){
            tableActivity.destroy();
            $("#table_action_recruitment_request tbody tr").remove();
        }

        let data = resp['results']['data'];
        Object.entries(data).forEach(([key, value]) => {
           $("#table_action_recruitment_request tbody").append(`<tr><td>${data[key].name}</td><td>${data[key].event_date}</td><td>${data[key].comment}</td><td>${data[key].time_lapsed}</td><td>${data[key].field_group}</td></tr>"`); 
        });
        
        let groupColumn = 4;
        tableActivity = $('#table_action_recruitment_request').DataTable({
            "columnDefs": [
                { "visible": false, "targets": groupColumn, "sortable": false }
            ],
            "responsive": true,
            "paging" : false,
            "order": [[ groupColumn, 'asc' ]],
            "info" : true,
            "filter" : false,
            "drawCallback": function ( settings ) {
                let api = this.api();
                let rows = api.rows( {page:'current'} ).nodes();
                let last=null;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq(i).before(
                          `<tr class="group" style="background-color: #B8CC33;text-align: inherit;"><td colspan="4">${group}</td></tr>`
                        );
                        last = group;
                    }
                } );
            }
        });

        /*suitecrm styles collide with this element I reset it*/
        $("#table_action_recruitment_request_length").find("select").css({"height": "30px","width": "5em"});

      }
    });

}
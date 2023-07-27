let tableQualificationsProfile = "";
let tableSkill              = "";
let tablaJobProfile         = "";
let tableRating             = "";
let tableJobApplications    = "";
let tablaApplicationsRating = "";
let tablaEmployeeRating     = "";
let tablaJobAccount         = "";
let tablaJobInterviewer     = "";
let tablaRelatedEmployee     = "";
let profileAllJobOffer      = new Array(); 

$(document).ready(function(){
  $("#groupTabs").hide();
  tinymce.init({
    selector: 'textarea',  
    plugins : 'advlist autolink link image lists charmap print preview table',
   });

toastr.options = {
    "positionClass": "toast-bottom-right",
}

$("#subpanel_list").css("display","none");
Calendar.setup ({
    inputField : "txt_expire_on",
    //form : "EditView",
    ifFormat : "%m/%d/%Y %H:%M",
    daFormat : "%m/%d/%Y %H:%M",
    button : "txt_expire_on_trigger",
    singleClick : true,
    dateStr : expireOn,
    startWeekday: 0,
    step : 1,
    weekNumbers:false
});



  createApplicationSearchElement(
    '#selectAccountWrapper',
    "account_list",
    window.JobApplicationId,
    'index.php?entryPoint=AccountApplicationEntryPoint',
    'getAccount',
    'Select for an Account',
    function (e) {accountSelect(e);
  }),
  createApplicationSearchElement(
    '#selectChargeWrapper',
    "charge_list",
    "",
    'index.php?entryPoint=GetJobDescriptionEntryPoint',
    'getJobDescription',
    'Select a Project to Add',
    function (e) {SelectAction(e,"charge_list","hd_jod_description_id", "old_jod_description_id",0);
  })










getProfileQualifications();
getProfileSkills();
candidate_ratings();
getApplicationsRatingobOffer();
getJobOffer("account_list");
getJobApplicationsOffer(); 
getProfileJobOffer();
getAccountsJobOffer();
getInterviewersJobOffer();
getRelatedEmployees();

});


function edit_job_offer(){

  let inputFile = document.getElementById('jobImage');
    let file = inputFile.files[0];
    let data_send = new FormData();
  
  let position_name     = $("#txt_position_name").val();
  let expire_on         = $("#txt_expire_on").val();
  let assigned_location = $("#txt_assigned_location").val();
  let new_position      = $("#hd_jod_description_id").val();
  let old_position      = $("#old_jod_description_id").val();
  let contact_type      = $("#txt_contact_type").val();
  let account           = $("#txt_id_account").val();
  let description       = tinyMCE.activeEditor.getContent();
  let action            = "EditJobOffer";

  data_send.append('file',file);
  data_send.append('JobApplicationId',JobApplicationId);
  data_send.append('position_name',position_name);
  data_send.append('position',new_position);
  data_send.append('old_position',old_position);
  data_send.append('expire_on',expire_on);
  data_send.append('assigned_location',assigned_location);
  data_send.append('contact_type',contact_type);
  data_send.append('account',account);
  data_send.append('description',description);
  data_send.append('action',action);  

  if(IsPublished == 1){
    toastr.error("The offer cannot be edited because it is published", 'Oops!');
    return;
  }

  
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=EditJobOfferEntryPoint',
    dataType: 'json',
    data: data_send,
    processData:false,
    contentType:false,
    cache:false,
    success: function(resp) {
      if(resp['results'] != 0){
        if(resp['results'] != 1){
          document.getElementById("image_img").src= resp['results'];
          document.getElementById("image_a").href = resp['results'];
        }
         toastr.success("Modified offer", 'Successful');
      }else{
        toastr.error('Error when modifying data', 'Oops!');
        location.reload();
      }
    }
  }); 

}


function delete_job_offer(){
  
  const data_send = {
    "action":"DeleteJobOffer",
    "JobApplicationId": JobApplicationId
  };

  if(IsPublished == 1){
     toastr.success("The offer cannot be delete because it is published", 'Successful');
     return;
  }

  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=DeleteJobOfferEntryPoint',
    dataType: 'json',
    data: data_send,
    success: function(resp) {
      if(resp['results'] == 1){
         toastr.success("Successfully removal data", 'Successful');
         window.location.assign("index.php?module="+moduleId);
      }else{
        toastr.error('Error when removal data', 'Oops!')
        location.reload();
      }
    }
  }); 

}

function deleteProfile(idProfile,JobApplicationId){
    
  const data_send = {
    "action":"deleteProfile",
    "id_profile": idProfile,
    "JobApplicationId": JobApplicationId
  };

  if(IsPublished == 1){
    toastr.warning("Profile cannot be deleted because the offer is published", 'Oops!');
   return;
  }

  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=DeleteProfileJobOfferEntryPoint',
    data: data_send,
    success: function(resp) {
      let data_respu = JSON.parse(resp);
     
      if(!data_respu.results){
        toastr.error("Could not make the transaction", 'Oops!');
        return;
      }

     
         /*I store in a string all the profiles of the offer*/
         if(profileAllJobOffer.length > 0 && profileAllJobOffer.includes(idProfile)){
             for (let index = 0; index < profileAllJobOffer.length; index++) {
               if(idProfile == profileAllJobOffer[index]){
                  profileAllJobOffer.splice(index,1);
               }
             }
         }
         tablaJobProfile.ajax.reload();
         getProfileQualifications();
         tableSkill.ajax.reload(drawRatings);
         getEmployeeRatingOffer(profileAllJobOffer);
         tablaApplicationsRating.ajax.reload(drawRatingsApplicationsRating);
         tableRating.ajax.reload(drawRatingsCandidate);
      }
    
  });

}


function change_dependecy_profile(idProfile,elem){

  const data_Send = {
    "action":"updateDependecyProfileJobOffer",
    "id_profile": idProfile,
    "dependecy": elem.val(),
    "JobApplicationId": JobApplicationId
  };

  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=UpdateDependecyProfileJobOfferEntryPoint',
    data: data_Send,
    success: function(resp) {
      if(resp) {
         tablaJobProfile.ajax.reload();
         getProfileQualifications();
      }
    }
  });

}


function getProfileQualifications(){

    const data_Send = {
      "action":"GetProfileQualifications",
      "JobApplicationId": JobApplicationId
    };

    $.ajax({
      type: "POST",
      url: 'index.php?entryPoint=GetProfileQualificationsEntryPoint',
      dataType: 'json',
      data: data_Send,
      success: function(resp) {

        if(tableQualificationsProfile != ""){
          tableQualificationsProfile.destroy();
          $("#table_qualifications tbody tr").remove();
        }

        let data = resp['data'];
        Object.entries(data).forEach(([key, value]) => {
           let url_profile        = `index.php?module=${data[key].object_name_profile}&offset=1&return_module=${data[key].object_name_profile}&action=DetailView&record=${data[key].id_profile}`;
           let url_qualifications = `index.php?module=${data[key].object_name_qualifications}&offset=1&return_module=${data[key].object_name_qualifications}&action=DetailView&record=${data[key].id_qualifications}`;

           $("#table_qualifications tbody").append(`<tr><td><a href='${url_profile}' target='_blank'>${data[key].name_profile}</a></td><td><a href='${url_qualifications}' target='_blank'>${data[key].name}</a></td><td>${data[key].mininum_requiered}</td><td>${data[key].dependecy}</td></tr>"`); 
        });
        
        let groupColumn = 3;
        tableQualificationsProfile = $('#table_qualifications').DataTable({
            "columnDefs": [
                { "visible": false, "targets": groupColumn }
            ],
            "responsive": true,
            "paging" : true,
            "order": [[ groupColumn, 'asc' ]],
            "info" : true,
            "filter" : true,
            "drawCallback": function ( settings ) {
                let api = this.api();
                let rows = api.rows( {page:'current'} ).nodes();
                let last=null;
      
                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq(i).before(
                          `<tr class="group"><td colspan="4">${group}</td></tr>`
                        );
                        last = group;
                    }
                } );
            }
        });

        /*suitecrm styles collide with this element I reset it*/
        $("#table_qualifications_length").find("select").css({"height": "30px","width": "5em"});
      
        /* Order by the grouping*/
        $('#table_qualifications tbody').on( 'click', 'tr.group', function () {
            let currentOrder = tableQualificationsProfile.order()[0];
            if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
              tableQualificationsProfile.order( [ groupColumn, 'desc' ] ).draw();
            }
            else {
              tableQualificationsProfile.order( [ groupColumn, 'asc' ] ).draw();
            }
        });

      }
    });

}


function drawRatings(){

  $('td.dt-body-rating').each(function() {
    let currentRow = $(this).closest("tr");
    let data = $('#table_skills').DataTable().row(currentRow).data();
    if(data){
      createRatingElement($( this ),data);
    }
  });
}


function createRatingElement(e,data){
  
  e.starRating({
    starSize: 15,
    totalStars: 5,
    readOnly: true,
    disableAfterRate: true,
    callback: function(currentRating, $el){}
  });

  e.starRating('setRating', data.rating);
 
  if(!e.html().includes('Year')){
      e.append(`<p><svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='currentColor' class='bi bi-alarm' viewBox='0 0 16 16'><path d='M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5z'/><path d='M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1h-3zm1.038 3.018a6.093 6.093 0 0 1 .924 0 6 6 0 1 1-.924 0zM0 3.5c0 .753.333 1.429.86 1.887A8.035 8.035 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5zM13.5 1c-.753 0-1.429.333-1.887.86a8.035 8.035 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1z'/></svg>&nbsp;${data.years} Year(s)</p>`);
  }

}


function drawRatingsEmployeeRating(){
  $('td.dt-body-rating-employee').each(function() {
    let currentRow = $(this).closest("tr");
    let data = $('#dataTableRatingEmployee').DataTable().row(currentRow).data();
    if(data){
      createRatingElementEmployee($( this ),data.general_rating);
    }
  });
}


function createRatingElementEmployee(e,data){

  
  //e.empty();
  e.starRating({
    starSize: 15,
    totalStars: 10,
    readOnly: true,
    disableAfterRate: true,
    callback: function(currentRating, $el){}
  });


  let numero_decimal = parseFloat(data).toFixed(1).split(".")[1];
  let numero_entero  = parseFloat(data).toFixed(1).split(".")[0];

  let start = 0;
  if(numero_decimal >= 4 && numero_decimal <= 7){
    start = numero_entero + '.5';
  }else {
    start = Math.round(parseFloat(data).toFixed(1));
  }
 
  e.starRating('setRating', start);
 
}

function drawRatingsCandidate(){
  $('td.dt-body-rating-candidate').each(function() {
    let currentRow = $(this).closest("tr");
    let data = $('#dataTableRating').DataTable().row(currentRow).data();
    if(data){
      createRatingElementCandidate($( this ),data);
    }
  });
}

function drawRatingsApplicationsRating(){
  $('td.dt-body-rating-applications_qualifications, td.dt-body-rating-applications_skill').each(function() {
    let currentRow = $(this).closest("tr");
    let data = $('#dataTableApplicationsRating').DataTable().row(currentRow).data();
    if(data){
         if($(this).hasClass('dt-body-rating-applications_qualifications')){
             createRatingElementApplicationsRating($( this ),data.qualification_rating);     
         }else if($(this).hasClass('dt-body-rating-applications_skill')){
             createRatingElementApplicationsRating($( this ),data.skill_rating); 
         }
    }
  });
}

function createRatingElementApplicationsRating(e,data){
  //e.empty();
  e.starRating({
    starSize: 15,
    totalStars: 10,
    readOnly: true,
    disableAfterRate: true,
    callback: function(currentRating, $el){}
  });

  let numero_decimal = parseFloat(data/10).toFixed(1).split(".")[1];
  let numero_entero  = parseFloat(data/10).toFixed(1).split(".")[0];
  let start = 0;

  if(numero_decimal >= 4 && numero_decimal <= 7){
    start = numero_entero + '.5';
  }else {
    start = Math.round(parseFloat(data/10).toFixed(1));
  }
 
  e.starRating('setRating', start);
  if(!e.html().includes('<span>')){
      e.append(`<br><span>${data} %</span>`); 
  }
}


function createRatingElementCandidate(e,data){
  //e.empty();
  e.starRating({
    starSize: 15,
    totalStars: 10,
    readOnly: true,
    disableAfterRate: true,
    callback: function(currentRating, $el){}
  });

  let numero_decimal = parseFloat(data.general_rating/10).toFixed(1).split(".")[1];
  let numero_entero  = parseFloat(data.general_rating/10).toFixed(1).split(".")[0];

  let start = 0;
  if(numero_decimal >= 4 && numero_decimal <= 7){
    start = numero_entero + '.5';
  }else {
    start = Math.round(parseFloat(data.general_rating/10).toFixed(1));
  }

  e.starRating('setRating', start);
 
}


function state_job_offer_modification(){

  const data_send = {
    "action":"UpdateStateJobOffer",
    "IsPublished": IsPublished,
    "record": JobApplicationId
  };

  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=UpdateStateJobOfferEntryPoint',
    data: data_send,
    success: function(resp) {
      if(resp == 1) {
         toastr.success("Successful modification", 'Successful');
         location.reload();
      }else{
        toastr.error('Error when modifying status', 'Oops!')
        location.reload();
      }
    }
  });
}


function edit_state_job_offer(){
  
  if(IsPublished == 0){
    $("#state_offer").css("background-color", "gray");
    $("#state_offer").text("Processing...");
  let data_sendIn = {
    action : "GetInterviewersJobOffer",
    JobApplicationId: JobApplicationId
  }
  
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=GetInterviewerJobOfferEntryPoint',
    data: data_sendIn,
    success: function(resp) {
      let data_respu = JSON.parse(resp);
      if(data_respu.data.length == 0){
        $("#state_offer").css("background-color", "#B8CC33");
        $("#state_offer").text("Publish");
        toastr.warning("Job Offer can't be posted without at least one interviewer", 'Oops!');
        return;
      }
      else{
        let data_sendJP = {
          action : "GetProfilesJobOffer",
          JobApplicationId: JobApplicationId
        }
        
        $.ajax({
          type: "POST",
          url: 'index.php?entryPoint=GetInterviewerJobOfferEntryPoint',
          data: data_sendJP,
          success: function(resp) {
            $("#state_offer").css("background-color", "#B8CC33");
            $("#state_offer").text("Publish");
            let data_respu = JSON.parse(resp);
            if(data_respu.data[0]['NoProfile']){
              toastr.warning("You Must add a Profile before publishing", 'Oops!');
              return;
            }
            else if(data_respu.data[0]['NoDependency']){
              toastr.warning("One Of the Profiles doesn't have dependency", 'Oops!');
              return;
            }
            else if(data_respu.data[0]['NoSkillyQua']){
              toastr.warning("One Of the Profiles doesn't have SKills Or Qualifications", 'Oops!');
              return;
            }else{
              state_job_offer_modification()
            }
          }
        });
      }
    }
  });

 
}else{
  state_job_offer_modification()
}

}



function getEmployeeRatingOffer(paramsprofileAllJobOffer){


     let data_send = {
        action : "GetEmployeeRatingJobOffer",
        profileAllJobOffer : paramsprofileAllJobOffer.join()
     }
  
     if(tablaEmployeeRating != ""){
        tablaEmployeeRating.destroy();
     }

     tablaEmployeeRating = $("#dataTableRatingEmployee").DataTable({
         "responsive": true,
        "ajax" :{
           "url": 'index.php?entryPoint=GetEmployeeRatingJobOfferEntryPoint',
           "type": "POST",
           'data' : data_send,
        },
        "paging" : true,
        "order": [[ 2, "desc" ]],
        "info" : true,
        "filter" : true,
        "columnDefs": [
           
              {
               "targets": [0], 
               "data": "name_employees",
               "width": "350px", 
               "render": function(data, type, row){
                  let url_employee_information =  `index.php?module=${row.object_name_employees}&offset=1&return_module=${row.object_name_employees}&action=DetailView&record=${row.id_employees}`;
                  return `<a href="${url_employee_information}" >${data}</a>`;            
               }
              },
              {
               "targets": [1], 
               "data": "qualification_rating",
               "render": function(data, type, row){
                 let data_td = (data*10).toFixed(1);
                 return `<p>${data_td} %</p>`;            
               }
              },
              {
                "targets": [2], 
                "data": "skill_rating", 
                "render": function(data, type, row){
                  let data_td = (data*10).toFixed(1);
                  return `<p>${data_td} %</p>`;     
                }
              },
              {
                "targets": [3], 
                "data": "general_rating",
                "className": 'dt-body-rating-employee',
                "render": function(data, type, row){
                  return "";     
                }
              },
              {
                "targets": [4], 
                "data": "id_employees", 
                "sortable": false,
                "className": "view_rating",
                "render": function(data, type, row){
                  return `<span onclick="matchCandidateResult('${data}','E')" class="suitepicon suitepicon-action-view" data-target="#modal_Candidate_Rating" data-toggle="modal" style="cursor: pointer;" title="View Graphics"></span>`; 
                }
              }
         ],
          "drawCallback": function( settings ) {
             drawRatingsEmployeeRating();
          }
          
        
      });

     

   
      /*suitecrm styles collide with this element I reset it*/
      $("#dataTableRatingEmployee_length").find("select").css({"height": "30px","width": "5em"});

}

      
function getApplicationsRatingobOffer(){

  let data_send = {
     action : "GetApplicationsRatingobOffer",
     JobApplicationId: JobApplicationId
  }

  if(tablaApplicationsRating != ""){
    tablaApplicationsRating.destroy();
  }

  tablaApplicationsRating = $("#dataTableApplicationsRating").DataTable({
    "responsive": true,
    "ajax" :{
       "url": 'index.php?entryPoint=GetApplicationsRatingobOfferEntryPoint',
       "type": "POST",
       'data' : data_send,
    },
    "paging" : true,
    "order": [[ 0, "desc" ]],
    "info" : true,
    "filter" : true,
    "columnDefs": [   
      {
       "targets": [0], 
       "data": "name",
       "className": "td_name_table_rating_application",
       "render": function(data, type, row){
          let url_candidate = `index.php?module=${row.object_name_candidate}&offset=1&return_module=${row.object_name_candidate}&action=DetailView&record=${row.id_candidate}`;
          return `<a href="${url_candidate}" >${data}</a>`;            
      }
      },
      {
       "targets": [1], 
       "data": "qualification_rating",
       "className": "dt-body-rating-applications_qualifications",
       "render": function(data, type, row){
          return "";
       }
      },
      {
        "targets": [2], 
        "data": "skill_rating", 
        "width": "280px",
        "className": "dt-body-rating-applications_skill",
        "render": function(data, type, row){
          return "";
        }
      }
      ],
      "drawCallback": function(settings, json) {
          drawRatingsApplicationsRating();
        }
  });

  /*suitecrm styles collide with this element I reset it*/
 $("#dataTableApplicationsRating_length").find("select").css({"height": "33px","width": "5em"});


}


function getProfileJobOffer(){

  let data_send = {
    action : "GetProfileJobOffer",
    JobApplicationId: JobApplicationId
  }


  if(tablaJobProfile != ""){
    tablaJobProfile.destroy();
  }

  tablaJobProfile = $("#dataTableJobProfile").DataTable({
     "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=GetProfileJobOfferEntryPoint',
        "type": "POST",
        'data' : data_send,
     },
     "paging" : true,
     "order": [[ 0, "desc" ]],
     "info" : true,
     "filter" : true,
      "columnDefs": [
        {
            "targets": [0], 
            "data": "id", 
            "className": "",
            "render": function(data, type, row){
                let url = `index.php?module=${row.object_name}&offset=1&return_module=${row.object_name}&action=DetailView&record=${row.id}`;
                return `<a href="${url}" >${row.name}</a>`;
            }
          },
          {
            "targets": [1], 
            "sortable": false,
            "data": "dependency_list", 
            "className": "td_dependecy",
            "render": function(data, type, row){

               let disabled_select = (IsPublished == 1) ? "disabled" : "";
                let html = `<select class="form-control" ${disabled_select} onchange="change_dependecy_profile('${row.id}',$(this))" id="txt_dependency" name="txt_dependency">`;
                html+= '<option value="">Selected</option>';

                Object.entries(data).forEach(([key, value]) => {
                   let selected = row.dependency == key ? "selected" : "";
                   html+=`<option value="${key}" ${selected}>${value}</option>`;
                });
                html+="</select>";

                return html;
            }
          },
           {
             "targets": [2],
             "sortable": false, 
              "data": "id", 
             "className": "td_delete_profile",
             "render": function(data, type, row){
               return  `<span title="Delete Profile" onclick="deleteProfile('${data}','${JobApplicationId}')" class="suitepicon suitepicon-action-delete"></span>`;
             }
           }
      ],
      "initComplete": function(settings, json) {
       
        /*I store in a string all the profiles of the offer*/
        profileAllJobOffer = [];
        Object.entries(json['data']).forEach(([key, value]) => {
          profileAllJobOffer.push(value.id);
        });

        createSearchElement(
          '#dataTableJobProfile_wrapper',
          "profile_list",
          window.JobApplicationId,
          'index.php?entryPoint=GetProfileEntryPoint',
           'getProfile',
          'Select a Profile to Add',
          function(e){ 
             addAjaxProfile(JobApplicationId, e.id );
        });
        
        getEmployeeRatingOffer(profileAllJobOffer);
          
      }
   });

   /*suitecrm styles collide with this element I reset it*/
  $("#dataTableJobProfile_length").find("select").css({"height": "33px","width": "5em"});
  

}


function candidate_ratings(){

    let data_send = {
      action : "GetCandidateRatingJobOffer",
      JobApplicationId: JobApplicationId
    }

         
     if(tableRating != ""){
        tableRating.destroy();
     }
   
     tableRating = $("#dataTableRating").DataTable({
         "responsive": true,
        "ajax" :{
           "url": 'index.php?entryPoint=GetCandidateRatingJobOfferEntryPoint',
           "type": "POST",
           'data' : data_send,
        },
        "paging" : true,
        "order": [[ 0, "desc" ]],
        "info" : true,
        "filter" : true,
        "columnDefs": [
           
              {
               "targets": [0], 
               "data": "name",
               "width": "350px", 
               "render": function(data, type, row){
                  let url_candidate = `index.php?module=${row.object_name_candidate}&offset=1&return_module=${row.object_name_candidate}&action=DetailView&record=${row.id_candidate}`;
                  return `<a href="${url_candidate}" >${data}</a>`;            
               }
              },
              {
               "targets": [1], 
               "data": "qualification_rating",
               "render": function(data, type, row){
                 return `<p>${data} %</p>`;            
               }
              },
              {
                "targets": [2], 
                "data": "skill_rating", 
                "render": function(data, type, row){
                  return `<p>${data} %</p>`; 
                }
              },
              {
                "targets": [3], 
                "data": "general_rating", 
                "className": 'dt-body-rating-candidate',
                "render": function(data, type, row){
                  return ""; 
                }
              },
              {
                "targets": [4], 
                "data": "id_candidate", 
                "sortable": false,
                "className": "view_rating",
                "render": function(data, type, row){
                  return `<span onclick="matchCandidateResult('${data}','C')" class="suitepicon suitepicon-action-view" data-target="#modal_Candidate_Rating" data-toggle="modal" style="cursor: pointer;" title="View Graphics"></span>`; 
                }
              }
         ],
         "drawCallback": function(settings, json) {
             drawRatingsCandidate();
          }
      });
   
      /*suitecrm styles collide with this element I reset it*/
      $("#dataTableRating_length").find("select").css({"height": "30px","width": "5em"});

}


function addAjaxProfile(JobApplicationId,id){

  if(IsPublished == 1){
    toastr.warning("Profile cannot be added because the offer is published", 'Oops!');
    return;
  }

  const update = {
    "action":"addProfile",
    "id_profile": id,
    "JobApplicationId": JobApplicationId
  };

  if(IsPublished == 1){
    return;
  }

  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=AddProfileJobOfferEntryPoint',
    data: update,
    success: function(resp) {
      let data_respu = JSON.parse(resp);
     
      if(!data_respu.results){
        toastr.error("Could not make the transaction", 'Oops!');
        return;
      }

     
        /*I store in a string all the profiles of the offer*/
        if(profileAllJobOffer.length > 0){
            if(!profileAllJobOffer.includes(id)){
              profileAllJobOffer.push(id);
            }
        }else{
          profileAllJobOffer.push(id);
        }

         tablaJobProfile.ajax.reload();
        // tableQualificationsProfile.ajax.reload(); 
         getProfileQualifications();
         tableSkill.ajax.reload(drawRatings);
         getEmployeeRatingOffer(profileAllJobOffer);
         tablaApplicationsRating.ajax.reload(drawRatingsApplicationsRating);
         tableRating.ajax.reload(drawRatingsCandidate);
      }
    
  });

}


function getProfileSkills(){

  let data_send = {
    action : "GetProfileSkills",
    JobApplicationId: JobApplicationId
  }

  if(tableSkill != ""){
     tableSkill.destroy();
  }

  tableSkill = $("#table_skills").DataTable({
      "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=GetProfileSkillsEntryPoint',
        "type": "POST",
        'data' : data_send,
     },
     "paging" : true,
     "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
     "order": [[ 0, "desc" ]],
     "info" : true,
     "filter" : true,
     "columnDefs": [
          {
            "targets": [0], 
            "data": "id_profile", 
            "className": "",
            "render": function(data, type, row){
               let url_profile        = `index.php?module=${row.object_name_profile}&offset=1&return_module=${row.object_name_profile}&action=DetailView&record=${row.id_profile}`;
               return `<a href="${url_profile}" >${row.name_profile}</a>`;            
            }
           },
           {
            "targets": [1], 
            "data": "id_skills", 
            "className": "",
            "render": function(data, type, row){
              let url_skill        = `index.php?module=${row.object_name_skills}&offset=1&return_module=${row.object_name_skills}&action=DetailView&record=${row.id_skills}`;
              return `<a href="${url_skill}" >${row.name}</a>`;            
            }
           },
           {
             "targets": [2], 
             "data": "id_skills", 
             "sortable": false,
             "className": "dt-body-rating",
             "render": function(data, type, row){
                return "";
             }
           }
      ],
      "drawCallback": function(settings, json) {
        drawRatings();
       }
   });

   /*suitecrm styles collide with this element I reset it*/
   $("#table_skills_length").find("select").css({"height": "30px","width": "5em"});

}


function getJobOffer(elementId){
    
  let data_send = {
    action : "GetJobOffer",
    JobApplicationId: JobApplicationId,
    "IdAccount": IdAccount
  }
  

  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=GetJobOfferEntryPoint',
    dataType: 'json',
    data: data_send,
    success: function(resp) {
     
      // fill checbox expire on 
      if(IsPublished == 1){
        $("#txt_is_published").prop("checked",true);
        $("#txt_is_published").prop("title","The offer is published");
        $("#txt_is_published").val(IsPublished);
        $("#state_offer").html("Unpublish");
      }

       // fill select assingned location
       $("#txt_assigned_location").html("");
       Object.entries(resp['assigned_location_list']).forEach(([key, value]) => {
          let selected = assignedLocation == key ? "selected" : "";
          $("#txt_assigned_location").append(`<option value="${key}" ${selected}>${value}</option>`);
       });
 
       // fill select contract type
       $("#txt_contact_type").html("");
       Object.entries(resp['contract_type_list']).forEach(([key, value]) => {
          let selected = contractType == key ? "selected" : "";
          $("#txt_contact_type").append(`<option value="${key}" ${selected}>${value}</option>`);
       });
       
      
      // fill select account
      if(resp['results']){
        let newOption = new Option(resp['results'][0].name, IdAccount, true, true);
        $("#txt_id_account").val(IdAccount);
        $('#'+elementId).append(newOption).trigger('change');
      }

      let newOptionP = new Option(resp['resultJD'][0].name, resp['resultJD'][0].id, true, true);
      $("#old_jod_description_id").val(resp['resultJD'][0].id);
      $("#hd_jod_description_id").val(resp['resultJD'][0].id);
      $("#charge_list").append(newOptionP).trigger('change');


    }
  });
}


function getJobApplicationsOffer(){
    
    let data_send = {
      action : "GetJobApplicationsOffer",
      JobApplicationId: JobApplicationId
    }

    if(tableJobApplications != ""){
      tableJobApplications.destroy();
    }

    tableJobApplications = $("#dataTableJobApplications").DataTable({
     "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=GetJobApplicationsOfferEntryPoint',
        "type": "POST",
        'data' : data_send,
     },
     "paging" : true,
     "order": [[ 0, "desc" ]],
     "info" : true,
     "filter" : true,
     "columnDefs": [
          {
            "targets": [0], 
            "data": "name", 
            "className": "td_name_jon_applications",
            "render": function(data, type, row){
               let url_application  = `index.php?module=CC_Job_Applications&offset=1&return_module=CC_Job_Applications&action=DetailView&record=${row.id_job_application}`;
               return `<a href="${url_application}" >${nameOffer}</a>`;            
            }
           },
           {
            "targets": [1], 
            "data": "stage", 
            "className": "",
            "render": function(data, type, row){
              return `<p>${data}</p>`;      
            }
           },
           {
            "targets": [2], 
            "data": "name", 
            "className": "dt-body-rating",
            "render": function(data, type, row){
              let url_candidate        = `index.php?module=${row.object_name_candidate}&offset=1&return_module=${row.object_name_candidate}&action=DetailView&record=${row.id_candidate}`;
              return `<a href="${url_candidate}" >${data}</a>`;      
            }
           },
           {
             "targets": [3], 
             "data": "type", 
             "className": "dt-body-rating",
             "render": function(data, type, row){
               return `<p>${data}</p>`;      
             }
           }
      ]
   });

    /*suitecrm styles collide with this element I reset it*/
    $("#dataTableJobApplications_length").find("select").css({"height": "30px","width": "5em"});
   
}


function accountSelect(data){
  if(data){
    accountData = data;
    let newOption = new Option(accountData.name, accountData.id, true, true);
    $("#txt_id_account").val(accountData.id);
    $('#account_list').html(newOption).trigger('change');
  }
}

function SelectAction(data,element,hd_id_element, old_id_element ,multi_option){

  if(element == "charge_list"){

    let data_send = {
      action : "checkProfilePosition",
      position : data.id,
    }

      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=GetJobDescriptionEntryPoint',
        dataType: 'json',
        data: data_send,
        success: function(resp) {
          let data = resp['results'];
          if(data.length == 0){
            toastr.error('This Position does not have related Profiles', 'Warning!');
          }
        }});
  }
 
    if(data && multi_option != 1){
      let newOption = new Option(data.name, data.id, true, true);
      $(`#${hd_id_element}`).val(data.id);
      $(`#${old_id_element}`).val(data.id);
      $(`#${element}`).html(newOption).trigger('change');
    }
    
  }


function createSearchElement(parent, elementId, jobOfferId, url, moduleAction, placeholder, functionActionSelect){
  
  $(parent+" > div:nth-child(2)").css({"width":"75%", "margin-bottom": "10px"});
  $(parent+" > div:nth-child(2)").html("<div id='"+elementId+"' class='dataTables_select'><select class='js-select-"+elementId+"'></select></div>");

  let element = $('#' + elementId).select2({
    placeholder: placeholder,
    allowClear: true,
    ajax: {
        delay: 250,
        transport: function (params, success, failure) {
            const query = {
                action: moduleAction,
                searchTerm: params.data.term,
                id:jobOfferId,
                type: 'public'
            };

            const $request = $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: query
            });

            $request.then(success);
            $request.fail(failure);

            return $request;
        },
        processResults: function (data) {
            let mapData = $.map(data.results, function (obj) {
                obj.id = obj.id || obj.Id;
                obj.text = obj.text || obj.Name;
                obj.text = (obj.text)?obj.text:obj.name;
                return obj;
            });
            return {
                results: mapData
            };
        }
    },
});

$(element).on("select2:select", function(e){
    let data = e?.params?.data;
    functionActionSelect(data);
});

}


function createApplicationSearchElement(parent, elementId, applicationId, url, moduleAction, placeholder, functionActionSelect){
  
  if(!$('#'+elementId).length) {
      $(parent).after("<div id='"+elementId+"'><select class='js-select-"+elementId+"'></select></div>");
       
      let element = $('#' + elementId).select2({
          width: '80%',
          placeholder: placeholder,
          allowClear: true,
          ajax: {
              delay: 250,
              transport: function (params, success, failure) {
                  const query = {
                      applicationId: applicationId,
                      action: moduleAction,
                      searchTerm: params.data.term,
                      type: 'public'
                  };

                  const $request = $.ajax({
                      type: "POST",
                      url: url,
                      dataType: 'json',
                      data: query
                  });

                  $request.then(success);
                  $request.fail(failure);
                  return $request;
              },
              processResults: function (data) {
                  let mapData = $.map(data.results, function (obj) {
                      obj.id = obj.id || obj.Id;
                      obj.text = obj.text || obj.Name;
                      obj.text = (obj.text)?obj.text:obj.name;
                      return obj;
                  });
                  return {
                      results: mapData
                  };
              }
          },
      });
   
      $(element).on("select2:select", function(e){
          let data = e?.params?.data;
          functionActionSelect(data);
      });
  }
}

/********** Interviewer ***********/

function getInterviewersJobOffer(){

  let data_send = {
    action : "GetInterviewersJobOffer",
    JobApplicationId: JobApplicationId
  }
  
  
  if(tablaJobInterviewer != ""){
    tablaJobInterviewer.destroy();
  }
  
  tablaJobInterviewer = $("#dataTableInterviewers").DataTable({
     "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=GetInterviewerJobOfferEntryPoint',
        "type": "POST",
        'data' : data_send,
     },
     "paging" : true,
     "order": [[ 0, "desc" ]],
     "info" : true,
     "filter" : true,
      "columnDefs": [
        {
            "targets": [0], 
            "data": "id", 
            "className": "",
            "render": function(data, type, row){
                let url = `index.php?module=${row.object_name}&offset=1&return_module=${row.object_name}&action=DetailView&record=${row.id}`;
                return `<a href="${url}" >${row.name}</a>`;
            }
          },
          {
            "targets": [1], 
            "sortable": false,
            "data": "role_list", 
            "className": "td_role",
            "render": function(data, type, row){
              
              let disabled_select = (IsPublished == 1) ? "disabled" : "";
  
                let html = `<select class="form-control" ${disabled_select} onchange="change_role_interviewer('${row.id}',$(this))" id="txt_role" name="txt_role">`;
                html+= '<option value="">Selected</option>';
  
                Object.entries(data).forEach(([key, value]) => {
                   let selected = row.role == key ? "selected" : "";
                   html+=`<option value="${key}" ${selected}>${value}</option>`;
                });
                html+="</select>";
  
                return html;
            }
          },
           {
             "targets": [2],
             "sortable": false, 
              "data": "id", 
             "className": "td_delete_profile",
             "render": function(data, type, row){
               return  `<span title="Delete Interviewer" onclick="deleteInterviewer('${row.id}','${JobApplicationId}')" class="suitepicon suitepicon-action-delete"></span>`;
             }
           }
      ],
      "initComplete": function(settings, json) {
       
        /*I store in a string all the interviewers of the offer*/
        interviewerAllJobOffer = [];
        Object.entries(json['data']).forEach(([key, value]) => {
          interviewerAllJobOffer.push(value.id);
        });
  
        createSearchElement(
          '#dataTableInterviewers_wrapper',
          "interviewer_list",
          window.JobApplicationId,
          'index.php?entryPoint=GetInterviewerEntryPoint',
           'getInterviewer',
          'Select an Employee to Add',
          function(e){ 
             addAjaxInterviewer(JobApplicationId, e.id );
        });
          
      }
   });
  
   /*suitecrm styles collide with this element I reset it*/
  $("#dataTableInterviewers_length").find("select").css({"height": "33px","width": "5em"});
  
  }
  
  
  function addAjaxInterviewer(JobApplicationId,id){
  
  if(IsPublished == 1){
    toastr.warning("Interviewer Employee cannot be added because the offer is published", 'Oops!');
    return;
  }
  
  const update = {
    "action":"addInterviewer",
    "id_employee": id,
    "JobApplicationId": JobApplicationId
  };
  
  if(IsPublished == 1){
    return;
  }
  
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=AddInterviewerJobOfferEntryPoint',
    data: update,
    success: function(resp) {
      let data_respu = JSON.parse(resp);
     
      if(!data_respu.results){
        toastr.error("Could not make the recording", 'Oops!');
        return;
      }
      getInterviewersJobOffer();
  
      }
    
  });
  
  }
  
  
  function change_role_interviewer(id_interviewer,elem){
  
  const data_Send = {
    "action":"updateRoleInterviewerJobOffer",
    "id_interviewer": id_interviewer,
    "role": elem.val(),
    "JobApplicationId": JobApplicationId
  };
  
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=UpdateRoleInterviewerJobOfferEntryPoint',
    data: data_Send,
    success: function(resp) {
      if(resp) {
         tablaJobInterviewer.ajax.reload();
      }
    }
  });
  
  }
  
  
  function deleteInterviewer(idInterviewer,JobApplicationId){
    
  const data_send = {
    "action":"deleteInterviewer",
    "id_interviewer": idInterviewer,
    "JobApplicationId": JobApplicationId
  };
  
  if(IsPublished == 1){
    toastr.warning("Interviewer cannot be deleted because the offer is published", 'Oops!');
   return;
  }
  
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=DeleteInterviewerJobOfferEntryPoint',
    data: data_send,
    success: function(resp) {
      let data_respu = JSON.parse(resp);
     
      if(!data_respu.results){
        toastr.error("Could not make the transaction", 'Oops!');
        return;
      }
      getInterviewersJobOffer();
    }
    
  });
  
  }



/******* Related Employees ***********/


function getRelatedEmployees(){


  let data_send = {
    action : "GetRelatedEmployees",
    JobApplicationId: JobApplicationId
  }
  
  
  if(tablaRelatedEmployee != ""){
    tablaRelatedEmployee.destroy();
  }
  
  tablaRelatedEmployee = $("#dataTableRelatedEmployee").DataTable({
     "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=GetInterviewerJobOfferEntryPoint',
        "type": "POST",
        'data' : data_send,
     },
     "paging" : true,
     "order": [[ 0, "desc" ]],
     "info" : true,
     "filter" : true,
      "columnDefs": [
        {
            "targets": [0], 
            "data": "id", 
            "className": "",
            "render": function(data, type, row){
                /*let url = `index.php?module=${row.object_name}&offset=1&return_module=${row.object_name}&action=DetailView&record=${row.id}`;
                return `<a href="${url}" >${row.name}</a>`;*/
                return `<p> ${row.name} </p>`
            }
          },
          {
            "targets": [1], 
            "sortable": false,
            "data": "role_list", 
            "className": "td_role",
            "render": function(data, type, row){

              let disabled_select = (IsPublished == 1) ? "disabled" : "";
  
                let html = `<select class="form-control" ${disabled_select} onchange="change_role_employee('${row.id}',$(this))" id="txt_role" name="txt_role">`;
                html+= '<option value="">Selected</option>';
  
                Object.entries(data).forEach(([key, value]) => {
                   let selected = row.type == key ? "selected" : "";
                   html+=`<option value="${key}" ${selected}>${value}</option>`;
                });
                html+="</select>";
  
                return html;
            }
          },
           {
             "targets": [2],
             "sortable": false, 
              "data": "id", 
             "className": "td_delete_profile",
             "render": function(data, type, row){
               return  `<span title="Delete Related Employee" onclick="deleteRelatedEmployee('${row.id}','${JobApplicationId}')" class="suitepicon suitepicon-action-delete"></span>`;
             }
           }
      ],
      "initComplete": function(settings, json) {
       
  
        createSearchElement(
          '#dataTableRelatedEmployee_wrapper',
          "employee_list",
          window.JobApplicationId,
          'index.php?entryPoint=GetInterviewerEntryPoint',
           'getEmployee',
          'Select an Employee to Add',
          function(e){ 
             addAjaxRelatedEmployee(JobApplicationId, e.id );
        });
          
      }
   });
  
   /*suitecrm styles collide with this element I reset it*/
  $("#dataTableRelatedEmployee_length").find("select").css({"height": "33px","width": "5em"});
  
  }
  
  
  function addAjaxRelatedEmployee(JobApplicationId,id){
  
  if(IsPublished == 1){
    toastr.warning(" Employee cannot be added because the offer is published", 'Oops!');
    return;
  }
  
  const update = {
    "action":"addRelatedEmployee",
    "id_employee": id,
    "JobApplicationId": JobApplicationId
  };
  
  if(IsPublished == 1){
    return;
  }
  
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=AddInterviewerJobOfferEntryPoint',
    data: update,
    success: function(resp) {
      let data_respu = JSON.parse(resp);
     
      if(!data_respu.results){
        toastr.error("Could not make the recording", 'Oops!');
        return;
      }
      getRelatedEmployees();
  
      }
    
  });
  
  }
  
  
  function change_role_employee(id_employee,elem){
  
  const data_Send = {
    "action":"updateRoleRelatedEmployee",
    "id_employee": id_employee,
    "role": elem.val(),
    "JobApplicationId": JobApplicationId
  };
  
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=UpdateRoleInterviewerJobOfferEntryPoint',
    data: data_Send,
    success: function(resp) {
      if(resp) {
         tablaRelatedEmployee.ajax.reload();
      }
    }
  });
  
  }
  
  
  function deleteRelatedEmployee(id_employee,JobApplicationId){
    
  const data_send = {
    "action":"deleteRelatedEmployee",
    "id_employee": id_employee,
    "JobApplicationId": JobApplicationId
  };
  
  if(IsPublished == 1){
    toastr.warning("Employee cannot be deleted because the offer is published", 'Oops!');
   return;
  }
  
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=DeleteInterviewerJobOfferEntryPoint',
    data: data_send,
    success: function(resp) {
      let data_respu = JSON.parse(resp);
     
      if(!data_respu.results){
        toastr.error("Could not make the transaction", 'Oops!');
        return;
      }
      getRelatedEmployees();
    }
    
  });
  
  }


  
 /********** Accounts ***********/

 function getAccountsJobOffer(){

  let data_send = {
    action : "GetAccountJobOffer",
    JobApplicationId: JobApplicationId
  }
  
  if(tablaJobAccount != ""){
    tablaJobAccount.destroy();
  }
  
  tablaJobAccount = $("#dataTableJobAccounts").DataTable({
     "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=GetInterviewerJobOfferEntryPoint',
        "type": "POST",
        'data' : data_send,
     },
     "paging" : true,
     "order": [[ 0, "desc" ]],
     "info" : true,
     "filter" : true,
      "columnDefs": [
        {
            "targets": [0], 
            "data": "id", 
            "className": "",
            "render": function(data, type, row){
                let url = `index.php?module=${row.object_name}&offset=1&return_module=${row.object_name}&action=DetailView&record=${row.id}`;
                return `<a href="${url}" >${row.name}</a>`;
            }
          },
           {
             "targets": [1],
             "sortable": false, 
              "data": "id", 
             "className": "td_delete_profile",
             "render": function(data, type, row){
               return  `<span title="Delete Account" onclick="deleteAccount('${row.id}','${JobApplicationId}')" class="suitepicon suitepicon-action-delete"></span>`;
             }
           }
      ],
      "initComplete": function(settings, json) {
       
        /*I store in a string all the Account of the offer*/
        AccountAllJobOffer = [];
        Object.entries(json['data']).forEach(([key, value]) => {
          AccountAllJobOffer.push(value.id);
        });
  
        createSearchElement(
          '#dataTableJobAccounts_wrapper',
          "Account_list",
          window.JobApplicationId,
          'index.php?entryPoint=AccountApplicationEntryPoint',
           'getAccount',
          'Select an Account to Add',
          function(e){ 
             addAjaxAccount(JobApplicationId, e.id );
        });
          
      }
   });
  
   /*suitecrm styles collide with this element I reset it*/
  $("#dataTableJobAccounts_length").find("select").css({"height": "33px","width": "5em"});
  
  }
  
  
  function addAjaxAccount(JobApplicationId,id){
  
  
  const update = {
    "action":"addAccount",
    "id_account": id,
    "JobApplicationId": JobApplicationId
  };
  
  
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=AddInterviewerJobOfferEntryPoint',
    data: update,
    success: function(resp) {
      let data_respu = JSON.parse(resp);
     
      if(!data_respu.results){
        toastr.error("Could not make the recording", 'Oops!');
        return;
      }
      getAccountsJobOffer();
  
      }
    
  });
  
  }
  
  
  function deleteAccount(idAccount,JobApplicationId){
    
  const data_send = {
    "action":"deleteAccount",
    "id_account": idAccount,
    "JobApplicationId": JobApplicationId
  };

  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=DeleteInterviewerJobOfferEntryPoint',
    data: data_send,
    success: function(resp) {
      let data_respu = JSON.parse(resp);
     
      if(!data_respu.results){
        toastr.error("Could not make the transaction", 'Oops!');
        return;
      }
      getAccountsJobOffer();
    }
    
  });
  
  }


/*******************************************************************************
********************** functions of graphics **********************************
*******************************************************************************/

let matchChart;
let candidateCtxRating = "";
if(candidateCtxRating == ""){
      candidateCtxRating = document.getElementById('CandidateChart').getContext('2d'); // Points to the canvas element
}


/**
    * Call the entrypoint for matching the employee with the profile
*/

function matchCandidateResult(secondaryId,ModuloAction) {

  if(ModuloAction == "C"){
      data_send = {
        secondaryModule:'CC_Candidate', 
        secondaryId: secondaryId, 
        profileId: profileAllJobOffer.join()
     }
  }

  if(ModuloAction == "E"){
    data_send = {
      secondaryModule:'CC_Employee_Information', 
      secondaryId: secondaryId, 
      profileId: profileAllJobOffer.join()
   }
  }


  clearTables('skillCandidateModalTable');
  showCChart = true;
  changeCandidateSkillView();
  $.ajax({
    type: "POST",
    url: 'index.php?entryPoint=profileMatchEntryPoint',
    dataType: 'json',
    data: data_send,
    success: function(resp) { 
      if(matchChart){
        matchChart.destroy();
      }
      createCMSkillChart(resp.Skills);
      createCMSkillTable(resp.Skills);
    }
  });

}


   /**
      * Create the skill chart
      * @param skills
      */
    function createCMSkillChart(skills){
      let labelsChart = [];
      let profileChartSkills = [];
      let moduleChartSkills = [];

      skills.forEach(skill => {
        if(skill.ProfileRelation === 'rating' || skill.moduleType === 'rating'){
          labelsChart.push(skill.Name);
          let profileAmount = (skill.ProfileAmount/5)*100;
          let moduleAmount = (skill.moduleAmount/5)*100;
          profileChartSkills.push(profileAmount);
          moduleChartSkills.push(moduleAmount);
        }
      })

      generateCChart(labelsChart,profileChartSkills,moduleChartSkills)
      
    };

    /**
    * Create the skill table
    * @param skills
    */
    function createCMSkillTable(skills){
      skills.forEach(skill => {
        if(skill.ProfileRelation === 'rating' || skill.moduleType === 'rating'){
          let td;
          let base = Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
          let profileId = 'modalP'+ base + '-' + skill.Id ;
          let moduleId = 'modalS'+ base + '-' + skill.Id;
          let element = document.getElementById("skillCandidateModalTable");
          let tr = document.createElement("tr");

          tr.appendChild(createCandidateColumn("\u00A0"));
          td = createCandidateColumn(skill.Name);
          td.style.width = "20%";
          tr.appendChild(td);

          td = createField(skill.ProfileAmount, profileId);
          td.style.width = "40%";
          tr.appendChild(td);

          td = createField(skill.moduleAmount, moduleId, true);
          td.style.width = "40%";
          tr.appendChild(td);

          element.appendChild(tr);
          starsField(profileId,5);
          starsField(moduleId,5);
        }
      })
    }

    /**
      * Generates the chart
      * @param labels
      * @param profileSkills
      * @param moduleSkills
      */
    function generateCChart(labels, profileSkills, moduleSkills){
      matchCandidateChart = new Chart(candidateCtxRating, {
      type: 'radar',
      data: {
        labels: labels,
        datasets: [{
          data: moduleSkills,
          label: 'Candidate',
          backgroundColor: 'rgba(59, 97, 238, 0.5)',
          borderColor: 'rgba(59, 97, 238, 0.8)',
        },
        {
          data: profileSkills,
          label: 'Profile',
          backgroundColor: 'rgba(255, 99, 71, 0.3)',
          borderColor: 'rgba(255, 99, 71, 0.8)',
        }
        ]
      },
      options: {
        maintainAspectRatio: true,
       scale: {
            ticks: {
                suggestedMin: 0,
                suggestedMax: 100,
            }
        }
      }
    });
    }

    /**
      * Change the view between table and chart
      */
    function changeCandidateSkillView(){
      let skillsTable = document.getElementById("mainSkillCandidateModalTable");
      let skillsTableLabels = document.getElementById("mainSkillCandidateModalTableLabels");
      let skillsChart = document.getElementById("skillCandidateChart");
      let changeButton = document.getElementById("chartCandidateButton");
      if(showCChart){
        showCChart = false;
        skillsChart.style.display = "block";
        skillsTable.style.display = "none";
        skillsTableLabels.style.display = "none";
        changeButton.value = "View on Table";
      } else {
        showCChart = true;
        skillsChart.style.display = "none";
        skillsTable.style.display = "block";
        skillsTableLabels.style.display = "block";
        changeButton.value = "View Chart";
      }

    }


    /**
      * Creates a regular column
      * @param text information to be presented on the column
      * @param align alignment of the column
      * @param candidateId Id of the candidate to create the link to the
      * detail view.
      */
    function createCandidateColumn(text, align = "left", candidateId = null){
      let fieldtext;
      let td = document.createElement("td");
      td.style.display = "table-cell";

      if(!candidateId){
        if (text === null){
          fieldtext = document.createTextNode("\u00A0");
        } else {
          fieldtext = document.createTextNode(text);
        }
      } else {
        fieldtext = document.createElement("a");
        let link = "?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DCC_Candidate%26offset%3D1%26stamp%3D1613768547006033300%26return_module%3DCC_Candidate%26action%3DDetailView%26record%3D";
        fieldtext.setAttribute("href",link+candidateId);
        fieldtext.innerHTML = text;
      }

      td.appendChild(fieldtext);
      td.style.textAlign = align;

      return td;
    }

   /**
    * Create the field depending on the requirements
    * @param amount amount of the skill/qualification
    * @param id id used for the starfield
    * @param isModal Establish differences so the field
    * is created with 5 or 10 stars
    */
    function createCandidateField(amount, id = "", isModal = false){
      let field;
      let startAmount = parseFloat((amount) ? amount : 0).toFixed(1);

      if(window.innerWidth > 460) {
        field = createStarfield(id, startAmount);
      } else {
        field = createCandidateColumn( startAmount + '/10', "center");
      }

      return field;
    }

    /**
      * Create the stars field
      * @param id id of the field
      * @param amount amount of the skill
      * secondary modules for the fields to be created
      */
    function createCandidateStarfield(id, amount){
      let ratingdiv = document.createElement("div");
      let td = document.createElement("td");
      
      td.style.display = "table-cell";
      //ratingdiv.setAttribute("id", "SkillRatingExpSelectorArea");
      ratingdiv.style.width = "100%";
      ratingdiv.style.float = "left";

      let stardiv = document.createElement("div");
      stardiv.style.float = "left";
      stardiv.setAttribute("id", "candidate"+id);
      stardiv.setAttribute("data-rating", amount);
      ratingdiv.appendChild(stardiv);
      td.appendChild(ratingdiv)
      return td
    }

    /**
     * Check changes on the screen width
     */
    function screenCandidateWidthChange(){
      window.onresize = displayCandidateWindowSize;
    }

    /**
      * Change skill table depending on width
      */
    function displayCandidateWindowSize(){
      if(window.innerWidth < 460 && !isCandidateMobile) {
        isCandidateMobile = true;
        clearTables('skillsCandidateTable');
        createCandidateMatchTable(candidateResults);
      } else if(window.innerWidth > 460 && isCandidateMobile) {
        isCandidateMobile = false;
        clearTables('skillsCandidateTable');
        createCandidateMatchTable(candidateResults);
      }
    }



    function createField(amount, id = "", isModal = false){
      let field;
      let startAmount = parseFloat((amount) ? amount : 0).toFixed(1);
  
      if(window.innerWidth > 460) {
        field = createStarfield(id, startAmount);
      } else {
        field = createCandidateColumn( startAmount + '/10', "center");
      }
  
      return field;
    }


  function createStarfield(id, amount){
    let ratingdiv = document.createElement("div");
    let td = document.createElement("td");

    td.style.display = "table-cell";
    //ratingdiv.setAttribute("id", "SkillRatingExpSelectorArea");
    ratingdiv.style.width = "100%";
    ratingdiv.style.float = "left";

    let stardiv = document.createElement("div");
    stardiv.style.float = "left";
    stardiv.setAttribute("id", id);
    stardiv.setAttribute("data-rating", amount);
    ratingdiv.appendChild(stardiv);
    td.appendChild(ratingdiv)

    return td
  }

/*******************************************************************************
********************** end functions of graphics *******************************
*******************************************************************************/
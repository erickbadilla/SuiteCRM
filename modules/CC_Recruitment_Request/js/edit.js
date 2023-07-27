var tableQualificationsProfile = "";
var tableSkill              = "";
var obj_new_data = new Object();
var profiles_existing = "";

$(document).ready(function(){

toastr.options = {
    "positionClass": "toast-bottom-right",
}

$("#subpanel_list").css("display","none");



let initial_data = {
  action: "getPriority"
}

$.post(
  'index.php?entryPoint=GetPriorityCaseEntryPoint',
  initial_data,
  function (data) {
    let resp = data['results']['data'][0]; 
    Object.entries(resp['case_priority_dom']).forEach(([key, value]) => {
       $("#slc_priority").append(`<option value="${key}" >${value}</option>`);
    });
  },
  'json'
)



Promise.all([
        createApplicationElement(
          '#selectAccountWrapper',
          "account_list",
          "",
          'index.php?entryPoint=AccountApplicationEntryPoint',
          'getAccount',
          'Select for a Account',
          function (e) {SelectAction(e,"account_list","hd_account_create",0);
       }), 
       createApplicationElement(
          '#selectProjectWrapper',
          "project_list",
          "",
          'index.php?entryPoint=GetProjectEntryPoint',
          'getProject',
          'Select a Project to Add',
          function (e) {SelectAction(e,"project_list","hd_project_id",0);
      }), 
      createApplicationElement(
          '#selectChargeWrapper',
          "charge_list",
          "",
          'index.php?entryPoint=GetJobDescriptionEntryPoint',
          'getJobDescription',
          'Select a Position to Add',
          function (e) {SelectAction(e,"charge_list","hd_jod_description_id",0);
      }),
      createApplicationElement(
        '#selectProfileWrapper',
        "profile_list",
        "",
        'index.php?entryPoint=GetProfileApplicationEntryPoint',
        'getProfile',
        'Select for a Profile',
        function (e) {SelectAction(e,"profile_list","hd_profile_id",1);
      }),
      createApplicationElement(
        '#selectSkillWrapper',
        "skill_list",
        "",
        'index.php?entryPoint=GetSkillApplicationEntryPoint',
        'getSkill',
        'Select for a Skill',
        function (e) {SelectAction(e,"skill_list","hd_skill_id",1);
      }),
      createApplicationElement(
        '#selectQualificationWrapper',
        "qualification_list",
        "",
        'index.php?entryPoint=GetQualificationApplicationEntryPoint',
        'getQualification',
        'Select for a Qualification',
        function (e) {SelectAction(e,"qualification_list","hd_qualification_id",1);
      }),
      createApplicationElement(
        '#selectAssignedWrapper',
        "list_assigned_to",
        "",
        'index.php?entryPoint=GetAssignedToRecruitmentEntryPoint',
        'getAssignedTo',
        'Select for a Assigned to',
        function (e) {SelectAction(e,"list_assigned_to","hd_assigned_to",0);
      })

]).then((values) => {


  let initial_data = {
    RecruitmentID : recruitmentID,
    action: "getRecruitmentRequest"
  }

  $.post(
    'index.php?entryPoint=GetRecruitmentRequestEntryPoint',
    initial_data,
    function (data) {
      let resp = data['results'][0]; 

      let newOption = new Option(resp.name_account, resp.id_account, true, true);
      $("#hd_account_create").val(resp.id_account);
      $("#account_list").append(newOption).trigger('change');

      let newOption2 = new Option(resp.name_project, resp.id_project, true, true);
      $("#hd_project_id").val(resp.id_project);
      $("#project_list").append(newOption2).trigger('change');

      let newOption3 = new Option(resp.name_job_decription, resp.id_job_decription, true, true);
      $("#hd_jod_description_id").val(resp.id_job_decription);
      $("#charge_list").append(newOption3).trigger('change');

      
      if(resp.id_assigned_to_id != "" && resp.id_assigned_to_id != null){ 
         $("#list_assigned_to").prop("disabled",true);
         $("#slc_priority").prop("disabled",true);
         $("#bnt_create_case").prop("disabled",true);
         $("#bnt_create_case").hide();
         let newOption4 = new Option(resp.assigned_to_name, resp.id_assigned_to_id, true, true);
         $("#hd_assigned_to").val(resp.id_assigned_to_id);
         $("#list_assigned_to").append(newOption4).trigger('change');
      }


      Object.entries(resp.data_profile).forEach(([key, value]) => {
         profiles_existing = `${profiles_existing},${value.name.trim()}`;
         obj_new_data[`profile_list_${value.id}`] = `${value.id}|${value.trim()}`;
      });

     
      $(`input[name="hd_profile_id"]`).val(profiles_existing);
      $(`input[name="hd_profile_id"]`).amsifySuggestags({
        afterRemove : function(value_input) {
          Object.fromEntries(Object.entries(obj_new_data).filter(([key,value]) => {
            if(value.replace(/[^a-zA-Z0-9]/g, '').search(value_input.replace(/[^a-zA-Z0-9]/g, '')) !== -1){
              delete obj_new_data[key];
            }
          }));
          getProfileSkills(obj_new_data);
          getProfileQualifications(obj_new_data);
          $(".amsify-suggestags-input").remove();
          
        },
     });
     
     
      getProfileQualifications(obj_new_data);
      getProfileSkills(obj_new_data);
      $(".amsify-suggestags-input").remove();
    
    },
    'json'
  )
 
});

});


setTimeout(function(){ 
  tinymce.init({
    selector: 'textarea',  
    plugins : 'advlist autolink link image lists charmap print preview table',
   });
}, 500);



////////////////// END DOCUMENT READY //////////////////////////////////////////////

function getProfileQualifications(obj_new_data){

  let data_send = {
    action : "GetProfileQualifications",
    obj_new_data
  }

  if(tableQualificationsProfile != ""){
    tableQualificationsProfile.destroy();
  }

  tableQualificationsProfile = $("#table_qualifications").DataTable({
      "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=GetQualificationsProfileEntryPoint',
        "type": "POST",
        'data' : data_send,
     },
     "paging" : false,
    // "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
     "order": [[ 0, "desc" ]],
     "filter" : true,
     "columnDefs": [
          {
            "targets": [0], 
            "data": "name_profile", 
            "className": "",
            "render": function(data, type, row){
              let url_profile        = `index.php?module=${row.object_name_profile}&offset=1&return_module=${row.object_name_profile}&action=DetailView&record=${row.id_profile}`;
              return `<a href="${url_profile}" >${row.name_profile}</a>`;            
            }
           },
           {
            "targets": [1], 
            "data": "name", 
            "className": "",
            "render": function(data, type, row){
              let url_qualifications = `index.php?module=${row.object_name_qualifications}&offset=1&return_module=${row.object_name_qualifications}&action=DetailView&record=${row.id_qualifications}`;
              return `<input type='hidden' name='data_qualification_id' value='${row.id_qualifications}' /><a href="${url_qualifications}" >${row.name}</a>`;            
            }
           },
           {
             "targets": [2], 
             "data": "mininum_requiered", 
             "sortable": false,
             "className": "dt-body-rating",
             "render": function(data, type, row){
                return `<p>${data}</p>`;
             }
           }
      ],
      "drawCallback": function(settings, json) {
        loadNewQualification();
      }
      
});

 /*suitecrm styles collide with this element I reset it*/
 $("#table_qualifications_length").find("select").css({"height": "30px","width": "5em"});

}


function loadNewQualification(){

  $("#table_qualifications").find("tbody tr.new_tr").remove();
  Object.fromEntries(Object.entries(obj_new_data).filter(([key,value]) => {
        if(key.includes('qualification')){
          let url_qualification  = `index.php?module=CC_Qualification&offset=1&return_module=CC_Qualification&action=DetailView&record=${value.split("|")[0]}`;      
          $("#table_qualifications").find("tbody").prepend(`<tr class="new_tr"><td style='vertical-align: inherit;'>&nbsp;</td><td class='data_qualification' style='vertical-align: inherit;'><input type='hidden' name='id_qualification' value='${value.split("|")[0]}' /> <a href='${url_qualification}'>${value.split("|")[1]}</a></td><td class='input_data' style='text-align: center;'></td></tr>`);  
        }
  }));
  
}


function getProfileSkills(obj_new_data){

  let data_send = {
    action : "GetProfileSkills",
   obj_new_data
  }

  if(tableSkill != ""){
     tableSkill.destroy();
  }

  tableSkill = $("#table_skills").DataTable({
      "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=GetSkillsProfileEntryPoint',
        "type": "POST",
        'data' : data_send,
     },
     "paging" : false,
     //"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
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
              return `<input type='hidden' name='data_profile_id' value='${data}' /><a href="${url_skill}" >${row.name}</a>`;            
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
        loadNewSkill();
        
      }
});

 /*suitecrm styles collide with this element I reset it*/
 $("#table_skills_length").find("select").css({"height": "30px","width": "5em"});


}


function loadNewSkill(){

  $("#table_skills").find("tbody tr.new_tr").remove();
  Object.fromEntries(Object.entries(obj_new_data).filter(([key,value]) => {
        if(key.includes('skill')){
          let url_skill  = `index.php?module=CC_Skill&offset=1&return_module=CC_Skill&action=DetailView&record=${value.split("|")[0]}`;      
          $("#table_skills").find("tbody").prepend(`<tr class="new_tr"><td style='vertical-align: inherit;' >&nbsp;</td><td class='data_skill' style='vertical-align: inherit;'><input type='hidden' name='id_skill' value='${value.split("|")[0]}'  /><a href='${url_skill}'>${value.split("|")[1]}</a></td><td class="dt-body-rating-new" style="text-align:center"></td></tr>`);  
        }
  }));
  drawRatingsNew();

}

function drawRatingsNew(){

  $('td.dt-body-rating-new').each(function(e) {
    let currentRow = $(this).closest("tr");
    let data = {
      rating: 0,
      years:0
    };
    if(currentRow){
      createRatingElementNew($( this ),data);
    }
  });
}


function createRatingElementNew(e,data){
  
  e.starRating({
    starSize: 18,
    totalStars: 5,
    readOnly: false,
    disableAfterRate: false,
    callback: function(currentRating, $el){
      $el.starRating('setRating', currentRating);
      $el.closest("td").find("input[name='value_rating']").val(currentRating);
    }
  });

  e.starRating('setRating', data.rating);
 
  if(!e.html().includes('Year')){
      e.append(`<input type='hidden' name='value_rating' value='${data.rating}' /><p><svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='currentColor' class='bi bi-alarm' viewBox='0 0 16 16'><path d='M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5z'/><path d='M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1h-3zm1.038 3.018a6.093 6.093 0 0 1 .924 0 6 6 0 1 1-.924 0zM0 3.5c0 .753.333 1.429.86 1.887A8.035 8.035 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5zM13.5 1c-.753 0-1.429.333-1.887.86a8.035 8.035 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1z'/></svg>&nbsp;<input type='number' name='year_skill' min='1' max='25' style='width: 50px;text-align: center;' value='${data.years}' /> Year(s)</p>`);
  }

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
    starSize: 18,
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


function edit_recruitment_request(elem){

  let id_skill     = new Array();
  let year_skill   = new Array();
  let rating_skill = new Array();
  let id_qualification = new Array();

  let fill_data = 0;
  $("#table_skills").find("tbody tr.new_tr").each(function(e){
     id_skill.push($(this).find("td.data_skill").find("input[name='id_skill']").val());
     year_skill.push($(this).find("td.dt-body-rating-new").find("input[name='year_skill']").val());
     rating_skill.push($(this).find("td.dt-body-rating-new").find("input[name='value_rating']").val());
     if($(this).find("td.dt-body-rating-new").find("input[name='year_skill']").val() == 0 || $(this).find("td.dt-body-rating-new").find("input[name='value_rating']").val() == 0){
        fill_data = fill_data +1;
     }
  })

  if(fill_data > 0){
    toastr.warning("Fill in the years of experience and rating, of the new skill you added", 'Oops!');
    return;
  }


  $("#table_qualifications").find("tbody tr.new_tr").each(function(e){
    id_qualification.push($(this).find("td.data_qualification").find("input[name='id_qualification']").val());
  })

  let sendData = {
    recruitment_id :  recruitmentID,
    position_name  : $("#txt_position_name_create").val(),
    account        : $("#hd_account_create").val(),
    project        : $("#hd_project_id").val(),
    charge         : $("#hd_jod_description_id").val(),
    open_positions : $("#txt_open_positions").val(),
    description    : tinyMCE.activeEditor.getContent(),
    options        : obj_new_data,
    id_skill,
    year_skill,
    rating_skill,
    id_qualification,
    action         : 'editRecruitmentRequest',
  }

  if(sendData.position_name == ""){
      toastr.warning("Select the position name", 'Oops!');
      return;
  }

  if(sendData.account == ""){
    toastr.warning("Select the account", 'Oops!');
    return;
  }

  if(sendData.project == ""){
    toastr.warning("Select the project", 'Oops!');
    return;
  }

  if(sendData.open_positions == ""){
    toastr.warning("Select the open positions", 'Oops!');
    return;
  }
  

  if(Object.keys(sendData.options).length === 0){
    toastr.warning("Select a Profile, Skill or Qualification", 'Oops!');
    return;
  }

  

  $.ajax({
      type: 'POST',
      url: 'index.php?entryPoint=EditRecruitmentRequestEntryPoint',
      data: sendData,
      beforeSend : function () {
        elem.html('<span class="glyphicon glyphicon-refresh spinning"></span> Sending ');
        elem.prop("disabled", true);
      },
      success: function (resp) {
        elem.html('SAVE');
        elem.prop("disabled", false);

        let data_resp = resp['results'];
        if(data_resp == 1){
          toastr.success("Registry modified successfully", 'Successful');
         
          setTimeout(function(){ 
            location.reload();
          }, 2000);
          
        }else{
          toastr.error('Error when created Rates', 'Oops!');
        }

      },
      dataType: 'json'
  });

}


function create_case(elem){

  let priority      = $("#slc_priority").val();
  let assigned_to   = $("#hd_assigned_to").val();
  let name          = $("#txt_position_name_create").val();
  let account       = $("#hd_account_create").val();
  let proyecto      = $("#hd_project_id").val();
  let description   = $("#description").val();

  let sendData = {
    recruitment_id :  recruitmentID,
    priority,  
    assigned_to,
    name,
    account,
    description,
    proyecto,
    action         : 'createCaseRecruitment',
  }

  let fill_data = 0;
  $("#table_skills").find("tbody tr").each(function(e){
    fill_data = fill_data +1;
  })

  if(fill_data == 0){
    toastr.warning("You cannot create a case without first adding the profiles", 'Oops!');
    return;
  }


  if(sendData.assigned_to == ""){
      toastr.warning("Select the assigned to", 'Oops!');
      return;
  }


  $.ajax({
      type: 'POST',
      url: 'index.php?entryPoint=CreateCaseRecruitmentRequestEntryPoint',
      data: sendData,
      beforeSend : function () {
        elem.html('<span class="glyphicon glyphicon-refresh spinning"></span> Sending ');
        elem.prop("disabled", true);
      },
      success: function (resp) {
        elem.html('SAVE');
        elem.prop("disabled", false);

        let data_resp = resp['results'];
        if(data_resp == 1){
          toastr.success("Case created successfully", 'Successful');
         
          setTimeout(function(){ 
            location.reload();
          }, 2000);
          
        }else{
          toastr.error('Error when created Case', 'Oops!');
        }

      },
      dataType: 'json'
  });

}



function createApplicationElement(parent, elementId, applicationId, url, moduleAction, placeholder, functionActionSelect){

  if(!$('#'+elementId).length) {
      $(parent).after("<div id='"+elementId+"'><select class='js-select-"+elementId+"'></select></div>");
       
      let element = $('#' + elementId).select2({
          width: '100%',
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
  
  
function SelectAction(data,element,hd_id_element,multi_option){
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
    $(`#${element}`).html(newOption).trigger('change');
  }

  if(data && multi_option == 1){

    let repeat = 0;
    $("#table_skills").find("tbody tr").each(function(e){
       if($(this).find("td").eq(1).find('input[name="data_profile_id"]').val() == data.id){
        repeat = repeat + 1;
       }
    })

    $("#table_qualifications").find("tbody tr").each(function(e){
      if($(this).find("td").eq(1).find('input[name="data_qualification_id"]').val() == data.id){
       repeat = repeat + 1;
      }
   })

    if(repeat != 0){
      toastr.warning("This skill or qualification is already added", 'Oops!');
      return;
    }
     

    // add obj dinamic of profile,skill,qualification   
     obj_new_data[`${element}_${data.id}`] = `${data.id}|${data.trim()}`;

     // fill in the multiple choice field
     let current_value =  $(`input[name="${hd_id_element}"]`).val();
     $(`input[name="${hd_id_element}"]`).val(`${current_value},${data.name.trim()}`);
     
     $(`input[name="${hd_id_element}"]`).amsifySuggestags({
          afterRemove : function(value_input) {
            Object.fromEntries(Object.entries(obj_new_data).filter(([key,value]) => {
              if(value.replace(/[^a-zA-Z0-9]/g, '').search(value_input.replace(/[^a-zA-Z0-9]/g, '')) !== -1){
                delete obj_new_data[key];
              }
            }));
            getProfileSkills(obj_new_data);
            getProfileQualifications(obj_new_data);
            
          },
     });

     getProfileSkills(obj_new_data);
     getProfileQualifications(obj_new_data);
     $(".amsify-suggestags-input").remove();

     
  }
  
}

function valideKey(evt){
        
  // code is the decimal ASCII representation of the pressed key.
  var code = (evt.which) ? evt.which : evt.keyCode;
  
  if(code==8) { // backspace.
    return true;
  } else if(code>=48 && code<=57) { // is a number.
    return true;
  } else{ // other keys.
    return false;
  }
}



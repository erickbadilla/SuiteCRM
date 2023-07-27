$(document).ready(function(){
    tinymce.init({
        selector: 'textarea',  
        plugins : 'advlist autolink link image lists charmap print preview table',
       });

    toastr.options = {
        "positionClass": "toast-bottom-right",
    }

    $("footer").hide();
    $("#groupTabs").hide();

    $('.js_select_account_list').select2({
        width: 'resolve',
        placeholder: 'Select one or more Accounts',
        selectionCssClass: 'interviewerSelectClass'
    });
    
    Calendar.setup ({
        inputField : "txt_expire_on_create",
        //form : "EditView",
        ifFormat : "%m/%d/%Y %H:%M",
        daFormat : "%m/%d/%Y %H:%M",
        button : "txt_expire_on_create_trigger",
        singleClick : true,
        dateStr : "",
        startWeekday: 0,
        step : 1,
        weekNumbers:false
        }
        );
        getListsJobOffer();
        /*createApplicationElement(
            '#selectAccountWrapper',
            "account_list",
            "",
            'index.php?entryPoint=AccountApplicationEntryPoint',
            'getAccount',
            'Select for a Account',
            function (e) {accountSelect(e);},
            "name='accountselect[]' multiple='multiple'"
            );*/
        createApplicationElement(
            '#selectRecruitmentWrapper',
            "recruitment_list",
            "",
            'index.php?entryPoint=AccountApplicationEntryPoint',
            'getRecruitment',
            'Select for a Recruitment Request',
            function (e) {recruitmentSelect(e);
        });


});


function create_job_offer(){

    let inputFile = document.getElementById('jobImage');
    let file = inputFile.files[0];
    let data_send = new FormData(); 
  
    let position_name     = $("#txt_position_name_create").val();
    let expire_on         = $("#txt_expire_on_create").val();
    let assigned_location = $("#txt_assigned_location_create").val();
    let contact_type      = $("#txt_contact_type_edit").val();
    //let account           = $("#txt_account_create").val();
    let description       = tinyMCE.activeEditor.getContent();
    let recruitment       = $("#txt_recruitment").val();
    let action            = "CreateJobOffer";

    let AccountsName = new Array();
    $(".js_select_account_list").find("option:selected").each(function(i){
        AccountsName.push($(this).text());
    });

    let account_id   = $(".js_select_account_list").val()?.toString();
    let account_name = AccountsName?.toString();


    if(position_name == ""){
        toastr.warning('Please enter the name of the offer', 'Oops!');
        $("#txt_position_name_create").focus();
        return;
    }

    data_send.append('file',file);
    data_send.append('position_name',position_name);
    data_send.append('expire_on',expire_on);
    data_send.append('assigned_location',assigned_location);
    data_send.append('contact_type',contact_type);
    data_send.append('account',account_id);
    data_send.append('description',description);
    data_send.append('recruitment',recruitment);
    data_send.append('action',action);  
    
    $.ajax({
      type: "POST",
      url: 'index.php?entryPoint=CreateJobOfferEntryPoint',
      dataType: 'json',
      data: data_send,
      processData:false,
      contentType:false,
      cache:false,
      success: function(resp) {
        let data = resp['results'];
        if(data){
           toastr.success("Offer created successfully", 'Successful');
           setTimeout(function(){ 
               window.location.assign(`index.php?module=${data.module}&offset=1&return_module=${data.module}&action=DetailView&record=${data.id}`);
            }, 2000);
        }else{
          toastr.error('Error when created Offer', 'Oops!');
        }
      }
    }); 
  
}


function createApplicationElement(parent, elementId, applicationId, url, moduleAction, placeholder, functionActionSelect){
  
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


/*function accountSelect(data){
    if(data){
      accountData = data;
      //let newOption = new Option(accountData.name, accountData.id, true, true);
      let AccountsName = new Array();
      $(".js-select-account_list").find("option:selected").each(function(i){
          AccountsName.push($(this).text());
      });
      //$("#txt_account_create").val(accountData.id);
      //$('#account_list').html(newOption).trigger('change');
    }
}*/

function recruitmentSelect(data){
    if(data){
      recruitmentData = data;
      let account = recruitmentData.account == null ? "" : recruitmentData.account.split(',');
      let newOption = new Option(recruitmentData.name, recruitmentData.id, true, true);
      $("#txt_recruitment").val(recruitmentData.id);
      $("#txt_position_name_create").val(recruitmentData.name);
      $("#txt_account_create").val(recruitmentData.account_id);
      tinyMCE.activeEditor.setContent(recruitmentData.description);
      $('.js_select_account_list').val(account);
      $('.js_select_account_list').trigger('change');
      $('#recruitment_list').html(newOption).trigger('change');
    }
  }


function getListsJobOffer(){
    
    let data_send = {
      action : "getListsJobOffer",
    }
  
    $.ajax({
      type: "POST",
      url: 'index.php?entryPoint=GetListsJobOfferEntryPoint',
      dataType: 'json',
      data: data_send,
      success: function(resp) {
       
  
         // fill select assingned location
         $("#txt_assigned_location_create").html("");
         Object.entries(resp['assigned_location_list']).forEach(([key, value]) => {
            $("#txt_assigned_location_create").append(`<option value="${key}" >${value}</option>`);
         });
   
         // fill select contract type
         $("#txt_contact_type_edit").html("");
         Object.entries(resp['contract_type_list']).forEach(([key, value]) => {
            $("#txt_contact_type_edit").append(`<option value="${key}">${value}</option>`);
         });
         
      
      }
    });
  }

var action_template = $('script[data-template="participant_action_template"]').text()
  .replace('/*', '').replace('*/', '').split(/\$\{(.+?)\}/g);

var first_action_template = $('script[data-template="first_participant_action_template"]').text()
  .replace('/*', '').replace('*/', '').split(/\$\{(.+?)\}/g);

var table_action_template = $('script[data-template="table_action_template"]').text()
  .replace('/*', '').replace('*/', '').split(/\$\{(.+?)\}/g);

var duty_action_template = $('script[data-template="duty_participant_action_template"]').text()
  .replace('/*', '').replace('*/', '').split(/\$\{(.+?)\}/g);

var duty_added_participant_action_template = $('script[data-template="duty_added_participant_action_template"]').text()
  .replace('/*', '').replace('*/', '').split(/\$\{(.+?)\}/g);

var participants= 1;
$(document).ready(function(){ 
    Calendar.setup ({
      inputField : "txt_start_date",
      //form : "EditView",
      ifFormat : "%m/%d/%Y %H:%M",
      daFormat : "%m/%d/%Y %H:%M",
      button : "txt_start_date_trigger",
      singleClick : true,
      dateStr : "",
      startWeekday: 0,
      step : 1,
      weekNumbers:false
      }
      );

  $(".activityR").click(function(){
    let item = {
      participants:participants,
    }
    let children = action_template.map(createParticipant(item)).join('');
    $("#participantList").append(children);
      createApplicationSearchElement(
        '#selectEmployeeWrapper_'+participants,
        "employees_list_"+participants,
        window.applicationId,
        'index.php?entryPoint=EmployeeInformationEntryPoint',
        'search',
        'Select for an Employee',
        function (e) { }
    );
    participants=participants+1;

    });

    getActivityReport();

    });

    function createParticipant(props) {
      return function (tok, i) {
          return (i % 2) ? props[tok] : tok;
      };
  }

    function getOwner() {
      let item = {
        participants:participants,
      }
      let children = first_action_template.map(createParticipant(item)).join('');
      $("#participantList").append(children);
      
      participants=participants+1;
    }

    function updateList() {
      var input = document.getElementById('file-upload');
      var output = document.getElementById('fileList');
      var children = "";
      for (var i = 0; i < input.files.length; ++i) {
          children += '<li><span class="attachedFiles">' + input.files.item(i).name + '</span>';
          children += `<textarea id='${input.files.item(i).name.replace(/[^\w]/gi, '')}' row=2></textarea> &nbsp;&nbsp;&nbsp;<span onclick="delete_file('${input.files.item(i).name.replace(/[^\w]/gi, '')}')" style='font-size: 16px; color: firebrick;' class='suitepicon suitepicon-action-clear' ></span></li><br>`;

      }      
      output.innerHTML = '<strong><span class="attachedFiles">File Selected</span>  Description</strong><ul>'+children+'</ul>';
      if(input.files.length == 0){
        $("#fileList").find('strong').remove();
        $("#file-upload").remove();
        $("#file_upload").append('<input type="file" multiple="" id="file-upload" onchange="updateList()"></input>');
      } 
    }

    function addDuty(participant){
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();

      today = mm + '/' + dd + '/' + yyyy;
      var duties = $('#duties_' + participant).children();
      var numDuty =0;
       if(duties.length > 0){
         numDuty = parseInt($('#duty_table_' + participant).find("tr").last().attr("data-num")) + 1;
       }else{
         numDuty =1;
         let item = {
          participant:participant,
        }
        let children = table_action_template.map(createParticipant(item)).join('');
           $('#duties_' + participant).append(children);
       }
       addI='';
       let items = {
        participant:participant,
        numDuty:numDuty,
        today:today,
        addI:addI,
      }
      let childrentr = duty_action_template.map(createParticipant(items)).join('');

      $('#duty_table_' + participant).append(childrentr);

      Calendar.setup ({
        inputField : "txt_due_date_"+participant+"_"+numDuty,
        //form : "EditView",
        ifFormat : "%m/%d/%Y %H:%M",
        daFormat : "%m/%d/%Y %H:%M",
        button : "txt_due_date_trigger_"+participant+"_"+numDuty,
        singleClick : true,
        dateStr : "",
        startWeekday: 0,
        step : 1,
        weekNumbers:false
        }
        );

    }

    function addDutyRel(participant){
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();

      today = mm + '/' + dd + '/' + yyyy;
      var duties = $('#duties_' + participant).children();
      var numDuty =0;
       if(duties.length > 0){
         numDuty = parseInt($('#duty_table_' + participant).find("tr").last().attr("data-num")) + 1;
       }else{
         numDuty =1;
         let item = {
          participant:participant,
        }
        let children = table_action_template.map(createParticipant(item)).join('');
           $('#duties_' + participant).append(children);
       }
       const addI=`<span id="add_duty_${participant}" onclick="addRela_duty('${participant}', '${numDuty}')" style="font-size: 16px; color: kiwi;" class="suitepicon suitepicon-action-confirm" ></span>`
       let items = {
        participant:participant,
        numDuty:numDuty,
        today:today,
        addI:addI,
      }
      let childrentr = duty_action_template.map(createParticipant(items)).join('');

      $('#duty_table_' + participant).append(childrentr);

      Calendar.setup ({
        inputField : "txt_due_date_"+participant+"_"+numDuty,
        //form : "EditView",
        ifFormat : "%m/%d/%Y %H:%M",
        daFormat : "%m/%d/%Y %H:%M",
        button : "txt_due_date_trigger_"+participant+"_"+numDuty,
        singleClick : true,
        dateStr : "",
        startWeekday: 0,
        step : 1,
        weekNumbers:false
        }
        );

    }



    function createApplicationSearchElement(parent, elementId, applicationId, url, moduleAction, placeholder, functionActionSelect){
      if(!$('#'+elementId).length) {
          $(parent).after("<div id='"+elementId+"'><select class='js-select-"+elementId+"'></select></div>");
          let element = $('#' + elementId).select2({
              width: '50%',
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
              participantSelect(data, elementId);
          });
      }
  }

  function participantSelect(data, element){
    if(data){
      participantData = data;
      let newOption = new Option(participantData.name, participantData.id, true, true);
      $("#txt_id_"+element).val(participantData.id);
      $('#'+element).html(newOption).trigger('change');
    }
  }

  $('#relatedTo').on('change', function() {
    const data_send = {
      "action":"bringRelatedTo",
      "relation": $('#relatedTo').find('option:selected').text()
    };

    var options = '<option value="">Select</option>'
  
    $.ajax({
      type: "POST",
      url: 'index.php?entryPoint=ActivityReportEntryPoint',
      data: data_send,
      success: function(resp) {
        let data_obj = JSON.parse(resp); 
        data_obj['results'].forEach(element => {
          options += '<option value="'+element.id+'">'+element.name+'</option>';
        });

        $('#relatedTo2').html(options);
      }
    });

    
  });


  function create_activity_report(){
    $("#save_ar").hide();

      let inputFile = document.getElementById('file-upload');
      let start_hour = $("#hour_start").find('option:selected').text();

      let data_send = new FormData(); 
      let id   = $("#id").val();
      let idMain   = $("#idMeeting").val();
      let subject   = $("#subject").val();
      let agenda   = $("#agenda").val();
      let txt_start_date    = $("#txt_start_date").val();
      let duration    = $("#duration").val();
      let horas = duration >= 60 ?  Math.floor(duration/60) : 0;
      let minutos = duration >= 60 ? Math.abs(((60*horas)- duration)) : duration;
      let new_hours = parseInt($("#minute_start").val()) + parseInt(duration);
      let new_end = (new_hours/60 >= 1) ? (parseInt(start_hour) + Math.floor(new_hours/60)) : parseInt(start_hour);
      let minute_end = (new_hours/60 >= 1) ? ((60*(Math.floor(new_hours/60)))- parseInt(new_hours)) : parseInt(new_hours);
      let hour_end =  new_end +":"+ Math.abs(minute_end);
      let relatedTo    = $("#relatedTo").find('option:selected').text();
      let relatedTo2    = $("#relatedTo2").val();
      let hour_start    = start_hour + ":" + $("#minute_start").val();
      let numberFiles = inputFile.files.length;
      //var numParticipant = document.getElementsByClassName("participants").length;
      numParticipant = parseInt(participants) -1;
      let action    = "CreateActivityReport";

      if(subject == "" || agenda == "" || txt_start_date == "" || duration == "" || relatedTo == "Select" || relatedTo2 == "" || start_hour == "" || $("#minute_start").val() == "" ){
        toastr.error('Please fill all the mandatory fields', 'Oops!');
        $("#save_ar").show();
        return;
      }

      if(numParticipant > 0){
        for (var i = 1; i <= numParticipant; ++i) {
          var getParticipant = ($('#employees_list_'+i+'').text() !='') ? $('#employees_list_'+i+'').val(): $('#employees_list_val_'+i+'').val();
          var getParticipantOwner = $('#owner_'+i+'').val();
          var getParticipantName = ($('#employees_list_'+i+'').text() !='') ? $('#employees_list_'+i+'').text(): $('#employees_list_'+i+'').val();
          var getParticipantName = ($('#employees_list_'+i+'').text() !='') ? $('#employees_list_'+i+'').text(): $('#employees_list_'+i+'').val();
          
          if(getParticipant == "") {
            toastr.error('Please Select ALL the participants', 'Oops!');
            $("#save_ar").show();
            return;
          }
          data_send.append('participant_'+i,getParticipant);
          data_send.append('participantName_'+i,getParticipantName);
          data_send.append('participantOwner_'+i,getParticipantOwner);

          var numDuties = ($('#duties_' + i).children().length >0)? $('#duty_table_' + i).find("tr").last().attr("data-num") : 0;
          const dutyArray = [];
          var dutyInfo ="";
          if(numDuties > 0){
            for (var j = 1; j <= numDuties; ++j) {
              var getDescription = $('#duty_text_'+i+'_'+j).val();
              var getDueDate = $('#txt_due_date_'+i+'_'+j).val();
              var getEstimatedTime = $('#estimed_time_'+i+'_'+j).val();
              
              if(getDescription == "" || getDueDate == "" || getEstimatedTime == "") {
                toastr.error('Please Give All the information about Duties!', 'Oops!');
                $("#save_ar").show();
                return;
              }
              dutyInfo= {Description:getDescription, dueDate:getDueDate, time:getEstimatedTime};
              dutyArray.push(dutyInfo);
            }
          }
          data_send.append('participant_duties_'+i,JSON.stringify(dutyArray));

        }
      }
    
      //data_send.append('file',file);
      data_send.append('id',id);
      data_send.append('idMain',idMain);
      data_send.append('subject',subject);
      data_send.append('agenda',agenda);
      data_send.append('start_date',txt_start_date + ' '+ hour_start);
      data_send.append('end_date',txt_start_date + ' '+ hour_end);
      data_send.append('hours',horas);
      data_send.append('minutos',minutos);
      data_send.append('parent_type',relatedTo);
      data_send.append('parent_id',relatedTo2);
      data_send.append('numberFiles',numberFiles);
      data_send.append('numParticipant',numParticipant);
      data_send.append('status','Planned');
      data_send.append('typeAr','Meetings');
      data_send.append('action',action);

      for (var i = 0; i < numberFiles; ++i) {
        var naming = inputFile.files.item(i).name.replace(/[^\w]/gi, '');
        var description = $('#'+naming+'').val();
        var descriptions = description == '' ? 'Created since AR' : description
        var file = inputFile.files[i];
        data_send.append('file'+i,file);
        data_send.append('description'+i,descriptions);
    }

    
      $.ajax({
        url: 'index.php?entryPoint=ActivityReportEntryPoint',
        type:'POST',
        data:data_send,
        processData:false,
        contentType:false,
        cache:false,
        success: function(resp) {
          let data_obj = JSON.parse(resp); 
          if(resp){
            let Msg = (idMain == '') ? "Activity Report Created Successfully" : "Activity Report Edited Successfully"
              $("#save_ar").show();
                toastr.success(Msg, 'Successful');
                setTimeout(function(){ 
                  window.location.assign(`index.php?module=${data_obj['results'].module}&action=index&return_module=${data_obj['results'].module}&return_action=DetailView`);
               }, 2000);
          }else{
                toastr.error('Error when creating a new Activity Report', 'Oops!');
          }
        }
      }); 
      
  }


  function addRela_duty(participant, num){
    $("#add_duty_"+participant).hide();


      let data_send = new FormData(); 
      let id   = $("#id").val();
      var getDescription = $('#duty_text_'+participant+'_'+num).val();
      var getDueDate = $('#txt_due_date_'+participant+'_'+num).val();
      var getEstimatedTime = $('#estimed_time_'+participant+'_'+num).val();
      let action    = "AddNewDuty";

      if(getDescription == "" || getDueDate == "" || getEstimatedTime == ""  ){
        toastr.error('Please fill all the duty fields', 'Oops!');
        $("#add_duty_"+participant).show();
        return;
      }

      data_send.append('description',getDescription);
      data_send.append('due_date',getDueDate);
      data_send.append('time',getEstimatedTime);
      data_send.append('id',id);
      data_send.append('participant',participant);
      data_send.append('subject',$("#subject").val());
      data_send.append('action',action);

  
      $.ajax({
        url: 'index.php?entryPoint=ActivityReportEntryPoint',
        type:'POST',
        data:data_send,
        processData:false,
        contentType:false,
        cache:false,
        success: function(resp) {
          let data_obj = JSON.parse(resp); 
          if(resp){
              $("#save_ar").show();
                toastr.success("New Duty Added successfully", 'Successful');
          }else{
                toastr.error('Error when creating a new Duty', 'Oops!');
          }
        }
      }); 
      
  }

  function delete_participant(part){
    $('#selectEmployeeLabel_'+part).parent().remove();
  }

  function delete_file(part){
    $('#'+part).parent().remove();
    if($("#fileList").find('ul').find('li').size() ==0){
        $("#fileList").find('strong').remove();
        $("#file-upload").remove();
        $("#file_upload").append('<input type="file" multiple="" id="file-upload" onchange="updateList()"></input>');
    }
  }

  function delete_duty(part){
    $('#duty_'+part).remove();
  }

  function getActivityReport(){

    let dataUrl    = window.location.toString().split("=");
    let id_activity_report = dataUrl[dataUrl.length - 1].trim();
    let action     = "GetActivityReport";

    if(id_activity_report.length == 36){
    $("#title_ar").text('EDIT ACTIVITY REPORT ');

    const data_send = {
        id_activity_report,
        action
    };
    
    $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=ActivityReportEntryPoint',
        dataType: 'json',
        data: data_send,
        success: function(resp) {
          let data = resp['results'][0];
          let related_2 = data.related2;
          let participants = data.participants;
          let notes = data.notes;

          const $selectHour = document.querySelector('#hour_start');
          const $options = Array.from($selectHour.options);
          const optionToSelect = $options.find(item => item.text ===data.hourStr);
          optionToSelect.selected = true;

          const $selectRelated = document.querySelector('#relatedTo');
          const $optionsR = Array.from($selectRelated.options);
          const optionToSelectR = $optionsR.find(item => item.text ===data.parent_type);
          optionToSelectR.selected = true;

          $("#subject").val(data.name);
          $("#agenda").val(data.description);
          $("#id").val(data.id);
          $("#idMeeting").val(data.idMeet);
          $("#txt_start_date").val(data.dateStr);
          $("#minute_start").val(data.minuteStr);
          $("#duration").val(data.duration);
          

          $("#relatedTo2").html("");
          Object.entries(related_2).forEach(([key, value]) => {
             let selected = data.parent_id == value.id ? "selected" : "";
             $("#relatedTo2").append(`<option value="${value.id}" ${selected}>${value.name}</option>`);
          });

          children ="";
          Object.entries(notes).forEach(([key, value]) => {
            children += '<li><span class="attachedFiles">' + value.name + '</span>';
            children += `<textarea id='${value.id}' row=2 disabled>${value.description}</textarea> &nbsp;&nbsp;&nbsp;<span id="${value.id}"  onclick="delete_fileAdded('${value.id}')" style='font-size: 16px; color: firebrick;' class='suitepicon suitepicon-action-clear' ></span></li><br>`;
         });
         $("#filesAdded").append(children);

         children ="";
         var owner =`<div class="tooltip" style="opacity : 1; display: contents"><span class="tooltip-content2">Owner</span> <span class="ui-button-icon-primary ui-icon ui-icon-info"></span></div>`
         Object.entries(participants).forEach(([key, value]) => {
          children += `<div style="margin-left:15%" ><span style="width: 500px; display: inline-block; text-decoration: underline; font-size: 20px;">${value.name} </span>`;
          children += `<button  style='margin-left:15%' type='button' class='button primary'  onclick="addDutyRel('${value.participant}')">Add duty</button>`;
          children += `&nbsp;&nbsp;&nbsp;<span id="${value.idParRel}" onclick="delete_participantAdded('${value.idParRel}')" data-part="${value.id}" style="font-size: 16px; color: firebrick;" class="suitepicon suitepicon-action-clear" ></span>`;
          if(value.is_owner == 1){
            children +=owner;
          }
          children += `<div class="col-xs-12 col-lg-12 edit-view-row-item" style="margin-top: 5px;" id="duties_${value.participant}">`;
          if(value.duties!=null){
            children += `<table style="width:85%" class="duties_participant" id='duty_table_${value.participant}'>
            <tr><th style="width:60%">Add Duty:</th><th style="width:35%">Due Date:</th><th style="width:7%">Estimed Time:</th><th style="width:3%">&nbsp;</th></tr>`;
            Object.entries(value.duties).forEach(([key, duty]) => {
              let items = {
                id_duty:duty.id_duty,
                description:duty.description,
                due_date:duty.due_date,
                original_estimate:duty.original_estimate,
              }
              children += duty_added_participant_action_template.map(createParticipant(items)).join('');

            });
          }
          children += '</table></div></div>';


        });
        $("#participants").append(children);
         

        }
    });
  } else {
    $("#title_ar").text('CREATE ACTIVITY REPORT ');
    getOwner();
    let action     = "GetOwner";

    const data_send = {
      action
    };
  
  $.ajax({
      type: "POST",
      url: 'index.php?entryPoint=ActivityReportEntryPoint',
      dataType: 'json',
      data: data_send,
      success: function(resp) {
        let data = resp['results'][0];
        if(data.id == 0){
          toastr.error('There are not an Employee for the current user, check the Email!', 'Oops!');
        }else{
          $('#employees_list_val_1').val(data.id);
          $('#employees_list_1').val(data.name);
          $('#employees_list_1').attr('disabled', true)
        }
          $('#owner_1').val(1);
      }
    });
  }

}


function delete_participantAdded(id){
  let idParRel= $('#'+id).data('part');
  let action     = "deleteParticipant";

  const data_send = {
    id,
    idParRel,
    action
};

$.ajax({
    type: "POST",
    url: 'index.php?entryPoint=ActivityReportEntryPoint',
    dataType: 'json',
    data: data_send,
    success: function(resp) {
          $('#'+id).parent().remove();
    }
  });

}

function delete_fileAdded(id){
  let action     = "deleteFile";

  const data_send = {
    id,
    action
};

$.ajax({
    type: "POST",
    url: 'index.php?entryPoint=ActivityReportEntryPoint',
    dataType: 'json',
    data: data_send,
    success: function(resp) {
          $('#'+id).parent().remove();
    }
  });
}

function deleteDutyAdded(id){
  let action     = "deleteDuty";

  const data_send = {
    id,
    action
};

$.ajax({
    type: "POST",
    url: 'index.php?entryPoint=ActivityReportEntryPoint',
    dataType: 'json',
    data: data_send,
    success: function(resp) {
        $('#duty_'+id).remove();
    }
  });
}

function updateDutyAdded(id){
  let action     = "updateDuty";
  var description = $('#duty_text_'+id).val();
  var due_date = $('#txt_due_date_'+id).val();
  var time = $('#estimed_time_'+id).val();


    if(description == "" || due_date == "" || time == "") {
      toastr.error('Please Give All the information about the edited dutie!', 'Oops!');
      $("#save_ar").show();
      return;
    }

    const data_send = {
      id,
      action,
      description,
      due_date,
      time
  };

$.ajax({
    type: "POST",
    url: 'index.php?entryPoint=ActivityReportEntryPoint',
    dataType: 'json',
    data: data_send,
    success: function(resp) {
          $('#'+id).parent().remove();
    }
  });
}

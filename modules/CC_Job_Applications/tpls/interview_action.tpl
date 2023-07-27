<div class="actionCard">
    <div class="actionCardHeader" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <h2>STEP {$smarty.foreach.actionStage.iteration}: {$actionStage->name}</h2>
    </div>
    <div class="container padding_container">
      <div class="row">
        <div class="col-md-3 container_div_section_text" >
            <div class="form-group">  
               <label for="interview_action_{$actionStage->id}">Interview Date</label><br />
               <span class="dateTime">
               <div class="row">
                  <div class="col-md-9 col-xs-9">
                    <input class="date_input" autocomplete="off" type="text" name="interview_action_{$actionStage->id}" id="interview_action_{$actionStage->id}" style="width: 100%; height: 34px" maxlength="10"/>
                  </div>
                  <div class="col-md-3 col-xs-3">
                      <button type="button" id="interview_action_trigger_{$actionStage->id}" class="btn btn-danger" style="float: right" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                  </div>
                </div>
             </div>
                <div class="row">
                  <div class="col-md-12 col-sm-3 scheduleActionSlot">
                  <div class="scheduleActionSlotTitle">Participants</div>
                    <select class="interviewer-interviewerparticipants_{$actionStage->id}" name="interviewerparticipants_{$actionStage->id}[]" multiple="multiple" style="width:85%" >
                        {foreach from=$INTERVIEWERS item=interviewer}
                            <option value="{$interviewer->interviewer_id}">{$interviewer->employee_information_name}</option>
                        {/foreach}
                    </select>
                    <script type="text/javascript">
                        {literal}
                        $(document).ready(function() {
                            $('.interviewer-interviewerparticipants_'+{/literal}"{$stageId}"{literal}).select2({
                                width: 'resolve',
                                placeholder: 'Select one or more interviewers',
                                selectionCssClass: 'interviewerSelectClass'
                            });
                        });
                        {/literal}
                    </script>
                </div>
            </div>
          
        </div>

        <div class="col-md-6 container_div_section_text">
            <div class="row">
              <div class="col-md-4">
                  <div class="form-group ">
                    <label for="english_level_{$stageId}" class="col-form-label">English Level</label>
                    <select class="form-control" id="english_level_{$stageId}" style="height:34px">
                        <option value="">Select an option</option>
                        <option value="A1">A1</option>
                        <option value="A2">A2</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="C1">C1</option>
                        <option value="C2">C2</option>
                    </select>
                   </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group ">
                        <label class="col-form-label" for="approved_interview_{$stageId}">Approved</label>
                        <select class="form-control" id="approved_interview_{$stageId}" style="height:34px">
                            <option value="">Select an option</option>
                            <option value="Approved">Approved</option>
                            <option value="Not Approved">Not Approved</option>
                        </select>
                    </div>
               </div>

                <div class="col-md-4">
                    <div class="form-group ">
                        <label class="col-form-label" for="recomended_position_{$stageId}">Recomended Position</label>
                        <select class="form-control" id="recomended_position_{$stageId}" style="height:34px">
                            <option value="">Select an option</option>
                            {foreach from=$JOBDESCRIPTION item=description}
                                <option value="{$description.id_job_description}">{$description.name_job_description}</option>
                            {/foreach}
                            
                        </select>
                    </div>
               </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="col-form-label" for="positove_aspects_{$stageId}">Positive Aspects</label>
                        <input type="text" class="form-control" id="positove_aspects_{$stageId}" placeholder="Positive Aspects">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="col-form-label" for="what_to_improve_{$stageId}">What to improve</label>
                        <input type="text" class="form-control" id="what_to_improve_{$stageId}" placeholder="What to improve">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 container_div_section_text">
           <div class="row">
              <div class="col-md-12 scheduleActionSlot">
                General Comments:
                <textarea id="general_comments_{$stageId}" name="general_comments_{$stageId}" cols="80" rows="3"></textarea>
                <strong>Attach File:</strong>
                <input type="file" id="interviewfile_{$stageId}" name="interviewfile_{$stageId}">
              </div>
            </div>
        </div>

        <div class="col-md-1 ">
            <input id="{$actionType}-{$stageId}" type="button" style="margin-top:17px" value="save">  
        </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    {literal}

     var id_application_stage = 0;
     var edit  = 0;

     function load_data_{/literal}{$stageId|replace:'-':'_'}{literal}(){

        let stageId   = {/literal}"{$stageId}"{literal};

        let initial_data = {
            stageId : {/literal}"{$stageId}"{literal},
            applicationId : {/literal}"{$BEANID}"{literal},     
        }

        $.post(
            'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getStepStatus',
            initial_data,
            function (data) {
                let resp = data['results'];
                edit = 0;
                id_application_stage = 0;
                
                if(resp.id){
                    edit = 1;
                    id_application_stage = resp.id;
                    let data_obj = JSON.parse(resp.data); 
                    $("#interview_action_"+stageId).val(data_obj.interview_date);
                    $("#english_level_"+stageId).val(data_obj.english_level);
                    $("#approved_interview_"+stageId).val(data_obj.approved_interview);
                    $("#recomended_position_"+stageId).val(data_obj.recomended_position);
                    $("#positove_aspects_"+stageId).val(data_obj.positove_aspects);
                    $("#what_to_improve_"+stageId).val(data_obj.what_to_improve);
                    $("#general_comments_"+stageId).val(data_obj.general_comments);
                    // $("[id*='"+stageId+"']").prop("disabled",true);
                    $("#interviewfile_"+stageId).prop("visibility","hidden");
                    
                    let dataPaticipants = data_obj.participants_data;
                    $(".interviewer-interviewerparticipants_"+stageId).prop("disabled", true);
                    $("#approved_interview_"+stageId).prop("disabled", true);
                    $(".interviewer-interviewerparticipants_"+stageId).next().find("textarea").hide();
                    
                    $(".interviewer-interviewerparticipants_"+stageId).next().find("ul").html("");
                    Object.entries(dataPaticipants).forEach(([key, value]) => {
                    let data_key   = Object.keys(value)[0];
                    let data_value = Object.values(value)[0];
                    console.log(data_key +"value "+data_value);
                    $(".interviewer-interviewerparticipants_"+stageId).next().find("ul").append(`<li class="select2-selection__choice" title="${data_value}" data-id="${data_key}">${data_value}</li>`); 
                    });

                    

                    
                }
            },
            'json'
        )
       
    }


    $(document).ready(function (){
        toastr.options = {
            "positionClass": "toast-bottom-right",
        }

        Calendar.setup ({
            inputField : "interview_action_{/literal}{$actionStage->id}{literal}",
            ifFormat : "%m/%d/%Y %H:%M",
            daFormat : "%m/%d/%Y %H:%M",
            button : "interview_action_trigger_{/literal}{$actionStage->id}{literal}",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
        });

         $({/literal}"#{$actionType}-{$stageId}"{literal}).click(function (event) {
            //stop submit the form, we will post it manually.
            event.preventDefault();

            let stageId   = {/literal}"{$stageId}"{literal};
            let participants_data = "[";
            let countDataPaticipants = 0;
            
            $(".interviewer-interviewerparticipants_"+stageId).find("option:selected").each(function(i){
                let variableKey   = $(this).val();
                let variableValue = $(this).text();
                participants_data+=`{"${variableKey}":"${variableValue}"},`;
                countDataPaticipants = 1;
            });

            //I use the same function to edit that to create to obtain the interviewers in the edit
            if(edit == 1 && participants_data == "["){                 
               $(".interviewer-interviewerparticipants_"+stageId).next().find("ul").find("li").each(function(i){
                let variableValue = $(this).html();
                let variableKey   = $(this).data('id');
                participants_data+=`{"${variableKey}":"${variableValue}"},`;
                countDataPaticipants = 1;
              });
            }

            // remove last comma
             if(countDataPaticipants == 1){
               participants_data = participants_data.substring(0, participants_data.length - 1);
             }
             participants_data+="]";

            let inputFile = document.getElementById('interviewfile_'+stageId);
            let file = inputFile.files[0];
            let data_send = new FormData(); 
          
                let applicationId  = {/literal}"{$BEANID}"{literal};
                let targetStageId = {/literal}"{$targetStageId}"{literal};
                let interview_date = $("#interview_action_"+stageId).val();
                let english_level  = $("#english_level_"+stageId).val();
                let approved_interview = $("#approved_interview_"+stageId).val();
                let recomended_position = $("#recomended_position_"+stageId).val();
                let positove_aspects  = $("#positove_aspects_"+stageId).val();
                let what_to_improve   = $("#what_to_improve_"+stageId).val();
                let general_comments  = $("#general_comments_"+stageId).val();


            data_send.append('file',file);
            data_send.append('edit',edit);
            data_send.append('id_application_stage',id_application_stage);
            data_send.append('stageId',stageId);
            data_send.append('applicationId',applicationId);
            data_send.append('targetStageId',targetStageId);
            data_send.append('interview_date',interview_date);
            data_send.append('english_level',english_level);
            data_send.append('approved_interview',approved_interview);
            data_send.append('recomended_position',recomended_position);
            data_send.append('positove_aspects',positove_aspects);
            data_send.append('what_to_improve',what_to_improve);
            data_send.append('participants_data',participants_data);
            data_send.append('general_comments',general_comments);
            //data_send.append('action',action);

            if(interview_date == ""){
                 toastr.warning("Enter the date of the interview", 'Oops!');
                 return;
            }

            if(english_level == ""){
                 toastr.warning("Enter the level of english", 'Oops!');
                 return;
            }

            if(approved_interview == ""){
                 toastr.warning("Enter the type of approved", 'Oops!');
                 return;
            }


            if(participants_data == "[]"){
                 toastr.warning("Enter at least one participant", 'Oops!');
                 return;
            }

            /*$.post(
                'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=updateStage',
                stageData,
                function (data) {
                    if(typeof actionComplete === "function"){
                        actionComplete(data);
                    }
                    $("header").find("div#steps").find("div.active").next().click();
                },
                'json'
            )*/

            $.ajax({
                    url: 'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=updateStage',
                    type:'POST',
                    data:data_send,
                    processData:false,
                    contentType:false,
                    cache:false,
                    dataType: 'json',
                    statusCode:{
                        200 : function(data){
                            if(typeof actionComplete === "function"){
                                actionComplete(data);
                            }
                            if(approved_interview == "Not Approved"){ 
                                $("header").find("div#steps").find("div").last().click();
                            }else{
                                $("header").find("div#steps").find("div.active").next().click();
                            }
                        }
                    }
                }); 
        });


    });
    {/literal}
</script>
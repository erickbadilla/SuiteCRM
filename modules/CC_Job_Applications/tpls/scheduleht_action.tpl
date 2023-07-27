<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Job_Applications/js/addInterviewerToSchedule.js'}"></script>
<div class="actionCard">
    <div class="actionCardHeader" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <h2>STEP {$smarty.foreach.actionStage.iteration}: {$actionStage->name}</h2>
    </div>
    <div class="collapse container padding_container">
        <div class="row">
            <div class="col-sm-2 scheduleActionSlot">
                <div class="scheduleActionSlotTitle">Candidate Availability</div>
                <div class="tableFixHead">
                    <table class="availabilityInfo" style="width: 100%;">
                        <thead>
                        <tr style="display: flex;justify-content: space-between;">
                            <th class="availabilityInfo">Day</th>
                            <th class="availabilityInfo">Start</th>
                            <th class="availabilityInfo">End</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$CANDIDATEAVAILABILITY item=availability}
                            <tr style="display: flex;justify-content: space-between;">
                                <td class="availabilityInfo">{$availability->day}</td>
                                <td class="availabilityInfo">{$availability->time_1}</td>
                                <td class="availabilityInfo">{$availability->time_2}</td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-3 scheduleActionSlot">
                <div class="scheduleActionSlotTitle">Select Date / Time</div>
                <div class="form-group">
                    <label for="txt_expire_on_create">Date</label><br />
                    <span class="dateTime">
                  <div class="row">
                      <div class="col-md-9 col-xs-9">
                        <input class="date_input" autocomplete="off" type="text" name="schedule_date_{$actionStage->id}" id="schedule_date_{$actionStage->id}" style="width: 100%; height: 34px" maxlength="10"/>
                      </div>
                      <div class="col-md-3 col-xs-3">
                        <button type="button" id="schedule_date_trigger_{$actionStage->id}" class="btn btn-danger" style="float: right" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                      </div>
                  </div>
                  <div class="">

                   <div class="" style="overflow: hidden;">
                         <div class="form-group">
                             <label for="">Start time</label>
                             <input class="timepicker" style="width:130px;" type="text"  id="start_time_{$actionStage->id}"  readonly />
                         </div>
                   </div>
                   <div class="">
                         <div class="form-group">
                             <label for="">Duration</label>
                              <select class="datetimecombo_time" style="width:130px;" size="1" id="duration_{$actionStage->id}" tabindex="0">
                                <option selected=""></option>
                                <option value="15">15 Minutes</option>
                                <option value="30">30 Minutes</option>
                                <option value="45">45 Minutes</option>
                                <option value="60">1 Hour</option>
                                <option value="90">1.5 Hour and half</option>
                                <option value="120">2 Hours</option>
                            </select>
                         </div>
                    </div>
                  </div>
              </span>
                    <script type="text/javascript">
                        {literal}
                        Calendar.setup ({
                                inputField : "schedule_date_{/literal}{$actionStage->id}{literal}",
                                ifFormat : "%m/%d/%Y %H:%M",
                                daFormat : "%m/%d/%Y %H:%M",
                                button : "schedule_date_trigger_{/literal}{$actionStage->id}{literal}",
                                singleClick : true,
                                dateStr : "",
                                startWeekday: 0,
                                step : 1,
                                weekNumbers:false
                            }
                        );
                        {/literal}
                    </script>
                </div>
            </div>
            <div class="col-sm-5 scheduleActionSlot">
                <div class="scheduleActionSlotTitle">Interviewers</div>
                <select class="interviewer-interviewerselect_{$actionStage->id}" name="interviewerselect_{$actionStage->id}[]" multiple="multiple" style="width:80%" >
                    {foreach from=$INTERVIEWERS item=interviewer}
                        <option value="{$interviewer->interviewer_id}">{$interviewer->employee_information_name}</option>
                    {/foreach}
                </select>
                <div class="" style="display: grid; margin-top: 4px;">
                    <div class="col-sm-12" style="padding: 0;">
                        <div id="employee_list_{$stageId}" class="col-sm-7" style="padding: 0; padding-right: 4px;">
                            <div class="dataTables_select"></div>
                        </div>
                        <div class="col-sm-5" style="padding: 0; padding-left: 2px;">
                            <button type="button" id="add_other_interviewers_{$actionStage->id}" class="btn btn-danger" style="float: right" onclick="IntSelHandler2.showHideAddInterviewers('{$stageId}')">
                                <span class="suitepicon suitepicon-action-add" alt="Enter Date"></span>
                                Add Others
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-12" style="justify-self: center;">
                        <table id="data_table_interviewers_{$stageId}" class="table table-bordered table-striped table-hover" ></table>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <input id="{$actionType}-{$stageId}" type="button" value="save">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    {literal}

    var stageId2 = {/literal}"{$stageId}";{literal}
    var applicationId2 = {/literal}"{$BEANID}";{literal}
    var candidateId = {/literal}"{$CANDIDATEID}";{literal}
    var jobOfferId = {/literal}"{$JOBOFFERID}";{literal}
    var targetStageId = {/literal}"{$targetStageId}";{literal}

    var IntSelHandler2 = new InterviewerSelectorHandler();
    IntSelHandler2.init(stageId2,jobOfferId);

    function load_data_{/literal}{$stageId|replace:'-':'_'}{literal}(){
        
        let stageSettings = {/literal}"{$stageSettings}"{literal};
        let stageId = {/literal}"{$stageId}"{literal};
        let initial_data = {
            stageId : {/literal}"{$stageId}"{literal},
            applicationId : {/literal}"{$BEANID}"{literal},     
        }

        $.post(
            'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getStepStatus',
            initial_data,
            function (data) {
                let resp = data['results'];
                if(resp.id){
                    let data_obj = JSON.parse(resp.data); 
                    $("#schedule_date_"+stageId).val(data_obj.schedule_date);
                    $("#start_time_"+stageId).val(data_obj.start_hours +":"+ data_obj.start_minutes);
                    
                    let startTime = new Date(data_obj.schedule_date +" "+ data_obj.start_hours +":"+ data_obj.start_minutes); 
                    let endTime   = new Date(data_obj.schedule_date +" "+ data_obj.end_hours +":"+ data_obj.end_minutes);
                    let difference = endTime.getTime() - startTime.getTime(); // This will give difference in milliseconds
                    let resultInMinutes = Math.round(difference / 60000);
                    $("#duration_"+stageId).val(resultInMinutes);

                       
                    $("[id*='"+stageId+"']").prop("disabled",true);
                    // section of interviewers 
                    let data_obj_interviewers = resp.data_interviewers; 
                    $(".interviewer-interviewerselect_"+stageId).prop("disabled", true);
                    $(".interviewer-interviewerselect_"+stageId).next().find("textarea").hide();
                    
                    $(".interviewer-interviewerselect_"+stageId).next().find("ul").html("");
                    Object.entries(data_obj_interviewers).forEach(([key, value]) => {
                        if(stageSettings == data_obj_interviewers[key].interview_type){
                        $(".interviewer-interviewerselect_"+stageId).next().find("ul").append(`<option class="select2-selection__choice" title="${data_obj_interviewers[key].name_interviewers}">${data_obj_interviewers[key].name_interviewers}</option>`); 
                        }
                    });
                   
                }
            },
            'json'
        )

     }

    $(document).ready(function (){
        const sheduleSlecet2 = $('.interviewer-interviewerselect_'+{/literal}"{$stageId}"{literal}).select2({
            width: 'resolve',
            placeholder: 'Select one or more interviewers',
            selectionCssClass: 'interviewerSelectClass'
        });
        IntSelHandler2.setSelect2Intance(sheduleSlecet2);
        IntSelHandler2.getInterviewersJobOffer();
        IntSelHandler2.showHideAddInterviewers();
        IntSelHandler2.hideAddInterviewers();

        toastr.options = {
            "positionClass": "toast-bottom-right",
        }

        function addMinutes(date, minutes) {

            let data = new Date(date.getTime() + minutes*60000);
            let hour_out = (data.getHours().toString().length == 1) ? "0"+data.getHours() : data.getHours();
            let minutes_out = (data.getMinutes().toString().length == 1) ? "0"+data.getMinutes() : data.getMinutes();

            return hour_out +":"+minutes_out;
   
        }

        $({/literal}"#{$actionType}-{$stageId}"{literal}).click(function (event) {
            //stop submit the form, we will post it manually.
            event.preventDefault();
            
            let employees_have_role = true;
            $({/literal}"#data_table_interviewers_{$stageId} > tbody > tr"{literal}).find("option:selected").each(function(i){
                if(this.value === ""){
                    employees_have_role = false;
                    return false;
                }
            });
            if(!employees_have_role){
                toastr.warning("Please assign an interviewer role to the employee you added.", "Oops!");
                return;
            }

            let stageId   = {/literal}"{$stageId}"{literal};
            let job_offer_name = {/literal}"{$job_offer_name}"{literal};
            let start_time = {/literal}"{$stageId}"{literal};
            let duration   =  {/literal}"{$stageId}"{literal};
            let start_time_field = $("#start_time_"+start_time).val().toString().split(" ").join(":").split(":");
            let duration_field   = $("#duration_"+duration).val();
            let fecha = new Date($("#schedule_date_"+stageId).val() +" "+  start_time_field[0] +":"+ start_time_field[1]);
            let end_time = addMinutes(fecha,duration_field).split(":");

            let interviewersName = new Array();
            $(".interviewer-interviewerselect_"+stageId).find("option:selected").each(function(i){
                interviewersName.push($(this).text());
            });
          
            let stageData = {
                stageId : {/literal}"{$stageId}"{literal},
                applicationId : {/literal}"{$BEANID}"{literal},
                targetStageId : {/literal}"{$targetStageId}"{literal},
                jobOfferName  : (job_offer_name!="")?job_offer_name: $("#selectJobOfferLabel").html(),
                schedule_date : $("#schedule_date_"+stageId).val(),
                start_hours   : start_time_field[0],
                end_hours     : end_time[0],
                start_minutes : start_time_field[1],
                end_minutes   : end_time[1],
                interviewers_id   : $(".interviewer-interviewerselect_"+stageId).val()?.toString(),
                interviewers_name : interviewersName?.toString()
            }

            if(stageData.schedule_date == ""){
                 toastr.warning("Select the date of the interview", 'Oops!');
                 return;
            }

            if(stageData.start_hours == "" ||  stageData.start_minutes == "" ){
                 toastr.warning("Fill in the hour and minute fields of the interview", 'Oops!');
                 return;
            }

            if(stageData.end_hours == "" || stageData.end_minutes == ""){
                 toastr.warning("Enter the duration field of the interview", 'Oops!');
                 return;
            }

            if(stageData.interviewers_id == "" || stageData.interviewers_id == null){
                 toastr.warning("Select at least one interviewer", 'Oops!');
                 return;
            }

            $.ajax({
                type: 'POST',
                url: 'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=updateStage',
                data: stageData,
                beforeSend : function () {
                   $({/literal}"#{$actionType}-{$stageId}"{literal}).prop("disabled", true);
                },
                success: function (data) {
                    if(typeof actionComplete === "function"){
                        actionComplete(data);
                    }
                   $("header").find("div#steps").find("div.active").next().click();
                   $({/literal}"#{$actionType}-{$stageId}"{literal}).prop("disabled", false);
                },
                dataType: 'json'
            });

        });


        $('.timepicker').timepicker({
            timeFormat: 'HH:mm',
            interval: 15,
            minTime: '6:00am',
            maxTime: '6:00pm',
           // defaultTime: '6',
            startTime: '06:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });

    });
    {/literal}
</script>

<div class="actionCard">
    <div class="actionCardHeader" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <h2>STEP {$smarty.foreach.actionStage.iteration}: {$actionStage->name}</h2>
    </div>
    <div class="collapse container padding_container">
        <div class="row">
            <div class="col-md-6 ">
                Add Notes:
                <textarea id="{$actionType}_{$stageId}" style="width:99%" name="{$actionType}" cols="90" rows="8"></textarea>
            </div>
            <div class="col-md-3 ">
              <div class="form-group">
                <label for="approved_{$actionType}_{$stageId}" class="col-form-label">Approved</label>
                    <select class="form-control" id="approved_{$actionType}_{$stageId}"   style="height:34px">
                        <option value="">Select an option</option>
                        <option value="Close Won">Close Won</option>
                        <option value="Close Lost">Close Lost</option>
                    </select>

                    <!--- section for successful closing -->

                    <div id="section_successful_closed_{$stageId}"  class="col-sm-12" style="margin-top:10px;display:none">
                      <div  class="form-group"><label>Date of admission</label><br />
                        <span class="dateTime">
                          <div class="row">
                            <div class="col-md-9 col-xs-9">
                              <input class="date_input" autocomplete="off" type="text" name="schedule_date_{$actionStage->id}" id="schedule_date_{$actionStage->id}" style="width: 100%; height: 34px" maxlength="10"/>
                            </div>
                            <div class="col-md-3 col-xs-3">
                               <button type="button" id="schedule_date_trigger_{$actionStage->id}" class="btn btn-danger" style="float: right" onclick="return false;">
                               <span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                            </div>
                          </div>
                       </div>
                    </div>

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


                     <!--- section for successful closing -->


            </div>
            </div>
            <div class="col-md-3 " style="text-align: center;">
                <input id="{$actionType}-{$stageId}" type="button" style="margin-top: 19px;" value="save">
                <input type="checkbox" id="send-email-{$stageId}" >
                <label for="send-email-{$stageId}">send e-mails</label><br>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    {literal}

    function load_data_{/literal}{$stageId|replace:'-':'_'}{literal}(){

        let textareaInput =  {/literal}"#{$actionType}_{$stageId}";{literal}
        let approved      =  {/literal}"#approved_{$actionType}_{$stageId}"{literal};
        let stageId       =  {/literal}"{$stageId}"{literal};
        let sendEmail      = {/literal}"#send-email-{$stageId}"{literal};

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
                    $(textareaInput).val(data_obj.note);
                    $(approved).val(data_obj.approved);
                    $(sendEmail).prop('checked',(data_obj.send_email == 'true') ? true : false);
                    if(data_obj.date_of_admission != "" && data_obj.date_of_admission != undefined){
                        let idDiv =  {/literal}"#section_successful_closed_{$stageId}"{literal};
                        $(idDiv).show("slow");
                        $(approved).val(data_obj.approved);
                        $("#schedule_date_"+stageId).val(data_obj.date_of_admission);
                    }
                    $("[id*='"+stageId+"']").prop("disabled",true);
                    
                }else{
                     $(sendEmail).prop('checked',true);
                }
            },
            'json'
        )
    }


    $(document).ready(function (){

        toastr.options = {
            "positionClass": "toast-bottom-right",
        }




        $({/literal}"#{$actionType}-{$stageId}"{literal}).click(function (event) {
            //stop submit the form, we will post it manually.
            event.preventDefault();
            let textareaInput =  {/literal}"#{$actionType}_{$stageId}"{literal};
            let approved      =  {/literal}"#approved_{$actionType}_{$stageId}"{literal};
            let dateOfAdmission =  {/literal}"#schedule_date_{$stageId}"{literal};
            let sendEmail      = {/literal}"#send-email-{$stageId}"{literal};

            let stageData = {
                stageId : {/literal}"{$stageId}"{literal},
                applicationId : {/literal}"{$BEANID}"{literal},
                candidateId : {/literal}"{$CANDIDATEID}"{literal},
                jobOfferId : {/literal}"{$JOBOFFERID}"{literal},
                targetStageId : {/literal}"{$targetStageId}"{literal},
                note : $(textareaInput).val(),
                approved : $(approved).val(),
                date_of_admission : $(dateOfAdmission).val(),
                send_email : $(sendEmail).prop('checked')

            }

            if( stageData.approved.toLowerCase() == "approved" && stageData.date_of_admission == ""){
                 toastr.warning("Enter the date of admission", 'Oops!');
                 return;
            }

            if(stageData.approved == ""){
                 toastr.warning("Enter the approved type", 'Oops!');
                 return;
            }

            if(stageData.note == ""){
                 toastr.warning("Enter the note", 'Oops!');
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
                  
                   $("header").find("div#steps").find("div.active").click();
                    
                },
                dataType: 'json'
            });

        });

                   
        $({/literal}"#approved_{$actionType}_{$stageId}"{literal}).change(function (event) {
         
           let idDiv =  {/literal}"#section_successful_closed_{$stageId}"{literal};
           
           if($(this).find(":selected").val() == "Close Won"){
               $(idDiv).show("slow");
           }else{
               $(idDiv).hide();
           }
        });


    });
    {/literal}
</script>

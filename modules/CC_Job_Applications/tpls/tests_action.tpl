<div class="actionCard">
    <div class="actionCardHeader" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <h2>STEP {$smarty.foreach.actionStage.iteration}: {$actionStage->name}</h2>
    </div>
    <div class="container padding_container">
      <div class="row">
        <div class="col-md-3 container_div_section_text" >
           <p class="action_test_text">Test information</p>
           <strong>Previous Stage:&nbsp; <span>Schedule Technical Test</span></strong>
        </div>
 
        <div class="col-md-5 container_div_section_text">
           <p class="action_test_text">Did candidate approved the previously sent tests ?</p>
           <strong>Approved:</strong> <span>The Job Applications will move to the next stage.</span><br>
           <strong>Not Approved:</strong> <span>The Job Applications will move to <i>Closed Lost</i> and the process will end here.</span>
        </div>

         <div class="col-md-2 container_div_section_text">
            <div class="form-group">
                <label for="approved_{$actionType}_{$stageId}_action_tests" class="col-form-label">Approved</label>
                    <select class="form-control" id="approved_{$actionType}_{$stageId}_action_tests" style="height:34px">
                    <option value="">Select an option</option>
                    <option value="Approved">Approved</option>
                    <option value="Not Approved">Not Approved</option>
                
                </select>
            </div>
         </div>

         <div class="col-md-2 ">
             <input id="{$actionType}-{$stageId}" type="button" style="margin-top:17px" value="save">  
        </div>
     </div>

    </div>
</div>
<script type="text/javascript">
    {literal}

    function load_data_{/literal}{$stageId|replace:'-':'_'}{literal}(){
        let approved =  {/literal}"#approved_{$actionType}_{$stageId}_action_tests"{literal};
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
                    
                if(resp.id){
                    let data_obj = resp.data; 
                    $(approved).val(data_obj.approved);
                    $("[id*='"+stageId+"']").prop("disabled",true);
                    
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
            let approved =  {/literal}"#approved_{$actionType}_{$stageId}_action_tests"{literal};
            let stageData = {
                stageId : {/literal}"{$stageId}"{literal},
                applicationId : {/literal}"{$BEANID}"{literal},
                targetStageId : {/literal}"{$targetStageId}",{literal}
                approved : $(approved).val()
            }

            if(stageData.approved == ""){
                 toastr.warning("Enter the approved type", 'Oops!');
                 return;
            }

            if(stageData.stageId == "" || stageData.applicationId == ""){
                 toastr.error("Rrror when loading the module, contact systems", 'Oops!');
                 return;
            }

            $.post(
                'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=updateStage',
                stageData,
                function (data) {
                    if(typeof actionComplete === "function"){
                        actionComplete(data);
                    }
                    $("header").find("div#steps").find("div.active").next().click();
                },
                'json'
            )

        });
    });
    {/literal}
</script>
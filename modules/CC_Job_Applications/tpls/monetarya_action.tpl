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
                    <select class="form-control" id="approved_{$actionType}_{$stageId}" style="height:34px">
                    <option value="">Select an option</option>
                    <option value="Approved">Approved</option>
                    <option value="Not Approved">Not Approved</option>
                
                </select>
            </div>
            </div>
            <div class="col-md-3 ">
                <input id="{$actionType}-{$stageId}" type="button" style="margin-top: 19px;" value="save">
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
            let textareaInput =  {/literal}"#{$actionType}_{$stageId}";{literal}
            let approved      =  {/literal}"#approved_{$actionType}_{$stageId}"{literal};
      
            let stageData = {
                stageId : {/literal}"{$stageId}"{literal},
                applicationId : {/literal}"{$BEANID}"{literal},
                candidateId : {/literal}"{$CANDIDATEID}"{literal},
                jobOfferId : {/literal}"{$JOBOFFERID}"{literal},
                targetStageId : {/literal}"{$targetStageId}",{literal}
                note : $(textareaInput).val(),
                approved : $(approved).val()
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
                   $("header").find("div#steps").find("div.active").next().click();
                   $({/literal}"#{$actionType}-{$stageId}"{literal}).prop("disabled", false);
                },
                dataType: 'json'
            });

            

        });
    });
    {/literal}
</script>
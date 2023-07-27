<div class="actionCard">
    <div class="actionCardHeader" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <h2>STEP {$smarty.foreach.actionStage.iteration}: {$actionStage->name}</h2>
    </div>
    <div class="collapse container padding_container">
        <div class="row">
            <div class="col-sm-9 scheduleActionSlot">
                Add Notes:
                <textarea id="{$actionType}_{$stageId}_action_note" name="{$actionType}_action_note" cols="80" rows="8"></textarea>
            </div>
            <div class="col-sm-3 scheduleActionSlot">
                <input id="{$actionType}-{$stageId}" type="button" value="save">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    {literal}

    function load_data_{/literal}{$stageId|replace:'-':'_'}{literal}(){

        let textareaInput =  {/literal}"#{$actionType}_{$stageId}_action_note";{literal}
        let stageId       = {/literal}"{$stageId}"{literal};

        let initial_data = {
            stageId : {/literal}"{$stageId}"{literal},
            applicationId : {/literal}"{$BEANID}"{literal},
        }

        $.post(
            'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getStepStatus',
            initial_data,
            function (data) {
            let resp = data['results'];
            if(resp.id && resp.data != ''){
                let data_obj = JSON.parse(resp.data); 
                $(textareaInput).val(data_obj.note);
                $("[id*='"+stageId+"']").prop("disabled",true);
            }else{
                console.log("Not data"); 
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
            let textareaInput =  {/literal}"#{$actionType}_{$stageId}_action_note";{literal}
            let stageData = {
                stageId : {/literal}"{$stageId}",{literal}
                applicationId : {/literal}"{$BEANID}",{literal}
                candidateId : {/literal}"{$CANDIDATEID}",{literal}
                jobOfferId : {/literal}"{$JOBOFFERID}",{literal}
                targetStageId : {/literal}"{$targetStageId}",{literal}
                note : $(textareaInput).val(),
            }

            $.post(
                'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=updateStage',
                stageData,
                function (data) {
                    if(data){
                        if(typeof actionComplete === "function"){
                            actionComplete(data);
                        }
                        $("header").find("div#steps").find("div.active").next().click();
                        
                    }
                },
                'json'
            )

        });
    });
    {/literal}
</script>
<div class="actionCard">
    <div class="actionCardHeader" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <h2>STEP {$smarty.foreach.actionStage.iteration}: {$actionStage->name}</h2>
    </div>
    <div class="collapse container padding_container">
        <div class="row">
            <p>{$actionStage->description}</p>
        </div>
    </div>
</div>
<script type="text/javascript">
    {literal}

    function load_data_{/literal}{$stageId|replace:'-':'_'}{literal}(){

    }
    
    $(document).ready(function (){
        toastr.options = {
            "positionClass": "toast-bottom-right",
        }
        $({/literal}"#{$actionType}-{$stageId}"{literal}).click(function (event) {
            //stop submit the form, we will post it manually.
            event.preventDefault();
            let stageData = {
                stageId : {/literal}"{$stageId}",{literal}
                targetStageId : {/literal}"{$targetStageId}",{literal}
            }

            $.post(
                'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=updateStage',
                stageData,
                function (data) {
                    if(data){
                        if(typeof actionComplete === "function"){
                            actionComplete(data);
                        }
                    }
                },
                'json'
            )

        });
    });
    {/literal}
</script>
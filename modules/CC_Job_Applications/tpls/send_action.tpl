<div class="actionCard">
    <div class="actionCardHeader" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <h2>STEP {$smarty.foreach.actionStage.iteration}: {$actionStage->name}</h2>
    </div>
    <div class="collapse container padding_container">
        <div class="row">
            <div class="col-md-2 container_div_section_shedule_tec">
                 <div class="form-group ">
                    <label for="tests_send_{$actionType}_{$stageId}_action_scheduletec" class="col-form-label">Test to send</label>
                    <select class="form-control" id="tests_send_{$actionType}_{$stageId}_action_scheduletec" style="height:34px">
                        <option value="">Select an option</option>
                        <option value="Custom Test">Custom Test</option>
                    </select>
                </div>
            </div>
           
            <div class="col-md-2 container_div_section_shedule_tec" >
                <div class="form-group ">
                    <label class="col-form-label">Email Template</label>
                    <input type="hidden" id="hdn_id_template" />
                    <div id="template_{$actionType}-{$stageId}_action_scheduletec"></div>
                </div>
            </div>
            <div class="col-md-7 container_div_section_shedule_tec" >
                 <div id="section_template_variables_{$stageId}" class="row"></div>                 
            </div>
            
            <div class="col-md-1" >
                 <div>
                      <input id="{$actionType}-{$stageId}" type="button" style="margin-top:17px" value="save">
                 </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    {literal}

    function load_data_{/literal}{$stageId|replace:'-':'_'}{literal}(){

        let testToSend =  {/literal}"#tests_send_{$actionType}_{$stageId}_action_scheduletec";{literal}
        let stageId    = {/literal}"{$stageId}"{literal};
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
                    $("#hdn_id_template").val(data_obj.idTemplate);
                    let newOption = new Option(data_obj.nameTemplate, data_obj.idTemplate, true, true);
                    $('#template_list_'+stageId).html(newOption).trigger('change');
                    let dataTemplate = data_obj.templatesVariables;
                
                    $("#section_template_variables_"+stageId).html("");
                    Object.entries(dataTemplate).forEach(([key, value]) => {
                        let data_key   = Object.keys(value)[0];
                        let data_value = Object.values(value)[0];
                        $("#section_template_variables_"+stageId).append(`<div class='col-md-6 div_template_variable_${stageId}'><div class='input-group inputs_variable_key' ><span class='input-group-addon'>$${data_key}</span><input type="text" disabled class="form-control variable_value" placeholder="Value" value=${data_value} ></div></div>`);
                    });

                    $(testToSend).val(data_obj.testToSend);
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

        let wrapperSelectTemplate = {/literal}"#template_{$actionType}-{$stageId}_action_scheduletec"{literal} 
        createApplicationSearchElement(
            wrapperSelectTemplate,
            "template_list_"+{/literal}"{$stageId}"{literal},
            0,
            'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getTemplate',
            'getTemplate',
            'Select for a Template',
            function (e) {templateSelect(e);
        });


        $({/literal}"#{$actionType}-{$stageId}"{literal}).click(function (event) {
            //stop submit the form, we will post it manually.
            event.preventDefault();
            let stageId   = {/literal}"{$stageId}"{literal};
            let testToSend    =  {/literal}"#tests_send_{$actionType}_{$stageId}_action_scheduletec";{literal}
            let idTemplate    = $("#hdn_id_template").val();
            let nameTemplate  = $("#template_list_"+stageId).find("option:selected").text();

            let templatesVariables = "[";

             $(".div_template_variable_"+stageId).each(function(i){
                let variableKey   = $(this).find("div span").html();
                let variableValue = $(this).find("div input[type='text']").val();
                templatesVariables+=`{"${variableKey}":"${variableValue}"},`;
             });
             // remove last comma
             templatesVariables = templatesVariables.substring(0, templatesVariables.length - 1);
             templatesVariables+="]";
             //templatesVariables = JSON.parse(templatesVariables);
    
            let stageData = {
                stageId : {/literal}"{$stageId}"{literal},
                applicationId : {/literal}"{$BEANID}"{literal},
                targetStageId : {/literal}"{$targetStageId}",{literal}
                testToSend : $(testToSend).val(),
                idTemplate,
                nameTemplate,
                templatesVariables
            }

            if(stageData.testToSend == ""){
                 toastr.warning("Enter a test to send", 'Oops!');
                 return;
            }

            if(stageData.idTemplate == ""){
                 toastr.warning("Enter a mail template", 'Oops!');
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


    function createApplicationSearchElement(parent, elementId, applicationId, url, moduleAction, placeholder, functionActionSelect){
  
  
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

    function templateSelect(data){

        let stageId   = {/literal}"{$stageId}"{literal};
        if(data){
            templateData = data;
            let newOption = new Option(templateData.name, templateData.id, true, true);
            $("#hdn_id_template").val(templateData.id);
            $('#template_list_'+stageId).html(newOption).trigger('change');

            let stageData = {
                template : templateData.body
            }
            
            $.post(
                'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getTemplateVariables',
                stageData,
                function (data) {
                    let result = data['results']; 
                    $("#section_template_variables_"+stageId).html("");
                    for(let i = 0; i < result.length; i ++){
                       $("#section_template_variables_"+stageId).append(`<div class='col-md-6 div_template_variable_${stageId}'><div class='input-group inputs_variable_key' ><span id="basic-addon_${i}" class='input-group-addon'>${result[i]}</span><input type="text" class="form-control variable_value" placeholder="Value" aria-describedby="basic-addon_${i}"></div></div>`);
                    }
                },
                'json'
            )

        }
    }

    

    {/literal}
</script>
<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/timepicker.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/timepicker/timepicker.min.js'}"></script>

<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/select2/select2.min.js'}"></script>
<script type="text/javascript" src="custom/include/generic/javascript/toastr/toastr.min.js"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Job_Applications/js/detail.js'}"></script>
<link href="modules/CC_Job_Applications/css/detail.css" rel="stylesheet" type="text/css">
<div id="content" class="" style="visibility: visible;">
    <div class="moduleTitle">
        <h2 class="module-title-text">DETAIL JOB APPLICATION</h2>
        <div style="padding:10px"><a id="getPDF">&#9660; GET PDF File</a>&nbsp;&nbsp;&nbsp; <a id="resume_candidate" style="display:none;" target="_blank">&#9660; GET Resume File</a></div>
        <div class="clear"></div>
    </div>

    <form method="POST" name="EditView" id="EditView">
        <div id="EditView_tabs">
            <div class="panel-content" style="overflow-x: hidden;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a class="collapsed" role="button" data-toggle="collapse-edit" aria-expanded="false">
                            <div class="col-xs-10 col-sm-11 col-md-11">
                                BASIC
                            </div>
                        </a>
                    </div>
                    <div class="panel-body panel-collapse collapse in panelContainer" id="detailpanel_-1"
                         data-id="DEFAULT">
                        <div class="tab-content">
                            <div class="row jobApplication-selectables-view-row">
                                <div class="col-xs-12 col-lg-6 edit-view-row-item">
                                    <div class="label">Candidate:</div>
                                    <div class="label" id="selectCandidateLabel"></div>
                                </div>
                                <div class="col-xs-12 col-lg-6 edit-view-row-item">
                                    <div class="label">Job Offer:</div>
                                    <div class="label" id="selectJobOfferLabel"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div id="ratingAreaContainer" class="row ratingAreaContainer">
                    <div class="ratingItemSkill col-sm-4 col-xs-12"><div class="container-fluid ratingItemContainer boxRating ApplicationRatingActive">
                            <p>Skill Rating: <span>50</span>%</p>
                        </div></div>
                    <div class="ratingItemQualification col-sm-4 col-xs-12"><div class="container-fluid ratingItemContainer boxRating ApplicationRatingExpired">
                            <p>Qualification Rating: <span>0</span>%</p>
                        </div></div>
                    <div class="ratingItemGeneral col-sm-4 col-xs-12"><div class="container-fluid ratingItemContainer boxRating ApplicationRatingWarning">
                            <p>General Rating: <span>25</span>%</p>
                        </div></div>
                </div>
                <div class="clear"></div>
                <header>
                    <div id="steps">
                        {foreach from=$STAGES item=result name=stageStep}
                            {if $smarty.foreach.stageStep.first}
                                <div data-step="{$smarty.foreach.stageStep.iteration}" onclick="load_data_{$result->id|replace:'-':'_'}()" data-step_true="{$result->stageorder}" class="active arrow-pointer"><p>{$result->name}</p></div>
                            {else}
                                <div data-step="{$smarty.foreach.stageStep.iteration}" onclick="load_data_{$result->id|replace:'-':'_'}()" data-step_true="{$result->stageorder}" class="arrow-pointer"><p>{$result->name}</p></div>
                            {/if}
                        {/foreach}
                    </div>
                </header>
                <div class="clear"></div>
                <br/>
                <div class="article_container">
                    {foreach from=$STAGES item=actionStage name=actionStage}
                    {if $smarty.foreach.actionStage.first}
                    <article class="active">
                    {else}
                    {if $smarty.foreach.actionStage.iteration==2}
                    <article class="active_p_1">
                    {else}
                    <article class="active_p_2">
                    {/if}
                    {/if}
                        {assign var=actionType value=$actionStage->settings|lower}
                        {assign var=stageId value=$actionStage->id}
                        {assign var=stageSettings value=$actionStage->settings}
                        {assign var=stageOrder value=$actionStage->stageorder}
                        {assign var=templateName value="`$TEMPLATEDIR``$actionType`_action.tpl"}
                        {include file="$templateName"}
                    </article>
                            {/foreach}
                </div>
                <div class="clear"></div>
                <br>
                <ul id="jobApplicationPanelDetailsSelector" class="nav nav-tabs mt-5 jobApplicationPanelDetailsSelector">
                    <li><a href="#candidateDataPanel" data-toggle="tab">Candidate</a></li>
                    <li><a href="#jobofferDataPanel" data-toggle="tab">Job Offer</a></li>
                    <li><a href="#personalityTest" data-toggle="tab">Personality Test Results</a></li>
                    <li><a href="#skillsQualifications" data-toggle="tab">Summary of Skills and Qualifications</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="candidateDataPanel">
                        <div class="panel-content">
                            <div class="panel panel-default">
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-12 edit-view-row-item">
                                            <div class="user-input" type="title" field="Name"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">First Name:</div>
                                            <input class="user-input" type="text" field="First_Name" />
                                            
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Last Name:</div>
                                            <input class="user-input" type="text" field="Last_Name" />

                                        </div>
                                    </div>
                                    <hr class="divider">
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Phone:</div>
                                            <input class="user-input" type="text" field="Phone" />
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Mobile:</div>
                                            <input class="user-input" type="text" field="Mobile" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Email:</div>
                                            <input class="user-input" type="text" field="Email" />
                                        </div>
                                    </div>
                                    <hr class="divider">
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Education:</div>
                                            <input class="user-input" type="text" field="Education" />
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Years Experience:</div>
                                            <input class="user-input" type="number" field="Years_Experience" />
                                        </div>
                                    </div>
                                    <hr class="divider">
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Address:</div>
                                            <input class="user-input" type="text" field="Street_Address_1" />
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Address Suite / Number :</div>
                                            <input class="user-input" type="text" field="Street_Address_2" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">State:</div>
                                            <input class="user-input" type="text" field="State" />
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">City:</div>
                                            <input class="user-input" type="text" field="City" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Country:</div>
                                            <input class="user-input" type="text" field="Country" />
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Postal Code:</div>
                                            <input class="user-input" type="text" field="Postal_Code" />
                                        </div>
                                    </div>
                                    <hr class="divider">
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Currently Employed:</div>
                                            <input class="user-input2" type="checkbox" field="Currently_Employed" />
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Current Employer:</div>
                                            <input class="user-input" type="text" field="Current_Employer" />
                                        </div>
                                    </div>
                                    <hr class="divider">
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Has Visa:</div>
                                            <input class="user-input2" type="checkbox" field="Has_Visa" />
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Has Passport:</div>
                                            <input class="user-input2" type="checkbox" field="Has_Passport" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Document Number:</div>
                                            <input class="user-input" type="text" field="Document_Number" />
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Id:</div>
                                            <div class="user-input" type="text" field="Id"></div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" onclick="change_candidate()" >Save</button>
                    </div>
                    
                    <div class="tab-pane" id="jobofferDataPanel">
                        <div class="panel-content">
                            <div class="panel panel-default">
                                
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-12 edit-view-row-item form-group">
                                            <div class="user-input" type="title" field="name"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Job Description:</div>
                                            <div class="user-input" type="text" field="description"></div>
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Expire On :</div>
                                            <div class="user-input" type="text" field="expire_on"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Contract Type:</div>
                                            <div class="user-input" type="text" field="contract_type"></div>
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Assigned Location:</div>
                                            <div class="user-input" type="text" field="assigned_location"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                            <div class="label">Id:</div>
                                            <div class="user-input" type="text" field="id"></div>
                                        </div>
                                        <div class="col-xs-12 col-lg-6 edit-view-row-item form-group">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="personalityTest">
                        <h3 id="personalityTestDataEmpty">The candidate don't have personality test</h3>

                        <div class="card" id="table_personality_test">
                            <div class="card-body">
                                <table id="tablePersonalityTest" class="display" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Pattern</th>
                                        <th>Score/Index</th>
                                        <th>Modify by user</th>
                                        <th>Date Modified</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Pattern</th>
                                        <th>Score/Index</th>
                                        <th>Modify by user</th>
                                        <th>Date Modified</th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div id="detailPersonalitytTestModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title" style="text-align: center;font-weight: 500;">Pattern</h4>
                                    </div>
                                    <div id="modal_body" class="modal-body"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="skillsQualifications">
                        <div class="row">
                            <div class="row">
                                <div class="col-xs-12 col-lg-12 edit-view-row-item">
                                    <div class="user-input" type="title" field="profile">Qualifications</div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table
                                            class="table table-bordered table-striped table-hover"
                                            id="table_qualifications_profile"
                                            cellspacing="0"
                                            width="100%"
                                    >
                                        <thead>
                                        <tr>
                                            <th>Profile Qualification</th>
                                            <th>Qualification Profile</th>
                                            <th>State Profile</th>
                                            <th>Qualification Candidate</th>
                                            <th>State Candidate</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-lg-12 edit-view-row-item">
                                    <div class="user-input" type="title" field="candidate">Skills</div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table  id="table_skills_profile" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Profile Skill</th>
                                            <th>Skill Profile</th>
                                            <th>&nbsp;</th>
                                            <th>Skill Candidate</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="clear"></div>

        </div>
        <input type="hidden" name="module" value="CC_Job_Applications">
        <input type="hidden" name="record" value="">
        <input type="hidden" name="isDuplicate" value="false">
        <input type="hidden" name="action" value="Process">
        <input type="hidden" name="return_module" value="CC_Job_Applications">
        <input type="hidden" name="return_action" value="ListView">
        <input type="hidden" name="return_id" value="">
        <input type="hidden" name="application_type" value="{$TYPE}">
        <input id="job_applications_apply_candidate_id" type="hidden" name="candidate_id" value="{$CANDIDATEID}">
        <input id="job_applications_apply_job_offer_id" type="hidden" name="job_offer_id" value="{$JOBOFFERID}">
        <input id="job_applications_apply_application_id" type="hidden" name="application_id" value="{$BEANID}">
    </form>
</div>

<script type="text/javascript">
    var moduleId = '{$MODULE}';
    var applicationId = '{$BEANID}';
    var candidateId = '{$CANDIDATEID}';
    var jobOfferId = '{$JOBOFFERID}';
    {literal}
    $(document).ready(function () {
        toastr.options = {
            "positionClass": "toast-bottom-right",
        }

        let initial_data = {
            applicationId    
        }

        $.post(
            'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getStatusJA',
            initial_data,
            function (data) {
              let respu = data['results'];
              let steps_completed = [];
              Object.entries(respu).forEach(([key, value]) => {
                  steps_completed.push(parseInt(respu[key].stageorder));
              });
            
              let elem_step = "";
              $("header").find("div#steps").find("div").each(function(i){
                if(steps_completed.includes(parseInt($(this).data("step_true")))){
                  elem_step = $(this);
                }
              });

              if(elem_step == ""){
                  $("header").find("div#steps").find("div").each(function(i){
                    if(steps_completed.includes(parseInt($(this).data("step_true")))){
                       
                    }else{
                       $(this).click();
                        return false;
                    }
                  
                  });

              }else{
                   elem_step.next().click();
              }
            },
            'json'
        )

        let canditate_data = {
            candidateId    
        }


        $.post(
            'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getResumeCandidate',
            canditate_data,
            function (data) {
              let respu = JSON.parse((data))['results'];
              let note = respu.id;
              if(note != undefined){
                  document.getElementById("resume_candidate").style.display = "inline";
                  document.getElementById("resume_candidate").href = "index.php?preview=yes&entryPoint=download&id="+ note +"&type=Notes";
              }
            }
        )

        $('#getPDF').click(function (event) {
            try{
                event.preventDefault();
                let xhr = new XMLHttpRequest();
                let candidateName = $('#selectCandidateLabel').html();
                xhr.onreadystatechange = function(){
                    if (this.readyState === 4 && this.status === 200){
                        let link=document.createElement('a');
                        link.href=window.URL.createObjectURL(this.response);
                        link.download=candidateName+" "+applicationId+".pdf";
                        link.click();
                    }
                }
                xhr.open('GET', "index.php?module=CC_Job_Applications&action=getpdf&applicationId="+applicationId);
                xhr.responseType = 'blob';
                xhr.send();
            } catch (e) {
                console.error(e);
                toastr.error("There was an error getting the Job Application PDF", 'Error');
            }
        });



    });


    function actionComplete(){
        $("#table_notes").DataTable().ajax.reload();
        $("#table_intresults").DataTable().ajax.reload();
    }

    function change_candidate(){

    let data_send = new FormData(); 
    let action    = "updateCandidate";
    let id_candidate = $("div[field='Id']").html();

    $("input.user-input").each(function( index ) {
        data_send.append($( this ).attr("field"), $( this ).val());
    });
    $("input.user-input2").each(function( index ) {
        let new_value = ($( this ).is(':checked') == true)? 1: 0; 
        data_send.append($( this ).attr("field"), new_value);
    });

    data_send.append('id_candidate',id_candidate);
    data_send.append('action',action);
    
    $.ajax({
      url: 'index.php?entryPoint=JobApplicationListViewEntryPoint',
      type:'POST',
      data:data_send,
      processData:false,
      contentType:false,
      cache:false,
      //dataType: 'json',
      success: function(resp) {
            let data_resp = JSON.parse(resp);
            if(data_resp.results){
              toastr.success("Candidate Modified successfully", 'Successful');
            }else{
              toastr.error('Error when uptading the candidate', 'Oops!');
            }
          }
    }); 
    }
    
    {/literal}
</script>
<script src='custom/include/generic/javascript/datatables/jquery.dataTables.min.js'></script>
<script src="custom/include/SugarFields/Fields/SkillRatingExperience/js/rating.js"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Job_Applications/js/addInterviewerToSchedule.js'}"></script>
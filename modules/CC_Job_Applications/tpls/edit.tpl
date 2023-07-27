<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Job_Applications/css/edit.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/select2/select2.min.js'}"></script>
<script type="text/javascript" src="custom/include/generic/javascript/toastr/toastr.min.js"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Job_Applications/js/edit.js'}"></script>

<div id="bootstrap-container" class="col-lg-12 expandedSidebar">
    <div id="content" class="content" style="visibility: visible;">
        <div class="moduleTitle">
            <h2 class="module-title-text">CREATE JOB APPLICATION</h2>
            <div class="clear"></div>
        </div>
        <div class="buttons">
            <input title="Cancel [Alt+l]" accesskey="l" class="button"
                   onclick="SUGAR.ajaxUI.loadContent('index.php?action=ListView&amp;module=CC_Job_Applications&amp;record=');return false;"
                   type="button" name="button" value="Cancel" id="CANCEL">
            <input title="Save" accesskey="a" class="button primary btnSubmitApplication" type="submit" value="Save" name="button">
        </div>
        <div class="clear"></div>
        <form method="POST" name="EditView" id="EditView">
            <div id="EditView_tabs">
                <div class="panel-content">
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
                                    
                                    <div class="col-xs-12 col-lg-4 edit-view-row-item">
                                        <div class="label">Candidate:</div>
                                        <div class="label" id="selectCandidateLabel"></div>
                                        <div id="selectCandidateWrapper"></div>
                                    </div>
                                    <div class="col-xs-12 col-lg-4 edit-view-row-item">
                                        <div class="label">Job Offer:</div>
                                        <div class="label" id="selectJobOfferLabel"></div>
                                        <div id="selectJobOfferWrapper"></div>
                                    </div>
                                    <div class="col-xs-12 col-lg-4 edit-view-row-item">
                                        <div class="label">Type:</div>
                                        <div id="application_type_list" style="margin-top: 7px;">
                                            <span
                                                title="View Change" 
                                                class="suitepicon suitepicon-action-caret" 
                                                style="position: absolute;left: 74%;top: 33px;font-size: smaller;"
                                            ></span>
                                            <select class="js-select-application-type-selector" style="
                                                width: 80%;
                                                border-color: #00000057;
                                                background: #FFF;"
                                            >
                                                <option value="EXTERNAL">External</option>
                                                <option value="INTERNAL">Internal</option>
                                            </select>
                                        </div>
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
                    <ul id="jobApplicationPanelDetailsSelector" class="nav nav-tabs mt-5 jobApplicationPanelDetailsSelector">
                        <li><a href="#candidateDataPanel" data-toggle="tab">Candidate</a></li>
                        <li><a href="#jobofferDataPanel" data-toggle="tab">Job Offer</a></li>
                    </ul>
                    <div class="tab-content">
                    <div class="tab-pane active" id="candidateDataPanel">
                        <div class="panel-content">
                            <div class="panel panel-default">
                                <div class="container">
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
                        </div>
                        <button type="button" class="btn btn-success" onclick="change_candidate()" >Update Candidate</button>
                    </div>
                    
                        <div class="tab-pane" id="jobofferDataPanel">
                            <h3 id="jobofferDataEmpty">Select a job offer first</h3>
                            <div class="panel-content">
                                <div class="panel panel-default">
                                    <div class="container">
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
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="buttons">
                    <input title="Cancel [Alt+l]" accesskey="l" class="button"
                           onclick="SUGAR.ajaxUI.loadContent('index.php?action=ListView&amp;module=CC_Job_Applications&amp;record='); return false;"
                           type="button" name="button" value="Cancel" id="CANCEL">
                    <input title="Save" accesskey="a" class="button primary btnSubmitApplication" type="submit" value="Save" name="button">
                </div>
            </div>
            <input type="hidden" name="module" value="CC_Job_Applications">
            <input type="hidden" name="record" value="">
            <input type="hidden" name="isDuplicate" value="false">
            <input type="hidden" name="action" value="Process">
            <input type="hidden" name="return_module" value="CC_Job_Applications">
            <input type="hidden" name="return_action" value="ListView">
            <input type="hidden" name="return_id" value="">
            <input id="job_application_type" type="hidden" name="application_type" value="{$TYPE}">
            <input id="job_applications_apply_candidate_id" type="hidden" name="candidate_id" value="">
            <input id="job_applications_apply_job_offer_id" type="hidden" name="job_offer_id" value="">
        </form>
    </div>
</div>

<script type="text/javascript">
    var moduleId = '{$MODULE}';
    var applicationId = '{$BEANID}';
    
    {literal}
    $(document).ready(function () {
        toastr.options = {
            "positionClass": "toast-bottom-right",
        }
        $(".btnSubmitApplication").click(function (event) {
            //stop submit the form, we will post it manually.
            event.preventDefault();
            // disabled the submit button
            $(".btnSubmitApplication").prop("disabled", true);

            // Get form
            var form = $('#EditView')[0];

            // FormData object
            var data = new FormData(form);

            // If you want to add an extra field for the FormData
            data.append("CustomField", "This is some extra data, testing");

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "index.php?module=CC_Job_Applications&action=Process",
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 800000,
                success: function (data) {
                    console.log("SUCCESS : ", data);
                    // Display a success toast, with a title
                    if(data?.success){
                        data.message.forEach(element =>{
                            toastr.success(element, 'Application created')
                            setTimeout(function(){ 
                            window.location.assign(`index.php?module=${data.module}`);
                            }, 2000);
                        });
                    } else{
                        data.message.forEach(element =>{
                            toastr.error(element, 'Error')
                        });
                    }
                    $(".btnSubmitApplication").prop("disabled",false);
                },
                error: function (e) {
                    $(".btnSubmitApplication").prop("disabled", false);
                    // Display an error toast, with a title
                    toastr.error('There was an unexpected error saving the application', 'Oops!')
                }
            });
        });

        
    });

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


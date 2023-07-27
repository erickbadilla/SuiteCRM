<link href="modules/CC_Recruitment_Request/css/create.css" rel="stylesheet" type="text/css" />
<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/amsify.suggestags.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/smartwizard5.css" rel="stylesheet" type="text/css" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/select2/select2.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/toastr/toastr.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/datatables/jquery.dataTables.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/SugarFields/Fields/SkillRatingExperience/js/rating.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/matcher/matcher.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/amsify/jquery.amsify.suggestags.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/smartwizard/smartwizard5.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Recruitment_Request/js/close.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Recruitment_Request/js/create.js'}"></script>

<div class="moduleTitle" >
  <h2 class="module-title-text"> &nbsp;Recruitment Request </h2>
</div>

<div class="content_fieldset">
 <br />
    <p>
      <label>Go To:</label>
      <select id="got_to_step" class="elementStep">
            <option value="0">0 Creation mode</option>
            <option value="1">1 Create the recruitment request</option>
            <option value="2">2 Assign skills and qualifications</option>
            <option value="3">3 Assign recruitment request</option>
            <option value="4">4 Close recruitment request</option>
      </select>
    </p>
    <br />


    <!-- SmartWizard html -->
    <div id="smartwizard">

        <ul class="nav nav_li">
            <li class="nav-item">
              <a class="nav-link" href="#step-0">
                <strong>Step 0</strong> <br>Creation mode
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#step-1">
                <strong>Step 1</strong> <br>Create the recruitment request
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#step-2">
                <strong>Step 2</strong> <br>Assign skills and qualifications
              </a>
            </li>
            <li class="nav-item">
             <a class="nav-link" href="#step-3">
                <strong>Step 3</strong> <br>Assign recruitment request
              </a>
            </li>
            <li class="nav-item">
             <a class="nav-link" href="#step-4">
                <strong>Step 4</strong> <br>Close recruitment request
              </a>
            </li>
        </ul>

        <div class="tab-content">

           <div id="step-0" class="tab-pane" style="width: 100%!important;" role="tabpanel" aria-labelledby="step-0">
                
              <div class="content_fieldset">                
                     
                     <div class="row">
                        <div class="col-md-3">&nbsp;</div>
                        <div class="col-md-6">
                          <div class="form-group" >
                            <label for="slc_crete_mode">Creation mode</label>
                            <select class="form-control" id="slc_crete_mode" onchange="create_mode_view()" style="height: 34px">
                               <option value="0">New</option>
                               <option value="1">Clone</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">&nbsp;</div>
                     </div>

                    <div id="div_option_clone" style="display:none">

                            <div class="row">  
                                  <div class="col-md-3">&nbsp;</div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="job_offer_list">Job Offer</label><br />
                                        <input type="hidden" id="hd_job_offer_id" />
                                        <div id="selectJobOfferWrapper"></div>
                                      </div>
                                  </div>
                                  <div class="col-md-3">&nbsp;</div>
                            </div>
                            <div class="row">
                                  <div class="col-md-3">&nbsp;</div>
                                  <div class="col-md-6">
                                    <div class="form-group" >
                                      <label for="slc_crete_mode_option_clone">Cloning options</label>
                                      <select class="form-control" id="slc_crete_mode_option_clone" onchange="" style="height: 34px">
                                        <option value="0">please select</option>
                                        <option value="1">Keep the data</option>
                                        <option value="2">Do not keep the data </option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-3">&nbsp;</div>
                            </div>
                    </div>
              </div>
           </div>
            <div id="step-1" class="tab-pane" style="width: 100%!important;" role="tabpanel" aria-labelledby="step-1">
                <div class="content_fieldset">
                     <div class="row">

                        <div class="col-md-5">
                          <div class="form-group">
                            <label for="txt_position_name_create">Position Name</label>
                            <input type="text" class="form-control" id="txt_position_name_create" value="{$NAME}"  autocomplete="off"/>
                          </div>
                        </div>
                        
                        <div class="col-md-2">&nbsp;</div>

                        <div class="col-md-5">
                          <div class="form-group">
                            <label for="txt_account_create">Account (You Can Select More Than 1)</label><br />
                            <!--<input type="hidden" id="hd_account_create" />
                            <div id="selectAccountWrapper"></div> -->
                            <select class="js_select_account_list" name="account_list[]" multiple="multiple" style="width:80%" >
                              {foreach from=$ACCOUNTS item=account}
                                  <option value="{$account->id}">{$account->name}</option>
                              {/foreach}
                            </select>

                          </div>
                        </div>
                      
                    </div>

                    <div class="row">

                      <div class="col-md-5">
                        <div class="form-group">
                          <label for="project_list">Project</label><br />
                          <input type="hidden" id="hd_project_id" />
                          <div id="selectProjectWrapper"></div>
                        </div>
                      </div>

                      <div class="col-md-2">&nbsp;</div>

                      <div class="col-md-5">
                        <div class="form-group">
                          <label for="charge_list">Position</label><br />
                          <input type="hidden" id="hd_jod_description_id" />
                          <div id="selectChargeWrapper"></div>
                        </div>
                      </div>

                    </div>

                    <div class="row">

                      <div class="col-md-5">
                        <div class="form-group">
                          <label for="txt_open_positions">Open Positions</label><br />
                          <input type="number" class="form-control" min="1" value="{$OPEN_POSITION}" id="txt_open_positions" />
                        </div>
                      </div>

                      <div class="col-md-2">&nbsp;</div>

                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="description">Description</label>
                          <textarea class="form-control" id="description" name="description" class="description" rows="10">{$DESCRIPTION}</textarea>
                        </div>
                      </div>
                    </div>
                </div>
            </div><!-- end first step tab -->
            <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                <div class="content_fieldset">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="profile_list">Add Profile</label><br />
                      <input type="hidden" id="hd_profile_id" />
                      <div id="selectProfileWrapper"></div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="skill_list">Add Skill</label><br />
                      <input type="hidden" id="hd_skill_id" />
                      <div id="selectSkillWrapper"></div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="qualification_list">Add Qualification</label><br />
                      <input type="hidden" id="hd_qualification_id" />
                      <div id="selectQualificationWrapper"></div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="justFavourites">Favourites</label><br />
                      <input type="checkbox" id="justFavourites" checked="checked">                      
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="hd_profile_id"  value=""/>
                  </div>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="hd_skill_id"  value=""/>
                  </div>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="hd_qualification_id"  value=""/>
                  </div>
                </div>
                <br><br>
                <div class="row">
                  <div class="col-md-12">
                        <div id="profile_skill"  style="min-height:200px">
                          
                          <div class="row">
                              <div class="col-md-12">
                              <div class="table-responsive">
                                <table  id="table_skills" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Skill</th>
                                             <th>Profile Skill</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                          </div>

                        </div>

                        <div id="profile_qualification" style="min-height:200px">
                            <div class="row">

                              <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="table_qualifications" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Qualification</th>
                                                <th>Profile Qualification</th>
                                                <th>Dependecy</th>
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
                </div>
            </div><!-- end tab two -->

            <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
              
              <div class="content_fieldset">   
              
                <div class="row">
                   
                    <div class="col-md-5">
                     <div class="form-group">
                        <label for="priority_list">Priority</label><br />
                        <select class="form-control" id="slc_priority" name="slc_priority">
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-md-2">&nbsp;</div>

                    <div class="col-md-5">
                      <div class="form-group">
                        <label for="list_assigned_to">Assigned to</label><br />
                        <input type="hidden" id="hd_assigned_to" />
                        <div id="selectAssignedWrapper"></div>
                      </div>
                    </div>
                
                </div>

              </div>
 
            </div><!-- end the step three -->
            <div id="step-4" class="tab-pane" style="width: 100%!important;" role="tabpanel" aria-labelledby="step-4">
              <div class="content_fieldset">
                <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="close_on">Close Date</label><br />
                          <span class="dateTime">
                           <div class="row">
                              <div class="col-md-9 col-xs-9">
                                <input class="date_input" autocomplete="off" type="text" name="close_on" id="close_on" style="width: 100%; height: 34px" maxlength="10"/>
                              </div>
                              <div class="col-md-3 col-xs-3">
                                  <button type="button" id="close_on_trigger" class="btn btn-danger" style="float: right" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                              </div>
                            </div>
                          </span>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group" >
                      <label for="slc_crete_mode">Reason for closing</label>
                      <textarea id="reasonClosing" class="form-control" style="height: 150px;width: 500px;"></textarea>
                    </div>
                  </div>
                  <div class="col-md-3">&nbsp;</div>
                </div>
              </div>
            </div>
            <!-- end tab four -->
        </div>
    </div>
</div>

<script type="text/javascript">
  var moduleId    = "{$MODULE}";
  var recruitmentID    = "{$BEANID}";
</script>
{if $URL_AJAX eq "0"}
    <script type="text/javascript" src="{sugar_getjspath file='custom/themes/SuiteP/js/style.js'}"></script>
{/if}
<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/amsify.suggestags.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Recruitment_Request/css/edit.css" rel="stylesheet" type="text/css" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<div class="moduleTitle" >
  <h2 class="module-title-text"> &nbsp;Recruitment Request </h2>
</div>

<br>

<div class="content_fieldset">
<div class="row">
  <div class="col-md-5">
    <div class="form-group">
      <label for="txt_position_name_create">Position Name</label>
      <input type="text" class="form-control" id="txt_position_name_create" value="{$NAME}" autocomplete="off"/>
    </div>
  </div>
  
  <div class="col-md-2">&nbsp;</div>

  <div class="col-md-5">
    <div class="form-group">
      <label for="txt_account_create">Account</label><br />
      <input type="hidden" id="hd_account_create" />
      <div id="selectAccountWrapper"></div>
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
      <textarea class="form-control" id="description" rows="6">{$DESCRIPTION}</textarea>
    </div>
  </div>
</div>


<br><br>


<div class="row">

  <div class="col-md-4">
    <div class="form-group">
      <label for="profile_list">Add Profile</label><br />
      <input type="hidden" id="hd_profile_id" />
      <div id="selectProfileWrapper"></div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label for="skill_list">Add Skill</label><br />
      <input type="hidden" id="hd_skill_id" />
      <div id="selectSkillWrapper"></div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label for="qualification_list">Add Qualification</label><br />
      <input type="hidden" id="hd_qualification_id" />
      <div id="selectQualificationWrapper"></div>
    </div>
  </div>

</div>


<div class="row">

  <div class="col-md-4">
     <input type="text" class="form-control" name="hd_profile_id"  value=""/>

  </div>

  <div class="col-md-4">
    <input type="text" class="form-control" name="hd_skill_id"  value=""/>
  </div>

  <div class="col-md-4">
     <input type="text" class="form-control" name="hd_qualification_id"  value=""/>
  </div>

</div>

<br><br>

<div class="row">
  <div class="col-md-12">

   <ul class="nav nav-tabs " style="margin-bottom: 15px;">
        <li class="active">
          <a data-toggle="tab" href="#profile_skill">Skill Summary</a>
        </li>
        <li>
          <a data-toggle="tab" href="#profile_qualification">Qualification Summary </a>
        </li>
        <li>
          <a data-toggle="tab" href="#create_case">Create Case </a>
        </li>
        
      </ul>

       <div class="tab-content">
        <div id="profile_skill" class="tab-pane fade in active" style="min-height:200px">
          
          <div class="row">
              <div class="col-md-12">
              <div class="table-responsive">
                <table  id="table_skills" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Profile Skill</th>
                            <th>Skill</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                     <tbody>
                     </tbody>
                </table>
            </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 btn_footer">
              <button type="button" class="button primary" onclick="edit_recruitment_request($(this))">Save</button>&nbsp;&nbsp;
              
            </div>
          </div>

        </div>


        <div id="profile_qualification" class="tab-pane fade " style="min-height:200px">
            <div class="row">

              <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="table_qualifications" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Profile Qualification</th>
                                <th>Qualification</th>
                                <th>Dependecy</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                  </div>
              </div>

             </div> 
             <div class="row">
              <div class="col-md-12 btn_footer">
                <button type="button" class="button primary" onclick="edit_recruitment_request($(this))">Save</button>&nbsp;&nbsp;
                 
              </div>
            </div>

        </div>


         <div id="create_case" class="tab-pane fade " style="min-height:250px">
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

            <br><br>

            <div class="row">
              <div class="col-md-12 btn_footer">
                 
                  <button type="button" id="bnt_create_case" class="button primary" onclick="create_case($(this))">Create Case</button>
              </div>
            </div>

        </div>


      

  </div>
</div>




</div>   

<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/select2/select2.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/datatables/jquery.dataTables.min.js'}"></script>
<script type="text/javascript">
  var moduleId    = "{$MODULE}";
  var recruitmentID    = "{$BEANID}";
</script>

<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/toastr/toastr.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/SugarFields/Fields/SkillRatingExperience/js/rating.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/matcher/matcher.js'}" ></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/amsify/jquery.amsify.suggestags.js'}"></script>

<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Recruitment_Request/js/edit.js'}"></script>

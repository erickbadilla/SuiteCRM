<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Job_Offer/css/edit.css" rel="stylesheet" type="text/css" />

<div class="row">
  <div class="col-md-12">
    <!-- titulo -->
    <div class="moduleTitle">
      <h2 class="module-title-text">Job Offer {$NAME} </h2>
      <div class="clear"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 col-xs-12">
    <div class="columna container_left">
      <ul class="nav nav-tabs tab_detail_offer" style="margin-bottom: 15px;">
        <li>
          <a data-toggle="tab" href="#profile_summary">Profile Summary</a>
        </li>
        <li class="active">
          <a data-toggle="tab" href="#details_job_offer"  >Details</a>
        </li>
      </ul>

      <div class="tab-content">
        <div id="profile_summary" class="tab-pane fade">
         
         <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading heading_new_styles">
                      <h4 class="panel-title" >
                        <a data-toggle="collapse" href="#collapse_qualifications">Qualifications</a>
                      </h4>
                  </div>
                  <div id="collapse_qualifications" class="panel-collapse collapse in">
                      <div class="panel-body" >
                        <div class="row">
                            <div class="col-md-12">

                                <div class="table-responsive">
                                  <table class="table table-bordered table-striped table-hover" id="table_qualifications" cellspacing="0" width="100%">
                                      <thead>
                                          <tr>
                                              <th>Profile Qualification</th>
                                              <th>Qualification</th>
                                              <th>State</th>
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
          </div>

          <br> <br>

          <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading heading_new_styles">
                      <h4 class="panel-title" >
                        <a data-toggle="collapse" href="#collapse_skills">Skills</a>
                      </h4>
                  </div>
                  <div id="collapse_skills" class="panel-collapse collapse in">
                      <div class="panel-body" >
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
                                  </table>
                              </div>

                            </div>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
          </div>


         
        </div>
        <div id="details_job_offer" class="tab-pane fade in active">
          <div class="panel-group" id="accordion">
            <div class="panel panel-default">
              <div class="panel-heading heading_new_styles" >
                <h4 class="panel-title" >
                  <a data-toggle="collapse" href="#collapse1">Information</a>
                  </h4>
              </div>
              <div id="collapse1" class="panel-collapse collapse in">
                <div class="panel-body" >
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="txt_position_name">Position Name</label>
                        <input type="text" class="form-control"  id="txt_position_name" value="{$NAME}" autocomplete="off"  />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                           <label for="txt_position_name">Expire ON</label><br>
                           <span class="dateTime">
                            <div class="row">
                               <div class="col-md-9 col-xs-9">
                                <input class="date_input" autocomplete="off" type="text" name="txt_expire_on" id="txt_expire_on" value="{$EXPIRE_ON}" style="width: 100%;height: 34px;"   maxlength="10">
                               </div>
                               <div class="col-md-3 col-xs-3">
                                <button type="button" id="txt_expire_on_trigger" class="btn btn-danger" style="float: right;" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                              </div>
                            </div>
                           </span>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="txt_contact_type">Contact Type</label>
                         <select class="form-control " id="txt_contact_type" style="height:34px"></select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="txt_assigned_location">Assigned Location</label>
                        <select class="form-control select" id="txt_assigned_location" name="txt_assigned_location" style="height:34px"></select>
      
                      </div>
                    </div>
                  </div>


                  <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="charge_list">Position</label><br />
                          <input type="hidden" id="old_jod_description_id" />
                          <input type="hidden" id="hd_jod_description_id" />
                          <div id="selectChargeWrapper"></div>
                        </div>
                    </div>

                  </div>

                  <div class="row">

                  <div class="col-md-6" style="overflow: hidden">
                      <div class="form-group">
                        <label for="jobImage">Select an Image:</label>
                        <input type="file" id="jobImage" name="jobImage">
                      </div>
                  </div>

                  <div class="col-md-6">
                      <div class="form-group">
                        <div id="image_info"></div>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-6" style="text-align: left;">
                      <div class="form-group">
                        <label for="txt_contact_type">Is Published</label><br>
                         <input type="checkbox" id="txt_is_published" name="txt_is_published" value="{$IS_PUBLISHED}" title="The offer is not published" disabled >
                      </div>
                    </div>



                  </div>
                </div>
              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading heading_new_styles">
                <h4 class="panel-title" >
                  <a data-toggle="collapse" href="#collapse2">Job Offer Description</a>
                </h4>
              </div>
              <div id="collapse2" class="panel-collapse collapse in">
                <div class="panel-body" >
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="txt_description">Job Description</label>
                        <textarea class="form-control" id="txt_description" rows="6" >{$DESCRIPTION}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12" style="margin-top:20px;text-align:center" >
                <button type="button" class="button primary" onclick="edit_job_offer()" >Save</button>
               <!--<button type="button" class="button primary" onclick="edit_job_offer_old()" >Edit</button>  -->
                <button type="button" id="state_offer" class="button primary" onclick="edit_state_job_offer()" >Publish</button>
                <button type="button" class="button primary" onclick="delete_job_offer()" >Delete</button> 
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>


<div class="col-md-6 col-xs-12">
    <div class="columna container_right">
      <div class="header_content">
        <h2 style="margin: 0; font-size: 20px"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16"><path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/></svg>&nbsp;&nbsp; Job Profile</h2>
      </div>
      
      <div class="body_content_section">
            <table id="dataTableJobProfile" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th></th>
                </tr>
                </thead>
              </table>
        </div>

    </div>
  </div>

 
</div>
<!-- end de row -->

<div class="row">



  <div class="col-md-6 col-xs-12">
    <div class="columna container_left">
      <div class="header_content">
        <h2 style="margin: 0; font-size: 20px"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16"><path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/></svg>&nbsp;&nbsp; Accounts</h2>
      </div>
      
      <div class="body_content_section">
            <table id="dataTableJobAccounts" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead>
                <tr>
                  <th>Name</th>
                  <th></th>
                </tr>
                </thead>
              </table>
        </div>

    </div>
  </div>


      <div class="col-md-6 col-xs-12">
    <div class="columna container_right">
      <div class="header_content">
        <h2 style="margin: 0; font-size: 20px"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16"><path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/></svg>&nbsp;&nbsp; Related Employees</h2>
      </div>
      
      <div class="body_content_section">
            <table id="dataTableRelatedEmployee" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th></th>
                </tr>
                </thead>
              </table>
        </div>

    </div>
  </div>

 
</div>
<!-- end de row -->

<div class="row">

    <div class="col-md-6 col-xs-12">
        <div class="columna container_left">
          <div class="header_content">
              <h2 style="margin: 0; font-size: 20px"> <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg> &nbsp;&nbsp;&nbsp;Interviewers</h2>
          </div>
          <div class="body_content_section">
               <table id="dataTableInterviewers" class="table table-bordered table-striped table-hover" style="width:100%">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th></th>
                      </tr>
                    </thead>
              </table>
          </div>
    
        </div>
      </div>

  <div class="col-md-6 col-xs-12">
    <div class="columna container_right">
      <div class="header_content">
          <h2 style="margin: 0; font-size: 20px"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16"><path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/></svg>&nbsp;&nbsp;&nbsp;Applications Rating</h2>
      </div>
      <div class="body_content_section">
           <table id="dataTableApplicationsRating" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Qualification</th>
                    <th>Skill Rating</th>
                  </tr>
                </thead>
          </table>
      </div>

    </div>
  </div>

 

</div>
<!-- end de row -->


<div class="row">

  <div class="col-md-6 col-xs-12">
    <div class="columna container_left">
       <div class="header_content">
           <h2 style="margin: 0; font-size: 20px"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg> &nbsp;&nbsp; Candidate Rating</h2>
       </div>

        <div class="body_content_section">
            <table id="dataTableRating" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead>
                <tr>
                  <th>Candidate</th>
                  <th>Qualification</th>
                  <th>Skill Rating</th>
                  <th>General Rating</th>
                  <th>View</th>
                </tr>
                </thead>
              </table>
        </div>

    </div>
  </div>



   <div class="col-md-6 col-xs-12">
    <div class="columna container_right">
      <div class="header_content">
         <h2 id="title_job_applications" style="margin: 0; font-size: 20px"> <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg> &nbsp;&nbsp;Job Applications</h2>
      </div>    
      <div class="body_content_section">
         <!--<div id="container_job_applications" style=""></div> -->
           <table id="dataTableJobApplications" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>State</th>
                    <th>Related Candidate</th>
                    <th>Applicant Type</th>
                  </tr>
                </thead>
          </table>
      </div>
    </div>
  </div>



</div>


<div class="row">

  <div class="col-md-6 col-xs-12">
    <div class="columna container_left">
      <div class="header_content">
           <h2 style="margin: 0; font-size: 20px"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16"><path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/></svg>&nbsp;&nbsp;&nbsp;Employee Matching</h2>
     </div>
      <div class="body_content_section">
           <table id="dataTableRatingEmployee" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Qualification</th>
                    <th>Skill Rating</th>
                    <th>Skill Rating</th>
                    <th>View</th>
                  </tr>
                </thead>
          </table>
      </div>

    </div>
  </div>





</div>

<!-- MODAL FOR COMPARISON-->
<div id="modal_Candidate_Rating" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center;font-weight: 500;">Skills</h4>
      </div>
      <div id="modalBody" class="modal-body">
        <div id="skillCandidateChart" style="position: relative; background-color: white;">
			    <canvas id="CandidateChart"></canvas>
		    </div>
        <table id="mainSkillCandidateModalTableLabels" style="display:none;" cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
          <thead>
            <tr style="font-weight:700; font-size: 13px">
              <th class="footable-first-visible" style="display: table-cell;">&nbsp;</th>
              <th style="display: table-cell;width: 20%">Skills</th>
              <th style="display: table-cell;width: 40%">Required</th>
              <th style="display: table-cell;width: 40%">Candidate</th>
            </tr>
          </thead>
        </table>
        <table id="mainSkillCandidateModalTable" style="display:none; max-height:340px; overflow:auto;" cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
          <tbody id="skillCandidateModalTable">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <input id="chartCandidateButton" type="button" class="button" onclick="changeCandidateSkillView()" value="View on Table" style="float:left">
        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>    

<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/select2/select2.min.js'}"></script>

<script type="text/javascript">
  var moduleId           = "{$MODULE}";
  var nameOffer          = "{$NAME}";
  var JobApplicationId   = "{$BEANID}";
  var IdAccount          = "{$ACCOUNT}";
  var IsPublished        = "{$IS_PUBLISHED}";
  var file_url           = "{$FILE_URL}";
  var expireOn           = "{$EXPIRE_ON}";
  var AssingLocationList = "{$ASSIGNED_LOCATION_LIST}";
  var ContractTypeList   = "{$CONTRACT_TYPE_LIST}";
  var assignedLocation   = "{$ASSIGNED_LOCATION}";
  var contractType       = "{$CONTRACT_TYPE}";
  {literal}
    if(file_url == ""){
    $( "#image_info" ).append( "<p>There is not an attached image</p>" );
    }else{
      $( "#image_info" ).append('<a id="image_a" title="Job Offer Image" href="'+file_url+'" target="_blank"><img id="image_img" src="'+file_url+'" alt="Job Offer Image" style="height: 100px;" /></a>');
    }
  {/literal}
</script>

<script type="text/javascript" src="{sugar_getjspath file='custom/include/SugarFields/Fields/SkillRatingExperience/js/rating.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/datatables/jquery.dataTables.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/chart/chart.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/matcher/matcher.js'}" ></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/toastr/toastr.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Job_Offer/js/edit.js'}"></script>

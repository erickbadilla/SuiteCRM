<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/select2/select2.min.js'}"></script>
<script type="text/javascript" src="custom/include/generic/javascript/toastr/toastr.min.js"></script>
<link href="modules/CC_Job_Applications/css/detail.css" rel="stylesheet" type="text/css">
<div id="content" class="" style="visibility: visible;">
    <div class="moduleTitle">
        <h2 class="module-title-text">Detail</h2>
        <div class="clear"></div>
    </div>
                   
    <div class="clear"></div>
    <div id="ratingAreaContainerRecruitment" class="row">
        <div class="col-sm-3 col-xs-12">
            <div class="container-fluid ratingItemContainer boxRating ApplicationRatingActive">
                <p>Registered candidates: <span id="lbl_candidates_registered">0</span></p>
            </div>
        </div>
        <div class="col-sm-3 col-xs-12">
            <div class="container-fluid ratingItemContainer boxRating ApplicationRatingExpired">
                <p>Interviewed Candidates: <span id="lbl_candidates_interviewed">0</span></p>
            </div>
        </div>
        <div class="col-sm-3 col-xs-12">
            <div class="container-fluid ratingItemContainer boxRating ApplicationRatingWarning">
                <p>Discarded Candidates: <span id="lbl_candidates_rejected">0</span></p>
            </div>
        </div>
        <div class="col-sm-3 col-xs-12">
            <div class="container-fluid ratingItemContainer boxRating ApplicationRatingBase">
                <p>Hired Candidates: <span id="lbl_candidates_hired">0</span></p>
            </div>
        </div>
    </div>
                    
</div>


<div class="row" style="margin-top:30px;margin-bottom:30px">
 <div class="col-md-4">
      <p><strong>Name: </strong> {$NAME}</p>
 </div> 
  <div class="col-md-4">
      <p><strong>Account: </strong><span id="lbl_account"></span></p>
 </div>  
 <div class="col-md-4">
       <p><strong>Project: </strong><span id="lbl_project"></span></p>
 </div>  
 <div class="col-md-4">
      <p><strong>Position: </strong><span id="lbl_position"></span></p>
 </div>  
  <div class="col-md-4">
      <p><strong>Assigned to: </strong><span id="lbl_assigned"></span></p>
 </div>   
 <div class="col-md-4">
       <p><strong>Open Positions: </strong>{$OPEN_POSITION}</p>
 </div>  
 <div class="col-md-12">
      <p><strong>Description: </strong> {$DESCRIPTION} </p>
 </div>             
</div>

<div class="row">
 <div class="col-md-12">

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="table_action_recruitment_request" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Comment</th>
                    <th>Time Elapsed</th>
                    <th>Nodo</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

 </div>              
</div>

<script type="text/javascript">
  var moduleId    = "{$MODULE}";
  var recruitmentID    = "{$BEANID}";
  
</script>

<script src='custom/include/generic/javascript/datatables/jquery.dataTables.min.js'></script>
<script src="custom/include/SugarFields/Fields/SkillRatingExperience/js/rating.js"></script>

{if $URL_AJAX eq "0"}
    <script type="text/javascript" src="{sugar_getjspath file='custom/themes/SuiteP/js/style.js'}"></script>
{/if}
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Recruitment_Request/js/detail.js'}"></script>
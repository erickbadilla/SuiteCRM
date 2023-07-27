<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/colReorder.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/jquery.contextMenu.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Recruitment_Request/css/list_view.css" rel="stylesheet" type="text/css" />

<div class="row">
  <div class="col-md-12">
    <!-- titulo -->
    <div class="moduleTitle">
      <h2 class="module-title-text">List Recruitment Request</h2>
      <div class="clear"></div>
      <div style="text-align:center">
        <span title="hide/show Columns" style="font-weight:bold">Toggle column: </span>
        <a class="toggle-vis" data-column="0">Name</a> - <a class="toggle-vis" data-column="1">Account</a> - 
        <a class="toggle-vis" data-column="2">Project</a> - <a class="toggle-vis" data-column="3">Position</a> - 
        <a class="toggle-vis" data-column="4">Open Position</a> - <a class="toggle-vis" data-column="5">Applications</a> - 
        <a class="toggle-vis" data-column="6">Applications In Progress</a> - <a class="toggle-vis" data-column="7">Applications Lost</a> - 
        <a class="toggle-vis" data-column="8">Applications Won</a> - <a class="toggle-vis" data-column="9">Total Interviews</a> -
        <a class="toggle-vis" data-column="10">Last Activity</a> - <a class="toggle-vis" data-column="11">Assigned To</a> -
        <a class="toggle-vis" data-column="12">Is Published</a> - <a class="toggle-vis" data-column="13">Closing</a> - 
        <a class="toggle-vis" data-column="14">Go</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-xs-12">
     <div class="table-responsive" >      
       <table id="table_main" class="table table-bordered table-striped table-hover table-responsive" style="width:100%">
            <thead>
              <tr>
                <th>Name</th>
                <th>Account</th>
                <th>Project</th>
                <th>Position</th>
                <th>Open Position</th>
                <th>Applications</th>
                <th>Applications In Progress</th>
                <th>Applications Lost</th>
                <th>Applications Won</th>
                <th>Total Interviews</th>
                <th>Last Activity</th>
                <th>Assigned To</th>
                <th>Is Published</th>
                <th>Closing</th>
                <th>Go</th>
              </tr>
            </thead>
            <div id="div_loading" style="display: none;">
              <div class="background-loading">
                <p style="font-size: 1.5rem; color: #fff">Loading ...</p>
              </div>
            </div>
        </table>
     </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="closeRequirement">
  <div class="modal-dialog" role="document" style="width:700px">
    <div class="modal-content">
      <div class="modal-header" style="padding:15px">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 class="modal-title" id="close_recruitment_request" style="text-align:center; font-size:25px">Close recruitment request</h2>
      </div>
      <div class="modal-body">
      <input id="recruitment_id" value="" style="display:none" />
                      <div class="form-group">
                          <label for="close_on">Close Date</label><br>
                          <span class="dateTime">
                           <div class="row">
                              <div class="col-md-11 col-xs11">
                                <input class="date_input" autocomplete="off" type="text" name="close_on" id="close_on" style="width: 100%; height: 34px" maxlength="10">
                              </div>
                              <div class="col-md-1 col-xs-1">
                                  <button type="button" id="close_on_trigger" class="btn btn-danger" style="float: right" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                              </div>
                            </div>
                          </span>
                      </div>
                    <div class="form-group">
                      <label for="slc_crete_mode">Reason for closing</label>
                      <textarea id="reasonClosing" class="form-control" style="height: 150px;width: 650px;"></textarea>
                    </div>

          <button type="button"  id="save_candidate" class="btn btn-danger" onclick="close_recruitment_request($(this), 1)" >Save</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
   {literal}
    Calendar.setup ({
            inputField : "close_on",
            ifFormat : "%m/%d/%Y %H:%M",
            daFormat : "%m/%d/%Y %H:%M",
            button : "close_on_trigger",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
        }
    );
 {/literal}
</script>

<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/datatables/jquery.dataTables.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/select2/select2.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/toastr/toastr.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/datatables/dataTables.colReorder.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/jquery.contextMenu/jquery.contextMenu.js'}"></script>
{if $URL_AJAX eq "0"}
    <script type="text/javascript" src="{sugar_getjspath file='custom/themes/SuiteP/js/style.js'}"></script>
{/if}
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Recruitment_Request/js/list_view.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Recruitment_Request/js/close.js'}"></script>
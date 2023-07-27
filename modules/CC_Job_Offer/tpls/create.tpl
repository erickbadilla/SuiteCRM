<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Job_Offer/css/create.css" rel="stylesheet" type="text/css" />


<div class="moduleTitle" >
  <h2 class="module-title-text"> &nbsp;Job Offer </h2>
</div>

<div class="content_fieldset">
<div class="row">

    <div class="form-group">
      <label for="selectRecruitmentWrapper">Recruitment Request:</label>
      <input type="hidden" id="txt_recruitment" />
      <div id="selectRecruitmentWrapper"></div>
    </div>

  
</div>

<div class="row">
  <div class="col-md-5">
    <div class="form-group">
      <label for="txt_position_name_create">Position Name</label>
      <input type="text" class="form-control" id="txt_position_name_create" autocomplete="off"/>
    </div>
  </div>
  <div class="col-md-2">&nbsp;</div>
  <div class="col-md-5">
    <div class="form-group">
      <label for="txt_expire_on_create">Expire ON</label><br />
      <span class="dateTime">
        <div class="row">
          <div class="col-md-9 col-xs-9">
            <input class="date_input" autocomplete="off" type="text" name="txt_expire_on_create" id="txt_expire_on_create" style="width: 100%; height: 34px" maxlength="10"/>
          </div>
          <div class="col-md-3 col-xs-3">
            <button type="button" id="txt_expire_on_create_trigger" class="btn btn-danger" style="float: right" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
          </div>
        </div>
      </span>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-5">
    <div class="form-group">
      <label for="txt_contact_type_edit">Contact Type</label>
      <select class="form-control" id="txt_contact_type_edit" style="height: 34px"></select>
    </div>
  </div>
  <div class="col-md-2">&nbsp;</div>
  <div class="col-md-5">
    <div class="form-group">
      <label for="txt_assigned_location_create">Assigned Location</label>
      <select class="form-control select" id="txt_assigned_location_create" name="txt_assigned_location_create" style="height: 34px"></select>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-5">
    <div class="form-group">
      <label for="txt_account_create">Account (You Can Select More Than 1)</label><br />
      {*<input type="hidden" id="txt_account_create" />
      <div id="selectAccountWrapper"></div>*}
      <select class="js_select_account_list" name="account_list[]" multiple="multiple" style="width:80%" >
        {foreach from=$ACCOUNTS item=account}
            <option value="{$account->id}">{$account->name}</option>
        {/foreach}
      </select>
    </div>
  </div>

<div class="col-md-2">&nbsp;</div>


  <div class="col-md-5">
    <div class="form-group">
      <label for="jobImage">Select an Image:</label>
      <input type="file" id="jobImage" name="jobImage">
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="txt_description">Job Description</label>
      <textarea class="form-control" id="txt_description_create" rows="6"></textarea>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 btn_footer">
    <button type="button" class="button primary" onclick="create_job_offer()">Save</button>
  </div>
</div>

</div>



<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/select2/select2.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/toastr/toastr.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/CC_Job_Offer/js/create.js'}"></script>

{literal}
<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="modules/AR_Activity_Report/css/edit.css" rel="stylesheet" type="text/css" />


<div class="moduleTitle" >
  <h2 class="module-title-text" id="title_ar" > </h2>
</div>

<div style="clear:both"></div>

<div>
   <div id="overview" class="panel panel-default">
      <div class="panel-heading" style="padding: 5px 10px; font-size: 16px;">OVERVIEW
      <button type="button" class="button primary" id="save_ar" onclick="create_activity_report()">Save</button>
      </div>
      <div class="tab-content">
         <div class="row edit-view-row">
         <div class="col-xs-12 col-lg-12 edit-view-row-item" data-field=""></div>
            <input type="hidden" name="id" id="id" value="" >
            <input type="hidden" name="idMeeting" id="idMeeting" value="" >

            <div class="clear"></div>
            <div class="clear"></div>

            <div class="col-xs-12 col-lg-12 edit-view-row-item" data-field="subject">
               <div class="col-xs-12 col-sm-2 label" data-label="LBL_SUBJECT">
                  Subject:<span style="color:red">*</span>
               </div>
               <div class="col-xs-12 col-sm-10 edit-view-field " type="varchar" field="subject">
                  <input type="text" name="subject" id="subject"  maxlength="255" value="" >
               </div>
               <!-- [/hide] -->
            </div>

            <div class="col-xs-12 col-lg-12 edit-view-row-item" data-field="agenda">
               <div class="col-xs-12 col-sm-2 label" data-label="LBL_AGENDA">
                  Agenda:<span style="color:red">*</span>
               </div>
               <div class="col-xs-12 col-sm-10 edit-view-field " type="text" field="agenda">
                  <textarea id="agenda" name="agenda" rows="4" cols="20"  tabindex="0"></textarea>
               </div>
               <!-- [/hide] -->
            </div>

            <div class="clear"></div>
            <div class="clear"></div>

            <div class="col-xs-12 col-lg-12 edit-view-row-item" >
               <div class="col-xs-12 col-sm-12  col-lg-6 label" style="padding-left:50px" data-label="LBL_START_DATE">
               <table style="width:100%">
               <tr>
                  <th style="width:50%"><span>Start Date:</span><span style="color:red">*</span></th>
                  <th style="width:25%"><span>Hour:</span><span style="color:red">*</span></th>
                  <th style="width:25%"><span>Minutes:</span><span style="color:red">*</span></th>
               </tr>
               <tr>
               <td><span class="dateTime" style="white-space: normal;">
                            <div class="row">
                                <input class="date_input" autocomplete="off" type="text" name="txt_start_date" id="txt_start_date" value="{/literal}{$START_DATE}{literal}" style="width: 70%;height: 34px;" maxlength="10">
                                <button type="button" id="txt_start_date_trigger" class="btn btn-danger"  onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                           </div></div>
                           </span></td>
               <td><select class="datetimecombo_time" size="1" id="hour_start" tabindex="0" style="width:70%; padding:0% 5%">
                                    {/literal}{html_options options=$HOURS}{literal}
                                 </select></td>
               <td><select class="datetimecombo_time" size="1" id="minute_start" tabindex="0" style="width:70%; padding:0% 5%; margin-left: 5%;">
                                    <option value="00">00</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                 </select></td>
               </tr>
               </table>

               </div>
               <div class="col-xs-12 col-sm-12  col-lg-3 label" data-label="LBL_DURATION">
                  <span style="padding-left:20px">Duration:</span><span style="color:red">*</span><br><br>
                           <select class="datetimecombo_time" size="1" id="duration" tabindex="0" style="width:60%">
                                <option value="15">15 Minutes</option>
                                <option value="30">30 Minutes</option>
                                <option value="45">45 Minutes</option>
                                <option value="60">1 Hour</option>
                                <option value="90">1.5 Hour and half</option>
                                <option value="120">2 Hours</option>
                            </select>
               </div>
               <div class="col-xs-12  col-sm-12  col-lg-3 label" data-label="LBL_RELATED">
                  <span>Related To:</span><span style="color:red">*</span><br><br>
                     <select class="datetimecombo_time related" size="1" id="relatedTo" tabindex="0">
                            <option value ="">Select</option>
                           {/literal}{html_options options=$RELATES}{literal}
                     </select><br>
                     <select class="datetimecombo_time related" size="1" id="relatedTo2" tabindex="0">
                        
                     </select>
               </div>

            </div>


            <div class="col-xs-12 col-lg-12 edit-view-row-item" data-field="participants">
               <div class="col-xs-12 col-sm-12 label" data-label="LBL_PARTICIPANTS">
                  Participants:
               </div>
               <div class="col-xs-12 col-lg-12 edit-view-row-item" data-field="" id="participants"></div>
               <div class="col-xs-12 col-sm-12 edit-view-row-item" id="participantList" data-label="LBL_PARTICIPANTS">

               </div>
            </div>

            
            <div class="clear"></div>
            <div class="clear"></div>
         </div>
      </div>
      </div>
   </div>
   <div id="related" class="panel panel-default">
      <div class="panel-heading" style="padding: 5px 10px; font-size: 16px;"> RELATED INFORMATION</div>
      <div class="tab-content">
         <div class="col-xs-12 col-lg-12 edit-view-row-item" data-field=""></div>
            <div class="clear"></div>
            <div class="clear"></div>
            
         <div class="row edit-view-row" id="wrap_Buttons">
         <button class="activityR">+  Add Participant </button><br>

         <label for="file-upload" class="custom-file-upload btn" id='file_upload'>
           +  Attach File 
         </label>
         <input type="file" multiple id="file-upload" onchange="updateList()"/><br>  
         </div>

      <div id="filesAdded"> </div>

      </div>
      <div id="fileList"> </div>

      </div>
   </div>
</div>
</div>
<div style="clear:both"></div>
</div>

<script type="text/template" data-template="participant_action_template">
/*
    <div style='margin-left:15%'><div class='label participants' id='selectEmployeeLabel_${participants}'></div><div id='selectEmployeeWrapper_${participants}'></div>
    <input type='hidden' id='txt_id_employees_list_${participants}' />
    <input type='hidden' id='owner_${participants}' value=0 />
    <button  style='margin-left:15%' type='button' class='button primary'  onclick='addDuty(${participants})'>Add duty</button>
    &nbsp;&nbsp;&nbsp;<span onclick="delete_participant(${participants})" style="font-size: 16px; color: firebrick;" class="suitepicon suitepicon-action-clear" ></span>         
    <div class="col-xs-12 col-lg-12 edit-view-row-item" style="margin-top: 5px;" id="duties_${participants}"></div></div>
*/
</script>

<script type="text/template" data-template="first_participant_action_template">
/*
      <div style='margin-left:15%'>
      <input type='hidden' id='employees_list_val_${participants}' />
      <input type='text' id='employees_list_${participants}' style='width:50%'/>
      <input type='hidden' id='owner_${participants}' value=1 />
      <button  style='margin-left:15%' type='button' class='button primary'  onclick='addDuty(${participants})'>Add duty</button>
         <div class="tooltip" style="opacity : 1; display: contents">
            <span class="tooltip-content2">Owner</span>
            <span class="ui-button-icon-primary ui-icon ui-icon-info"></span>
        </div>
      <div class="col-xs-12 col-lg-12 edit-view-row-item" style="margin-top: 5px;" id="duties_${participants}"></div></div>
*/
</script>

<script type="text/template" data-template="table_action_template">
/*
         <table style="width:85%" class="duties_participant" id='duty_table_${participant}'>
           <tr><th style="width:60%">Add Duty:</th><th style="width:35%">Due Date:</th><th style="width:7%">Estimed Time:</th><th style="width:3%">&nbsp;</th></tr>
         </table>
*/
</script>

<script type="text/template" data-template="duty_participant_action_template">
/*
      <tr id='duty_${participant}_${numDuty}' data-num='${numDuty}'>
        <td><textarea id='duty_text_${participant}_${numDuty}' row=2 style="width:100%"></textarea></td>
        <td><span class="dateTime" style="white-space: normal;">
          <input class="date_input" autocomplete="off" type="text" name="txt_due_date" id="txt_due_date_${participant}_${numDuty}" value="${today}" style="width: 70%;height: 34px;" maxlength="10">
          <button type="button" id="txt_due_date_trigger_${participant}_${numDuty}" class="btn btn-danger" style="float: right;" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
        </span> </td>
        <td><input id="estimed_time_${participant}_${numDuty}" type="number" value="" ></td>
        <td>${addI}<span onclick="delete_duty('${participant}_${numDuty}')" style="font-size: 16px; color: firebrick;" class="suitepicon suitepicon-action-clear" ></span></td>
      </tr>
*/
</script>


<script type="text/template" data-template="duty_added_participant_action_template">
/*
<tr id='duty_${id_duty}'>
              <td><textarea id='duty_text_${id_duty}' row=2 style="width:100%" onchange="updateDutyAdded('${id_duty}')">${description}</textarea></td>
              <td><span class="dateTime" style="white-space: normal;">
                <input class="date_input" autocomplete="off" type="text" name="txt_due_date" id="txt_due_date_${id_duty}" value="${due_date}" style="width: 70%;height: 34px;" maxlength="10" onchange="updateDutyAdded('${id_duty}')">
                <button type="button" id="txt_due_date_trigger_${id_duty}" class="btn btn-danger" style="float: right;" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
              </span> </td>
              <td><input id="estimed_time_${id_duty}" type="number" value="${original_estimate}" onchange="updateDutyAdded('${id_duty}')"></td>
              <td><span onclick="deleteDutyAdded('${id_duty}')" style="font-size: 16px; color: firebrick;" class="suitepicon suitepicon-action-clear" ></span></td>
            </tr>
*/
</script>


{/literal}

<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/select2/select2.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='custom/include/generic/javascript/toastr/toastr.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/AR_Activity_Report/js/edit.js'}"></script>


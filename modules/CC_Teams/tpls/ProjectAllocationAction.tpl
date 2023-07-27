<div class="actionCard">
    <div class="actionCardHeader" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <h2>Allocation Details</h2>
    </div>
    <div class="collapse container padding_container">
        <div class="row" style="height: auto;">
            <div class="col-lg-12 col-md-12 col-xs-12 scheduleActionSlot">
                {if $ACTUAL_PROJECT eq 'true'}
                <div class="actualProject row">
                    <h5 style="padding: 0;background-color: #ddd;margin: -5px -5px 5px;"><b>Actual Project</b> - {$ROW_PROJECT_NAME}</h5>
                </div>
                <div class="actualProject row">
                    <table style="width: 100%">
                        <tr>
                            <td class="col-label"><label>Employee Name:</label></td>
                            <td class="col-data">{$ROW_EMPLOYEE_NAME}</td>
                            <td class="col-label"><label>Position:</label></td>
                            <td class="col-data">{$EMPLOYEE_POSITION_NAME}</td>
                        </tr>
                        <tr>
                            <td class="col-label"><label>Role Description:</label></td>
                            <td class="col-data">{$ROW_DESCRIPTION}</td>
                            <td class="col-label"><label>Role:</label></td>
                            <td class="col-data">{$EMPLOYEE_ROLE}</td>
                        </tr>
                        <tr>
                            <td class="col-label"><label>Module:</label></td>
                            <td class="col-data">{$ROW_PROJECT_MODULE}</td>
                            <td class="col-label"><label>Worktype:</label></td>
                            <td class="col-data">{$ROW_PROJECT_WORKTYPE}</td>
                        </tr>
                        <tr>
                            <td><label>Date Start:</label></td>
                            <td>{$ROW_START_DATE}</td>
                            <td><label for="row_date_end">End Date:</label></td>
                            <td>
                                <div class="form-group" style="max-width: 250px;">
                                    <span class="dateTime">
                                        <input class="date_input" autocomplete="off" type="text" name="row_date_end"
                                    id="row_date_end" style="max-width: 200px;" maxlength="10" value="{$ROW_END_DATE}"/>
                                    <button type="button" id="action_trigger_row_date_end" class="btn btn-danger"
                                            style="float: right" onclick="return false;">
                                        <span class="suitepicon suitepicon-module-calendar" ></span>
                                    </button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-label"><label>User Notes:</label></td>
                            <td class="col-data">
                                <div class="form-group">
                                    <textarea id="row_notes" name="row_notes"></textarea>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                {/if}
                <div class="newProject row">
                    <h5 style="padding: 0;background-color: #ddd;margin: -5px -5px 5px"><b>New Project</b> - {$TARGET_PROJECT_NAME}</h5>
                </div>
                <div class="newProject row">
                    <div class="col-lg-3 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label for="project_modules_list">Project Module:</label><br/>
                            <div id="select_project_modules_list">
                                {html_options name="project_modules_list" id="project_modules_list" options=$PROJECT_MODULES}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label for="project_worktypes_list">Project Worktype:</label><br/>
                            <div id="select_project_worktypes_list">
                                {html_options name="project_worktypes_list" id="project_worktypes_list" options=$PROJECT_WORKTYPES}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label for="date_start">Start Date:</label><br/>
                            <span class="dateTime">
                                <input class="date_input" autocomplete="off" type="text" name="date_start"
                                       id="date_start" maxlength="10"/>
                                <button type="button" id="action_trigger_date_start" class="btn btn-danger"
                                      style="float: right" onclick="return false;">
                                      <span class="suitepicon suitepicon-module-calendar" ></span>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label for="date_end">End Date:</label><br/>
                            <span class="dateTime">
                                <input class="date_input" autocomplete="off" type="text" name="date_end"
                                       id="date_end" maxlength="10"/>
                                <button type="button" id="action_trigger_date_end" class="btn btn-danger"
                                        style="float: right" onclick="return false;">
                                      <span class="suitepicon suitepicon-module-calendar" ></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row" style="vertical-align: top;display: contents;">
                    <div class="col-lg-3 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label for="target_role">New Role:</label><br/>
                            {html_options name="target_role" id="target_role" options=$EMP_PROJECT_ROLE_LIST}
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label for="target_description">New Role Description:</label><br/>
                            <textarea id="target_description" name="target_description"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label for="target_load">Load:</label><br/>
                            <input type="number" name="target_load" id="target_load" max="100" min="1" value="50">
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-xs-6">
                        <div class="form-group">
                            <label for="target_is_assigned">Is Assigned:</label><br/>
                            <input type="checkbox" name="target_is_assigned" id="target_is_assigned">
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-xs-6">
                        <div class="form-group">
                            <label for="target_is_default">Default Project:</label><br/>
                            <input type="checkbox" name="target_is_default" id="target_is_default">
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="row">
        <input id="updateProject" type="button" value="save">
    </div>
</div>
<script type="text/javascript">
    {literal}
    $(document).ready(function (){

        toastr.options = {
            "positionClass": "toast-bottom-right",
        }

        Calendar.setup ({
            inputField : "date_end",
            ifFormat : "%Y-%m-%d %H:%M",
            daFormat : "%Y-%m-%d %H:%M",
            button : "action_trigger_date_end",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
        });

        Calendar.setup ({
            inputField : "date_start",
            ifFormat : "%Y-%m-%d %H:%M",
            daFormat : "%Y-%m-%d %H:%M",
            button : "action_trigger_date_start",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
        });

        Calendar.setup ({
            inputField : "row_date_end",
            ifFormat : "%Y-%m-%d %H:%M",
            daFormat : "%Y-%m-%d %H:%M",
            button : "action_trigger_row_date_end",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
        });

        $("#updateProject").click(function (event) {
            //stop submit the form, we will post it manually.
            event.preventDefault();
            let eventData = {
                isExistingCard : {/literal}"{$ACTUAL_PROJECT}",{literal}
                row_id : {/literal}"{$ROW_ID}",{literal}
                row_notes : $("#row_notes").val(),
                row_date_end : $("#row_date_end").val(),
                employee_id : {/literal}"{$EMPLOYEE_ID}",{literal}
                target_project_id : {/literal}"{$TARGET_PROJECT_ID}",{literal}
                target_worktype : $("#project_worktypes_list").val(),
                target_module : $("#project_modules_list").val(),
                target_description : $("#target_description").val(),
                target_load : $("#target_load").val(),
                target_date_start : $("#date_start").val(),
                target_date_end : $("#date_end").val(),
                target_role : $("#target_role").val(),
                target_is_assigned : ($("#target_is_assigned").val()==="on"),
                target_is_default : ($("#target_is_default").val()==="on"),
            }

            $.post(
                'index.php?entryPoint=TeamsEntryPoint&userAction=updateProjectAllocationData',
                eventData,
                function (data) {
                    if(data){
                        if(typeof actionComplete === "function"){
                            actionComplete(data);
                        }
                    }
                },
                'json'
            )

        });
    });
    {/literal}
</script>

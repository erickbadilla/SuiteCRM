<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="custom/include/generic/javascript/select2/select2.min.js"></script>
<script type="text/javascript" src="custom/include/generic/javascript/toastr/toastr.min.js"></script>
<script type="text/javascript" src="custom/include/generic/javascript/careers/domElements.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.0/chart.min.js" integrity="sha512-qKyIokLnyh6oSnWsc5h21uwMAQtljqMZZT17CIMXuCQNIfFSFF4tJdMOaJHL9fQdJUANid6OB6DRR0zdHrbWAw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{literal}
<style>
    p.projectName span {
        font-size: 10pt;
        font-weight: 600;
    }
    p.projectName {
        font-size: 15pt;
        display: block;
        border-bottom: 1px solid #C0C0C0;
        padding: 5px;
    }
    .projectChart {
        padding: 10px;
    }
</style>
<div>
    <div class="col-lg-2 col-md-6 col-xs-6">
        <div class="form-group">
            <label for="activity_from">From Date</label><br/>
            <span class="dateTime">
                <div class="row">
                  <div class="col-md-9 col-xs-9">
                    <input class="date_input" autocomplete="off" type="text" name="activity_from" id="activity_from"
                           style="width: 100%; height: 34px" maxlength="10" value="{/literal}{$activity_from}{literal}"/>
                  </div>
                  <div class="col-md-3 col-xs-3">
                    <button type="button" id="activity_from_on_create_trigger" class="btn btn-danger" style="float: right"
                            onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                  </div>
                </div>
            </span>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-xs-6">
        <div class="form-group">
            <label for="activity_to">To Date</label><br/>
            <span class="select">
                <div class="row">
                  <div class="col-md-9 col-xs-9">
                    <input class="date_input" autocomplete="off" type="text" name="activity_to" id="activity_to"
                           style="width: 100%; height: 34px" maxlength="10" value="{/literal}{$activity_to}{literal}"/>
                  </div>
                  <div class="col-md-3 col-xs-3">
                    <button type="button" id="activity_to_on_create_trigger" class="btn btn-danger" style="float: right"
                            onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                  </div>
                </div>
            </span>
        </div>
    </div>
</div>
<div id="projectContainer" class="col-lg-12 col-md-12 col-xs-12"></div>
<script type="text/javascript">
    $(document).ready(function() {

        Calendar.setup ({
            inputField : "activity_from",
            ifFormat : "%Y-%m-%d",
            daFormat : "%Y-%m-%d",
            button : "activity_from_on_create_trigger",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
        });

        Calendar.setup ({
            inputField : "activity_to",
            ifFormat : "%Y-%m-%d",
            daFormat : "%Y-%m-%d",
            button : "activity_to_on_create_trigger",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
        });
    });
</script>
{/literal}
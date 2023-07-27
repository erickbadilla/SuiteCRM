{literal}
<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" media="screen" href="custom/include/generic/css/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="custom/include/generic/css/ui.jqgrid-bootstrap.css" />
<link href="modules/CT_Activity/css/custom.css" rel="stylesheet" type="text/css" />
<script src="custom/include/generic/javascript/trirand/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="custom/include/generic/javascript/trirand/jquery.jqGrid.js" type="text/javascript"></script>
<script src="custom/include/generic/javascript/trirand/jquery.sortable.js" type="text/javascript"></script>
<script src="custom/include/generic/javascript/trirand/jquery.fmatter.js" type="text/javascript"></script>
<script type="text/javascript" src="custom/include/generic/javascript/select2/select2.min.js"></script>
<script type="text/javascript" src="custom/include/generic/javascript/toastr/toastr.min.js"></script>
<script type="text/javascript" src="custom/include/generic/javascript/careers/domElements.js"></script>
    <style>
        .select2-container--default .select2-selection--single{
            height: 35px;
            border: 1px solid #000;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px;
            right: 5px;
        }
        .select2-container--default .select2-selection--single .select2-selection__clear {
            height: 35px;
            margin-right: 30px;
        }

        select.ui-widget-content {
            line-height: 16px;
        }

        select#gs_project_name {
            max-width: 180px;
        }

        .ui-widget-content {
            background: none;
            line-height: 16px;
        }

        .ui-widget-content .jqgroup {
            background-color: #fff !important;
            line-height: 25px;
            color: #333333FF;
            opacity: 1 !important;
        }

        .ui-jqgrid tr.jqgroup span {
            margin-top: 4px;
        }

        .ui-jqgrid-table-striped > tbody > tr:nth-of-type(odd) {
            opacity: .6;
            background-color: #ddddddad;
        }

        .ui-widget-content .jqfoot{
            background-color: #fff !important;
            opacity: 1 !important;
        }

        .ui-widget-content .jqfoot td span{
            color: #0c0c0c !important;
        }


        .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
            border: 1px solid #0f8f07;
            background-color: #3b8a3dd1 !important;
            color: #f8f8f8;
            background: none;
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
    <div class="col-lg-2 col-md-6 col-xs-6">
        <div class="form-group">
            <label for="search_action"></label><br/>
            <span class="dateTime">
                <div class="row">
                  <div class="col-md-2 col-xs-2" style="padding: 5px;">
                      <input id="search_action" type="button" name="Search" value="Search" class="button">
                  </div>
                </div>
            </span>
        </div>
    </div>
    <br>
</div>

{/literal}
<div id="mainActivityDiv" class="col-lg-12 col-xs-12">
    {$GRID}
</div>
{literal}
<script type="text/javascript">
    jQuery.fn.hideMobile = function(a) {
        // `this` is the jQuery Object on which the yourFunctionName method is called.
        // `arguments` will contain any arguments passed to the yourFunctionName method.
        var firstElement = this[0];
        console.log(a)
        return this; // Needed for other methods to be able to chain off of yourFunctionName.
    };

    $(document).ready(function(){
        $('#MassAssign_SecurityGroups').hide();
        $.jgrid.no_legacy_api = true;
        $.jgrid.useJSON = true;

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

        function updateDatesRange(){
            $.cookie('careers_activity_from', $("#activity_from").val());
            $.cookie('careers_activity_to', $("#activity_to").val());
        }

        updateDatesRange();

        $("#search_action").click(function(){
            updateDatesRange();
            $('#jqGrid')[0].triggerToolbar();
        });

        $(window).on("resize", function () {
            var $grid = $("#jqGrid"),
                newWidth = $grid.closest(".ui-jqgrid").parent().width();
            $grid.jqGrid("setGridWidth", newWidth, true);
        });

        $(window).on('popstate', handleLocationChanged);

        function handleLocationChanged(e){
            $("#alertmod_jqGrid").hide();
            window.removeEventListener('popstate', this, true);
        }

    });
</script>
{/literal}

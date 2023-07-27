<div>
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
</div>
<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Job_Applications/css/kanbanView.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/timepicker.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="custom/include/generic/javascript/timepicker/timepicker.min.js"></script>

<script type="text/javascript" src="custom/include/generic/javascript/toastr/toastr.min.js"></script>
<script type="text/javascript" src="custom/include/generic/javascript/select2/select2.min.js"></script>
<script type="text/javascript" src="custom/include/generic/javascript/datatables/jquery.dataTables.min.js"></script>


<div id="mainKanbanDiv">
    <div class="container-fluid">
        <div class="header-kanban__jobApplications">
            <div class="col-lg-4">
                <strong>Kanban Job Applications</strong>
                <div class="header-kanaban__hideClosed">
                    <input type='checkbox' id='hide-clesed-applications'>
                    <span>Hide closed applications</span>
                </div>
            </div>
            <div class="dataTables_filter"></div>

            <div class="col-lg-4 header-kanbab__applicationType">
                <strong>Type:</strong>
                <div id="application_type_list">
                    <select class="js-select-application-type-selector" >
                        <option value="EXTERNAL">External</option>
                        <option value="INTERNAL">Internal</option>
                    </select>
                </div>
            </div>

            <div id="viewKanbanJobApplications_filter" class="col-lg-4">
                <label>
                    Search:
                    <input id='jobApplicationsFilterInput' type="search" class="" placeholder=""
                           aria-controls="viewKanbanJobApplications">
                </label>
            </div>
        </div>
        <div class="row">
            <div id="kanbanJobApplications">
                <div class="kanbanContainer">
                    <div class="kanbanScroll">
                        {foreach from=$STAGES item=result name=stageStep}
                            <div class="kanban-col">
                                <div class="kanbanTitle">{$result->name}</div>
                                <div class="kanban-items" data-stageid="{$result->id}"></div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div id="mStageActivity" class="kanbanModal">
    <div class="kanbanModalBox">
        <!-- Modal content -->
        <div class="kanbanModalHeader">
            <span class="close">&times;</span>
        </div>
        <div class="kanbanModalBody">
            <div class="contentModal"></div>
        </div>
    </div>
</div>

{literal}
    <script type="text/template" data-template="accountDataItem">/*
        <div class="account_link"><a
                    href="index.php?module=Accounts&offset=1&return_module=Accounts&action=DetailView&record=${id}">${name}</a>
        </div>*/</script>
    <script type="text/template" data-template="cardApplicationItem">
        /*
        <div id="${applications_id}" class="container_card" data-closed="${closed_won}" data-actualstageid="${stage_id}"
             draggable="true">
            <div class="cardTitle"><a
                        href="index.php?module=CC_Candidate&offset=1&return_module=CC_Job_Applications&action=DetailView&record=${candidate_id}">${candidate_name}</a><a
                        href="index.php?module=CC_Job_Applications&offset=1&return_module=CC_Job_Applications&action=DetailView&record=${applications_id}"><i
                            class="glyphicon glyphicon-eye-open"></i></a></div>
            <div class="jOfferTitle"><a
                        href="index.php?module=CC_Job_Offer&offset=1&return_module=CC_Job_Applications&action=DetailView&record=${job_offer_id}">${job_offer_name}</a>
            </div>
            <div class="accountTitle">
                <a href="index.php?module=Accounts&offset=1&return_module=Accounts&action=DetailView&record=${account_id}">${account_name}</a>
                <a href="#" class="other_accounts_link"
                   data-application-id="${applications_id}">${accounts_total_str}</a>
                <div id="${applications_id}-accounts" class="accounts_related">
                    ${otherAddressString}
                </div>
            </div>
            <p class="${style} whiteBold">Rating: ${general_rating}</p>
        </div>
        */
    </script>
{/literal}
{literal}
<script>
    const accountRelatedTpl = $('script[data-template="accountDataItem"]').text().replace('/*', '').replace('*/', '').split(/\$\{(.+?)\}/g);
    const cardApplicationTpl = $('script[data-template="cardApplicationItem"]').text().replace('/*', '').replace('*/', '').split(/\$\{(.+?)\}/g);

    const stageMap = new Map();

    var modal = document.getElementById("mStageActivity");
    var btn = document.getElementById("myBtn");
    var span = document.getElementsByClassName("close")[0];
    var prevStageModal = null;
    var dispatchQueue = [];

    if($.cookie('CC_job-applications-type-selected')){
        $(".js-select-application-type-selector").val($.cookie('CC_job-applications-type-selected'))
    }else{
        $.cookie('CC_job-applications-type-selected', 'EXTERNAL');
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            moveBackCard(event);
            modal.style.display = "none";
        }
    }

    span.onclick = function (event) {
        moveBackCard(event);
        modal.style.display = "none";
    }

    {/literal}
    {foreach from=$STAGES item=result name=stageStep}
    stageMap.set('{$result->name}', '{$result->id}');
    {/foreach}

    const stageTypeMap = new Map();
    {foreach from=$STAGES item=result name=stageTypeStep}
    stageTypeMap.set('{$result->id}', '{$result->settings}');
    {/foreach}

    const stageOrderMap = new Map();
    {foreach from=$STAGES item=result name=stageOrderStep}
    stageOrderMap.set('{$result->id}', '{$result->stageorder}');
    {/foreach}
    {literal}
    
    $('document').ready(initView(stageMap, stageTypeMap, stageOrderMap));

    function getRatingStyle(value) {
        let rating_styles = ["Expired", "Warning", "Active", "Base"];
        let base_style_name = "ApplicationRating";
        let rating_result = Math.floor(value / (100 / rating_styles.length));
        if (rating_result >= rating_styles.length) {
            rating_result = rating_styles.length - 1;
        }
        return base_style_name + rating_styles[rating_result];
    }

    function createApplicationCard(props) {
        return function (tok, i) {
            return (i % 2) ? props[tok] : tok;
        };
    }

    function ApplicationModel(stageMap, stageTypeMap, stageOrderMap) {
        let self = this;
        this.stageMap = stageMap;
        this.stageTypeMap = stageTypeMap;
        this.stageOrderMap = stageOrderMap;
        // dataset
        this.baseData = [];
        this.data = [];
        //collection of observers
        this.observers = [];
        //add to the collection of observers
        this.registerObserver = function (observer) {
            self.observers.push(observer);
        }
        //Iterate over observers, calling their update method
        this.notifyAll = function () {

            self.observers.forEach(function (observer) {
                observer.update(self);
            });

            // CRM-483-job-application-dragging-a-card fix size
            let actualCols = $(".kanban-col").toArray();
            if(Array.isArray(actualCols) && actualCols.length>1){
                const cardHeight = 90;
                let colNumber = 0
                let lastCol = {};
                lastCol.col = null; lastCol.size = 0;
                actualCols.forEach(function(value) {
                    if(colNumber>=0){
                        $(value).css('min-height', lastCol.size*cardHeight+'px');
                        $(value).children(".kanban-items").css('min-height', lastCol.size*cardHeight+'px');
                    }
                    let columnChilden = $(value).children(".kanban-items")[0];
                    if($(columnChilden).children("div").length != 0){
                        lastCol.col = value;
                        lastCol.size = $(columnChilden).children("div").length;
                    }else{
                       lastCol.size = 1; 
                    }
                    colNumber++;
                });
            }
        }
        // set new dataset
        this.setData = function (data) {
            self.baseData = Object.assign({}, data);
            self.data = data;
            self.notifyAll();
        }
        // get actual data set
        this.getData = function () {
            return self.data;
        }
        // filter data
        this.applyFilter = function (filter) {
            // Clear Actual Values
            $(".kanban-items").html("");

            function filterData(filter) {
                    return function (value) {
                    let close = $("#hide-clesed-applications").prop('checked') ? 'Closed': '';

                    return(value.job_offer_name.toLocaleLowerCase().includes(filter) || value.candidate_name.toLocaleLowerCase().includes(filter) ||
                        value.account_name?.toLocaleLowerCase().includes(filter) || value.searchableAddressString?.toLocaleLowerCase().includes(filter)) && (value.stage != close);

                }
            }

            self.data = Object.assign({}, self.baseData);
            self.data.data = self.data.data.filter(filterData(filter));
            self.notifyAll();
        }

        this.getDragStartTemplate = function (applicationData) {
            $.post(
                'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getStageTemplate',
                applicationData,
                function (data) {
                    // Report any error from backend
                    dispatchQueue = [];
                    if (data.startsWith('Error')) {
                        toastr.error(data, 'Error');
                        return;
                    }

                    $('#mStageActivity div.contentModal').html(data);
                    $('div.actionCard div').removeClass("collapse").removeClass("container");
                    $('div.actionCard textarea').css('width', '95%');
                    modal.style.display = "block";
                },
                'html'
            );
        }

        this.updateApplicationStage = function (notecard, actualStageValue, newStageValue, force) {
            let stateData = null;
            for (const obj of self.baseData.data) {
                if (obj.applications_id === notecard) {
                    obj.target_stage_id = newStageValue;
                    obj.actual_stage_id = actualStageValue;
                    stateData = Object.assign({}, obj);
                    obj.stage_id = newStageValue;
                    break;
                }
            }

            if ((stateData !== null) && !force && !dispatchQueue.includes(notecard)) {
                dispatchQueue.push(notecard)
                self.getDragStartTemplate(stateData);
            }
        }

    }

    function createCard(element) {
        let container = $("[data-stageid='" + element.data.stage_id + "']");
        $(container).append(element.tpl);
    }

    function KanbanView(appModel) {
        let self = this;
        this.data = [];
        this.update = function (model) {
            let response = model.getData();
            self.data = response.data;
            let r = self.data.map(function (item) {
                item.general_rating = Math.round(item.general_rating * 100) / 100;
                item.style = getRatingStyle(item.general_rating);
                let otherAddressString = "";
                let searchableAddressString = "";
                if (parseInt(item.accounts_total) >= 2 && Array.isArray(item.other_accounts_json)) {
                    item["accounts_total_str"] = "[+]";
                    item.other_accounts_json.forEach(addElem => {
                        let currentAddressTpl = accountRelatedTpl.map(createApplicationCard(addElem)).join('');
                        otherAddressString = otherAddressString.concat(currentAddressTpl)
                        searchableAddressString = searchableAddressString.concat(addElem.name, " ");
                    });
                }
                item["otherAddressString"] = otherAddressString;
                item["searchableAddressString"] = searchableAddressString;
                return {data: item, tpl: cardApplicationTpl.map(createApplicationCard(item)).join('')};
            });
            r.forEach(element => createCard(element));
            initKanban(appModel);
        }
    }

    function initView(stageMap, stageTypeMap, stageOrderMap) {
        toastr.options = {"positionClass": "toast-bottom-right"}
        if (stageMap.size === 0 || stageTypeMap.size === 0) {
            toastr.error('Missing Job Application Stage', 'Error')
        }

        let appModel = new ApplicationModel(stageMap, stageTypeMap, stageOrderMap);
        let view = new KanbanView(appModel);

        $("#jobApplicationsFilterInput").keyup(function () {
            // Retrieve the input field text and reset the count to zero
            let filter = $(this).val().trim().toLocaleLowerCase();
            appModel.applyFilter(filter)
        });

        appModel.registerObserver(view)
        loadData(appModel);
    }

    function initKanban(appModel) {

        let kanbanItems = $('.kanban-items');

        $("a.other_accounts_link").mouseover(function (e) {
            $(e.target).next("div").show(300)
        });

        $("a.other_accounts_link").mouseout(function (e) {
            $(e.target).next("div").hide(300);
        });


        $('[draggable="true"]').bind('dragstart', function (event) {
            event.originalEvent.dataTransfer.setData("text/plain", event.target.getAttribute('id'));
        });

        // bind the dragover event on the board sections
        kanbanItems.bind('dragover', function (event) {
            event.preventDefault();
        });


        // bind the drop event on the board sections
        kanbanItems.bind('drop', function (event) {
            event.preventDefault();
            try {
                let notecard = event.originalEvent.dataTransfer.getData("text/plain");
                let element = document.getElementById(notecard);
                let actualStageValue = element.getAttribute('data-actualstageid');
                let newStageValue = this.getAttribute('data-stageid');
                let actualStage = stageOrderMap.get(actualStageValue);
                let targetStage = stageOrderMap.get(newStageValue);

                if (prevStageModal === null) {
                    prevStageModal = {
                        "element": element,
                        "notecard": notecard,
                        "actualStageValue": actualStageValue,
                        "newStageValue": newStageValue,
                        "actualStage": actualStage,
                        "targetStage": targetStage,
                        "model": appModel,
                        "target": this
                    }
                }

                if (parseInt(targetStage) >= parseInt(actualStage)) {
                    moveCard(this, notecard, element, newStageValue, actualStageValue, appModel, false);
                } else {
                    toastPreviousTargetError();
                }
            } catch (e) {
                toastr.error("Error, An unexpected error occurred during the action.", 'Error');
                console.log(e);
            }

        });
    }

    function moveCard(el, notecard, element, newStageValue, actualStageValue, appModel, force) {
        element.setAttribute('data-actualstageid', newStageValue);
        appModel.updateApplicationStage(notecard, actualStageValue, newStageValue, force);
        $(el).append(document.getElementById(notecard));
    }

    function moveBackCard(event) {
        if (prevStageModal != null) {
            event.preventDefault();
            let target = $('div.kanban-items[data-stageid="' + prevStageModal.actualStageValue + '"]').first();
            moveCard(
                target,
                prevStageModal.notecard,
                prevStageModal.element,
                prevStageModal.actualStageValue,
                prevStageModal.newStageValue,
                prevStageModal.model,
                true
            );
            prevStageModal = null;
        }
    }

    function getColumn(_data, _name, _searchable, _orderable, _searchValue, _searchRegex) {
        return {
            data: _data,
            name: _name,
            searchable: _searchable,
            orderable: _orderable,
            search: {
                value: _searchValue,
                regex: _searchRegex
            }
        }
    }

    function getBaseColumns() {
        let actualColumns = ['candidate_id', 'job_offer_id', 'applications_id',
            'account_id', 'account_name', 'candidate_name', 'job_offer_name', 'skill_rating'];
        let columns = [];
        actualColumns.forEach(function (item) {
            columns.push(getColumn(item, null, false, false, null, false));
        });
        return columns;
    }

    function actionComplete() {
        modal.style.display = "none";
        prevStageModal = null;
        toastr.success("Step Saved", 'Successful');
    }

    function loadData(model) {
        let columns = getBaseColumns();
        const application_type = $.cookie('CC_job-applications-type-selected');
        $.ajax({
            url: 'index.php?entryPoint=JobApplicationListViewEntryPoint',
            type: "POST",
            dataType: 'json',
            data: {
                action: "get",
                secondaryModule: 'CC_Candidate',
                application_type: application_type,
                columns: columns
            }
        }).done(function (data) {
            model.setData(data);
        });
    }

    function toastPreviousTargetError() {
        toastr.error("Error, Target stage should not be a previous stage", 'Error');
    }

    $("#hide-clesed-applications").click(function () {
        
        $(".kanbanTitle").each(function (e) {
            if ($(this).text().toLowerCase().match("close") && $("#hide-clesed-applications").prop('checked') == true) {
                $(this).parent().find("div.kanban-items").find('div').hide();
            } else {
                if($("#hide-clesed-applications").prop('checked') == false){
                    document.getElementById("jobApplicationsFilterInput").value = "";
                    initView(stageMap, stageTypeMap, stageOrderMap);
                }
                $(this).parent().find("div.kanban-items").find('div').show();
            }
        });
    });

    $(".js-select-application-type-selector").on('change',function() { 
        let type=$(".js-select-application-type-selector").val();
        $.cookie('CC_job-applications-type-selected', type);
        location.reload();
    });
    
</script>
{/literal}
<script type="text/javascript" src="modules/CC_Job_Applications/js/addInterviewerToSchedule.js"></script>
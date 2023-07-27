<link href="/custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Teams/css/kanbanView.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src='/custom/include/generic/javascript/select2/select2.min.js'></script>
<script type="text/javascript" src="custom/include/generic/javascript/toastr/toastr.min.js"></script>
{literal}
<div class="moduleTitle">
    <h2 class="module-title-text">&nbsp;Teams</h2>
    <div class="clear"></div>
</div>
<div id="mainKanbanDiv">
    <div class="container-fluid">
        <div class="header-kanban__teams">
            <div class="col-lg-3 col-md-12 col-xs-12">
                <div class="form-group">
                    <label for="managers">{/literal}{$TYPE_TITLE}{literal}</label><br/>
                    <span class="project-select">
                        <div class="row">
                          <div class="managers_select_container" style="width: 100%">
                            {/literal}
                            {html_options name="managers" id="managers" options=$MANAGERS_LIST}
                            {literal}
                          </div>
                        </div>
                    </span>
                </div>
            </div>
            <div class="col-lg-2 col-md-12 col-xs-12 header-kanbab__applicationType">
                <div class="form-group">
                    <label for="js-select-view-type-selector">View Type:</label><br/>
                    <div id="application_type_list">
                        {/literal}
                        {html_options name="js-select-view-type-selector" id="js-select-view-type-selector" options=$KANBAN_TYPES selected=$KANBAN_TYPES_SELECT}
                        {literal}
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-xs-12">
                <div class="form-group">
                    <label for="select_employee">Select Employee:</label><br/>
                    <div class="select_employee_select_container" style="width: 60%;float: left">
                    {/literal}
                      {html_options name="select_employee" id="select_employee" options=$EMPLOYEE_LIST}
                    {literal}
                    </div>
                    <div class="create_employee_card">
                        <input type='button' id='create_employee_card' value="Create Card">
                    </div>
                </div>
                <div id="newCardsSlot" style="width: 250px;"></div>
            </div>
            <div class="col-lg-2 col-md-12 col-xs-12">
                <div class="form-group">
                    <label for="employee_filter_input">Search:</label><br/>
                    <div class="search_container">
                        <input id='employee_filter_input' type="text" aria-controls="viewKanbanTeamProjects">
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div id="kanbanTeams">
                <div class="kanbanContainer">
                    <div class="kanbanScroll">
                        {/literal}
                        {foreach from=$MANAGERS item=result name=stageStep}
                            <div class="kanban-col" data-id="{$result->id}">
                                <div class="kanbanTitle">{$result->name}</div>
                                <div class="kanban-items" data-managerid="{$result->id}"></div>
                            </div>
                        {/foreach}
                        {literal}
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
<script type="text/template" data-template="cardEmployeeItem">
    /*
    <div id="${row_id}" class="container_card" data-actualmanagerid="${manager_id}" data-actualemployeeid="${employee_id}"  draggable="true">
        <div class="cardTitle">
            <a href="index.php?module=CC_Employee_Information&offset=1&return_module=CC_Teams&action=DetailView&record=${employee_id}">${employee_name}</a>
            <a href="#" onclick="removeManager('${row_id}');" alt="Remove manager relation"><i class="glyphicon glyphicon-remove"></i></a>
        </div>
        <div class="jobTitle">
            <a href="index.php?module=CC_Job_Description&offset=1&return_module=CC_Teams&action=DetailView&record=${cc_job_description_id}">${employee_position_name}</a>
        </div>
    </div>
    */
</script>
<script>
    const accountRelatedTpl = $('script[data-template="accountDataItem"]').text().replace('/*', '').replace('*/', '').split(/\$\{(.+?)\}/g);
    const cardEmployeeTpl = $('script[data-template="cardEmployeeItem"]').text().replace('/*', '').replace('*/', '').split(/\$\{(.+?)\}/g);

    const managerMap = new Map();

    var modal = document.getElementById("mStageActivity");
    var btn = document.getElementById("myBtn");
    var span = document.getElementsByClassName("close")[0];
    var prevStageModal = null;
    var dispatchQueue = [];
    var managerLST = null;
    var employeeLST = null;
    var selectedManagers = null;
    var selectedEmployees = null;
    var appModel = null;

    if($.cookie('CC_teams-type-selected')){
        $(".js-select-application-type-selector").val($.cookie('CC_teams-type-selected'))
    }else{
        $.cookie('CC_teams-type-selected', 'PROJECT');
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
    {foreach from=$MANAGERS item=result name=stageStep}
    managerMap.set('{$result->name}', '{$result->id}');
    {/foreach}
    {literal}

    const stageOrderMap = new Map();
    {/literal}
    {foreach from=$STAGES item=result name=stageOrderStep}
    stageOrderMap.set('{$result->id}', '{$result->stageorder}');
    {/foreach}
    {literal}
    
    $('document').ready(initView(managerMap, stageOrderMap));

    function createEmployeeCard(props) {
        return function (tok, i) {
            return (i % 2) ? props[tok] : tok;
        };
    }

    function ConfirmDialog(title, message,record,action ) {
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true,
                title: title,
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                    Yes: function() {
                        action(record);
                        $(this).dialog("close");
                    },
                    No: function() {
                        $(this).dialog("close");
                    }
                },
                close: function(event, ui) {
                    $(this).remove();
                }
            });
    }

    function removeManager(row_id){
        ConfirmDialog('Remove','Remove selected relation', row_id,()=>{
            $.ajax({
                url: 'index.php?entryPoint=TeamsEntryPoint&userAction=removeManagementSlot',
                type: "POST",
                dataType: 'json',
                data: {
                    row_id: row_id,
                }
            }).done(function (data) {
                if(data?.result===true){
                    toastr.success(data.message, 'Successful');
                } else {
                    toastr.error(data.message, 'Error');
                }
                loadData(appModel,true);
            });
        })
    }

    function AllocationModel(managerMap, stageOrderMap) {
        let self = this;
        this.managerMap = managerMap;
        // dataset
        this.baseData = [];
        this.data = [];
        //collection of observers
        this.observers = [];
        this.stageOrderMap = stageOrderMap;
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
                        $(value).css('min-height', (lastCol.size*cardHeight+cardHeight)+'px');
                        $(value).children(".kanban-items").css('min-height', (lastCol.size*cardHeight+cardHeight)+'px');
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
        this.setData = function (data, clearBefore) {
            if(clearBefore){
                $( ".container_card" ).remove();
                self.baseData = []; self.data = [];
            }
            self.baseData = [...data];
            self.data = Array.from(data);
            self.notifyAll();
        }

        this.removeData = function () {
            self.baseData = [];
            self.data = Array.from(data);
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
                    return ( value.employee_name.toLocaleLowerCase().includes(filter) );
                }
            }
            self.data = Array.from(self.baseData);
            self.data = self.data.filter(filterData(filter));
            self.notifyAll();
        }

        this.triggerManagementAction = function (applicationData,action) {
            $.post(
                'index.php?entryPoint=TeamsEntryPoint&userAction='+action,
                applicationData,
                function (data) {
                    if(data?.result===true){
                        toastr.success(data.message, 'Successful');
                    } else {
                        toastr.error(data.message, 'Error');
                    }
                    loadData(appModel,true);
                },
                'json'
            );
        }

        this.createAllocationCard = function (notecard, newManagerValue){

            let element = document.getElementById(notecard);
            let actualEmployeeValue = element.getAttribute('data-actualemployeeid')
            let stateData= {
                "employee_id":actualEmployeeValue,
                "target_manager_id":newManagerValue,
                "new_allocation":true
            }
            if (!dispatchQueue.includes(notecard) && newManagerValue!==null) {
                dispatchQueue.push(notecard)
                self.triggerManagementAction(stateData,'createManagementSlot');
            }
        }

        this.updateSelectedManager = function (notecard, actualManagerValue, newManagerValue, force) {
            let stateData = null;

            const selectedCard = (card)=>{
                return function (value){
                    return (card === value.row_id)
                }
            }

            let cardSelected = self.baseData.filter(selectedCard(notecard));
            let first = null;
            if(cardSelected.length>0){
                [first] = cardSelected;
                first.target_manager_id = newManagerValue;
                first.actual_manager_id = actualManagerValue;
                stateData = Object.assign({}, first);
                first.manager_id = newManagerValue;
            }
            if ((first !== null) && !force && !dispatchQueue.includes(notecard)) {
                dispatchQueue.push(notecard)
                self.triggerManagementAction(stateData,'updateManagementSlot');
            }
        }

    }

    function createCard(element) {
        let container = $("[data-managerid='" + element.data.manager_id + "']");
        $(container).append(element.tpl);
    }

    function KanbanView(appModel) {
        let self = this;
        this.data = [];
        this.update = function (model) {
            self.data = model.getData();
            let r = self.data.map(function (item) {
                return {data: item, tpl: cardEmployeeTpl.map(createEmployeeCard(item)).join('')};
            });
            r.forEach(element => createCard(element));
            initKanban(appModel);
        }
    }

    function createEmployeeDragCard(employee){
        $.ajax({
            url: 'index.php?entryPoint=TeamsEntryPoint&userAction=getEmployeeCard',
            type: "POST",
            dataType: 'json',
            data: employee
        }).done(function (data) {
            const template = cardEmployeeTpl.map(createEmployeeCard(data)).join('')
            let container = $("#newCardsSlot");
            $(container).append(template);
            $('#newCardsSlot div[draggable="true"]').bind('dragstart', function (event) {
                event.originalEvent.dataTransfer.setData("text/plain", event.target.getAttribute('id'));
            });
        });
    }

    function enableNewEmployeeCards(){
        employeeLST = document.getElementById('select_employee');
        selectedEmployees = $(employeeLST).select2();
        selectedEmployees.val(null).trigger('change');
        return selectedEmployees;
    }


    function enableManagerFilters(){
        managerLST = document.getElementById('managers');
        managerLST.removeAttribute('multiple');
        managerLST.setAttribute('multiple', true);

        selectedManagers = $(managerLST).select2();
        selectedManagers.val(null).trigger('change');
        selectedManagers.on('change', function() {
            let selectedValues = $(this).val();

            $('.kanban-col').show();
            if(selectedValues.length>0){
                $('.kanban-col').filter(function() {
                    var managerId = $(this).data('id');
                    return !selectedValues.includes(managerId);
                }).hide();
            }
        });

        return selectedManagers;
    }

    function initView(managerMap, stageOrderMap) {

        toastr.options = {"positionClass": "toast-bottom-right"}
        if (managerMap.size === 0 ) {
            toastr.error('Missing Job Application Stage', 'Error')
        }

        appModel = new AllocationModel(managerMap, stageOrderMap);
        let view = new KanbanView(appModel);
        let projects = enableManagerFilters();
        let employees = enableNewEmployeeCards();

        $("#employee_filter_input").keyup(function () {
            // Retrieve the input field text and reset the count to zero
            let filter = $(this).val().trim().toLocaleLowerCase();
            appModel.applyFilter(filter)
        });

        $("#create_employee_card").click(function (e){
            e.preventDefault();
            if(!employees.val() || employees.val()?.length===0){
                toastr.error("You must select an employee before create an allocation card.", 'Error');
                return false;
            }
            createEmployeeDragCard(employees);
        })


        appModel.registerObserver(view)
        loadData(appModel,false);
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

        setTimeout(()=>{$("#MassAssign_SecurityGroups").hide();},500);

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
                let actualManagerValue = element.getAttribute('data-actualmanagerid') || null;
                let actualEmployeeValue = element.getAttribute('data-actualemployeeid') || null;
                let newManagerValue = this.getAttribute('data-managerid');

                if (prevStageModal === null) {
                    prevStageModal = {
                        "element": element,
                        "notecard": notecard,
                        "actualManagerValue": actualManagerValue,
                        "actualEmployeeValue": actualEmployeeValue,
                        "newManagerValue": newManagerValue,
                        "model": appModel,
                        "target": this
                    }
                }

                moveCard(this, notecard, element, newManagerValue, actualManagerValue, appModel, false);

            } catch (e) {
                toastr.error("Error, An unexpected error occurred during the action.", 'Error');
                console.log(e);
            }

        });
    }

    function moveCard(el, notecard, element, newManagerValue, actualManagerValue, appModel, force) {
        element.setAttribute('data-actualmanagerid', newManagerValue);
        if(notecard.includes("newCard")){
            appModel.createAllocationCard(notecard,newManagerValue)
        }else{
            appModel.updateSelectedManager(notecard, actualManagerValue, newManagerValue, force);
        }
        $(el).append(document.getElementById(notecard));
    }

    function moveBackCard(event) {
        if (prevStageModal != null) {
            event.preventDefault();
            if(prevStageModal.notecard.includes("newCard")){
                let element = document.getElementById(prevStageModal.notecard);
                element.parentNode.removeChild(element);
                prevStageModal = null;
                return;
            }
            let target = $('div.kanban-items[data-managerid="' + prevStageModal.actualManagerValue + '"]').first();
            moveCard(
                target,
                prevStageModal.notecard,
                prevStageModal.element,
                prevStageModal.actualManagerValue,
                prevStageModal.newManagerValue,
                prevStageModal.model,
                true
            );
            prevStageModal = null;
        }
    }

    function actionComplete(data) {
        modal.style.display = "none";
        prevStageModal = null;
        if(data?.result){
            toastr.success(data.message, 'Successful');
        }else{
            toastr.error((data?.message)?data.message:"There was an error processing your request", 'Successful');
        }
        loadData(appModel,true);
    }

    function loadData(model,clearBefore) {
        $.ajax({
            url: 'index.php?entryPoint=TeamsEntryPoint',
            type: "POST",
            dataType: 'json',
            data: {
                action: {/literal}'{$TYPE_ACTION}'{literal},
            }
        }).done(function (data) {
            model.setData(data, clearBefore);
        });
    }

    function toastPreviousTargetError() {
        toastr.error("Error, Target stage should not be a previous stage", 'Error');
    }

    $("#js-select-view-type-selector").on('change',function() {
        let type=$("#js-select-view-type-selector").val();
        $.cookie('careers-teams-view-selected', type);
        location.reload();
    });
    
</script>
{/literal}
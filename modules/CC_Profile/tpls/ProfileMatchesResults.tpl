<link rel="stylesheet" href="modules/CC_Profile/css/profile-matches.css">
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.colVis.min.js"></script>
<div class="panel-content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a class="collapsed" role="button" data-toggle="collapse" href="#top-panel-profilematcher"
               aria-expanded="true">
                <div class="col-xs-10 col-sm-11 col-md-11">
                    PROFILE MATCHES
                </div>
            </a>
        </div>
    </div>
    <div class="panel-body panel-collapse panelContainer collapse" aria-expanded="true" id="top-panel-profilematcher">
        <div class="tabs">
            <nav class="tab-list">
                <a class="tab profileMatches active" href="#tab-employees">Employees</a>
                <a class="tab profileMatches " href="#tab-candidates">Candidates</a>
            </nav>
            <div id="tab-employees" class="tab-content matcher show">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <div style="margin-top: 10px;margin-bottom: 10px;">
                            <label>
                                <input type="checkbox" class="refreshAction" style="margin-right: 10px;"
                                       id="onlyActive" onclick="refreshEmployeeData()" checked="checked">Active Only
                            </label>
                            <label>
                                <input type="checkbox" class="refreshAction"
                                       style="margin-left: 15px;margin-right: 10px;" onclick="refreshEmployeeData()"
                                       id="onlyUnassigned">UnAssigned Only
                            </label>
                        </div>
                        <table id="dataTableEmployeesMatchingInformation" class="table table-bordered table-striped table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Country Law</th>
                                <th>Position</th>
                                <th>English Level</th>
                                <th>Profile Rating</th>
                                <th>Active</th>
                                <th>Is Assigned</th>
                                <th>Project Id</th>
                                <th>Project</th>
                                <th>Chart</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="tab-candidates" class="tab-content matcher">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table id="dataTableCandidatesMatchingInformation"
                               class="table table-bordered table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>&nbsp</th>
                                <th>Name</th>
                                <th>Rating</th>
                                <th>Country</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Chart</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODALS FOR COMPARISON-->
<div id="modal_Employee" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center;font-weight: 500;">Skills</h4>
            </div>
            <div id="modalBody" class="modal-body">
                <div id="skillEmployeeChart" style="position: relative; background-color: white;">
                    <canvas id="EmployeeChart"></canvas>
                </div>
                <table id="mainSkillEmployeeModalTableLabels" style="display:none;" cellpadding="0" cellspacing="0"
                       border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
                    <thead>
                    <tr style="font-weight:700; font-size: 13px">
                        <th class="footable-first-visible" style="display: table-cell;">&nbsp;</th>
                        <th style="display: table-cell;width: 20%">Skills</th>
                        <th style="display: table-cell;width: 40%">Required</th>
                        <th style="display: table-cell;width: 40%">Employee</th>
                    </tr>
                    </thead>
                </table>
                <table id="mainSkillEmployeeModalTable" style="display:none; max-height:340px; overflow:auto;"
                       cellpadding="0" cellspacing="0" border="0"
                       class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
                    <tbody id="skillEmployeeModalTable">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <input id="chartEmployeeButton" type="button" class="button" onclick="changeEmployeeSkillView()"
                       value="View on Table" style="float:left">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<div id="modal_Candidate" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center;font-weight: 500;">Skills</h4>
            </div>
            <div id="modalBody" class="modal-body">
                <div id="skillCandidateChart" style="position: relative; background-color: white;">
                    <canvas id="CandidateChart"></canvas>
                </div>
                <table id="mainSkillCandidateModalTableLabels" style="display:none;" cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
                    <thead>
                    <tr style="font-weight:700; font-size: 13px">
                        <th class="footable-first-visible" style="display: table-cell;">&nbsp;</th>
                        <th style="display: table-cell;width: 20%">Skills</th>
                        <th style="display: table-cell;width: 40%">Required</th>
                        <th style="display: table-cell;width: 40%">Candidate</th>
                    </tr>
                    </thead>
                </table>
                <table id="mainSkillCandidateModalTable" style="display:none; max-height:340px; overflow:auto;" cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
                    <tbody id="skillCandidateModalTable">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <input id="chartCandidateButton" type="button" class="button" onclick="changeCandidateSkillView()" value="View on Table" style="float:left">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
{literal}
<script type="text/javascript">
    const ctx = document.getElementById('EmployeeChart').getContext('2d'); // Points to the canvas element
    const candidateCtx = document.getElementById('CandidateChart').getContext('2d'); // Points to the canvas element
    var matchs; // response from the entry point
    var showEChart = false; // if true, the chart is showed
    var matchChart, results, isMobile;
    var maxRecords = {/literal}{$QUANTITYMATCH}{literal} // Quantity of records
    var offset = 0; // Start of pagination
    var last = maxRecords < 10 ? maxRecords : 10; // End of pagination
    var sortby = 'rating'; // Pagination order by
    var order = 'DESC'; // Pagination order
    var eprofileId = '{/literal}{$BEANID}{literal}';

    var modelEmployees = new matcherModel(eprofileId, 'Employee');
    var modelCandidates = new matcherModel(eprofileId, 'Candidate');

    const renderLink = (module, record, text) => {
        if (record !== null) {
            let url = `index.php?module=${module}&offset=1&return_module=CC_Profile&action=DetailView&record=${record}`;
            return `<a href="${url}" target="_self">${text}</a>`;
        }
        return `<div>&nbsp;</div>`;
    }

    const renderCheckbox = (row, item,type) => {
        let checked = (row[item]) ? "checked" : "";
        if(type==='export'){
            return (row[item]) ? "Yes" : "No";
        }
        return `<input name="checkbox_display_${item}_${row.id}" class="checkbox" type="checkbox" disabled ${checked} ></a>`;
    }

    const renderActionIcons = (row,module) => {
        return `<span class="chart-detail-view suitepicon suitepicon-action-view" data-record="${row.id}" data-module="${module}" data-target="#modal_Employee" data-toggle="modal" style="cursor: pointer;" onclick="createModal('${row.id}','${module}');"></span>`;
    }

    const renderCandidateActionIcons = (row,module) => {
        return `<span class="chart-detail-view suitepicon suitepicon-action-view" data-record="${row.id}" data-module="${module}" data-target="#modal_Candidate" data-toggle="modal" style="cursor: pointer;" onclick="createModal('${row.candidate_id}','${module}');"></span>`;
    }

    const activeOnly = () => {
        return document.getElementById('onlyActive').checked
    }

    const assignedOnly = () => {
        return document.getElementById('onlyUnassigned').checked
    }

    const tableAjaxSettings = (data, order, module) => {
        return {
            draw: data.draw,
            profileId: eprofileId,
            offset: data.start,
            limit: data.length,
            sortby: order.columnName,
            activeOnly: activeOnly(),
            assignedOnly: assignedOnly(),
            order: order.dir,
            childModule: module
        }
    }

    function dataTableEmployeesFormattingNumber(data,row,column,node, columnNumber){
        if (typeof data !== 'undefined') {
            if (data != null) {
                if (column === columnNumber) {
                    console.log(data);
                }
            }
        }
        return data;
    }

    function dataTableEmployeeSettings(){
        return {
            dom: 'Bfrtip',
            buttons: [{
                extend: 'copy',
                exportOptions: {
                    columns: [ 1,2,3,4,5,6,9 ],
                    orthogonal: 'export',
                }
            },{
                extend: 'pdf',
                orientation:'landscape',
                title: 'Cecropia / Multiplied CRM',
                pageSize:'A4',
                message: 'Profile Matching',
                pageMargins: [40, 60, 40, 40],
                exportOptions: {
                    columns: [ 1,2,3,4,5,6,9 ],
                    orthogonal: 'export',
                }
            }, {
                extend: 'csv',
                exportOptions: {
                    columns: [ 1,2,3,4,5,6,9 ],
                    orthogonal: 'export',
                }
            },{
                extend: 'excel',
                exportOptions: {
                    columns: [  1,2,3,4,5,6,9 ],
                    orthogonal: 'export',
                }
            },'pageLength'],
            columnDefs: [
                { visible: false, searchable: false, targets: 0 }, // id
                { width: "20%",  targets: 1 }, // name
                { width: "10%", targets: 2 }, // country_law
                { width: "15%", targets: 3 }, // position
                { width: "10%", targets: 4 }, // english_level
                { width: "15%", targets: 5 }, // matches
                { width: "5%", searchable: false, targets: 6 }, // active
                { width: "5%", searchable: false, targets: 7 }, // is_assigned
                { visible: false, searchable: false, targets: 8 }, // project_id
                { width: "20%", targets: 9 },  // project_name
                { width: "5%", targets: 9 },  // chart
            ],
            columns: [
                {data: "id"},
                {
                    data: "name",
                    render: function (data, type, row) {
                        return renderLink('CC_Employee_Information', row.id, row.name);
                    },
                },
                {data: "country_law"},
                {data: "position"},
                {data: "english_level" },
                {
                    data: "matches",
                    render: function (data, type, row) {
                        let elementId = row.id + "_rating";
                        let startAmount = (parseFloat((data) ? data : 0).toFixed(2)) / 10;
                        if(type === 'display') {
                            return `<div id="${elementId}" class="SkillRatingSelector" data-rating="${startAmount}"></div>`
                        }
                        return Math.round(startAmount*10)+"%";
                    }
                },
                {
                    data: "active", className: "dt-center",
                    render: function (data, type, row) {
                        return renderCheckbox(row, 'active', type);
                    }
                },
                {
                    data: "is_assigned", className: "dt-center",
                    render: function (data, type, row) {
                        return renderCheckbox(row, 'is_assigned',type);
                    }
                },
                {data: "project_id"},
                {
                    data: "project_name",
                    render: function (data, type, row) {
                        return renderLink('CC_Employee_Information', row.project_id, row.project_name);
                    }
                },
                {
                    data: "view_action", searchable: false, targets: 'no-sort', orderable: false,
                    render: function (data, type, row) {
                        return renderActionIcons(row,'Employee')
                    }
                }
            ],
            processing: true,
            serverSide: true,
            lengthMenu: [[10, 25, 50, 100, 10000], [10, 25, 50, 100, "All"]],
            pageLength: 10,
            filter: true,
            autoWidth: false,
            activeOnly: activeOnly(),
            assignedOnly: assignedOnly(),
            ajax: function (data, callback) {
                let orderArray = (Array.isArray(data.order) && data.order.length > 0) ? data.order[0] : {
                    column: 1,
                    dir: "asc"
                };
                orderArray.columnName = data.columns[orderArray.column].data
                modelEmployees.getMatches(tableAjaxSettings(data, orderArray, 'Employee'), callback);
            },
            initComplete: (settings, json) => {
            },
            drawCallback: (settings, json) => {
                $('.SkillRatingSelector').starRating({
                    starSize: 15,
                    totalStars: 10,
                    readOnly: true,
                    disableAfterRate: true
                });
            }
        }
    };


    function dataTableCandidateSettings() {
        return {
            "dom": 'Bfrtip',
            "buttons": [{
                extend: 'copy',
                exportOptions: {
                    columns: [ 1,2,3,4,5 ],
                    orthogonal: 'export'
                }
            },{
                extend: 'pdf',
                orientation:'landscape',
                title: 'Cecropia / Multiplied CRM',
                pageSize:'A4',
                message: 'Profile Matching',
                pageMargins: [40, 60, 40, 40],
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                    orthogonal: 'export'
                }
            },{
                extend: 'csv',
                exportOptions: {
                    columns: [1,2,3,4,5],
                    orthogonal: 'export'
                }
            },{
                extend: 'excel',
                exportOptions: {
                    columns: [ 1,2,3,4,5 ],
                    orthogonal: 'export'
                }
            },'pageLength'],
            "columns": [
                {data: "candidate_id", visible: false},
                {
                    data: "candidate_name", width: "25%",
                    render: function (data, type, row) {
                        return renderLink('CC_Candidate', row.candidate_id, row.candidate_name);
                    },
                },
                {
                    data: "matches", width: "25%",
                    render: function (data, type, row) {
                        let elementId = row.id + "_rating";
                        let startAmount = (parseFloat((data) ? data : 0).toFixed(2)) / 10;
                        if(type === 'display') {
                            return `<div id="${elementId}" class="SkillRatingSelector" data-rating="${startAmount}"></div>`
                        }
                        return Math.round(startAmount*10)+"%";
                    }
                },
                {data: "country", width: "10%"},
                {data: "email", width: "10%"},
                {data: "phone", width: "10%",
                    render: function (data, type, row) {
                        if(type === 'export'){
                            return " "+data;
                        } else return data;
                    }
                },
                {
                    data: "view_action", width: "5%", searchable: false, targets: 'no-sort', orderable: false,
                    render: function (data, type, row) {
                        return renderCandidateActionIcons(row, 'Candidate')
                    }
                }
            ],
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": 10,
            "filter": true,
            "activeOnly": activeOnly(),
            "assignedOnly": assignedOnly(),
            "ajax": function (data, callback) {
                let orderArray = (Array.isArray(data.order) && data.order.length > 0) ? data.order[0] : {
                    column: 1,
                    dir: "asc"
                };
                orderArray.columnName = data.columns[orderArray.column].data
                modelCandidates.getMatches(tableAjaxSettings(data, orderArray, 'Candidate'), callback);
            },
            "initComplete": (settings, json) => {
            },
            "drawCallback": (settings, json) => {
                $('.SkillRatingSelector').starRating({
                    starSize: 15,
                    totalStars: 10,
                    readOnly: true,
                    disableAfterRate: true
                });
            }
        }
    };

    var tableEmployees = $('#dataTableEmployeesMatchingInformation').DataTable(dataTableEmployeeSettings());
    var tableCandidates = $('#dataTableCandidatesMatchingInformation').DataTable(dataTableCandidateSettings());

    function isActiveTab(tabHref) {
        let tabItem = $('a.tab.profileMatches.active');
        if(tabItem.length>0){
            return (tabItem[0].href.includes(tabHref));
        }
        return false;
    }

    function refreshEmployeeData() {
        if(isActiveTab('#tab-employees')){
            tableEmployees?.ajax?.reload();
        }
    };

    function refreshCandidateData() {
        if(isActiveTab('#tab-candidates')) {
            tableCandidates?.ajax?.reload();
        }
    };

    $(document).ready(function () {
        if (window.innerWidth > 460) {
            isMobile = false;
        } else {
            isMobile = true;
        }

        $(".tab-list").on("click", ".tab", function(event) {
            event.preventDefault();

            $(".tab").removeClass("active");
            $(".tab-content.matcher").removeClass("show");

            $(this).addClass("active");
            if(this.href === '#tab-employees'){
                refreshEmployeeData();
            }else{
                refreshCandidateData();
            }

            $($(this).attr('href')).addClass("show");
        });

        $('#whole_subpanel_cc_profile_cc_skill').hide();
        $('#whole_subpanel_cc_profile_cc_qualification').hide();

        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        screenEmployeeWidthChange();
    });

    /* CREATION OF TABLES */
    /**
     * Call the entrypoint for matching the employee with the profile
     */
    function matchEmployeeResult(employeeId) {
        clearTables('skillEmployeeModalTable');
        clearTables('skillCandidateModalTable');
        showEChart = true;
        changeEmployeeSkillView();
        $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=profileMatchEntryPoint',
            dataType: 'json',
            data: {
                secondaryModule: 'CC_Employee_Information',
                secondaryId: employeeId,
                profileId: eprofileId
            },
            success: function (resp) {
                if (matchChart) {
                    matchChart.destroy();
                }
                createModalSkillChart(resp.Skills);
                createModalSkillTable(resp.Skills);
            }
        });
    }

    function createModal(element, type) {
        if(type=='Employee'){
            matchEmployeeResult(element);
        } else {
            matchCandidateResult(element);
        }
    }


    /**
     * Create the skill chart
     * @param employeeId
     */
    function createModalSkillChart(skills) {
        let labelsChart = [];
        let profileChartSkills = [];
        let moduleChartSkills = [];

        skills.forEach(skill => {
            if (skill.ProfileAmount>=0 && skill.moduleAmount>=0) {
                labelsChart.push(skill.Name);
                let profileAmount = (skill.ProfileAmount) * 10;
                let moduleAmount = (skill.moduleAmount) * 10;
                if(skill.ProfileRelation==='rating' || skill.moduleType==='rating'){
                    profileAmount = (skill.ProfileAmount / 5) * 100;
                    moduleAmount = (skill.moduleAmount / 5) * 100;
                }
                profileChartSkills.push(profileAmount);
                moduleChartSkills.push(moduleAmount);
            }
        });

        generateChart(labelsChart, profileChartSkills, moduleChartSkills)

    };

    /**
     * Create the skill table
     * @param skills
     */
    function createModalSkillTable(skills) {
        skills.forEach(skill => {
            if (skill.ProfileRelation === 'rating' || skill.moduleType === 'rating') {
                let td;
                let base = Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
                let profileId = 'modalEP' + base + '-' + skill.Id;
                let moduleId = 'modalES' + base + '-' + skill.Id;
                let element = document.getElementById("skillEmployeeModalTable");
                let tr = document.createElement("tr");

                tr.appendChild(createColumn("\u00A0"));
                td = createColumn(skill.Name);
                td.style.width = "20%";
                tr.appendChild(td);

                td = createField(skill.ProfileAmount,
                    profileId);
                td.style.width = "40%";
                tr.appendChild(td);

                td = createField(skill.moduleAmount,
                    moduleId, true);
                td.style.width = "40%";
                tr.appendChild(td);

                element.appendChild(tr);
                starsField(profileId, 5);
                starsField(moduleId, 5);
            }
        })
    }

    /**
     * Generates the chart
     * @param labels
     * @param profileSkills
     * @param moduleSkills
     */
    function generateChart(labels, profileSkills, moduleSkills) {
        matchChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    data: moduleSkills,
                    label: 'Employee',
                    backgroundColor: 'rgba(59, 97, 238, 0.5)',
                    borderColor: 'rgba(59, 97, 238, 0.8)',
                },
                    {
                        data: profileSkills,
                        label: 'Profile',
                        backgroundColor: 'rgba(255, 99, 71, 0.3)',
                        borderColor: 'rgba(255, 99, 71, 0.8)',
                    }
                ]
            },
            options: {
                maintainAspectRatio: true,
                scale: {
                    ticks: {
                        suggestedMin: 0,
                        suggestedMax: 100,
                    }
                }
            }
        });
    }

    /**
     * Change the view between table and chart
     */
    function changeEmployeeSkillView() {
        let skillsTable = document.getElementById("mainSkillEmployeeModalTable");
        let skillsTableLabels = document.getElementById("mainSkillEmployeeModalTableLabels");
        let skillsChart = document.getElementById("skillEmployeeChart");
        let changeButton = document.getElementById("chartEmployeeButton");
        if (showEChart) {
            showEChart = false;
            skillsChart.style.display = "block";
            skillsTable.style.display = "none";
            skillsTableLabels.style.display = "none";
            changeButton.value = "View on Table";
        } else {
            showEChart = true;
            skillsChart.style.display = "none";
            skillsTable.style.display = "block";
            skillsTableLabels.style.display = "block";
            changeButton.value = "View Chart";
        }
    }

    /**
     * Create the field depending on the requirements
     * @param amount amount of the skill/qualification
     * @param id id used for the starfield
     * @param isModal Establish differences so the field
     * is created with 5 or 10 stars
     */
    function createRatingField(amount, id = "", isModal = false) {
        let field;
        let elementExists = document.getElementById(id);
        if (elementExists === null) {
            let startAmount = parseFloat((amount) ? amount : 0).toFixed(1);
            if (window.innerWidth > 460) {
                field = createStarfield(id, startAmount).innerHTML;
            } else {
                field = startAmount + '/10';
            }
            return field;
        }
        return elementExists.innerHTML;
    }

    /**
     * Check changes on the screen width
     */
    function screenEmployeeWidthChange() {
        window.onresize = displayEmployeeWindowSize;
    }

    /**
     * Change skill table depending on width
     */
    function displayEmployeeWindowSize() {
        if (window.innerWidth < 460 && !isMobile) {
            isMobile = true;
            clearTables('skillsEmployeeTable');
            createMatchTable(results);
        } else if (window.innerWidth > 460 && isMobile) {
            isMobile = false;
            clearTables('skillsEmployeeTable');
            createMatchTable(results);
        }
    }

    /**
     * Call the entrypoint for matching the employee with the profile
     */
    function matchCandidateResult(secondaryId) {
        clearTables('skillCandidateModalTable');
        clearTables('skillEmployeeModalTable');
        showCChart = true;
        changeCandidateSkillView();
        $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=profileMatchEntryPoint',
            dataType: 'json',
            data: {secondaryModule:'CC_Candidate', secondaryId: secondaryId, profileId: eprofileId },
            success: function(resp) {
                if(matchChart){
                    matchChart.destroy();
                }
                createCMSkillChart(resp.Skills);
                createCMSkillTable(resp.Skills);
            }
        });
    }


    /**
     * Create the skill chart
     * @param skills
     */
    function createCMSkillChart(skills){
        let labelsChart = [];
        let profileChartSkills = [];
        let moduleChartSkills = [];

        skills.forEach(skill => {
            if (skill.ProfileAmount>=0 && skill.moduleAmount>=0) {
                labelsChart.push(skill.Name);
                let profileAmount = (skill.ProfileAmount) * 10;
                let moduleAmount = (skill.moduleAmount) * 10;
                if(skill.ProfileRelation==='rating' || skill.moduleType==='rating'){
                    profileAmount = (skill.ProfileAmount / 5) * 100;
                    moduleAmount = (skill.moduleAmount / 5) * 100;
                }
                profileChartSkills.push(profileAmount);
                moduleChartSkills.push(moduleAmount);
            }
        });

        generateCChart(labelsChart,profileChartSkills,moduleChartSkills)

    };

    /**
     * Create the skill table
     * @param skills
     */
    function createCMSkillTable(skills){
        skills.forEach(skill => {
            if(skill.ProfileRelation === 'rating' || skill.moduleType === 'rating'){
                let td;
                let base = Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
                let profileId = 'modalP'+ base + '-' + skill.Id ;
                let moduleId = 'modalS'+ base + '-' + skill.Id;
                let element = document.getElementById("skillCandidateModalTable");
                let tr = document.createElement("tr");

                tr.appendChild(createCandidateColumn("\u00A0"));
                td = createCandidateColumn(skill.Name);
                td.style.width = "20%";
                tr.appendChild(td);

                td = createField(skill.ProfileAmount, profileId);
                td.style.width = "40%";
                tr.appendChild(td);

                td = createField(skill.moduleAmount, moduleId, true);
                td.style.width = "40%";
                tr.appendChild(td);

                element.appendChild(tr);
                starsField(profileId,5);
                starsField(moduleId,5);
            }
        })
    }

    /**
     * Generates the chart
     * @param labels
     * @param profileSkills
     * @param moduleSkills
     */
    function generateCChart(labels, profileSkills, moduleSkills){
        matchCandidateChart = new Chart(candidateCtx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    data: moduleSkills,
                    label: 'Candidate',
                    backgroundColor: 'rgba(59, 97, 238, 0.5)',
                    borderColor: 'rgba(59, 97, 238, 0.8)',
                },
                    {
                        data: profileSkills,
                        label: 'Profile',
                        backgroundColor: 'rgba(255, 99, 71, 0.3)',
                        borderColor: 'rgba(255, 99, 71, 0.8)',
                    }
                ]
            },
            options: {
                maintainAspectRatio: true,
                scale: {
                    ticks: {
                        suggestedMin: 0,
                        suggestedMax: 100,
                    }
                }
            }
        });
    }

    /**
     * Change the view between table and chart
     */
    function changeCandidateSkillView(){
        let skillsTable = document.getElementById("mainSkillCandidateModalTable");
        let skillsTableLabels = document.getElementById("mainSkillCandidateModalTableLabels");
        let skillsChart = document.getElementById("skillCandidateChart");
        let changeButton = document.getElementById("chartCandidateButton");
        if(showCChart){
            showCChart = false;
            skillsChart.style.display = "block";
            skillsTable.style.display = "none";
            skillsTableLabels.style.display = "none";
            changeButton.value = "View on Table";
        } else {
            showCChart = true;
            skillsChart.style.display = "none";
            skillsTable.style.display = "block";
            skillsTableLabels.style.display = "block";
            changeButton.value = "View Chart";
        }

    }


    /**
     * Creates a regular column
     * @param text information to be presented on the column
     * @param align alignment of the column
     * @param candidateId Id of the candidate to create the link to the
     * detail view.
     */
    function createCandidateColumn(text, align = "left", candidateId = null){
        let fieldtext;
        let td = document.createElement("td");
        td.style.display = "table-cell";

        if(!candidateId){
            if (text === null){
                fieldtext = document.createTextNode("\u00A0");
            } else {
                fieldtext = document.createTextNode(text);
            }
        } else {
            fieldtext = document.createElement("a");
            let link = "?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DCC_Candidate%26offset%3D1%26stamp%3D1613768547006033300%26return_module%3DCC_Candidate%26action%3DDetailView%26record%3D";
            fieldtext.setAttribute("href",link+candidateId);
            fieldtext.innerHTML = text;
        }

        td.appendChild(fieldtext);
        td.style.textAlign = align;

        return td;
    }

    /**
     * Create the field depending on the requirements
     * @param amount amount of the skill/qualification
     * @param id id used for the starfield
     * @param isModal Establish differences so the field
     * is created with 5 or 10 stars
     */
    function createCandidateField(amount, id = "", isModal = false){
        let field;
        let startAmount = parseFloat((amount) ? amount : 0).toFixed(1);

        if(window.innerWidth > 460) {
            field = createStarfield(id, startAmount);
        } else {
            field = createCandidateColumn( startAmount + '/10', "center");
        }

        return field;
    }

    /**
     * Create the stars field
     * @param id id of the field
     * @param amount amount of the skill
     * secondary modules for the fields to be created
     */
    function createCandidateStarfield(id, amount){
        let ratingdiv = document.createElement("div");
        let td = document.createElement("td");

        td.style.display = "table-cell";
        //ratingdiv.setAttribute("id", "SkillRatingExpSelectorArea");
        ratingdiv.style.width = "100%";
        ratingdiv.style.float = "left";

        let stardiv = document.createElement("div");
        stardiv.style.float = "left";
        stardiv.setAttribute("id", "candidate"+id);
        stardiv.setAttribute("data-rating", amount);
        ratingdiv.appendChild(stardiv);
        td.appendChild(ratingdiv)
        console.log(stardiv);
        return td
    }

    /**
     * Check changes on the screen width
     */
    function screenCandidateWidthChange(){
        window.onresize = displayCandidateWindowSize;
    }

    /**
     * Change skill table depending on width
     */
    function displayCandidateWindowSize(){
        if(window.innerWidth < 460 && !isCandidateMobile) {
            isCandidateMobile = true;
            clearTables('skillsCandidateTable');
            createCandidateMatchTable(candidateResults);
        } else if(window.innerWidth > 460 && isCandidateMobile) {
            isCandidateMobile = false;
            clearTables('skillsCandidateTable');
            createCandidateMatchTable(candidateResults);
        }
    }


</script>
{/literal}


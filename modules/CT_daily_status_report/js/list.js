function createActionColumn(props) {
    return function (tok, i) {
        return (i % 2) ? props[tok] : tok;
    };
}

$(document).ready(function () {
    toastr.options = {
        "positionClass": "toast-bottom-right",
    }

    $('#MassAssign_SecurityGroups').hide();

    const isValidDate = (dateString) => {
        const regEx = /^\d{4}-\d{2}-\d{2}$/;
        if (!dateString.match(regEx)) return false;  // Invalid format
        const d = new Date(dateString);
        const dNum = d.getTime();
        if (!dNum && dNum !== 0) return false; // NaN value, Invalid date
        return d.toISOString().slice(0, 10) === dateString;
    }

    const validateInput = (e) => {
        if (!isValidDate($(e).val())) {
            $(e).css('border', 'solid 2px red');
            $('#search_action').prop('disabled', true)
            return false;
        } else {
            $(e).css('border', '1px solid #090a0a');
            $('#search_action').prop('disabled', false)
            return true;
        }
    }
    const validateIt = () => {
        setTimeout(function () {
            $('.date_input').each(function () {
                let result = validateInput("#" + this.id);
                if (!result) {
                    return false;
                }
            });
        }, 100);
    }

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

    const inputElementFrom = document.getElementById('activity_from');
    const inputElementTo = document.getElementById('activity_to');
    YAHOO.util.Event._addListener(inputElementFrom, "change", validateIt);
    YAHOO.util.Event._addListener(inputElementFrom, "change", validateIt);

    $('.date_input').keydown(function (e) {
        if (e.keyCode >= 37 && e.keyCode <= 40 ||
            e.keyCode >= 46 && e.keyCode <= 57 || e.keyCode == 189 || e.keyCode == 8 || e.keyCode == 9) {
            return true;
        } else {
            return false;
        }
    });

    function updateDatesRange() {
        $.cookie('daily_status_date_from', $("#activity_from").val());
        $.cookie('daily_status_date_to', $("#activity_to").val());
        $.cookie('daily_status_related', $("#selected_project").val());
    }

    $('.date_input').focusout(function() {
        validateInput("#"+this.id)
    }).trigger("focusout");

    updateDatesRange();

    const renderLink = (module, record, text) => {
        if (record !== null) {
            let url = `index.php?module=${module}&offset=1&return_module=CC_Profile&action=DetailView&record=${record}`;
            return `<a href="${url}" target="_self">${text}</a>`;
        }
        return `<div>&nbsp;</div>`;
    }

    const renderSchedule = (text) => {
        let label = text;
        if(label==='ontime'){
            label = 'on time'
        }
        return `<div class="schedule-${text}">${label}</div>`;
    }

    const datatableDailyStatusReport = $("#dataTableDailyStatusReport").DataTable({
        "responsive": true,
        "ajax": {
            "url": 'index.php?entryPoint=CTDailyStatusReportEntryPoint',
            "type": "POST",
            'data': function (d) {
                d.action = "get";
            }
        },
        "paging": true,
        "order": [[0, "desc"]],
        "info": true,
        "filter": true,
        "columns": [
            {orderable: false, searchable: false, data: "id", targets: 0, name: '0'},
            {orderable: false, searchable: false, data: "employee_id", targets: 1, name: 'cei.id'},
            {
                orderable: true, searchable: true, data: "employee_name", targets: 2, name: '2',
                render: function (data, type, row) {
                    return renderLink('CC_Employee_Information', row.employee_id, row.employee_name);
                },
            },
            {orderable: false, searchable: false, data: "project_id" , targets: 3 , name: 'sr.project_id_c'},
            {
                orderable: true, searchable: true, data: "project_name", targets: 4, name: '4',
                render: function (data, type, row) {
                    return renderLink('Project', row.project_id, row.project_name);
                },
            },

            {orderable: true, searchable: false, width: "6%", data: "date_reported", targets: 5, name: 'sr.date_reported'},
            {orderable: false, searchable: true, width: "20%", data: "yesterday", name:"sr.description" },
            {orderable: false, searchable: true, width: "20%", data: "today", name:"sr.description" },
            {orderable: false, searchable: true, width: "10%", data: "reason", name:"sr.description" },
            {orderable: true, searchable: false, width: "6%", data: "eta", name:"sr.eta" },
            {orderable: true, searchable: false, data: "last_blockers", targets: 8, name: '8'},
            {
                orderable: true, searchable: true, data: "schedule", targets: 9, name: 'sr.schedule',
                render: function (data, type, row) {
                    return renderSchedule(row.schedule);
                },
            },
            {orderable: true, searchable: false, data: "last_schedule", name:"last_schedule", targets: 10, name: '10'},
            {orderable: true, searchable: true, data: "mood", targets: 11, name: 'sr.mood'},
        ],
        "columnDefs": [
            { targets: 0, visible: false, searchable: false },
            { targets: 1, visible: false, searchable: false },
            { targets: 3, visible: false, searchable: false },
        ],
        "drawCallback": function (settings, json) {
        },
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "pageLength": 25,
        "displayLength": 25,
    });

    $("#search_action").click(function(){
        updateDatesRange();
        window.location = 'index.php?module=CT_daily_status_report&action=index&parentTab=All';
    });

});

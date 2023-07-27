<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/colReorder.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/jquery.contextMenu.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/listView.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Employee_Information/css/edit.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />

<div>
<label>
 <input type="checkbox" style="margin-left: 15px;" id="hideInactive" onchange="recalculeData()"> Hide/Show Inactives
</label>

<label>
 <input type="checkbox" style="margin-left: 15px;" id="hideUnassigned" onchange="recalculeData()"> Hide/Show Unassigned
</label>

</div>

<div id="mainSkillsDiv" class="col-lg-6 col-xs-12 detail-view-row-item">
  <div class="card">
    <div class="card-header">
      <h3>List of Employees</h3>
    </div>
    <div style="text-align:center">
        <span title="hide/show Columns" style="font-weight:bold">Toggle column: </span><a class="toggle-vis" data-column="1">Name</a> - <a class="toggle-vis" data-column="2">Country Law</a> - <a class="toggle-vis" data-column="3">Status</a> - <a class="toggle-vis" data-column="4">Position</a> - <a class="toggle-vis" data-column="5">Role</a> - <a class="toggle-vis" data-column="6">English Level</a> - <a class="toggle-vis" data-column="7">Project</a> - <a class="toggle-vis" data-column="8">Has Passport</a> - <a class="toggle-vis" data-column="9">Active</a> - <a class="toggle-vis" data-column="10">Has Visa</a> - <a class="toggle-vis" data-column="11">Is Assigned</a>
    </div>
    
    <div class="card-body">
    <div class="table-responsive" >
      <table id="dataTableEmployeesInformation" class="table table-bordered table-striped table-hover" style="width:100%">
        <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Name</th>
          <th class="groupy" id="country_law">Country Law</th>
          <th>Status</th>
          <th class="groupy" id="position">Position</th>
          <th class="groupy" id="role">Role</th>
          <th>English Level</th>
          <th class="groupy" id="project">Project</th>
          <th>Has Passport</th>
          <th>Active</th>
          <th>Has Visa</th>
          <th>Is Assigned</th>
        </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    </div>
  </div>
</div>


{literal}

<script src='custom/include/generic/javascript/datatables/jquery.dataTables.min.js'></script>
<script src='custom/include/generic/javascript/datatables/dataTables.colReorder.min.js'></script>
<script src='custom/include/generic/javascript/jquery.contextMenu/jquery.contextMenu.js'></script>


<script src="custom/include/generic/javascript/buttons/dataTables.buttons.min.js"></script>
<script src="custom/include/generic/javascript/buttons/buttons.html5.min.js"></script>
<script src="custom/include/generic/javascript/buttons/buttons.print.min.js"></script>
<script src="custom/include/generic/javascript/buttons/jszip.min.js"></script>
<script src="custom/include/generic/javascript/buttons/pdfmake.min.js"></script>
<script src="custom/include/generic/javascript/buttons/vfs_fonts.js"></script>

<script>

$("#massassign_form").hide();

var filterColum = [];
var groupColumn = 0;
var tdIdentifier = 0;
var flag = true;
let tablaEmployeesInformation = ""; 
  $(function() {
  
  if(tablaEmployeesInformation != ""){
    tablaEmployeesInformation.destroy();
  }
  
  tablaEmployeesInformation = $("#dataTableEmployeesInformation").DataTable({

      dom: 'Blfrtip',
      buttons: [
          'excel'
      ],
     "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=EmployeeInformationEntryPoint',
        "type": "POST",
        'data' : function(d) {
               d.action = "GetEmployeesInformation";
               d.filterColumData = getFilterColumn();
            }
     },
     "paging" : true,
     columnDefs: [{ visible: false, targets: groupColumn }],
     "order": [[ groupColumn, "asc" ]],
     "info" : true,
     "filter" : true,
     
      "columnDefs": [
        {
             "targets": [0],
             "sortable": false, 
              "data": "id", 
             "className": "td_delete_profile",
             "render": function(data, type, row){
              let url = `index.php?module=${row.object_name}&offset=1&return_module=${row.object_name}&action=EditView&record=${row.id}`;
                return `<input type=hidden value=${row.id} ><a href="${url}" target="_self" class="edit-link"><span class="suitepicon suitepicon-action-edit"></span></a>`;
             }
        },
        {
            "targets": [1], 
            "data": "id", 
            "className": "",
            "render": function(data, type, row){
              let url = `index.php?module=${row.object_name}&offset=1&return_module=${row.object_name}&action=DetailView&record=${row.id}`;
                return `<a href="${url}" target="_self">${row.name}</a>`;
            }
          },
          {
            "targets": [2], 
            "data": "country_law", 
            "className": "",
            "render": function(data, type, row){
              
                return `<div>${row.country_law}</div>`;
            }
          },
          {
            "targets": [3], 
            "data": "id", 
            "className": "",
            "render": function(data, type, row){
            
                return `<div>${row.status}</div>`;
            }
          },
          {
            "targets": [4], 
            "data": "position", 
            "className": "",
            "render": function(data, type, row){
              let url = `index.php?module=CC_Job_Description&offset=1&return_module=CC_Job_Description&action=DetailView&record=${row.cc_job_description_id_c}`;
                return `<a href="${url}" target="_blank">${row.position}</a>`;
            }
          },
          {
            "targets": [5], 
            "data": "role", 
            "className": "",
            "render": function(data, type, row){
            
                return `<div>${row.role}</div>`;
            }
          },
          {
            "targets": [6], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
                let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<input class="form-control  english_empl"  name="txt_english" onchange="change_text($(this),2)" style="display:none" /> `;
                html+=`<a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),2)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_english" class="txt_english" >${row.english_level}</div> ${editF}</div>`;
            }
          },
          {
            "targets": [7], 
            "data": "project", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
               let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<select class="form-control  select_project"  name="txt_project" onchange="change_text($(this),1)" style="display:none"> `;
                html+= '<option value="">Selected</option>';
                html+=`</select><a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),1)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_project" class="txt_project" >${row.project} </div> ${editF}</div>`;
            }
          },
          {
            "targets": [8], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
              let editF=`<div class="inlineEditIcon" ><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` 
              let checked = row.has_passport == 1 ? "checked" : "";
                return `<p style="display:none">${row.has_passport}</p><input name="checkbox_display" class="checkbox" type="checkbox" disabled="" ${checked} >&nbsp;&nbsp;${editF}<a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),3)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
            }
          },
          {
            "targets": [9], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
               let editF=`<div class="inlineEditIcon" ><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` 
              let checked = row.active == 1 ? "checked" : "";
                return `<p style="display:none">${row.active}</p><input name="checkbox_display" class="checkbox" type="checkbox" disabled="" ${checked}>&nbsp;&nbsp;${editF}<a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),4)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                
            }
          },
          {
            "targets": [10], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
              let editF=`<div class="inlineEditIcon" ><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` 
               let checked = row.has_visa == 1 ? "checked" : "";
                return `<p style="display:none">${row.has_visa}</p><input name="checkbox_display" class="checkbox" type="checkbox" disabled="" ${checked} >&nbsp;&nbsp;${editF}<a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),5)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;

            }
          },
          {
            "targets": [11], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
              let editF=`<div class="inlineEditIcon" ><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` 
               let checked = row.is_assigned == 1 ? "checked" : "";
                return `<p style="display:none">${row.is_assigned}</p><input name="checkbox_display" class="checkbox" type="checkbox" disabled="" ${checked} >&nbsp;&nbsp;${editF}<a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),0)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;

            }
          }
      ],
      "drawCallback": function(settings, json) {
        if(groupColumn != 0){
        var api = this.api();
        
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;
 
            api
                .column(groupColumn, { page: 'current' })
                .data()
                .each(function (group, i) {
                  
                    if (last !== group) {
                        $(rows)
                            .eq(i)
                            .before('<tr class="group"><td colspan="11">' + group  + '</td></tr>');
 
                        last = group;
                    }
                });
        }

       jQuery('.inlineEdit').click(function() { 

        const Row = this.closest("tr");
        const Dataemplo = tablaEmployeesInformation.row( Row ).data();

            $(this).find(".saveCheckButton").css('display', 'inline');
         if($(this).find('input[type="checkbox"]').attr('name')=='checkbox_display'){   
            $(this).find('input[type="checkbox"]').prop('disabled', false);
            jQuery('.inlineEditIcon').hide();
          }else if($(this).find('input').attr('name')=='txt_english'){
            $(this).parent().find('#txt_english').hide();
            jQuery('.inlineEditIcon').hide();
            $(this).find('input').css({'display': 'inline', 'width': '50px'});
          }else if($(this).find('select').attr('name')=='txt_project' && tdIdentifier == 0){
            $(this).find('select').find('option').remove();
            $(this).find('select').append('<option value="">Selected</option>');
                Object.entries(Dataemplo["project_list"]).forEach(([key, value]) => {
                   let ValueData = Object.values(value); 
                   let KeyData   = Object.keys(value);
                   let selected = Dataemplo["project_id_c"] == KeyData ? "selected" : "";
                   $(this).find('select').append(`<option value="${KeyData}" ${selected}>${ValueData}</option>`);
                   tdIdentifier = 1;
                });
                
            $(this).parent().find('#txt_project').hide();
            jQuery('.inlineEditIcon').hide();
            $(this).find(".saveProjectButton").css('display', 'inline');
            $(this).find('select').css({'display': 'inline', 'width': '100px'});
          }else{
            $(this).parent().find('#txt_project').hide();
            jQuery('.inlineEditIcon').hide();
            $(this).find(".saveProjectButton").css('display', 'inline');
            $(this).find('select').css({'display': 'inline', 'width': '100px'});
          }
        });
          
      }
   });
  



  


   //suitecrm styles collide with this element I reset it
  $("#dataTableEmployeesInformation_length").find("select").css({"height": "33px","width": "5em"});
  
     $.contextMenu({
        selector: '#dataTableEmployeesInformation thead tr th.groupy', 
        callback: function(key, options) {
            
            if(key === "group_by"){
              switch(this.attr('id')) {
                case "country_law":
                  groupColumn = 2;
                  break;
                case "position":
                  groupColumn = 4;
                  break;
                case "role":
                  groupColumn = 5;
                  break;
                case "project":
                  groupColumn = 7;
                  break;
                } 
            }
           tablaEmployeesInformation.order( [ groupColumn, 'asc' ] ).draw();
        },
        items: {
          "group_by": {name: "Group by"},
        }
   
     });

    });

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = tablaEmployeesInformation.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );


    function change_info(id_employee,elem,opt){
      if(flag == true){
        flag = false;
        elem.css('display', 'none');
      let change= elem.parent().find('select').val() != undefined ? 1 : opt ;
      let info= opt == 2 ? elem.parent().find('input').val() : (elem.parent().find('select').val() != undefined ? elem.parent().find('select').val(): elem.parent().find('input[type="checkbox"]').is( ":checked" )) ;

      if(info == "" && opt == 1){
        if (confirm("Esta Seguro que este Empleado No Tiene Projecto?") == true) {
          const data_Send = {
            "action":"inactivateEmployee",
            "id_employee": id_employee
          };
          
          $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=EmployeeInformationEntryPoint',
            data: data_Send,
            success: function(resp) {}
          });
        }
      }
      
        const data_Send = {
        "action":"quickEditEmployee",
        "id_employee": id_employee,
        "change": change,
        "info": info
      };
      
      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=EmployeeInformationEntryPoint',
        data: data_Send,
        success: function(resp) {
          if(resp) {
            elem.css('display', 'none');
            jQuery('.txt_project').show();
            jQuery('.txt_english').show();
            jQuery('.select_project').hide();
            jQuery('.english_empl').hide();
            elem.parent().find('input[type="checkbox"]').prop('disabled', true);
            jQuery('.inlineEditIcon').show();
            tdIdentifier = 0;
            flag = true;
          }
        }
      });
      }
    }

    function change_text(elem, opt){
      if(opt == 1){
        if(elem.val() == ""){
          elem.parent().find('#txt_project').text('');
        }else{
          let optionText = elem.find('option[value=' + elem.val() + ']').text();
          elem.parent().find('#txt_project').text(optionText);
        }
      }else{
        let optionText = elem.val();
        elem.parent().find('#txt_english').text(optionText);
      }
    }

    function recalculeData(){
      filterColum = [];
      filterColum.push({filter_active  : $('#hideInactive').is(':checked') ? 1 : 0,
                        filter_project : $('#hideUnassigned').is(':checked') ? 1 : 0,});
      $("#dataTableEmployeesInformation").DataTable().ajax.reload();
    }
       
   function getFilterColumn() {
     
      return filterColum;
   }



</script>
{/literal}
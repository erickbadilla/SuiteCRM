<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/colReorder.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/jquery.contextMenu.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/listView.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />



<div id="mainSkillsDiv" class="col-lg-6 col-xs-12 detail-view-row-item">
  <div class="card">

    <div style="text-align:center">
        <span title="hide/show Columns" style="font-weight:bold">Toggle column: </span> <a class="toggle-vis" data-column="8">Description</a>
    </div>
    
    <div class="card-body">
    <div class="table-responsive" >
      <table id="dataTableProfessionalExperience" class="table table-bordered table-striped table-hover" style="width:100%">
        <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Business Name</th>
          <th>Position</th>
          <th>Related Name</th>
          <th>Assigned Project</th>
          <th>Assigned PM</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Description</th>
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
<script src="custom/include/generic/javascript/buttons/vfs_fonts.js"></script>

<script>


var flag = true;
let tablaProfessionalExperience = ""; 
  $(function() {
  

  let data_send = {
    action : "GetProfessionalExperience"
  }
  
  
  if(tablaProfessionalExperience != ""){
    tablaProfessionalExperience.destroy();
  }
  
  tablaProfessionalExperience = $("#dataTableProfessionalExperience").DataTable({

      dom: 'Blfrtip',
      buttons: [
          'excel'
      ],
     "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=ProfessionalExperienceEntryPoint',
        "type": "POST",
        'data' : data_send,
     },
     "paging" : true,
     "order": [[ 0, "desc" ]],
     "info" : true,
     "filter" : true,
      "columnDefs": [
        {
             "targets": [0],
             "sortable": false, 
              "data": "id", 
             "className": "td_delete_profile",
             "render": function(data, type, row){
              let url = `index.php?module=CC_Professional_Experience&offset=1&return_module=CC_Professional_Experience&action=EditView&record=${row.id}`;
                return `<input type=hidden value=${row.id} ><a href="${url}" target="_self" class="edit-link"><span class="suitepicon suitepicon-action-edit"></span></a>`;
             }
        },
        {
            "targets": [1], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
              let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<input class="form-control  business_name"  name="txt_business_name" onchange="change_text($(this),2)" style="display:none" /> `;
                html+=`<a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),2,'${row.id_employee}')"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_business_name" class="txt_business_name" >${row.business_name}</div> ${editF}</div>`;
              
            }
          },
          {
            "targets": [2], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
               let url = `index.php?module=CC_Professional_Experience&offset=1&return_module=CC_Professional_Experience&action=DetailView&record=${row.id}`;

                let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<input class="form-control  name"  name="txt_name" onchange="change_text($(this),2)" style="display:none" /> `;
                html+=`<a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),3,'${row.id_employee}')"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_name" class="txt_name" ><a href="${url}" target="_self">${row.name}</a></div> ${editF}</div>`;

            }
          },
          {
            "targets": [3], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
                return `<div>${row.employee}</div>`;
            }
          },
          {
            "targets": [4], 
            "data": "project_list", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
             let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<select class="form-control  select_project"  name="txt_project" onchange="change_text($(this),1)" style="display:none"> `;
                html+= '<option value="">Selected</option>';
  
                html+=`</select><a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),1,'${row.id_employee}')"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_project" class="txt_project" >${row.project} </div> ${editF}</div>`;
           
            }
          },
          {
            "targets": [5], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
              return `<div>${row.pm_name}</div>`;            
            }
          },
          {
            "targets": [6], 
            "data": "project_list", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
               let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<span name="txt_start_date" class="dateTime" style="display:none">${row.start_date}</span> `;
                html+=`<a class='saveCheckButton' style="display:none"  onclick="change_info('${row.id}',$(this),4,'${row.id_employee}')"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_start_date_cont" class="txt_start_date" >${row.start_date}</div> ${editF}</div>`;
            }
          },
          {
            "targets": [7], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
              let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
              let html = `<span name="txt_end_date" class="dateTime" style="display:none">${row.end_date}</span> `;
                  html+=`<a class='saveCheckButton' style="display:none"  onclick="change_info('${row.id}',$(this),5,'${row.id_employee}')"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_end_date_cont" class="txt_end_date" >${row.end_date}</div> ${editF}</div>`;
            }
          },
          {
            "targets": [8], 
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
              let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<textarea class="form-control  description"  name="txt_description" onchange="change_text($(this),2)" style="display:none" > ${row.description} </textarea>`;
                html+=`<a class='saveCheckButton' style="display:none" onclick="change_info('${row.id}',$(this),6,'${row.id_employee}')"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_description" class="txt_description" >${row.description}</div> ${editF}</div>`;
                
            }
          }
          
          
      ],
      "initComplete": function(settings, json) {
          $('a.toggle-vis').click();

      },
      "drawCallback": function(settings, json) {
        
       jQuery('.inlineEdit').click(function() { 

        const Row = this.closest("tr");
        const DataProj = tablaProfessionalExperience.row( Row ).data();
        $(this).find(".saveCheckButton").css('display', 'inline');
        $(this).find(".saveCheckButton").find("span").css('display', 'inline');
        jQuery('.inlineEditIcon').hide();

        if($(this).find('span').attr('name')=='txt_start_date'){
              let datenow = ($(this).find('span[name="txt_start_date"]').text());
              let ram= Math.floor(Math.random() * 1000);

              let current = $(this).find('span[name="txt_start_date"]').parent().html();
            
              $(this).find('span[name="txt_start_date"]').parent().html(`
              <form name="EditViewCalendarL" id="EditViewCalendarL">
                  <div id="container_caledar" >
                    <span class="dateTime">
                        <input class="date_input" autocomplete="off" type="text" name="txt_start_date_${ram}" id="txt_start_date_${ram}" value="${datenow}" title="" tabindex="1" size="11" maxlength="10" onchange="change_text($(this),3)">
                        <button type="button" id="txt_start_date_${ram}_trigger" class="btn btn-danger" onclick="return false;">
                          <span class="suitepicon suitepicon-module-calendar" alt="Enter Date" > </span>
                         </button>
                    </span>
                  </div>
              </form>` + current);
                var g = document.createElement('script');
                var s = document.getElementsByTagName('script')[0];
                g.text = `
                          Calendar.setup({ 
                            inputField : \'txt_start_date_${ram}\',
                            form : \'EditViewCalendarL\',
                            ifFormat : \'%m/%d/%Y %H:%M\',
                            daFormat : \'%m/%d/%Y %H:%M\',
                            button : \'txt_start_date_${ram}_trigger\',
                            singleClick : true,
                            startWeekday: 0,
                            step : 1,
                            weekNumbers:false
                          })`;
                s.parentNode.insertBefore(g, s);

    

              //$(this).find('span[name="txt_start_date"]').parent().css('display', 'inline');
             $("#contaier_caledar").css({'display': 'inline', 'width': '160px'});
             $(this).parent().find('#txt_start_date_cont').hide();

          }else if($(this).find('span').attr('name')=='txt_end_date'){
              let datenow = ($(this).find('span[name="txt_end_date"]').text());
              let ram=Math.floor(Math.random() * 1000);
              let current = $(this).find('span[name="txt_end_date"]').parent().html();

              $(this).find('span[name="txt_end_date"]').parent().html(`
              <form name="EditViewCalendarL" id="EditViewCalendarL">
                  <div id="container_caledar" >
                    <span class="dateTime">
                        <input class="date_input" autocomplete="off" type="text" name="txt_end_date_${ram}" id="txt_end_date_${ram}" value="${datenow}" title="" tabindex="1" size="11" maxlength="10" onchange="change_text($(this),3)">
                        <button type="button" id="txt_end_date_${ram}_trigger" class="btn btn-danger" onclick="return false;">
                          <span class="suitepicon suitepicon-module-calendar" alt="Enter Date" > </span>
                         </button>
                    </span>
                  </div>
              </form>` + current);
                var g = document.createElement('script');
                var s = document.getElementsByTagName('script')[0];
                g.text = `
                          Calendar.setup({ 
                            inputField : \'txt_end_date_${ram}\',
                            form : \'EditViewCalendarL\',
                            ifFormat : \'%m/%d/%Y %H:%M\',
                            daFormat : \'%m/%d/%Y %H:%M\',
                            button : \'txt_end_date_${ram}_trigger\',
                            singleClick : true,
                            startWeekday: 0,
                            step : 1,
                            weekNumbers:false
                          })`;
                s.parentNode.insertBefore(g, s);

    

            //$(this).find('span[name="txt_end_date"]').parent().css('display', 'inline');
             $("#contaier_caledar").css({'display': 'inline', 'width': '160px'});
             $(this).parent().find('#txt_end_date_cont').hide();

          }else if($(this).find('select').attr('name')=='txt_project'){

                Object.entries(DataProj["project_list"]).forEach(([key, value]) => {
                   let ValueData = Object.values(value); 
                   let KeyData   = Object.keys(value);
                   let selected = DataProj["project_id_c"] == KeyData ? "selected" : "";
                   $(this).find('select').append(`<option value="${KeyData}" ${selected}>${ValueData}</option>`);
                });

            $(this).parent().find('#txt_project').hide();
            $(this).find(".saveProjectButton").css('display', 'inline');
            $(this).find('select').css({'display': 'inline', 'width': '150px'});
            
          }else if($(this).find('textarea').attr('name')=='txt_description'){
            $(this).parent().find('#txt_description').hide();
            $(this).find('div').find('textarea').css({'display': 'inline', 'width': '100%'});
          }else if($(this).find('input').attr('name')=='txt_business_name' || $(this).find('input').attr('name')=='txt_name'){
            var current_val = ($(this).find('div').find('div').text());
            $(this).find(".saveButton").css('display', 'inline');
            $(this).find('div').find('div').hide();
            $(this).find('div').find('input').css({'display': 'inline', 'width': '150px'});
            $(this).find('input').val(current_val);
           
            
          }
        });
          
      }
   });
  



   //suitecrm styles collide with this element I reset it
  $("#dataTableProfessionalExperience_length").find("select").css({"height": "33px","width": "5em"});
  
        
    });

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = tablaProfessionalExperience.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );



    function change_info(id,elem,opt, id_employee){
      if(flag == true){
        flag = false;
        elem.css('display', 'none');
        flag = false;
        elem.css('display', 'none');
      let change= elem.parent().find('select').val() != undefined ? 1 : opt ;
      let info= opt == 1 ? elem.parent().find('select').val() : (opt == 6 ? elem.parent().find('textarea').val() : elem.parent().find('input').val());

      if(info == "" && opt == 1){
        if (confirm("Esta Seguro que este Empleado No Tiene Projecto?") == true) {
          const data_Send = {
            "action":"inactivateEmployee",
            "id_experience": id,
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
        "action":"quickEditProfessionalExperience",
        "id_experience": id,
        "id_employee": id_employee,
        "change": change,
        "info": info
      };
      
      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=ProfessionalExperienceEntryPoint',
        data: data_Send,
        success: function(resp) {
          if(resp) {
            elem.css('display', 'none');
            elem.css('display', 'none');
            elem.parent().find('div').show();
            elem.parent().find('input').hide();
            elem.parent().find('form').remove();
            elem.parent().find('input').find('.date_input').show();
            elem.parent().find('span').hide();
            jQuery('.txt_project').show();
            jQuery('.select_project').hide();
            jQuery('.inlineEditIcon').show();
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
          
          const data_Send = {
            "action":"get_pm_name",
            "id_project": elem.val()
          };
          
          $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=ProfessionalExperienceEntryPoint',
            data: data_Send,
            success: function(resp) {

              let data = JSON.parse(resp);
              elem.closest('td').next().find('div').text(data[0].name);
            }
          });

        }
      }else if(opt == 3){
        let optionText = elem.val();
        const dtes = optionText.split("/");
        let new_date = dtes[2] + "-" + dtes[0] + "-" + dtes[1];
        elem.closest('form').parent().find('div').eq(1).text(new_date);
        elem.closest('form').parent().find('span').eq(2).text(new_date);
      }else{
        let optionText = elem.val();
        elem.parent().find('div').eq(0).text(optionText);
      }
    }


</script>
{/literal}
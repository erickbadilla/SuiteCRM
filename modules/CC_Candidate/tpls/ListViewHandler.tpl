<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/colReorder.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/jquery.contextMenu.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/listView.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Employee_Information/css/edit.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />



<div id="mainSkillsDiv" class="col-lg-6 col-xs-12 detail-view-row-item">
  <div class="card">

    <div style="text-align:center">
        <span title="hide/show Columns" style="font-weight:bold">Toggle column: </span><a class="toggle-vis" data-column="1">Name</a> - <a class="toggle-vis" data-column="2">First Name</a> - <a class="toggle-vis" data-column="3">Last Name </a> - <a class="toggle-vis" data-column="4">Country</a> - <a class="toggle-vis" data-column="5">City</a> 
    </div>
    
    <div class="card-body">
    <div class="table-responsive" >
      <table id="dataTableCandidates" class="table table-bordered table-striped table-hover" style="width:100%">
        <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Name</th>
          <th>First Name</th>
          <th>Last Name </th>
          <th>City</th>
          <th>Country</th>
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
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
  'locale-compare-asc': function ( a, b ) {
     return a.localeCompare(b, 'cs', { sensitivity: 'case' })
  },
  'locale-compare-desc': function ( a, b ) {
     return b.localeCompare(a, 'cs', { sensitivity: 'case' })
  }
})

jQuery.fn.dataTable.ext.type.search['locale-compare'] = function (data) {
	return NeutralizeAccent(data);
}

function NeutralizeAccent(data)
{
  return !data
      ? ''
        : typeof data === 'string'
        ? data
        .replace(/\n/g, ' ')
        .replace(/[éÉěĚèêëÈÊË]/g, 'e')
        .replace(/[šŠ]/g, 's')
        .replace(/[čČçÇ]/g, 'c')
        .replace(/[řŘ]/g, 'r')
        .replace(/[žŽ]/g, 'z')
        .replace(/[ýÝ]/g, 'y')
        .replace(/[áÁâàÂÀ]/g, 'a')
        .replace(/[íÍîïÎÏ]/g, 'i')
        .replace(/[ťŤ]/g, 't')
        .replace(/[ďĎ]/g, 'd')
        .replace(/[ňŇ]/g, 'n')
        .replace(/[óÓ]/g, 'o')
        .replace(/[úÚůŮ]/g, 'u')
        : data
}

let tablaCandidates = ""; 
  $(function() {
        
  
  if(tablaCandidates != ""){
    tablaCandidates.destroy();
  }
  
  tablaCandidates = $("#dataTableCandidates").DataTable({

      dom: 'Blfrtip',
      buttons: [
          'excel'
      ],
     "responsive": true,
     "ajax" :{
        "url": 'index.php?entryPoint=CandidateApplicationEntryPoint',
        "type": "POST",
        'data' : function (d) {
                    d.action = "GetCandidatesInformation";
                }
     },
     "paging" : true,
     "order": [[ 0, "desc" ]],
     "info" : true,
     "filter" : true,
     "columns": [
                {orderable: false, searchable: false, name: "id"},
                {orderable: true, searchable: true, name: "name" },
                {orderable: true, searchable: true, name: "first_name" },
                {orderable: true, searchable: true, name: "last_name" },
                {orderable: true, searchable: true, name: "city" },
                {orderable: true, searchable: true, name: "country" },
            ],
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
            "type": "locale-compare",
            "data": "id", 
            "className": "hidden-xs ",
            "render": function(data, type, row){
              let url = `index.php?module=${row.object_name}&offset=1&return_module=${row.object_name}&action=DetailView&record=${row.id}`;
                              return `<div id="txt_name" ><input type='hidden' value="${row.name}"><a href="${url}" target="_self" class="edit-link">${row.name}  </a></div>`;
            }
          },
          {
            "targets": [2], 
            "type": "locale-compare",
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
               let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<input type="text" style="display:none" value = "${row.first_name}" onchange="change_text($(this))"><a class='saveButton' style="display:none" onclick="change_info('${row.id}',$(this),2)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_first_name" >${row.first_name}  </div>${editF}</div>`;
            }
          },
          {
            "targets": [3], 
            "type": "locale-compare",
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
               let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<input type="text" style="display:none" value = "${row.last_name}" onchange="change_text($(this))"><a class='saveButton' style="display:none" onclick="change_info('${row.id}',$(this),3)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_last_name" >${row.last_name}  </div>${editF}</div>`;
            }
          },
          {
            "targets": [4], 
            "type": "locale-compare",
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
               let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<input type="text" style="display:none" value = "${row.city}" onchange="change_text($(this))"><a class='saveButton' style="display:none" onclick="change_info('${row.id}',$(this),4)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_city" >${row.city}  </div>${editF}</div>`;
            }
          },
          {
            "targets": [5], 
            "type": "locale-compare",
            "data": "id", 
            "className": "hidden-xs inlineEdit",
            "render": function(data, type, row){
               let editF=`<div class="inlineEditIcon"><svg version="1.1" id="inline_edit_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                          <g class="icon" id="Icon_6_">	<g><path class="icon" d="M64,368v80h80l235.727-235.729l-79.999-79.998L64,368z M441.602,150.398
			                    c8.531-8.531,8.531-21.334,0-29.865l-50.135-50.135c-8.531-8.531-21.334-8.531-29.865,0l-39.468,39.469l79.999,79.998
			                    L441.602,150.398z"></path></g></g></svg></div>` ;
                let html = `<input type="text" style="display:none" value = "${row.country}" onchange="change_text($(this))"><a class='saveButton' style="display:none" onclick="change_info('${row.id}',$(this),5)"><span class='suitepicon suitepicon-action-confirm'></span></a>`;
                return `<div style="display:flex">${html}<div id="txt_country" >${row.country}  </div>${editF}</div>`;
            }
          }
        
      ],
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            "pageLength": 25,
            "displayLength": 25,
      "initComplete": function(settings, json) {
       jQuery('.inlineEdit').click(function() { 
            $(this).find(".saveButton").css('display', 'inline');
            $(this).find('div').find('div').hide();
            jQuery('.inlineEditIcon').hide();
            $(this).find('div').find('input').css({'display': 'inline', 'width': '100px'});

        });
          
      }
   });
  

   //suitecrm styles collide with this element I reset it
  $("#dataTableCandidates_length").find("select").css({"height": "33px","width": "5em"});
  
    });

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = tablaCandidates.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );

     $('#dataTableCandidates_filter input').keyup(function () {
      table
      .search(
        jQuery.fn.dataTable.ext.type.search.string(NeutralizeAccent(this.value))
      )
      .draw()
     })


    function change_info(id_candidate,elem,opt){

      let info= elem.parent().find('input').val();
      
        const data_Send = {
        "action":"quickEditCandidate",
        "id_candidate": id_candidate,
        "change": opt,
        "info": info
      };
      
      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=CandidateApplicationEntryPoint',
        data: data_Send,
        success: function(resp) {
          if(resp) {
            elem.css('display', 'none');
            elem.parent().find('div').show();
            elem.parent().find('input').hide();
            jQuery('.inlineEditIcon').show();
          }
        }
      });

    }

    function change_text(elem){
      if(elem.val() == ""){
        elem.parent().find('div').text('');
      }else{
        let optionText = elem.val();
        elem.parent().find('div').eq(0).text(optionText);
      }
    }


</script>
{/literal}

let filterColum = [];
let table_main = "";

$(function() {
  
  toastr.options = {
    "positionClass": "toast-bottom-right",
  }

  $("footer").hide();
  $("#massassign_form").hide();
  
  table_main = $("#table_main").DataTable({
    "sDom": 'l<"H"Rf>t<"F"ip>',
    "responsive": true,
    "retrieve": true,
    "ajax" :{
      "url": 'index.php?entryPoint=GetRecruitmentRequestEntryPoint',
      "type": "POST",
      "data": function(d) {
        showLoading();
        d.action = "getRecruitmentRequestDataAll";
        d.filterColumData = getFilterColumn();
        d.hideClosed = $('#hide_closed').is(':checked');
      }
    },
    "paging" : true,
    "info" : true,
    "processing": true,
    "serverSide": true,
    "filter" : true,
    "columns": [
      {data: 'name_rec_req' , className: 'filter name_main'},
      {
        data: 'data_accounts' , 
        className: 'name_main' ,
        render: function (data, type, row) { 
          let data_account = row.data_accounts.split(",");
          let icon = (data_account.length > 1) ? `<svg xmlns="http://www.w3.org/2000/svg" style="vertical-align: sub;" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5z"/></svg>` : "";
          
          let result = "";
          for (let i = 0; i < data_account.length; i++) {
            const element = data_account[i].split("|");
            let url  = `index.php?module=Accounts&return_module=Accounts&action=DetailView&record=${element[0]}`; 
            result += `${icon} <a href="${url}" title="go to see ${element[1]}">${element[1]}</a><br>`;
            
          }
          return result;
        } 
      },
      {
        data: 'name_project' , 
        className: 'filter' ,
        render: function (data, type, row) { 
          let url  = `index.php?module=Project&return_module=Project&action=DetailView&record=${row.id_project}`; 
          return `<a href="${url}">${data}</a>`;
        } 
      },
      {
        data: 'name_description' , 
        className: 'filter' ,
        render: function (data, type, row) { 
          let url  = `index.php?module=CC_Job_Description&return_module=CC_Job_Description&action=DetailView&record=${row.id_description}`; 
          return `<a href="${url}">${data}</a>`;
        } 
      },
      {data: 'open_positions' , className: ''},
      {data: 'total_applications' , className: 'column_total'},
      {data: 'InProgress' , className: 'column_total'},
      {data: 'NotApproved' , className: 'column_total'},
      {data: 'Approved' , className: ' column_total'},
      {data: 'total_interviews' , className: 'column_total'},
      {data: 'last_activity_date' , className: ''},
      {
        data: 'user_assigned' , 
        className: 'filter' ,
        render: function (data, type, row) { 
          let url  = `index.php?module=Users&return_module=Users&action=DetailView&record=${row.id_user}`; 
          return `<a href="${url}">${data}</a>`;
        } 
      },
      {
        data: 'is_published' , 
        className: 'filter' ,
        render: function (data, type, row) { 
          let lbl = (parseInt(data) == 1) ? "Yes" : "Not";
          return lbl;
        } 
      },
      {
        data: 'closed_recruitment' ,
        className: 'closed_recruitment',
        render: function (data, type, row) {
          const information = parseInt(data) === 1? `Closed on <stronger>${row.closed_on}</stronger>` : `Open`;
          return `<label style="text-align: center;">${information}</label>`;
        }
      },
    ],
    "columnDefs": [
      {
        "targets": [14], 
        "data": "viewAction",
        "className": "td_defecto",
        "render": function(data, type, row){
           return data;
        }
      }
    ],
    "drawCallback": function(settings) {
      hideLoading();
    }
  });
  
  //suitecrm styles collide with this element I reset it
  $("#table_main_length").find("select").css({"height": "30px","width": "5em"});
  
  $.contextMenu({
     selector: '#table_main tbody tr td.filter', 
     callback: function(key, options) {
  
       if(key === "filter"){
         const textFiltered = this.text().replace(/ %/, '');
         filterColum.push({text: textFiltered, column:  this.index()});
       }
       if(key === "remove-filter"){
         filterColum = [];
       }
       $('#table_main').DataTable().ajax.reload();
  
     },
     items: {
       "filter": {name: "Filter"},
       "remove-filter": {name: "Remove filter"}
     }
  
  });
  
});


function getFilterColumn() {
    
  return filterColum;
}

function hideColumnsByDefault()
{
  //Numbers of data-colums to hide
  const dataColumns = ["2", "7", "8", "9", "10", "11"];
  
  dataColumns.map(dataC => {
    const column = table_main.column( dataC );
    column.visible( ! column.visible() );
  });
}

function makeCheckboxHide()
{
  $(".dataTables_length").append(`
    <div style="display: flex; padding-top: 10px; margin-left: 15px;">
      <input type="checkbox" id="hide_closed" value="true" />
      <label>Hide Closed</label>
    </div>                              
  `);
  $(".dataTables_length").css({"display": "flex", "justify-content": "space-between"});
}
  
$('a.toggle-vis').on( 'click', function (e) {
  e.preventDefault();
  // Get the column API object
  const column = table_main.column( $(this).attr('data-column') );

  // Toggle the visibility
  column.visible( ! column.visible() );
});
  
function showLoading()
{
  $('#table_main_info').css('display', 'none');
  $('#table_main_paginate').css('display', 'none');
  $('tr[class]').css('display', 'none');
  $('#div_loading').removeAttr('style');
}

function hideLoading()
{
  $('#div_loading').css('display', 'none');
  $('#table_main_info').removeAttr('style');
  $('tr[class]').removeAttr('style');
  $('#table_main_paginate').removeAttr('style');
}

$(document).ready(function() {
  hideColumnsByDefault();
  makeCheckboxHide();

  $('#hide_closed').change(function() {
    table_main.ajax.reload();
  });
});


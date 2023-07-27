<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/colReorder.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/jquery.contextMenu.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/listView.css" rel="stylesheet" type="text/css">
<div id="mainSkillsDiv" class="col-lg-6 col-xs-12 detail-view-row-item">
  <div class="card">
    <div class="card-header">
      <h3>List of Job Applications</h3>
    </div>
    <div class="card-body">
      <table id="dataTableJobApplications" class="display" style="width:100%">
        <thead>
        <tr>
          <th>Candidate Id</th>
          <th>Job Offer Id</th>
          <th>Applications Id</th>
          <th>Candidate Name</th>
          <th>Job Offer Name</th>
          <th>Skill Rating</th>
          <th>Qualification Rating</th>
          <th>General Rating</th>
          <th>Stage</th>
        </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
{literal}
<script src='custom/include/generic/javascript/datatables/jquery.dataTables.min.js'></script>
<script src='custom/include/generic/javascript/datatables/dataTables.colReorder.min.js'></script>
<script src='custom/include/generic/javascript/jquery.contextMenu/jquery.contextMenu.js'></script>

<script>
    $(function() {
      let hideStage = "";
      let tempColum = "";
      let filterColum = [];
      let applicationType = $.cookie('CC_job-applications-type-selected');

      if(!applicationType){
        applicationType = 'EXTERNAL';
        $.cookie('CC_job-applications-type-selected', applicationType);
      }
  
      const table = $('#dataTableJobApplications').DataTable({
        "sDom": 'l<"H"Rf>t<"F"ip>',
        "columns": [
          { data: "candidate_id"  },
          { data: "job_offer_id"  },
          { data: "applications_id"  },
          { data: "candidate_name", width: "20%",
            render: function(data, type, row) {
              const moduleId = "CC_Job_Applications";
              const link = "index.php?module="+moduleId+"&offset=1&return_module="+moduleId+"&action=DetailView&record="+row.applications_id+"&candidateId="+row.candidate_id+"&jobOfferId="+row.job_offer_id;
              return `<a href="${link}" style="cursor: pointer;">${data}</a>`;
            } 
          },
          { data: "job_offer_name", width: "20%" },
          { 
            data: "skill_rating", width: "15%", className: 'dt-center',
            render: function (e) { return formatRating(e); } 
          },
          { data: "qualification_rating", width: "15%", className: 'dt-center',
            render: function (e) { return formatRating(e); } 
          },
          { data: "general_rating", width: "15%", className: 'dt-center',
            render: function (e) { return formatRating(e); } 
          },
          { 
            data: "stage", width: "15%", className: 'dt-center'
          }
        ],            
        "columnDefs": [
          { targets: 0, visible: false, searchable: false },
          { targets: 1, visible: false, searchable: false },
          { targets: 2, visible: false, searchable: false },
          { targets: 5, searchable: false, className: 'dt-body-rating' },
          { targets: 6, searchable: false, className: 'dt-body-rating' },
          { targets: 7, searchable: false, className: 'dt-body-rating' },
          { targets: 8, searchable: false }
        ],
        "processing": true,
        "serverSide": true,
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "pageLength": 10,
        "filter": true,
        "ajax": {
          "url": 'index.php?entryPoint=JobApplicationListViewEntryPoint',
          "type": "POST",
          "data": function(d) {
            d.action = "get";
            d.secondaryModule = 'CC_Candidate';
            d.application_type = applicationType;
            d.hideStage = getHideStage();
            d.filterColumData = getFilterColumn();
          }
        },
        "initComplete": (settings, json) => {
          $(".js-select-application-type-selector").val(applicationType);
        }
      });
      
      $("#massassign_form").hide();

      function getHideStage() {
        const res = hideStage;
        return res;
      }

      function getFilterColumn() {
        return filterColum;
      }

      function formatRating (rating) {
        return `${Math.round(rating)} %`;
      }

      if ($(window).width() < 420) {
        $('#dataTableJobApplications').DataTable().columns( [4,5] ).visible( false );
      }

      const checkBox = `<div style="display: flex;">
        <input type='checkbox' id='hide-clesed-applications'>
        <p>Hide closed applications</p>
      </div>`;
      $('#dataTableJobApplications_length').append(checkBox);

      $('#hide-clesed-applications').change(function(){
        if($(this).prop('checked')){
          hideStage = 'CLOSED';
        }else {
          hideStage = '';
        }
        $('#dataTableJobApplications').DataTable().ajax.reload();
      });

      const applicationTypeSelect = `
        <div style="
          display: flex;
          align-items: center;
          justify-content: space-around;"
        >
          <strong>Type:</strong>
          <div id="application_type_list" style="width: 90px;">
            <select class="js-select-application-type-selector" style="width: 100%;">
              <option value="EXTERNAL">External</option>
              <option value="INTERNAL">Internal</option>
            </select>
          </div>
        </div>`;
      $('#dataTableJobApplications_length').append(applicationTypeSelect);
      $('#dataTableJobApplications_length').css({"align-items":"center", "width":"50%", "justify-content":"space-between"})
      
      $(".js-select-application-type-selector").on('change',function() { 
        let type=$(".js-select-application-type-selector").val();
        $.cookie('CC_job-applications-type-selected', type);
        location.reload();
      });

      $.contextMenu({
        selector: '#dataTableJobApplications tbody tr td', 
        callback: function(key, options) {
          if(key === "filter"){
            const textFiltered = (this.contents().text())?.replace(/ %/, '');
            filterColum.push({text: textFiltered, column: tempColum});
          }
          if(key === "remove-filter"){
            filterColum = [];
          }
            $('#dataTableJobApplications').DataTable().ajax.reload();
        },
        items: {
          "filter": {name: "Filter"},
          "remove-filter": {name: "Remove filter"}
        }
      });

      $('#dataTableJobApplications tbody').on('contextmenu', 'td', function(e) {
        tempColum = $('#dataTableJobApplications thead tr th').eq($(this).index()).html().trim();
        tempColum = tempColum.replace(/ /g, '_').toLowerCase();
      });
    });
</script>
{/literal}
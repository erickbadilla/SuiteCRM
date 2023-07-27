<link href="/custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="/custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="/custom/include/generic/css/listView.css" rel="stylesheet" type="text/css">
<div id="mainSkillsDiv" class="col-lg-6 col-xs-12 detail-view-row-item">
  <div class="card">
    <div class="card-header">
      <h3>List of Job Applications</h3>
    </div>
    <div class="card-body">
      <table id="dataTableJobApplications" class="display" style="width:100%">
        <thead>
        <tr>
          <th data-columns-data="candidate_id">Candidate Id</th>
          <th data-columns-data="job_offer_id">Job Offer Id</th>
          <th data-columns-data="candidate_name">Candidate Name</th>
          <th data-columns-data="job_offer_name">Job Offer Name</th>
          <th data-columns-data="skill_rating">Skill Rating</th>
          <th data-columns-data="qualification_rating">Qualification Rating</th>
          <th data-columns-data="general_rating">General Rating</th>
          <th data-columns-data="stage">Stage</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
          <th>Candidate Id</th>
          <th>Job Offer Id</th>
          <th>Candidate Name</th>
          <th>Job Offer Name</th>
          <th>Skill Rating</th>
          <th>Qualification Rating</th>
          <th>General Rating</th>
          <th>Stage</th>
        </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
{literal}
<script src='/custom/include/generic/javascript/datatables/jquery.dataTables.min.js'></script>
<script src='/custom/include/generic/javascript/select2/select2.min.js'></script>

<script>
    $(function() {

      function formatRating (rating) {
        return `${Math.round(rating*10)} %`;
      }
        
      $('#dataTableJobApplications').DataTable({
        "columns": [
          { name: "candidate_id"  },
          { name: "job_offer_id"  },
          { name: "candidate_name", width: "20%" },
          { name: "job_offer_name", width: "20%" },
          { 
            name: "skill_rating", width: "15%", className: 'dt-center',
            render: function (e) { return formatRating(e); } 
          },
          { name: "qualification_rating", width: "15%", className: 'dt-center',
            render: function (e) { return formatRating(e); } 
          },
          { name: "general_rating", width: "15%", className: 'dt-center',
            render: function (e) { return formatRating(e); } 
          },
          { 
            name: "stage", width: "15%", className: 'dt-center',
            render: function (e) { return e==null? false : true; }
          }
        ],            
        "columnDefs": [
          { targets: 0, visible: false, searchable: false },
          { targets: 1, visible: false, searchable: false },
          { targets: 4, searchable: false, className: 'dt-body-rating' },
          { targets: 5, searchable: false, className: 'dt-body-rating' },
          { targets: 6, searchable: false, className: 'dt-body-rating' },
          { targets: 7, searchable: false }
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
          "url": 'index.php?entryPoint=JobApplicationListViewEntryPoint',
          "type": "POST",
          "data": {
            "action":"get",
            "secondaryModule" : 'CC_Candidate',
          }
        }
      });

      if ($(window).width() < 420) {
        $('#dataTableJobApplications').DataTable().columns( [4,5] ).visible( false );
      }

    });
</script>
{/literal}
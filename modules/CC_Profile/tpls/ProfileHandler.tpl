<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/profile.css" rel="stylesheet" type="text/css">
<div class="panel-content">
  <div class="panel panel-default">
    <div class="panel-heading">
      <a class="collapsed" role="button" data-toggle="collapse" href="#top-panel-profileHandler" aria-expanded="false">
        <div class="col-xs-10 col-sm-11 col-md-11">
          SKILLS & QUALIFICATIONS
        </div>
      </a>
    </div>
  </div>
  <div class="panel-body panel-collapse panelContainer collapse" aria-expanded="false" id="top-panel-profileHandler">
    <div class="tab-content">
      <div class="row detail-view-row">
        <div id="mainSkillsDiv" class="col-lg-6 col-xs-12 detail-view-row-item">
          <div class="card">
            <div class="card-header">
              <h3>Skills</h3>
            </div>
            <div class="card-body">
              <table id="dataTableSkills" class="display" style="width:100%">
                <thead>
                <tr>
                  <th data-columns-data="id">Id</th>
                  <th data-columns-data="name">Name</th>
                  <th data-columns-data="rating">Expected Rating</th>
                  <th data-columns-data="amount">Experience</th>
                  <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Expected Rating</th>
                  <th>Experience</th>
                  <th></th>
                </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <div id="mainQualificationsDiv" class="col-lg-6 col-xs-12 detail-view-row-item">
          <div class="card">
            <div class="card-header">
              <h3>Qualifications</h3>
            </div>
            <div class="card-body">
              <div id="justFavouritesDiv"><input type="checkbox" id="justFavourites" checked="checked"><label for="justFavourites">Favourites</label></div>
              <table id="dataTableQualifications" class="display" style="width:100%">
                <thead>
                <tr>
                  <th data-columns-data="id">Id</th>
                  <th data-columns-data="Name">Name</th>
                  <th data-columns-data="MinimumRequired">Level</th>
                  <th data-columns-data="DigitalSupportRequired">Support Required</th>
                  <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Level</th>
                  <th>Support Required</th>
                  <th></th>
                </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{literal}
<script >
  $(function() {
    let profileId = {/literal}'{$BEANID}'{literal};
    let moduleId = 'CC_Profile';

    function updateMatches(){
      let table = $('#dataTableSkills').DataTable();
      table.ajax.reload();
      if(typeof refreshEmployeeData === "function") {
          refreshEmployeeData();
      }
      if(typeof refreshCandidateData === "function") {
          refreshCandidateData();
      }
      $('div#dataTableSkills_processing').hide();
    }

    function updateAjaxSkill(profileId,data){
      const update = {
        ...data,
        "action":"updateSkill",
        "secondaryModule" : 'CC_Skills',
        "profileId": profileId
      };
      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=ProfileSkillHandlerEntryPoint',
        dataType: 'json',
        data: update,
        success: function(resp) {
          if(resp) {
            updateMatches();
          }
        }
      });
    }

    function deleteAjaxSkill(profileId,data,currentRow){
      const update = {
        ...data,
        "action":"deleteSkill",
        "secondaryModule" : 'CC_Skills',
        "profileId": profileId
      };
      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=ProfileSkillHandlerEntryPoint',
        dataType: 'json',
        data: update,
        success: function(resp) {
          if(resp) {
            updateMatches();
          }
        }
      });
    }

    function addAjaxSkill(profileId,data){
      const update = {
        ...data,
        "action":"addSkill",
        "secondaryModule" : 'CC_Skills',
        "profileId": profileId
      };
      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=ProfileSkillHandlerEntryPoint',
        dataType: 'json',
        data: update,
        success: function(resp) {
          if(resp) {
            updateMatches();
          }
        }
      });
    }

    function createSearchElement(parent, elementId, profileId, url, moduleAction, placeholder, functionActionSelect){
      $(parent+" > div:nth-child(2)").after("<div id='"+elementId+"' class='dataTables_select'><select class='js-select-"+elementId+"'></select></div>");
      let element = $('#'+elementId).select2({
        width: '320px',
        placeholder: placeholder,
        allowClear: true,
        ajax: {
          transport: function (params, success, failure) {
            let query = {
              profileId: profileId,
              action: moduleAction,
              searchTerm: params.data.term,
              justFavourites: $("#justFavourites").prop('checked'),
              type: 'public'
            }

            var $request = $.ajax({
              type: "POST",
              url: url,
              dataType: 'json',
              data: query
            });

            $request.then(success);
            $request.fail(failure);

            return $request;
          }
        }
      });
      $(element).on("select2:select", function(e){
        functionActionSelect(e?.params?.data);
      });
    }



    function addAjaxQualification(profileId,data){
      const update = {
        ...data,
        "action":"addQualification",
        "secondaryModule" : 'CC_Qualifications',
        "profileId": profileId
      };
      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=ProfileQualificationHandlerEntryPoint',
        dataType: 'json',
        data: update,
        success: function(resp) {
          if(resp) {
            let table = $('#dataTableQualifications').DataTable();
            table.ajax.reload();
            updateMatches();
          }
        }
      });
    }

    function deleteAjaxQualification(profileId,data,currentRow){
      const update = {
        ...data,
        "action":"deleteQualification",
        "secondaryModule" : 'CC_Qualifications',
        "profileId": profileId
      };
      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=ProfileQualificationHandlerEntryPoint',
        dataType: 'json',
        data: update,
        success: function(resp) {
          if(resp) {
            let table = $('#dataTableQualifications').DataTable();
            table.row($(currentRow)).remove().draw();
            updateMatches();
          }
        }
      });
    }

    $('#dataTableSkills').on( 'draw.dt', function () {
          $('div#dataTableSkills_processing').show();
      }).DataTable({
      "columns": [
        { data: "id"  },
        { data: "name", width: "40%" },
        { data: "rating", width: "25%", sType: "rank", bSortable: true,
          render: function (data, type, row) {
            let elementId = row.id + "_skill_rating";
            let startAmount = (parseFloat((data) ? data : 0).toFixed(1));
            return `<div id="${elementId}" class="SkillTableRatingSelector" data-rating="${startAmount}"></div>`
          }
        },
        { data: "years_of_experience", width: "25%", className: 'dt-center editable'},
        {
          data: null,
          className: "dt-center editor-delete",
          defaultContent: '<span class="suitepicon suitepicon-action-delete"></span>',
          width: "10%",
          orderable: false
        }
      ],
      "columnDefs": [
        { targets: 0, visible: false, searchable: false },
        { className: 'dt-body-rating', targets: 2 },
      ],
      "ajax": {
        "url": 'index.php?entryPoint=ProfileSkillHandlerEntryPoint',
        "type": "POST",
        "data": {
          "action":"get",
          "secondaryModule" : 'CC_Skills',
          "secondaryId": moduleId,
          "profileId": profileId
        }
      },
      "processing": true,
      "drawCallback": (settings, json) => {
        // Enable Ratings on table
        $('.SkillTableRatingSelector').starRating({
          starSize: 18,
          totalStars: 5,
          readOnly: false,
          disableAfterRate: false,
          callback: function(currentRating, $el){
            let table = $('#dataTableSkills').DataTable();
            let currentRow = $el.closest("tr");
            let data = table.row(currentRow).data();

            $el.starRating('setRating', currentRating);
            if(data?.rating || data?.rating===null){
              $('div#dataTableSkills_processing').show();
              data.rating = String(currentRating);
              table.rows().invalidate();
              updateAjaxSkill(profileId,data);
              updateMatches();
            }
          }
        });
      },
      "initComplete": function(settings, json) {
          createSearchElement(
              '#dataTableSkills_wrapper',
              "skill_list",
              profileId,
              'index.php?entryPoint=ProfileSkillHandlerEntryPoint',
              'searchSkills',
              'Select a Skill',
              function(e){
                addAjaxSkill(profileId,{ id : e.id });
              });
      }
    });

    function sortRating(elem){
        return  parseFloat($(elem).attr('data-rating'));
    }

    jQuery.fn.dataTableExt.oSort["rank-asc"] = function (x, y) {
      return ((sortRating(x) < sortRating(y)) ? -1 : ((sortRating(x) > sortRating(y)) ? 1 : 0));
    };

    jQuery.fn.dataTableExt.oSort["rank-desc"] = function (x, y) {
      return ((sortRating(x) < sortRating(y)) ? 1 : ((sortRating(x) > sortRating(y)) ? -1 : 0));
    }

    // Activate an inline edit on click of a table cell
    $('#dataTableSkills').on( 'click', 'tbody td.editable', function (e) {
      let currentRow = $(e.target).closest("tr");
      let tdObj = $(e.target);
      let data = $('#dataTableSkills').DataTable().row(currentRow).data();
      if(tdObj.children("input").length > 0)
        return false;

      let preText = tdObj.html();
      let inputObj = $("<input type='number' />");
      tdObj.html("");

      inputObj.width("60px")
              .height("25px")
              .css({border:"0px",fontSize:"12px"})
              .val(preText)
              .appendTo(tdObj)
              .trigger("focus")
              .trigger("select");

      inputObj.keyup(function(event){
        // press ENTER-key
        if(13 == event.which) {
          let text = $(this).val();
          data.years_of_experience = text;
          tdObj.html(text);
          updateAjaxSkill(profileId,data);
        }
        // press ESC-key
        else if(27 == event.which) {
          tdObj.html(preText);
        }
      });
      // left input
      inputObj.blur(function(){
        let text = $(this).val();
        data.years_of_experience = text;
        tdObj.html(text);
        updateAjaxSkill(profileId,data);
      });

      inputObj.click(function(){
        return false;
      });

    } );

    $('#dataTableQualifications').DataTable({
      "columns": [
        { data: "Id" },
        { data: "Name" },
        { data: "MinimumRequired" },
        // { data: "DigitalSupportRequired",className: "dt-center" },
        { 
          data: null,
          className: "dt-center",
          defaultContent: '<input type="checkbox" id="Id" checked disabled></input>',
          width: "10%",
          orderable: false
        },
        {
          data: null,
          className: "dt-center editor-delete",
          defaultContent: '<span class="suitepicon suitepicon-action-delete"></span>',
          width: "10%",
          orderable: false
        }
      ],
      "columnDefs": [
        { targets: 0, visible: false, searchable: false },
        { className: 'dt-body-level', targets: 2 },
        { className: 'dt-body-support', targets: 3 },
      ],
      "ajax": {
        "url": 'index.php?entryPoint=ProfileQualificationHandlerEntryPoint',
        "type": "POST",
        "data": {
          "action":"get",
          "secondaryModule" : 'CC_Qualification',
          "secondaryId": moduleId,
          "profileId": profileId
        }
      },
      "initComplete": function(settings, json) {
        createSearchElement(
          '#dataTableQualifications_wrapper',
          'qualification_list',
          profileId,
          'index.php?entryPoint=ProfileQualificationHandlerEntryPoint',
          'searchQualifications',
          'Select a Qualification',
          function(e) {
            addAjaxQualification(profileId,{ id : e.id });
          });
      }
    });

    // Activate an inline edit on click of a table cell
    $('#dataTableQualifications').on( 'click', 'tbody td.dt-body-level', function (e) {
    } );

    $('#dataTableQualifications').on( 'click', 'tbody td.dt-body-support', function (e) {
    } );

    // Activate an inline edit on click of a table cell
    $('#dataTableSkills').on( 'click', 'tbody td.editable', function (e) {
      e.preventDefault();
      let currentRow = $(e.target).closest("tr");
      let data = $('#dataTableSkills').DataTable().row(currentRow).data();
    } );

    function getDataRecord(e, tableId) {
      e.preventDefault();
      let currentRow = $(e.target).closest("tr");
      let data = $(tableId).DataTable().row(currentRow).data();
      return {data, currentRow};
    }

    // Delete a record
    $('#dataTableSkills').on('click', 'td.editor-delete', function (e) {
      const moduleId = '#dataTableSkills';
      let {data,currentRow} = getDataRecord(e, moduleId);
      deleteAjaxSkill(profileId,data,currentRow)
    } );

    $('#dataTableQualifications').on('click', 'td.editor-delete', function (e) {
      const moduleId = '#dataTableQualifications';
      let {data,currentRow} = getDataRecord(e, moduleId);
      deleteAjaxQualification(profileId,data,currentRow)
    } );

  });
</script>
{/literal}
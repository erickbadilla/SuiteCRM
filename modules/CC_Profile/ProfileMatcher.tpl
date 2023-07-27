<div class="panel-content">
  <div class="panel panel-default">
    <div class="panel-heading">
      <a class="" role="button" data-toggle="collapse" href="#top-panel-profilematcher" aria-expanded="true">
        <div class="col-xs-10 col-sm-11 col-md-11">
          PROFILE MATCHER
        </div>
      </a>
    </div>
  </div>
  <div class="panel-body panel-collapse panelContainer collapse aria-expanded="true" id="top-panel-profilematcher">
    <div class="tab-content">
      <div class="row detail-view-row">    
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
          <div class="col-xs-12 col-sm-4 label col-1-label">
            Profile:
          </div>
          <div class="col-xs-12 col-sm-8 detail-view-field inlineEdit">
            <select name='profileId' id="profileId" style="width:70%">{$TYPE_OPTIONS}</select>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 detail-view-row-item ">
          <div class="col-xs-12 col-sm-6 detail-view-field inlineEdit">
            <input type="button" class="button" onclick="goMatch('{$MODULENAME}','{$BEANID}', document.getElementById('profileId').value);" value="Match a Profile">
          </div>
        </div>
        <div id="mainDiv" class="col-xs-12 detail-view-row-item" style="display:none">
          <div class="col-xs-12 detail-view-field inlineEdit">
            <div id="resultsDiv">
              {$ratingResults}
              <div class="col-sm-6 col-xs-12">
                <div id="skillChart" style="position: relative; background-color: white;">
                  <canvas id="chart"></canvas>
                </div>
                <table id="skillMainTable" style="display:none;" cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
                      <div  id="skillTitle" class="panel panel-default" style="display:none;  margin-bottom: -1px;">
                        <div class="panel-heading" style="padding: 5px 10px;">
                          Skills
                        </div>
                      </div>
                      <thead>
                          <tr style="font-weight:700; font-size: 13px">
                              <th class="footable-first-visible" style="display: table-cell;">&nbsp;</th>
                              <th style="display: table-cell;width: 10%">Profile</th>
                              <th style="display: table-cell;width: 10%">Skills</th>
                              <th style="display: table-cell;width: 40%">Required</th>
                              <th style="display: table-cell;width: 40%">{$TITLE}</th>
                          </tr>
                      </thead>
                      <tbody id="skillsTable">
                      </tbody>
                </table>
                <table id="noSkillMessage" style="display:none" cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
                    <thead>
                        <tr style="font-weight:700; font-size: 13px">
                          <th class="footable-first-visible" style="display: table-cell;">&nbsp;</th>
                          <th style="display: table-cell;width: 100%;">The profile does not have any skills</th>
                        </tr>
                    </thead>
                </table>
                <div class="col-xs-12 col-sm-6 detail-view-field inlineEdit">
                  <input id="chartButton" type="button" class="button" onclick="changeSkillView()" value="View on Table">
                </div>
              </div>
              <div class="col-sm-5 col-sm-offset-1 col-xs-12">
                <table id="qualificationMainTable" cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
                  <div  id="qualificationTitle" class="panel panel-default" style="display:none;  margin-bottom: -1px;">
                    <div class="panel-heading" style="padding: 5px 10px;">
                      Qualifications
                    </div>
                  </div>
                  <thead>
                      <tr style="font-weight:700; font-size: 13px">
                        <th class="footable-first-visible" style="display: table-cell;">&nbsp;</th>
                        <th style="display: table-cell; width: 20%">Profile</th>
                        <th style="display: table-cell; width: 20%">Qualification</th>
                        <th style="display: table-cell; width: 30%">Required</th>
                        <th style="display: table-cell; width: 30%">{$TITLE}</th>
                      </tr>
                  </thead>
                  <tbody id="qualificationsTable">
                  </tbody>
                </table>
                <table id="noQualificationMessage" cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md">
                  <thead>
                      <tr style="font-weight:700; font-size: 13px">
                        <th class="footable-first-visible" style="display: table-cell;">&nbsp;</th>
                        <th style="display: table-cell;width: 100%">The profile does not have any qualifications</th>
                      </tr>
                  </thead>
                </table>
              </div>
            </div>
            <div>&nbsp;</div>
            <div id="defaultdiv">
              <div class="col-12">
                <table cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table footable footable-1 breakpoint-md" style="">
                  <thead>
                      <tr style="font-weight:700; font-size: 13px">
                        <th class="footable-first-visible" style="display: table-cell;">&nbsp;</th>
                        <th style="display: table-cell;width: 100%; white-space: initial">The profile does not have any qualification or skill</th>
                      </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="custom/include/SugarFields/Fields/SkillRatingExperience/js/rating.js"></script>
<script src='custom/include/generic/javascript/chart/chart.js'></script>
<script language="JavaScript" type="text/javascript">
  {literal}

$(".inlineEditIcon").click(function() {
  setTimeout(function(){ 
    document.getElementById("mobile") == null ? "" : document.getElementById("mobile").onkeyup = function() {changeChar("mobile")};
    document.getElementById("phone") == null ? "" : document.getElementById("phone").onkeyup = function() {changeChar("phone")};
  }, 5000);

});

function changeChar(elem) {
  let x = document.getElementById(elem);
  x.value = x.value.replace(/[^0-9 +]+/g, '');
}

    var showChart = true; // if true, the chart is showed
    var isMobile = false; 
    var matchs; // response from the entry point
    const ctx = document.getElementById('chart').getContext('2d'); // Points to the canvas element

    /**
      * Call the entrypoint for matching the profile
      * @param modulename
      * @param moduleId
      * @param profileId
      */
    function goMatch(modulename, moduleId, profileId) {

      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=profileMatchEntryPoint',
        dataType: 'json',
        data: {secondaryModule:modulename, secondaryId: moduleId, profileId: profileId },
        success: function(resp) {
         matchs = resp;
         createTables(matchs);
        }
      });

      if (typeof updateRatingArea == 'function') {
          $.ajax({
              type: "POST",
              url: 'index.php?entryPoint=profileRatingEntryPoint',
              dataType: 'json',
              data: {secondaryModule:modulename, secondaryId: moduleId, profileId: profileId },
              success: function(resp) {
                  updateRatingArea(resp);
              }
          });
      }
    }
    /**
      * Create the skill and qualification tables
      * with the information from the response
      * @param resp data obtained from the POST method
      */
    function createTables(resp) {
      let qualifications = resp.Qualifications;
      let hasQualifications = false;
      let skills = resp.Skills;
      let hasSkills = false;

      clearTables("qualificationsTable");

      
      let defaultdiv = document.getElementById("defaultdiv");
      let qualificationMainTable = document.getElementById("qualificationMainTable");
      let qualificationTitle = document.getElementById("qualificationTitle");
      let noQualificationMessage = document.getElementById("noQualificationMessage");
      let skillChart = document.getElementById("skillChart");
      let skillMainTable = document.getElementById("skillMainTable");
      let skillTitle = document.getElementById("skillTitle");
      let noSkillMessage = document.getElementById("noSkillMessage");
      let resultdiv = document.getElementById("resultsDiv");
      let changeButton = document.getElementById("chartButton");

    // Set divs to they initial value
      if(skills.length < 3){
        showChart = false;
        skillChart.style.display = "none";
        skillMainTable.style.display = "block";
        changeButton.value = "View Chart";
      }else{
        showChart = true;
        skillChart.style.display = "block";
        skillMainTable.style.display = "none";
        changeButton.value = "View on Table";
      }
      
      defaultdiv.style.display = "initial";
      skillTitle.style.display = "none";
      noSkillMessage.style.display = "none";
      qualificationMainTable.style.display = "block";
      qualificationTitle.style.display = "block";
      noQualificationMessage.style.display = "none";
      changeButton.style.display = "block";

      qualifications.forEach(qualification => {
        if(qualification.ProfileMinimumRequired !== null){
          hasQualifications = true;
          let element = document.getElementById("qualificationsTable");
          let tr = document.createElement("tr");

          tr.appendChild(createColumn("\u00A0"));
          tr.appendChild(createColumn($("#profileId option:selected").text()));
          tr.appendChild(createColumn(qualification.Name));
          tr.appendChild(createColumn(qualification.ProfileMinimumRequired));
          tr.appendChild(createColumn(qualification.ModuleActualQualification));

          element.appendChild(tr);
        }
      })
      
      createSkillChart(skills);
      hasSkills = createSkillTable(skills);

      if(!hasSkills){
        skillChart.style.display = "none";
        skillMainTable.style.display = "none";
        skillTitle.style.display = "none";
        noSkillMessage.style.display = "block";
        changeButton.style.display = "none";
      }
    
      if(!hasQualifications){
        qualificationMainTable.style.display = "none";
        qualificationTitle.style.display = "none";
        noQualificationMessage.style.display = "block";
      }

      if(!hasQualifications && !hasSkills){ 
        defaultdiv.style.display = "initial";
        resultdiv.style.display = "none";
        changeButton.style.display = "none";
      } else {
        defaultdiv.style.display = "none";
        resultdiv.style.display = "block";
      }

      let mainDiv = document.getElementById("mainDiv");
      mainDiv.style.display = "initial";
      screenWidthChange();
    }

    /**
      * Clear the tables
      * @param tableId table id.
      */
    function clearTables(tableId){
      let element = document.getElementById(tableId);

      while (element.hasChildNodes()) {  
        element.removeChild(element.firstChild);
      }
    }

   /**
      * Create the field depending on the requirements
      * @param type type of field
      * @param amount amount of the skill/qualification
      * @param id id used for the starfield
      * @param isProfile establish diferences between profile and 
      * secondary module for the fields to be created
      */
    function createField(type, amount, id = "", isProfile = true){
      let field;
      let startAmount = (amount) ? amount : 0;

      if(window.innerWidth > 460) {
        field = createStarfield(id, startAmount, isProfile);
      } else {
        field = createColumn( startAmount + '/5', "center");
      }

      return field;
    }

    /**
      * Creates a regular column
      * @param text information to be presented on the column
      */
    function createColumn(text, align = "left"){
      let fieldtext;
      let td = document.createElement("td");
      td.style.display = "table-cell";

      if (text === null){
        fieldtext = document.createTextNode("\u00A0");
      } else {
        fieldtext = document.createTextNode(text);
      }

      td.appendChild(fieldtext);
      td.style.textAlign = align;

      return td;
    }

    /**
      * Create the stars field
      * @param id id of the field
      * @param amount amount of the skill/qualification
      * @param isProfile establish diferences between profile and 
      * secondary modules for the fields to be created
      */
    function createStarfield(id, amount, isProfile = true){
      let ratingdiv = document.createElement("div");
      let td = document.createElement("td");
      
      td.style.display = "table-cell";
      ratingdiv.setAttribute("id", "SkillRatingExpSelectorArea");
      ratingdiv.style.width = "100%";
      ratingdiv.style.float = "left";

      if(!isProfile) {
        ratingdiv.style.left = "52%";
      }

      let stardiv = document.createElement("div");
      stardiv.style.float = "left";
      stardiv.setAttribute("id", id);
      stardiv.setAttribute("data-rating", amount);
      ratingdiv.appendChild(stardiv);
      td.appendChild(ratingdiv)

      return td
    }
    /**
    * Create the skill table
    * @param skills
    */
    function createSkillTable(skills){
      clearTables("skillsTable");
      let hasSkills = false;
      skills.forEach(skill => {
        if(skill.ProfileRelation === 'rating' || skill.moduleType === 'rating'){
          hasSkills = true;
          let profileId = skill.Id + 'profile';
          let moduleId = skill.Id + 'secondaryModule';
          let element = document.getElementById("skillsTable");
          let tr = document.createElement("tr");

          tr.appendChild(createColumn("\u00A0"));
          tr.appendChild(createColumn($("#profileId option:selected").text()));
          tr.appendChild(createColumn(skill.Name));
          tr.appendChild(createField(skill.ProfileRelation,
                                     skill.ProfileAmount,
                                     profileId));
          tr.appendChild(createField(skill.moduleType,
                                     skill.moduleAmount,
                                     moduleId, false));

          element.appendChild(tr);
          starsField(profileId);
          starsField(moduleId);
        }
      })

      return hasSkills;
    }


    /**
    * Create the skill chart
    * @param skills
    */
    function createSkillChart(skills){
      let labelsChart = [];
      let profileChartSkills = [];
      let moduleChartSkills = [];

      skills.forEach(skill => {

        if(skill.ProfileRelation === 'rating' || skill.moduleType === 'rating'){
          labelsChart.push(skill.Name);
          let profileAmount = (skill.ProfileAmount/5)*100;
          let moduleAmount = (skill.moduleAmount/5)*100;
          profileChartSkills.push(profileAmount);
          moduleChartSkills.push(moduleAmount);
        }
      })

      generateChart(labelsChart,profileChartSkills,moduleChartSkills)
      
    };

    /**
      * Generates the chart
      * @param labels
      * @param profileSkills
      * @param moduleSkills
      */
    function generateChart(labels, profileSkills, moduleSkills){
      var chart = new Chart(ctx, {
      type: 'radar',
      data: {
        labels: labels,
        datasets: [{
          data: moduleSkills,
          label: '{/literal}{$TITLE}{literal}',
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
    function changeSkillView(){
      let skillsTable = document.getElementById("skillMainTable");
      let skillTitle = document.getElementById("skillTitle");
      let skillsChart = document.getElementById("skillChart");
      let changeButton = document.getElementById("chartButton");

      if(showChart){
        showChart = false;
        skillsChart.style.display = "none";
        skillsTable.style.display = "block";
        skillTitle.style.display = "block";
        changeButton.value = "View Chart";
      } else {
        showChart = true;
        skillsChart.style.display = "block";
        skillsTable.style.display = "none";
        skillTitle.style.display = "none";
        changeButton.value = "View on Table";
      }

    }

    /**
      * Check changes on the screen width
      */
    function screenWidthChange(){
      window.onresize = displayWindowSize;
    }

    /**
      * Generate the skill tables depending on the screen width
      */
    function displayWindowSize() {
      let screenWidth = window.innerWidth;
      if(screenWidth < 460 && !isMobile) {
        isMobile = true;
        createSkillTable(matchs.Skills);
      } else if(screenWidth > 460 && isMobile){
        isMobile = false;
        createSkillTable(matchs.Skills);
      }
    };

    /**
      * Generate starsField
      */
    function starsField(id){
      $('#' + id).starRating({
        starSize: 23,
        totalStars: 5,
        readOnly: true
      });
    }


  {/literal}
</script>

    let tablaNotes = ""; 
    let tablaInterviewResult = ""; 
    $(document).ready(function(){
        var applicationId = $('#job_applications_apply_application_id').val();
        waitForApplicationId();
        Calendar.setup ({
            inputField : "txt_interview_date",
            ifFormat : "%m/%d/%Y %H:%M",
            daFormat : "%m/%d/%Y %H:%M",
            button : "txt_interview_date_trigger",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
            }
            );

    });


$(function() {
    let candidateData = null;
    let jobOfferData = null;
    
    let candidateId = $('#job_applications_apply_candidate_id').val();
    let jobOfferId = $('#job_applications_apply_job_offer_id').val();

    function activeTab(tab){
        $('#jobApplicationPanelDetailsSelector a[href="#' + tab + '"]').tab('show');
    }

    function checkForCandidateRatings(){
        if(Boolean(jobOfferId) && Boolean(candidateId)){
            $.ajax({
                type: "POST",
                url: 'index.php?entryPoint=JobApplicationsEntryPoint',
                dataType: 'json',
                data: {
                    action: 'getRatings',
                    candidateId: candidateId,
                    jobOfferId: jobOfferId
                }
            }).done(function (data){
                if(data){
                    updateRatingElement(".ratingItemSkill", data.skills);
                    updateRatingElement(".ratingItemQualification", data.qualifications);
                    updateRatingElement(".ratingItemGeneral", data.general);
                    $('#ratingAreaContainer').show();
                } else {
                    $('#ratingAreaContainer').hide();
                }

            });
        } else {
            $('#ratingAreaContainer').hide();
        }
    }

    function updateRatingElement(element, value){
        let rating_styles = [ "Expired", "Warning", "Active", "Base" ];
        let base_style_name = "ApplicationRating";
        let actual_rating_styles = rating_styles.map(i => base_style_name + i);
        let rating_result = Math.floor(value / (100/rating_styles.length ) );
        if(rating_result >= rating_styles.length){
            rating_result = rating_styles.length -1;
        }
        let rating_element_new_style = base_style_name + rating_styles[rating_result];
        $(element+" div:first-child").removeClass(actual_rating_styles.join(' '));
        $(element+" div:first-child").addClass(rating_element_new_style);
        $(element+" span:first-child").html(value);
    }

   function jobOfferSelect(data) {
        if(data){            
            activeTab("jobofferDataPanel");
            jobOfferData = data;
            for (const [key, value] of Object.entries(jobOfferData)) {
                let element = $("#jobofferDataPanel div.user-input[field="+key+"]");
                if(element.length>0) {
                    $(element).html(value);
                }
            }
            $('#job_applications_apply_job_offer_id').val(jobOfferData.id);
            $("#selectJobOfferLabel").html(jobOfferData.name);
            checkForCandidateRatings();
        } else {
            jobOfferData = null;
        }
    }

    function showEmptyInfo() {
        $( "#personalityTestDataEmpty" ).show();
        $( "#personalityTest > div:first" ).hide();
    }
 
    function hideEmptyInfo(){
        $( "#personalityTestDataEmpty" ).hide();
        $( "#personalityTest > div:first" ).show();
    }
    
    function candidateSelect(data){
        if(data){                        
            activeTab("candidateDataPanel");
            candidateData = data;
            for (const [key, value] of Object.entries(candidateData)) {
                $("#candidateDataPanel div.user-input[field="+key+"]").html(value);
                let element = $("input.user-input[field="+key+"]");
                if(element.length>0) {
                    $(element).val(value);
                }

                let elementchk = $("input.user-input2[field="+key+"]");
                if(elementchk) {
                    $(elementchk).prop("checked", value);
                }

            }

            checkForCandidateRatings();

            const personalityTest = data.PersonalityTest;
            
            if(personalityTest.length>0){
                hideEmptyInfo();
                let table = $('#tablePersonalityTest').DataTable({
                    data: personalityTest,
                    columns: [
                        { data: 'pattern' },
                        { data: 'score_index' },
                        { data: 'modified_user_id' },
                        { data: 'date_entered' },
                        {
                            orderable: false,
                            render: function () {
                                return '<a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#detailPersonalitytTestModal">Pattern Detail</a>';
                            }
                        }
                    ]
                });

                $("#tablePersonalityTest tbody").on("click", "tr", function () {
                    const personalityTest = table.row( this ).data();
                    let string = '';
                    for (const [key, value] of  Object.entries(personalityTest.pattern_data) ) {
                        if(key != 'pattern'){
                            let subtitle = key.replace(/_/g, ' ');
                            subtitle = subtitle.charAt(0).toUpperCase() + subtitle.slice(1);
                            string = string+'<h4 style="color: #333333;padding: 0;font-weight: bold;">'+subtitle+'</h4>';                
                            for (i=0; i < value.length; i++) {
                                string = string + '<p style="color: #585858;white-space: break-spaces;">'+value[i]+'</p>';
                            }
                        }
                    }
                    $(".modal-title").text(personalityTest.Pattern)
                    $("#modal_body").empty();
                    $("#modal_body").append(string);
                });
            }else {
                showEmptyInfo();
            }
            $('#job_applications_apply_candidate_id').val(candidateData?.Id);
            $("#selectCandidateLabel").html(candidateData?.Name);
            checkForCandidateRatings();
        } else {
            jobOfferData = null;
        }
    }

    function loadData(elementId, url, moduleAction, functionActionSelect){        
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                action: moduleAction,
                elementId: elementId
            }
        }).done(function (data){
            const mapData = $.map(data.results, function (obj) {
                obj.id = obj.id || obj.Id;
                obj.text = obj.text || obj.Name;
                obj.text = (obj.text)?obj.text:obj.name;
                return obj;
            });
            // mapData[0].City ? 
            //     $('div.user-input[field="candidate"]').html(`Candidate ${mapData[0].Name}`) : 
            //     $('div.user-input[field="profile"]').html(`${mapData[0].name} ${mapData[0].profiles.length>1 ? 'Profiles' : 'Profile'}`)
            const recordData = mapData[0];

            functionActionSelect(recordData);

        });
    }

    function drawRatings(className, tableId){
        $('td.'+className).each(function() {
          let currentRow = $(this).closest("tr");
          let data = $('#'+tableId).DataTable().row(currentRow).data();
          if(data){
            createRatingElement($( this ),data);
          }
        });
      }
      
      
    function createRatingElement(e,data){
        e.empty();
        e.starRating({
          starSize: 15,
          totalStars: 5,
          readOnly: true,
          disableAfterRate: true,
          callback: function(currentRating, $el){}
        });
      
        e.starRating('setRating', data.rating);
        e.append(`<p class="dt-body-rating"><svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='currentColor' class='bi bi-alarm' viewBox='0 0 16 16'><path d='M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5z'/><path d='M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1h-3zm1.038 3.018a6.093 6.093 0 0 1 .924 0 6 6 0 1 1-.924 0zM0 3.5c0 .753.333 1.429.86 1.887A8.035 8.035 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5zM13.5 1c-.753 0-1.429.333-1.887.86a8.035 8.035 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1z'/></svg>&nbsp;${data.years} Year(s)</p>`);
      
    }

    function getSummarySkills(){
        const data_Send = {
            "action":"getSkillsSummary",
            "jobOfferId": jobOfferId,
            "candidateId": candidateId
        };

        $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=JobApplicationListViewEntryPoint',
            dataType: 'json',
            data: data_Send,
            success: function(resp) {
                const data = resp['data'];
                Object.entries(data).forEach(([key, value]) => {
                    const url_profile = `index.php?module=${value.object_name_profile}&offset=1&return_module=${value.object_name_profile}&action=DetailView&record=${value.id_profile}`;
                    const url_skill = `index.php?module=${value.object_name_skills}&offset=1&return_module=${value.object_name_skills}&action=DetailView&record=${value.id_skills}`;
                    const name_profile = value.name_profile ? value.name_profile : (value.others_candidate_skills ? value.others_candidate_skills : "");
                    const subheading = value.id_profile ? 
                            `<a href='${url_profile}' target='_blank'>${name_profile}</a>` :
                            `<strong>${name_profile}</strong>`;
                    const name_skill_profile = value.name ? value.name : "";
                    const name_skill_candidate = value.name_skill_candidate?value.name_skill_candidate: "";

                    $("#table_skills_profile tbody").append(`<tr>
                        <td>${subheading}</td>
                        <td><a href='${url_skill}' target='_blank'>${name_skill_profile}</a></td>
                        <td class="dt-body-rating-profile-${key}"></td>
                        <td><a href='${url_skill}' target='_blank'>${name_skill_candidate}</a></td>
                        <td class="dt-body-rating-candidate-${key}"></td>
                    </tr>"`);

                    if(value.rating || value.years){
                        const profileElement = $('td.dt-body-rating-profile-'+key);
                        createRatingElement(profileElement, value);
                    }
                    if(value.rating_candidate || value.years_candidate){
                        const candidateElement = $('td.dt-body-rating-candidate-'+key);
                        const values = {
                            rating: value.rating_candidate,
                            years: value.years_candidate
                        }
                        createRatingElement(candidateElement, values);
                    }
                });
                
                let groupColumn = 0;
                let tableskillsProfile = $('#table_skills_profile').DataTable({
                    "columnDefs": [
                        { "visible": false, "targets": groupColumn }
                    ],
                    "responsive": true,
                    "retrieve": true,
                    "paging" : true,
                    "order": [[ groupColumn, 'asc' ]],
                    "info" : true,
                    "filter" : true,
                    "drawCallback": function ( settings ) {
                        let api = this.api();
                        let rows = api.rows( {page:'current'} ).nodes();
                        let last=null;
                
                        api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                            if ( last !== group ) {
                                $(rows).eq(i).before(
                                    `<tr class="group"><td colspan="4">${group}</td></tr>`
                                );
                                last = group;
                            }
                        } );
                    }
                });
                
                // Order by the grouping
                $('#table_skills_profile tbody').on( 'click', 'tr.group', function () {
                    let currentOrder = tableskillsProfile.order()[0];
                    if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                        tableskillsProfile.order( [ groupColumn, 'desc' ] ).draw();
                    }
                    else {
                        tableskillsProfile.order( [ groupColumn, 'asc' ] ).draw();
                    }
                });
            }
        });
    }

    function getSummaryQualifications(){
        const data_Send = {
            "action":"getQualificationsSummary",
            "jobOfferId": jobOfferId,
            "candidateId": candidateId
        };
    
        $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=JobApplicationListViewEntryPoint',
            dataType: 'json',
            data: data_Send,
            success: function(resp) {
                let data = resp['data'];
                Object.entries(data).forEach(([key, value]) => {
                    const url_profile = `index.php?module=${value.object_name_profile}&offset=1&return_module=${value.object_name_profile}&action=DetailView&record=${value.id_profile}`;
                    const url_qualifications = `index.php?module=${value.object_name_qualifications}&offset=1&return_module=${value.object_name_qualifications}&action=DetailView&record=${value.id_qualifications}`;
                    const name_profile = value.name_profile ? value.name_profile : (value.others_candidate_qualifications ? value.others_candidate_qualifications : "");
                    const name_qualification_profile = value.name ? value.name : "";
                    const name_qualification_candidate = value.name_qualification_candidate ? value.name_qualification_candidate : "";
                    const minimum_reuiered_profile = value.mininum_requiered ? value.mininum_requiered : "";
                    const minimum_reuiered_candidate = value.minimum_reuiered_candidate ? value.minimum_reuiered_candidate : "";
                    const subheading = value.id_profile ? 
                            `<a href='${url_profile}' target='_blank'>${name_profile}</a>` :
                            `<strong>${name_profile}</strong>`;

                    $("#table_qualifications_profile tbody").append(`<tr>
                        <td>${subheading}</td>
                        <td><a href='${url_qualifications}' target='_blank'>${name_qualification_profile}</a></td>
                        <td>${minimum_reuiered_profile}</td>  
                        <td><a href='${url_qualifications}' target='_blank'>${name_qualification_candidate}</a></td>
                        <td>${minimum_reuiered_candidate}</td> 
                    </tr>"`); 
                });
                
                let groupColumn = 0;
                let tableQualificationsProfile = $('#table_qualifications_profile').DataTable({
                    "columnDefs": [
                        { "visible": false, "targets": groupColumn }
                    ],
                    "responsive": true,
                    "retrieve": true,
                    "paging" : true,
                    "order": [[ groupColumn, 'asc' ]],
                    "info" : true,
                    "filter" : true,
                    "drawCallback": function ( settings ) {
                        let api = this.api();
                        let rows = api.rows( {page:'current'} ).nodes();
                        let last=null;
                
                        api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                            if ( last !== group ) {
                                $(rows).eq(i).before(
                                    `<tr class="group"><td colspan="4">${group}</td></tr>`
                                );
                                last = group;
                            }
                        } );
                    }
                });
                
                // Order by the grouping
                $('#table_qualifications_profile tbody').on( 'click', 'tr.group', function () {
                    let currentOrder = tableQualificationsProfile.order()[0];
                    if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                        tableQualificationsProfile.order( [ groupColumn, 'desc' ] ).draw();
                    }
                    else {
                        tableQualificationsProfile.order( [ groupColumn, 'asc' ] ).draw();
                    }
                });
    
            }
        });
    
    }

    getSummarySkills();
    getSummaryQualifications();

    loadData(
        jobOfferId,
        'index.php?entryPoint=JobOfferApplicationEntryPoint',
        'getJobOfferById',
        function (e) {     
            jobOfferSelect(e);
        });

    loadData(
        candidateId,
        'index.php?entryPoint=CandidateApplicationEntryPoint',
        'getCandidateById',
        function (e) {
            candidateSelect(e);
        });

    $('#steps > div').click(function(){

        var i = $(this).attr('data-step') - 1;

        $('#steps > div').each(function( index ){
            if ( index < i) {
                $(this).addClass('done');
                $(this).removeClass('active');
            } else if ( index == i) {
                $(this).addClass('active');
                $(this).removeClass('done');
            } else {
                $(this).removeClass('done');
                $(this).removeClass('active');
            }
        });

        $('article').each(function( index ){
            if ( index < i - 1 ) {
                clearClasses( $(this) );
                $(this).addClass('active_m_2');
            } else if ( index > i + 1 ) {
                clearClasses( $(this) );
                $(this).addClass('active_p_2');
            } else if ( index < i ) {
                clearClasses( $(this) );
                $(this).addClass('active_m_1');
            } else if ( index > i ) {
                clearClasses( $(this) );
                $(this).addClass('active_p_1');
            } else {
                clearClasses( $(this) );
                $(this).addClass('active');
            }
        });

        function clearClasses( item ) {
            var a = [
                "active_m_2",
                "active_m_1",
                "active",
                "active_p_1",
                "active_p_2"
            ];
            a.forEach( function( class_name ) {
                item.removeClass( class_name );
            });
        }

    });

});

//////// Related Notes ////////////

function quickCreateShow(elem, flg){
    if(flg == 1){
        document.getElementById('QuickCreate').style.display = 'inline';
        elem.style.display = 'none';

        $.post(
            'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getPermissions',
            '',
            function (data) {
                if(data){
                   let result = data['results'];
                   $("#slc_permissions").html("");
                 
                   $("#slc_permissions").append(`<option value="">Select an option</option>`);
                   for (let index = 0; index < result.length; index++) {
                       const element = result[index];
                        $("#slc_permissions").append(`<option value="${element.id}">${element.name}</option>`);
                   }
                    
                }
            },
            'json'
        )

    }else{
        document.getElementById('QuickCreate').style.display = 'none';
        document.getElementById('id_note').value = '';
        document.getElementById('create-note').style.display = 'inline';
        document.getElementById('name').value = '';
        document.getElementById('description').value = '';
        document.getElementById('myfile').value = '';
        document.getElementById('contect_attachement').style.display = 'none'
    }
}

function getRelatedNotes(applicationId){
    
   
    let data_send = {
      action : "getRelatedNotes",
      applicationId: applicationId
    }
      
  if(tablaNotes != ""){
    tablaNotes.destroy();
  }
  
  tablaNotes =  $("#table_notes").DataTable({
       "responsive": true,
       "ajax" :{
          "url": 'index.php?entryPoint=JobApplicationListViewEntryPoint',
          "type": "POST",
          'data' : data_send,
       },
       "paging" : true,
       "order": [[ 1, "desc" ]],
       "info" : true,
       "filter" : true,
        "columnDefs": [
          {
              "targets": [0], 
              "data": "name", 
              "className": "",
              "render": function(data, type, row){
                let url_note =  `?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DNotes%26action%3DDetailView%26record%3D${row.id}`;
                return `<a href="${url_note}" target="_blank">${data}</a>`;   
              }
            },
            {
                "targets": [1], 
                "data": "date_entered", 
                "className": "",
                "render": function(data, type, row){
                   return `<p>${data}</p>`;   
                }
              },
            {
              "targets": [2], 
              "data": "id", 
              "className": "",
              "render": function(data, type, row){
                if(row.filename === null ){
                    return '<p>Not Attachment</p>'
                }else{
                let url_file =  `index.php?preview=yes&entryPoint=download&id=${data}&type=Notes`;
                return `<a href="${url_file}" target="_blank">${row.filename}</a>`;
                }   
              }
            },
            {
                "targets": [3], 
                "data": "id", 
                "className": "",
                "render": function(data, type, row){
                    return `<p>${row.description}</p>`;
                }
              },
             {
               "targets": [4],
               "sortable": false, 
                "data": "id", 
               "className": "td_delete_profile",
               "render": function(data, type, row){
                  
                  if(parseInt(row.stage) === 1 || row.stage === null){
                      
                    return `<span title="Edit Note" style="font-size: 16px;" onclick="editNote('${row.id}','${applicationId}')" class="suitepicon suitepicon-action-edit"></span></span>`;
                   }else{
                       return '<span></span>'
                   }
                }
             }
        ]
     });
    
    }

function createRelatedNote(elem){

    let inputFile = document.getElementById('myfile');
    let file = inputFile.files[0];
    let data_send = new FormData(); 
    let name              = $("#name").val();
    let description       = $("#description").val();
    let hasfile           = $("#hasfile").val();
    let id_note           = $("#id_note").val();
    let id_status              = $("#slc_step").val();
    let name_status            = $("#slc_step").find("option:selected").text();
    let group_security    = $("#slc_permissions").val();
    let action            = "CreateNote";
  
    data_send.append('file',file);
    data_send.append('name',name);
    data_send.append('description',description);
    data_send.append('hasfile',hasfile);
    data_send.append('id_note',id_note);
    data_send.append('applicationId',applicationId);
    data_send.append('id_status',id_status);
    data_send.append('name_status',name_status);
    data_send.append('group_security',group_security);
    data_send.append('action',action);
  
    if(name == ""){
      toastr.warning("Please enter the Subject", 'Oops!');
      return;
    }  
    if(id_status == ""){
        toastr.warning("Please select a step", 'Oops!');
        return;
      }    
  
    $.ajax({
      url: 'index.php?entryPoint=JobApplicationListViewEntryPoint',
      type:'POST',
      data:data_send,
      processData:false,
      contentType:false,
      cache:false,
      dataType: 'json',
      beforeSend: function(){
        elem.html('<span class="glyphicon glyphicon-refresh spinning"></span> Sending ');
        elem.prop("disabled", true);
      },
      statusCode:{
          200 : function(resp){
            elem.html('Save');
            elem.prop("disabled", false);
            let data_resp = resp['results'];
            if(data_resp){
              toastr.success("Note created successfully", 'Successful');
              quickCreateShow(0, 0);
              getRelatedNotes(applicationId);
            }else{
              toastr.error('Error when created a new note', 'Oops!');
            }
          },
          500 : function(resp) {
            elem.html('Save');
            elem.prop("disabled", false);
            toastr.error('Error when created a new note: Error:500', 'Oops!');
          }
      }
    }); 
  
  }


    function editNote(idNote){

        document.getElementById('QuickCreate').style.display = 'inline';
        document.getElementById('create-note').style.display = 'none';
        $("#contect_attachement").hide();
        
        let action = "GetNoteSingle";
    
        const data_send = {
            idNote,
            action
        };
        
        $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=JobApplicationListViewEntryPoint',
            dataType: 'json',
            data: data_send,
            success: function(resp) {
              let data = resp['results'][0];
    
              $("#name").val(data.name);
              $("#description").val(data.description);
              $("#id_note").val(data.id);
              $("#slc_step").val(data.state);
             
              if(data.filename.length != 0 ){ 
                $("#hasfile").val(1);
                $("#contect_attachement").show();
                $("#attachement_path").prop("href",`index.php?preview=yes&entryPoint=download&id=${data.id}&type=Notes`);

              }

              let group_id = data.group_id;

              $.post(
                'index.php?entryPoint=JobApplicationsEntryPoint&stageAction=getPermissions',
                '',
                function (data) {
                    if(data){
                       let result = data['results'];
                       $("#slc_permissions").html("");
                       $("#slc_permissions").append(`<option value="">Select an option</option>`);
                       for (let index = 0; index < result.length; index++) {
                           const element = result[index];
                           let selected = (group_id == element.id) ? "selected" : "";
                            $("#slc_permissions").append(`<option value="${element.id}" ${selected}>${element.name}</option>`);
                       }
                        
                    }
                },
                'json'
               )

               $("#name").focus();

            }
        });
    }

//////// Interview Resul ////////////

function quickCreateInterviewShow(elem, flg){
    if(flg == 1){
        document.getElementById('QuickCreateResult').style.display = 'inline';
        elem.style.display = 'none';
    }else{
        document.getElementById('QuickCreateResult').style.display = 'none';
        document.getElementById('create-intresult').style.display = 'inline';
        $('#QuickCreateResult').find('input').each(function() {
            $(this).val('');
          })
        $('#QuickCreateResult').find('textarea').each(function() {
            $(this).val('');
          })
    }
}

function getInterviewResult(applicationId){
    
    let data_send = {
      action : "getInterviewResult",
      applicationId: applicationId
    }
      
  if(tablaInterviewResult != ""){
    tablaInterviewResult.destroy();
  }
  
  tablaInterviewResult =  $("#table_intresults").DataTable({
       "responsive": true,
       "ajax" :{
          "url": 'index.php?entryPoint=JobApplicationListViewEntryPoint',
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
              "data": "name", 
              "className": "",
              "render": function(data, type, row){
                return `<p style="color:#f08377" onclick="editIntResult('${row.id}',1)">${data}</p>`;   
              }
            },
            {
              "targets": [1], 
              "data": "id", 
              "className": "",
              "render": function(data, type, row){
                return `<p>${row.type}</p>`;  
              }
            },
            {
                "targets": [2], 
                "data": "id", 
                "className": "",
                "render": function(data, type, row){
                    return `<p>${row.result}</p>`;
                }
              },
              {
                "targets": [3], 
                "data": "id", 
                "className": "",
                "render": function(data, type, row){
                    return `<p>${row.interview_date}</p>`;
                }
              },
             {
               "targets": [4],
               "sortable": false, 
                "data": "id", 
               "className": "td_delete_profile",
               "render": function(data, type, row){
                 return  `<span title="Edit Interview Result" style="font-size: 16px;" onclick="editIntResult('${row.id}','${applicationId}')" class="suitepicon suitepicon-action-edit"></span></span>`;
               }
             }
        ]
     });
    
    }

function createInterviewResult(elem){

    let data_send = new FormData(); 
    let name              = $("#nameInt").val();
    let description       = $("#descriptionInt").val();
    let approved          = $("#approved").val();
    let txt_interview_date= $("#txt_interview_date").val();
    let interview_hours   = $("#interview_date_hours").val();
    let interview_minutes = $("#interview_date_minutes").val();
    let observation       = $("#observation").val();
    let result            = $("#result").val();
    let type              = $("#type").val();
    let english_level     = $("#english_level").val();
    let positive_aspects  = $("#positive_aspects").val();
    let what_to_improve   = $("#what_to_improve").val();
    let recommended       = $("#recommended").val();
    let other_position    = $("#other_position").val();
    let id_intresult      = $("#id_intresult").val();
    let action            = "CreateInterviewResult";
    let interview_results = "";
    	
    var today = new Date();
    var momentmin = today.getMinutes() < 10 ? '0'+ today.getMinutes() :today.getMinutes();
    if('00'< momentmin > '14'){
        var minutesF = '00'
    }else if('15'< momentmin > '29'){
        var minutesF = '15'
    }else if('30'< momentmin > '44'){
        var minutesF = '30'
    }else{
        var minutesF = '45'
    }
    var actualD = today.getFullYear() + '-' + today.getMonth() + 1  + '-' + (today.getDate() < 10 ? '0'+ today.getDate() :today.getDate());
    var actualH = (today.getHours() < 10 ? '0'+ today.getHours() :today.getHours()) + ':' + (minutesF) + ':00';
    var dAndH = actualD + ' ' + actualH;

    let interview_date    = txt_interview_date === '' ? dAndH : convertDateFormat(txt_interview_date) + ' ' + (interview_hours === '' ? '00' : interview_hours) + ':' + (interview_minutes === '' ? '00' : interview_minutes)  + ':00';

  
    data_send.append('name',name);
    data_send.append('description',description);
    data_send.append('approved',approved);
    data_send.append('interview_date',interview_date);
    data_send.append('observation',observation);
    data_send.append('result',result);
    data_send.append('type',type);
    data_send.append('english_level',english_level);
    data_send.append('positive_aspects',positive_aspects);
    data_send.append('what_to_improve',what_to_improve);
    data_send.append('recommended',recommended);
    data_send.append('other_position',other_position);
    data_send.append('id_intresult',id_intresult);
    data_send.append('applicationId',applicationId);
    data_send.append('interview_results',interview_results);
    data_send.append('action',action);
  
    if(name == ""){
      toastr.warning("Please enter the Subject", 'Oops!');
      return;
    }    
  
    $.ajax({
      url: 'index.php?entryPoint=JobApplicationListViewEntryPoint',
      type:'POST',
      data:data_send,
      processData:false,
      contentType:false,
      cache:false,
      dataType: 'json',
      beforeSend: function(){
        elem.html('<span class="glyphicon glyphicon-refresh spinning"></span> Sending ');
        elem.prop("disabled", true);
      },
      statusCode:{
          200 : function(resp){
            elem.html('Save');
            elem.prop("disabled", false);
            let data_resp = resp['results'];
            if(data_resp){
              toastr.success("Interview Result added successfully", 'Successful');
              quickCreateInterviewShow(0, 0);
              getInterviewResult(applicationId);
            }else{
              toastr.error('Error adding a new Interview Result', 'Oops!');
            }
          },
          500 : function(resp) {
            elem.html('Save');
            elem.prop("disabled", false);
            toastr.error('Error adding a new Interview Result: Error:500', 'Oops!');
          }
      }
    }); 
  
  }

  function convertDateFormat(string) {
    var info = string.split('/');
    return info[2] + '-' + info[0] + '-' + info[1];
  }


    function editIntResult(idResult, flag){

        document.getElementById('QuickCreateResult').style.display = 'inline';
        document.getElementById('create-intresult').style.display = 'none';
        
        let action = "GetIntResultSingle";
    
        const data_send = {
            idResult,
            action
        };
        
        $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=JobApplicationListViewEntryPoint',
            dataType: 'json',
            data: data_send,
            success: function(resp) {
              let data = resp['results'][0];
                const dateLong = data.interview_date.split(" ");
                const hourLong = (Array.isArray(dateLong))?dateLong[1].split(":"):['00','00'];

              $("#nameInt").val(data.name);
              $("#descriptionInt").val(data.description);
              $("#approved").val(data.approved);
              $("#txt_interview_date").val(dateLong[0]);
              $("#interview_date_hours").val(hourLong[0]);
              $("#interview_date_minutes").val(hourLong[1]);
              $("#observation").val(data.observation);
              $("#result").val(data.result);
              $("#type").val(data.type);
              $("#english_level").val(data.english_level);
              $("#positive_aspects").val(data.positive_aspects);
              $("#what_to_improve").val(data.what_to_improve);
              $("#recommended").val(data.recommended);
              $("#other_position").val(data.other_position);
              $("#id_intresult").val(data.id);
             
              if(flag == 1){
                $("#save_inter").hide();
              }else{
                $("#save_inter").show();
              }

            }
        });
    }

    function getIntResultType(){  
        let action = "GetIntResultType";
        const data_send = {
            action
        };
        let html = "";
        
        $.ajax({
            type: "POST",
            url: 'index.php?entryPoint=JobApplicationListViewEntryPoint',
            dataType: 'json',
            data: data_send,
            success: function(resp) {
                let data  = resp['types']
                Object.entries(data).forEach(([key, value]) => {
                   html+=`<option value="${key}">${value}</option>`;
                 });
              $("#type").html(html);
            }
        });
    }

function waitForApplicationId(){
    if(typeof applicationId !== undefined && applicationId != "") {
        getRelatedNotes(applicationId);
        getInterviewResult(applicationId);
        getIntResultType();
    }
    else{
        setTimeout(waitForApplicationId, 250);
    }
}


    
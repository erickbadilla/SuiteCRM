$(function() {

    let candidateData = null;
    let jobOfferData = null;
    let selectAppType = null;

    function activeTab(tab){
        $('#jobApplicationPanelDetailsSelector a[href="#' + tab + '"]').tab('show');
    }

    function showPanelInfo(panel){
       $( "#"+panel+"DataPanel > h3" ).hide();
       $( "#"+panel+"DataPanel > div:first" ).show();
    }

    function hidePanelInfo(panel){
       $( ""+panel+"DataPanel > h3" ).show();
       $( "#"+panel+"DataPanel > div:first" ).hide();
    }

    function checkForCandidateRatings(){
        let candidateId = $('#job_applications_apply_candidate_id').val();
        let jobOfferId = $('#job_applications_apply_job_offer_id').val();
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
            showPanelInfo("joboffer");
            jobOfferData = data;
            for (const [key, value] of Object.entries(jobOfferData)) {
                let element = $("div.user-input[field="+key+"]");
                if(element.length>0) {
                    $(element).html(value);
                }
            }
            $('#job_applications_apply_job_offer_id').val(jobOfferData.id);
            $("#selectJobOfferLabel").html(jobOfferData.name);
            checkForCandidateRatings();
        } else {
            jobOfferData = null;
            hidePanelInfo("joboffer");
        }
    }

    function candidateSelect(data){
        if(data){
            activeTab("candidateDataPanel");
            showPanelInfo("candidate");
            candidateData = data;
            for (const [key, value] of Object.entries(candidateData)) {
                $("div.user-input[field="+key+"]").html(value);
                let element = $("input.user-input[field="+key+"]");
                if(element.length>0) {
                    $(element).val(value);
                }

                let elementchk = $("input.user-input2[field="+key+"]");
                if(elementchk) {
                    $(elementchk).prop("checked", value);
                }
                
            }
            $('#job_applications_apply_candidate_id').val(candidateData.Id);
            $("#selectCandidateLabel").html(candidateData.Name);
            checkForCandidateRatings();
        } else {
            jobOfferData = null;
            hidePanelInfo("candidate");
        }
    }

    function createApplicationSearchElement(parent, elementId, applicationId, url, moduleAction, placeholder, functionActionSelect){
        if(!$('#'+elementId).length) {
            $(parent).after("<div id='"+elementId+"'><select class='js-select-"+elementId+"'></select></div>");
            let element = $('#' + elementId).select2({
                width: '80%',
                placeholder: placeholder,
                allowClear: true,
                ajax: {
                    delay: 250,
                    transport: function (params, success, failure) {
                        const query = {
                            applicationId: applicationId,
                            action: moduleAction,
                            searchTerm: params.data.term,
                            type: 'public'
                        };

                        const $request = $.ajax({
                            type: "POST",
                            url: url,
                            dataType: 'json',
                            data: query
                        });

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    },
                    processResults: function (data) {
                        let mapData = $.map(data.results, function (obj) {
                            obj.id = obj.id || obj.Id;
                            obj.text = obj.text || obj.Name;
                            obj.text = (obj.text)?obj.text:obj.name;
                            return obj;
                        });
                        return {
                            results: mapData
                        };
                    }
                },
            });
            
            $(element).on("select2:select", function(e){
                let data = e?.params?.data;
                functionActionSelect(data);
            });
        }
    }
    
    createApplicationSearchElement(
        '#selectCandidateWrapper',
        "candidates_list",
        window.applicationId,
        'index.php?entryPoint=CandidateApplicationEntryPoint',
        'search',
        'Select for a Candidate',
        function (e) { candidateSelect(e); }
    );
    
    createApplicationSearchElement(
        '#selectJobOfferWrapper',
        "job_offer_list",
        window.applicationId,
        'index.php?entryPoint=JobOfferApplicationEntryPoint',
        'search',
        'Select a Job Offer',
        function (e) { jobOfferSelect(e); }
    );
        
    $(".js-select-application-type-selector").on('change',function() { 
        let type = $(".js-select-application-type-selector").val();
        $('#job_application_type').val(type);
    });
        
});

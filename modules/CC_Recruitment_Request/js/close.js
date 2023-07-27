function close_recruitment_request(elem, flag = 0)
{

    if(typeof recruitmentID === 'undefined') {
        recruitmentID = $("#recruitment_id").val();
    }

    let closing_reason = $('#reasonClosing').val();
    let closed_on = $('#close_on').val();

    if(closing_reason === ""){
        toastr.error('The Reason for closing is required', 'Oops!');
        return;
    }

    if(closing_reason.length < 5){
        toastr.warning('Too short the reason', 'Oops!');
        return;
    }

    let sendData = {
        closing_reason,
        closed_on : closed_on,
        recruitment_id :  recruitmentID,
        action         : 'closeRecruitmentAndCase',
    }

    $.ajax({
        type: 'POST',
        url: 'index.php?entryPoint=CreateCaseRecruitmentRequestEntryPoint',
        data: sendData,
        beforeSend : function () {
            let finishBtn = $('.btn-finish-step');
            finishBtn.html('<span class="glyphicon glyphicon-refresh spinning"></span> Sending ');
            finishBtn.prop("disabled", true);
        },
        success: function (resp) {
            let finishBtn = $('.btn-finish-step');
            let data_resp = resp.results;

            if(data_resp === 1){
                toastr.success("Recruitment has been successfully closed");
                finishBtn.html('Closed');
                if(flag == 1){
                    $("#closeRequirement").modal('hide');
                    window.location.reload();
                }
                if(typeof blockAllStepsElements === 'function'){
                    blockAllStepsElements();
                }
                setTimeout(function(){
                    window.location.assign(`index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DCC_Recruitment_Request%26action%3Dindex%26parentTab%3DAll`);
                }, 1200);
            }else{
                finishBtn.prop("disabled", false);
                finishBtn.html('Close AND Save');
                toastr.error(data_resp, 'Oops!');
            }

        },
        dataType: 'json'
    });
}
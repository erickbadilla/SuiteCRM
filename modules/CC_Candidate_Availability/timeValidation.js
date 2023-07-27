$( document ).ready(function() {
    // $(":input[title='Save']").removeAttr("onclick");
   // $(":input[title='Save']").attr("type","button");
   window.onload = validateTime();
});


var observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        calculateTime();
    });
});


function validateTime(){
    calculateTime();
    let inputs = document.querySelectorAll('input[id^="time_"]');
    inputs.forEach(element => {
        observer.observe(element, {
            attributes: true
        });
    })
}

function calculateTime() {
    let time1 = document.querySelector('#time_1').value;
    let time2 = document.querySelector('#time_2').value; 
    
    
    let time1Hour = (time1.slice(-2).toLowerCase() == "am") ? parseInt(time1.split(/:|a/)[0]) : parseInt(time1.split(/:|a/)[0])+12;
    let time1Minutes = parseInt(time1.split(/:|a/)[1]);
    
    let time2Hour = (time2.slice(-2).toLowerCase() == "am") ? parseInt(time2.split(/:|a/)[0]) : parseInt(time2.split(/:|a/)[0])+12;
    let time2Minutes = parseInt(time2.split(/:|a/)[1]); 
    
    if ((time1Hour > time2Hour) ||  (time1Hour == time2Hour && time1Minutes >= time2Minutes)){
        showMessage();
        $(':input[title="Save"]').prop('disabled', true);
    } else {
        removeMessage();
        $(':input[title="Save"]').prop('disabled', false);
    }
}

function showMessage(){
    if(!document.querySelector('#timeMessage')){
        let messageDiv = document.createElement("div");
        messageDiv.classList.add("required","validation-message");
        messageDiv.setAttribute("id", "timeMessage");
        messageDiv.innerHTML = "End time must be greater than start time";
        document.querySelector('div:nth-child(4) > div.col-xs-12.col-sm-8.edit-view-field').appendChild(messageDiv);
    }
}

function removeMessage(){
    let mainDiv = document.querySelector('div:nth-child(4) > div.col-xs-12.col-sm-8.edit-view-field');
    let messageDiv = document.querySelector('#timeMessage')

    if(messageDiv){
        mainDiv.removeChild(messageDiv);
    }
}
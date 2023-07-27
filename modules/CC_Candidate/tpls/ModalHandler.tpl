<link href="custom/include/generic/css/datatable.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/colReorder.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/jquery.contextMenu.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/toastr.css" rel="stylesheet" type="text/css">
<link href="custom/include/generic/css/listView.css" rel="stylesheet" type="text/css">
<link href="modules/CC_Candidate/css/edit.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />
<script type="text/javascript" src="custom/include/generic/javascript/toastr/toastr.min.js"></script>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLongTitle" style="text-align:center">Upload Candidate</h2>
      </div>


      <div class="modal-body">

        <div id="uploadSelect">

            <div class="form-row">
              <div class="form-group col-md-6 .col-sm-12">
                <label for="pdfCandidate">Select a PDF File:</label>
                <input type="file" id="pdfCandidate" name="pdfCandidate" accept="application/pdf">
              </div>
              <div class="form-group col-md-6 .col-sm-12">
                <label for="slc_lang">Resume Language:</label><br>
                <select class="form-select" id="slc_lang">
                  <option value="en" selected>English</option>
                  <option value="es">Spanish</option>
                </select>
              </div>
            </div>

          <br>
          <div style="text-align:center"><button type="button" id="upload_candidate" class="btn btn-primary" onclick="upload_candidate()" style="background-color: green;">Upload</button><br>
          <span class="alert_mss" style="color: crimson; font-weight: 700; font-size: 15px;"> This process could take a bit... Please wait </span></div>
        </div>

        <div id="uploadForm">
        <h3>Main Information</h3>
            <div class="form-row">
              <div class="form-group col-md-6 .col-sm-12">
                <label for="text_first_name">First Name</label>
                <input type="text" class="form-control" id="txt_first_name" value="" >
                <select class="form-select change_select" id="slc_first_name"></select>
              </div>
              <div class="form-group col-md-6 .col-sm-12">
                <label for="txt_last_name">Last Name</label>
                <input type="text" class="form-control" id="txt_last_name" value="" >
                <select class="form-select change_select" id="slc_last_name"></select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6 .col-sm-12">
                <label for="txt_document_number">Document Number</label>
                <input type="text" class="form-control" id="txt_document_number" value="" >
              </div>
              <div class="form-group col-md-6 .col-sm-12">
                <label for="txt_phone">Phone</label>
                <input type="text" class="form-control" id="txt_phone" value="" >
                <select class="form-select change_select" id="slc_phone"></select>
              </div>
            </div>
            <div class="form-group">
              <label for="txt_email">Email</label>
              <input type="email" class="form-control" id="txt_email" value="" >
              <select class="form-select change_select" id="slc_email"></select>
            </div>
            <h3>Other Information</h3>

            <div class="form-group" id="address1">
              <label for="txt_street_address_1">Address</label>
              <input type="text" class="form-control other_info" id="txt_street_address_1" value="" >
                <select class="form-select change_select" id="slc_street_address_1"></select>
            </div>

            <div class="form-group"  id="address2">
              <label for="txt_street_address_2">Address 2</label>
              <input type="text" class="form-control other_info" id="txt_street_address_2" value="" >
                <select class="form-select change_select" id="slc_street_address_2"></select>
            </div>

            <div class="form-group"  id="country">
              <label for="txt_country">Country</label>
              <input type="text" class="form-control other_info" id="txt_country" value="" >
                <select class="form-select change_select" id="slc_country"></select>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6 .col-sm-12"  id="city">
                <label for="txt_city">City</label>
                <input type="text" class="form-control other_info" id="txt_city" value="" >
                <select class="form-select change_select" id="slc_city"></select>
              </div>

              <div class="form-group col-md-4 .col-sm-12"  id="state">
                <label for="txt_state_province">State</label>
                <input type="text" class="form-control other_info" id="txt_state_province" value="" >
                <select class="form-select change_select" id="slc_state_province"></select>
              </div>

              <div class="form-group col-md-2 .col-sm-12"  id="zip">
                <label for="txt_zip_postal_code">Zip</label>
                <input type="text" class="form-control other_info" id="txt_zip_postal_code" value="" >
                <select class="form-select change_select" id="slc_zip_postal_code"></select>
              </div>
            </div>

             <div class="form-row">
              <div class="form-group col-md-8 .col-sm-12"  id="education">
                <label for="txt_education">Education</label>
                <input type="text" class="form-control  other_info" id="txt_education" value="" >
                <select class="form-select" id="slc_education" ></select>
              </div>
              <div class="form-group col-md-4 .col-sm-12"  id="years">
                <label for="txt_years_of_experience">Years of Experience</label>
                <input type="text" class="form-control  other_info" id="txt_years_of_experience" value="" >
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-12 .col-sm-12"  id="employer">
                <label for="txt_current_employer">Current Employer</label>
                <input type="text" class="form-control  other_info" id="txt_current_employer" value="" >
                <select class="form-select change_select" id="slc_current_employer"></select>
              </div>

            </div>

            <div class="form-row">
            <div class="form-group col-md-4 .col-sm-12"  id="employed">
                <input class="form-check-input" type="checkbox" id="txt_currently_employed">
                <label class="form-check-label" for="txt_currently_employed">
                  Currently Employed
                </label>
              </div>
            <div class="form-group col-md-4 .col-sm-12"  id="pass">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="txt_has_passport">
                <label class="form-check-label" for="txt_has_passport">
                  Has Passport
                </label>
              </div>
            </div>
            <div class="form-group col-md-4 .col-sm-12"  id="visa">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="txt_has_visa">
                <label class="form-check-label" for="txt_has_visa">
                  Has Visa 
                </label>
              </div>
            </div>
            </div>
      </div>

      
      </div>

      <div class="modal-footer">
      <div>
        <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="close_candidate()">Close</button>
        <button type="button" id="save_candidate" class="btn btn-primary" onclick="create_candidate()" data-toggle="modal" data-target="#waitingModal" data-backdrop="static" data-keyboard="false">Save</button>
      </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="waitingModal">
  <div class="modal-dialog" role="document" style="width:300px">
    <div class="modal-content">
      <div class="modal-body">
        <h3>We are Creating Your Skills and Qualifications, Please Stay with Us</h3>
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
let dataTempResult;
$(document).ready(function(){

document.getElementById("mobile").onkeyup = function() {changeChar("mobile")};
document.getElementById("phone").onkeyup = function() {changeChar("phone")};

function changeChar(elem) {
  let x = document.getElementById(elem);
  x.value = x.value.replace(/[^0-9 +]+/g, '');
}

  $("input[type='button']").after('<button type="button" class="button" data-toggle="modal" data-target="#exampleModalCenter">Upload Candidate</button>');
 
  toastr.options = {
      "positionClass": "toast-bottom-right",
    }
  $("#save_candidate").hide();
  $("#uploadForm").hide();
  $(".alert_mss").hide();

  $(".change_select").change(function(i){
    /*if($(this).val() == 'other'){
    $(this).parent().find('input').prop("disabled", false);
    $(this).parent().find('input').focus();
    }else{*/
      $(this).parent().find('input').val($(this).val());
    //}
  });

  $("#slc_education").change(function(i){
    $(this).parent().find('input').val($(this).val());
  });
});

function upload_candidate(){

  let inputFile = document.getElementById('pdfCandidate');
  let file = inputFile.files[0];
  let lan  = $("#slc_lang").val();


  if(file === undefined){
    toastr.error('You must select a file!', 'Oops!');
    return;
  }

  $("#upload_candidate").prop("disabled", true);
  $("#upload_candidate").text("...");
  $(".alert_mss").show();
 

  let action = "CandidateUploadResume";
  let data_send = new FormData(); 

    data_send.append('file',file);
    data_send.append('lan',lan);
    data_send.append('action',action); 

      $.ajax({
      type: "POST",
      url: 'index.php?entryPoint=CandidateApplicationEntryPoint',
      dataType: 'json',
      data: data_send,
      processData:false,
      contentType:false,
      cache:false,
      success: function(respu) {
        let data_out = respu["data"][0];
        let respEdu =data_out.education_list;
        let resp =data_out.candidate;
        dataTempResult = resp;
        if(resp){
          $("#exampleModalLongTitle").text("Create Candidate");
          $(".change_select").append('<option value=""></option>');

          resp.First_Name.length == 1 ? $("#txt_first_name").val(resp.First_Name[0]) : $("#txt_first_name").val()
          resp.First_Name.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
            $("#slc_first_name").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
          });

          resp.Last_Name.length == 1 ? $("#txt_last_name").val(resp.Last_Name[0]) : $("#txt_last_name").val()
          resp.Last_Name.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
            $("#slc_last_name").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
          });

          resp.Phone.length == 1 ? $("#txt_phone").val(resp.Phone[0]) : $("#txt_phone").val()
          resp.Phone.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
            $("#slc_phone").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
          });

          $("#txt_mobile").val(resp.Mobile[0]);

          resp.Email.length == 1 ? $("#txt_email").val(resp.Email[0]) : $("#txt_email").val()
          resp.Email.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
            $("#slc_email").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
          });

            if(resp.City.length == 0){
                $("#city").hide()
            }else{
              resp.City.length == 1 ? $("#txt_city").val(resp.City[0]) : $("#txt_city").val()
              resp.City.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
                $("#slc_city").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
              });
            }

            if(resp.Country.length == 0){
                $("#country").hide()
            }else{
              resp.Country.length == 1 ? $("#txt_country").val(resp.Country[0]) : $("#txt_country").val()
              resp.Country.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
                $("#slc_country").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
              });
            }

            /*if(resp.Education.length == 0){
                $("#education").hide()
            }else{
              resp.Education.length == 1 ? $("#txt_education").val(resp.Education[0]) : $("#txt_education").val()
              resp.Education.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
                $("#slc_education").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
              });
            }*/
            $("#slc_education").append('<option value="">--Select--</option>');
            let educationLevel = "";
            if(resp.Education.length == 1){
                educationLevel = resp.Education[0];
            }else{
            for(var index in respEdu) {
              let selected = ""
              if(respEdu[index] == educationLevel){
                selected =  "selected";
                $("#txt_education").val(index);
              }
                $("#slc_education").append('<option value="'+index+'" '+selected+'>'+respEdu[index]+'</option>');
            }
            }

            if(resp.Current_Employer.length == 0){
                $("#employer").hide()
            }else{
              resp.Current_Employer.length == 1 ? $("#txt_current_employer").val(resp.Current_Employer[0]) : $("#txt_current_employer").val()
              resp.Current_Employer.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
                $("#slc_current_employer").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
              });
            }

            if(resp.State.length == 0){
                $("#state").hide()
            }else{
              resp.State.length == 1 ? $("#txt_state_province").val(resp.State[0]) : $("#txt_state_province").val()
              resp.State.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
                $("#slc_state_province").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
              });
            }


            if(resp.Street_Address_1.length == 0){
                $("#address1").hide()
            }else{
              resp.Street_Address_1.length == 1 ? $("#txt_street_address_1").val(resp.Street_Address_1[0]) : $("#txt_street_address_1").val()
              resp.Street_Address_1.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
                $("#slc_street_address_1").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
              });
            }

            if(resp.Street_Address_2.length == 0){
                $("#address2").hide()
            }else{
              resp.Street_Address_2.length == 1 ? $("#txt_street_address_2").val(resp.Street_Address_2[0]) : $("#txt_street_address_2").val()
              resp.Street_Address_2.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
                $("#slc_street_address_2").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
              });
            }

            if(resp.Postal_Code.length == 0){
                $("#zip").hide()
            }else{
              resp.Postal_Code.length == 1 ? $("#txt_zip_postal_code").val(resp.Postal_Code[0]) : $("#txt_zip_postal_code").val()
              resp.Postal_Code.forEach(function(element, index) {
            let selected = index == 0 ? "selected" : "";
                $("#slc_zip_postal_code").append('<option value="'+element+'" '+selected+'>'+element+'</option>');
              });
            }

              resp.Has_Passport.length == 0 ? $("#txt_has_passport").prop('checked', false) : $("#txt_has_passport").prop('checked', true);
              resp.Has_Visa.length == 0 ? $("#txt_has_visa").prop('checked', false) : $("#txt_has_visa").prop('checked', true);
              resp.Currently_Employed.length == 0 ? $("#txt_currently_employed").prop('checked', false) : $("#txt_currently_employed").prop('checked', true);
              $("#txt_years_of_experience").val(resp.Years_Experience);

              //$(".change_select").append('<option value="other">Other</option>');

              $("#save_candidate").show();
              $("#uploadForm").show();
              $("#uploadSelect").hide();
        }else{
          toastr.error('Error when Reading the PDF Try Again Later', 'Oops!');
        }
      }
    }); 
}

function close_candidate(){
  $("#save_candidate").hide();
  $("#uploadForm").hide();
  $(".alert_mss").hide();
  $("#uploadSelect").show();
  $("#upload_candidate").prop("disabled", false);
  $("#upload_candidate").text("Upload");
}

function create_candidate(){

  $("#exampleModalCenter").css("opacity", "0.6");

  
  let inputFile = document.getElementById('pdfCandidate');
  let file = inputFile.files[0];
  let first_name        = $("#txt_first_name").val();
  let last_name         = $("#txt_last_name").val();
  let name              = first_name + ', ' + last_name;
  let city              = $("#txt_city").val();
  let country           = $("#txt_country").val();
  let document_number   = $("#txt_document_number").val();
  let email             = $("#txt_email").val();
  let education         = $("#txt_education").val();
  let has_passport      = $("#txt_has_passport").is(":checked") ? 1 : 0;
  let has_visa          = $("#txt_has_visa").is(":checked") ? 1 : 0;
  let mobile            = $("#txt_mobile").val();
  let current_employer  = $("#txt_current_employer").val();
  let currently_employed= $("#txt_currently_employed").is(":checked") ? 1 : 0;
  let phone             = $("#txt_phone").val();
  let state_province    = $("#txt_state_province").val();
  let street_address_1  = $("#txt_street_address_1").val();
  let street_address_2  = $("#txt_street_address_2").val();
  let years_of_experience= $("#txt_years_of_experience").val();
  let zip_postal_code   = $("#txt_zip_postal_code").val();
  let action            = "CandidateUploadCreate";

  let data_send = new FormData(); 

    data_send.append('file',file);
    data_send.append('first_name',first_name);
    data_send.append('last_name',last_name);
    data_send.append('name',name);
    data_send.append('city',city);
    data_send.append('country',country);
    data_send.append('document_number',document_number);
    data_send.append('email',email);
    data_send.append('education',education);
    data_send.append('has_passport',has_passport);
    data_send.append('has_visa',has_visa);
    data_send.append('mobile',mobile);
    data_send.append('phone',phone);
    data_send.append('current_employer',current_employer);
    data_send.append('currently_employed',currently_employed);
    data_send.append('state_province',state_province);
    data_send.append('street_address_1',street_address_1);
    data_send.append('street_address_2',street_address_2);
    data_send.append('years_of_experience',years_of_experience);
    data_send.append('zip_postal_code',zip_postal_code);
    data_send.append('skills',JSON.stringify(dataTempResult.CandidateSkills));
    data_send.append('quali',JSON.stringify(dataTempResult.CandidateQualifications));
    data_send.append('action',action); 

      $.ajax({
      type: "POST",
      url: 'index.php?entryPoint=CandidateApplicationEntryPoint',
      dataType: 'json',
      data: data_send,
      processData:false,
      contentType:false,
      cache:false,
      success: function(resp) {

        if(resp){
           toastr.success("Candidate created successfully", 'Successful');
           setTimeout(function(){ 
               window.location.assign(`index.php?module=${resp.module}&offset=1&return_module=${resp.module}&action=DetailView&record=${resp.id}`);
            }, 2000);
        }else{
          toastr.error('Error when created Candidate', 'Oops!');
        }
      }
    }); 
}

</script>
{/literal}
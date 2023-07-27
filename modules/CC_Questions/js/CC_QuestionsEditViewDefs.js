$(document).ready(function(){
    function updateValidationField(formName, fieldName,type, required, msg) {
        // Get the validate array for the form
        if(validate[formName] === undefined) return;

        var validateArray = validate[formName];

        // Find the validation rule for the specified field
        var validationRule = validateArray.find(function(rule) {
            return rule[0] === fieldName;
        });

        if (validationRule) {
            // Update the required flag in the validation rule
            validationRule[2] = required;
        } else {
            // Add a new validation rule for the field
            validateArray.push([fieldName, type, required, msg]);
        }
    }
    const createRequiredSpan = () => {
        return `<span class='required'>*</span>`;
    }
    console.log(createRequiredSpan());
    const showAnswerOptionsInput = () => {
        $("[data-field='answer_options']").show();
        document.getElementById("answer_options").required = true;
        const div_elem = document.querySelector('[data-label="LBL_ANSWER_OPTIONS"]');
        let labelText = div_elem.innerHTML;
        let span_elems = div_elem.getElementsByTagName("span")
        if(span_elems.length===0){
            div_elem.innerHTML = div_elem.innerHTML + createRequiredSpan();
        }
    }
    const hideAnswerOptionsInput = () => {
        $("[data-field='answer_options']").hide();
        document.getElementById("answer_options").required = false;
    }
    const showCodeTypeInput = () => {
        $("[data-field='code_type']").show();
        document.getElementById("code_type").required = true;
        document.getElementById("cc_tq_code_type_id_c").required = true;
        updateValidationField('EditView','code_type','text',true,'Code type is requiered when using technical exercices');
        addToValidate('EditView', 'cc_tq_code_type_id_c', 'id', false,'code type (related  ID)' );
        const div_elem = document.querySelector('[data-label="LBL_CODE_TYPE"]');
        let labelText = div_elem.innerHTML;
        let span_elems = div_elem.getElementsByTagName("span")
        if(span_elems.length===0){
            div_elem.innerHTML = div_elem.innerHTML + createRequiredSpan();
        }
    }
    const hideCodeTypeInput = () =>  {
        $("[data-field='code_type']").hide();
        document.getElementById("code_type").required = false;
        document.getElementById("cc_tq_code_type_id_c").required = false;
        updateValidationField('EditView','code_type','text',false,'Code type is requiered when using technical exercices');
    }
    const validateAnswerOptionStatus = () => {
        if ($("#type").val() === "CustomOptions") {
            showAnswerOptionsInput();
        } else {
            hideAnswerOptionsInput();
        }
    }
    const validateCodeTypeStatus = () => {
        if ($("#category").val() === "Technical_Exercise") {
            showCodeTypeInput();
        } else {
            hideCodeTypeInput();
        }
    }

    $("#type").on("change", validateAnswerOptionStatus);
    setTimeout(validateAnswerOptionStatus,500);
    $("#category").on("change",validateCodeTypeStatus);
    setTimeout(validateCodeTypeStatus,500);

});
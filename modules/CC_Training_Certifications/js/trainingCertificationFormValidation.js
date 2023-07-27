$(document).ready(function(){

    setTimeout(function () {
        var certificationNeverExpireIsChecked = $('#never_expire:checkbox:checked').length > 0;
        ExpCertificationEnable(certificationNeverExpireIsChecked);

        $( "#start_year" ).addClass( "trainingYearClass" );
        $( "#end_year" ).addClass( "trainingYearClass" );
        $( "#expires_on_year" ).addClass( "trainingYearClass" );
        $( "#start_month" ).addClass( "trainingMonthClass" );
        $( "#end_month" ).addClass( "trainingMonthClass" );

        //$( "#start_year" ).val(new Date().getFullYear()-1);
        //$( "#end_year" ).val(new Date().getFullYear());


        $( ".trainingMonthClass" ).on('change', function () {
            validateDates($(this));
            return false;
        });


        $( ".trainingYearClass" ).on('change', function () {
            handleYearChange($(this));
            return false;
        });

        function isBefore(date1, date2) {
            return date1 < date2;
        }

        function getMonthNumber(month){
            let months = ["January","February","March","April","May","June","July",
                "August","September","October","November","December"];
            return months.indexOf(month);
        }

        function handleYearChange(elem){
            let yearValidation = new RegExp('^[0-9]{4}$');
            let node = elem[0];
            let actualFormName = $("#"+node.id).closest("form")[0].getAttribute('name');

            if (yearValidation.test($(node).val()) && validateDates(elem)){
                clear_all_errors();
                $("#"+node.id).closest("form").find(':submit').each(function () {
                    if($(this ).val()==='Save') $(this).prop('disabled', false);
                });
                return true;
            } else {
                $("#"+node.id).closest("form").find(':submit').each(function () {
                    if($(this).val()==='Save') $(this).prop('disabled', true);
                });
                add_error_style(actualFormName,node.id, 'Year should be a value of 4 digits between 0-9',true);
            }

            return false;

        }

        function validateDates(elem){
            let node = elem[0];
            let start_year = $( "#start_year" ).val();
            let end_year = $( "#end_year" ).val();
            let start_month = getMonthNumber($( "#start_month" ).val());
            let end_month = getMonthNumber($( "#end_month" ).val());
            let start_date = new Date(start_year, start_month, 1,0, 0, 1, 0);
            let end_date = new Date(end_year, end_month, 1,0, 0, 1, 0);

            if(isBefore(end_date, start_date)){
                let actualFormName = $("#"+node.id).closest("form")[0].getAttribute('name');
                $("#"+node.id).closest("form").find(':submit').each(function () {
                    if($(this).val()==='Save') $(this).prop('disabled', true);
                });
                add_error_style(actualFormName,node.id, 'Start date should be before end date',true);
                return false;
            }
            clear_all_errors();
            $("#"+node.id).closest("form").find(':submit').each(function () {
                if($(this ).val()==='Save') $(this).prop('disabled', false);
            });
            return true;
        }

    },500);

    $('#never_expire').change(function() {
            ExpCertificationEnable(this.checked);
    });

    function ExpCertificationEnable(value){
        $('#expires_on_month').prop( "disabled", value );
        $('#expires_on_year').prop( "disabled", value );
    }

});

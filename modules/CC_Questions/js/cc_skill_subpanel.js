$(function() {
    toastr.options = {
        "positionClass": "toast-bottom-right",
    }
});


function reorder_questions(id, elem){
        $.ajax({
            type: "POST",
            url: "index.php?entryPoint=QuestionsEntryPoint",
            dataType: 'json',
            data: {
                action: 'reorderRecords',
                elementId: id,
            }
        }).done(function (data){
            toastr.success(`New order applied`, 'Successful');
            showSubPanel('cc_skill_cc_questions', null, true);
        });
}

function give_order_questions(id, elem){
        $.ajax({
            type: "POST",
            url: "index.php?entryPoint=QuestionsEntryPoint",
            dataType: 'json',
            data: {
                action: 'give_order_questions',
                elementId: id,
                value: elem.val(),
            }
        }).done(function (data){
            toastr.success(`New order applied`, 'Successful');

        });
}
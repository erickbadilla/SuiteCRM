$(function() {
    var elementName = 'json-viewer';
    var element = document.getElementById(elementName);
    if(element===null){
        var jsonViewer = new JSONViewer();
        var parent = $('#description').parent().first();
        $('<div id="'+elementName+'"></div>').appendTo(parent);
        document.querySelector("#"+elementName).appendChild(jsonViewer.getContainer());
        var jsonObj = null;
        var setJSON = function() {
            try {
                var value = $('#description').html();
                jsonObj = JSON.parse(value);
                $('#description').hide();
            }
            catch (err) {
                console.error(err);
            }
        };
        setJSON();
        jsonViewer.showJSON(jsonObj,null, 1);
    }
});
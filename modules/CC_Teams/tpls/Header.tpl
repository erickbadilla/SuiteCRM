<link href="modules/CC_Teams/css/CC_Teams.css" rel="stylesheet" type="text/css">
<div class="ct-teams-view-selector row">
    <div class="col-lg-2 col-md-2 col-xs-12" style="padding: 5px;padding-bottom: 15px;">
        <button id="teams-list-type-selected" type="button" name="project-dashboard" data-value="list-type">
            <span class="glyphicon glyphicon-list"></span>&nbsp&nbsp;List View
        </button>
    </div>
    <div class="col-lg-2 col-md-2 col-xs-12" style="padding: 5px;padding-bottom: 15px;">
        <button id="teams-kanban-type-selected" type="button" name="project-details" data-value="kanban-type">
            <span class="glyphicon glyphicon-object-align-top"></span>&nbsp;Kanban View
        </button>
    </div>
    <script>
        {literal}
        $(".ct-teams-view-selector button").click(function() {
            let data=$(this).data("value");
            console.log(data);
            $.cookie('careers-teams-view-selected', data);
            location.reload();
        });
        {/literal}
    </script>
</div>

<style>
    {literal}
    .ct-activity-view-selector button {
        min-width: 100%;
    }

    button {
        font-size: 13px;
        font-weight: 500;
        background: #B8CC33;
        color: #FFF;
        cursor: pointer;
        padding: 0 10px 0 10px;
        margin: 0 0 0 0;
        border: none;
        border-radius: 3px;
        letter-spacing: 1px;
        line-height: 34px;
        height: 35px;
        text-transform: uppercase;
    }
    {/literal}
</style>
<div class='ct-activity-view-selector row'>
    <div class="col-lg-2 col-md-2 col-xs-12" style="padding: 5px;padding-bottom: 15px;">
        <button id="project-dashboard" type="button" name="project-dashboard" data-value="project-dashboard">
            <img src="themes/default/images/ProspectLists.gif" border="0" alt="View Projects Summary">&nbsp;Projects Summary
        </button>
    </div>
    <div class="col-lg-2 col-md-2 col-xs-12" style="padding: 5px;padding-bottom: 15px;">
        <button id="project-details" type="button" name="project-details" data-value="project-details">
            <img src="themes/default/images/Project.gif" border="0" alt="View Project Activity Details">&nbsp;Project Details
        </button>
    </div>
    <script>
        {literal}
        let elements = [];
        $(".ct-activity-view-selector button").click(function() {
            let data=$(this).data("value");
            $.cookie('careers-activity-view-selected', data);
            location.reload();
        });
        {/literal}
    </script>
</div>
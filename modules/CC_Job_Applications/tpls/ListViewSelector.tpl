<style>
    {literal}
    .job-application-view-selector ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .job-application-view-selector ul li {
        float: left;
    }

    .job-application-view-selector ul li a {
        display: block;
        text-align: center;
        padding: 8px 8px 8px 16px;
        text-decoration: none;
        cursor: pointer;
    }
    {/literal}
</style>
<div class='job-application-view-selector row'>
    <div class='col'>
        <h1 style='display: flex'>Job Applications</h1>
    </div>
    <div class='col'>
        <ul>
            <li><a data-value="list-type"><span class="glyphicon glyphicon-list"></span>&nbsp;List View</a></li>
            <li><a data-value="kanban-type"><span class="glyphicon glyphicon-object-align-top"></span>&nbsp;Kanban View</a></li>
        </ul>
    </div>
    <script>
        {literal}
        let elements = [];
        $(".job-application-view-selector a").click(function() {
            let data=$(this).data("value");
            $.cookie('CC_job-applications-view-selected', data);
            location.reload();
        });
        {/literal}
    </script>
</div>
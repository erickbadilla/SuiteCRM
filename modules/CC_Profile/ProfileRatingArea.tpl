{literal}
    <style>
        .ApplicationRatingBase{ background: linear-gradient( 87deg ,#2dce89,#2dcecc) !important; }
        .ApplicationRatingActive { background: linear-gradient( 87deg ,#11cdef,#1171ef) !important; }
        .ApplicationRatingWarning { background: linear-gradient( 87deg ,#fb6340,#fbb140) !important; }
        .ApplicationRatingExpired { background: linear-gradient( 87deg ,#f5365c,#f56036) !important; }
        .boxRating {
            color: white;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 4px 0px;
            border-style: solid;
            background-size: auto !important;
            padding: 15px;
            margin-bottom: 7px;
            border-radius: 5px;
        }
        .boxRating p { font-size: 1.2em; }
        .profileRatingAreaContainer {
          margin-bottom: 5px;
        }
    </style>
{/literal}
<div class="container-fluid profileRatingAreaContainer">
    <div>
        <div class="row profileRatingArea">
            {foreach from=$ratings item=rating}
                <div class="profileRatingItem{$rating->class} col-sm-4 col-xs-12">{$rating->tpl}</div>
            {/foreach}
        </div>
    </div>
</div>
{literal}
<script>
    function updateRatingElement(element, value){
        let rating_styles = [ "Expired", "Warning", "Active", "Base" ];
        let base_style_name = "ApplicationRating";
        let actual_rating_styles = rating_styles.map(i => base_style_name + i);
        let rating_result = Math.floor(value / (100/rating_styles.length ) );
        if(rating_result >= rating_styles.length){
            rating_result = rating_styles.length -1;
        }
        let rating_element_new_style = base_style_name + rating_styles[rating_result];
        $(element+" div:first-child").removeClass(actual_rating_styles.join(' '));
        $(element+" div:first-child").addClass(rating_element_new_style);
        $(element+" span:first-child").html(value);
    }

    function updateRatingArea(data) {
        updateRatingElement(".profileRatingItemSkill", data.skill_rating*10);
        updateRatingElement(".profileRatingItemQualification", data.qualification_rating*10);
        updateRatingElement(".profileRatingItemGeneral", data.general_rating*10);
    }
</script>
{/literal}
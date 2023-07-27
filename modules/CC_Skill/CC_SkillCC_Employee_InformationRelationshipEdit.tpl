
<!-- BEGIN: main -->
<form action="index.php" method="post" name="EditView" onsubmit="return check_form('EditView')">
    <input type="hidden" name="module" value="CC_Skill" />
    <input type="hidden" name="record" value="{$ID}" />
    <input type="hidden" name="return_module" value="{$RETURN_MODULE}" />
    <input type="hidden" name="return_action" value="{$RETURN_ACTION}" />
    <input type="hidden" name="return_id" value="{$RETURN_ID}" />
    <input type="hidden" name="action" value="" />
    <input type="hidden" name="{$USER}" value="{$USER}" />
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td>
                <input id="save_btn_48516" type="submit" name="button" class="button"
                       title="{$APP.LBL_SAVE_BUTTON_TITLE}"
                       accesskey="{$APP.LBL_SAVE_BUTTON_KEY}"
                       onclick="this.form.action.value='SaveCC_SkillCC_Employee_InformationRelationship';"
                       value="  {$APP.LBL_SAVE_BUTTON_LABEL}  "
                />
                <input id="cancel_btn_48516" type="submit" name="button" class="button"
                       title="{$APP.LBL_CANCEL_BUTTON_TITLE}"
                       accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}"
                       onclick="this.form.action.value='{$RETURN_ACTION}'; this.form.module.value='{$RETURN_MODULE}'; this.form.record.value='{$RETURN_ID}'"
                       value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  "
                />
            </td>
            <td align="right" nowrap="nowrap"><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}</td>
        </tr>
    </table>
    <div class="clearfix">&nbsp;</div>

 
        <script src="custom/include/SugarFields/Fields/SkillRatingExperience/js/rating.js"></script>
        {if strlen($fields.rating.value) <= 0}
            {assign var="SkillBusinessRatingExpValue" value=$fields.rating.default_value}
        {else}
            {assign var="SkillBusinessRatingExpValue" value=$fields.amount.value}
        {/if}

        {if strlen($fields.rating.value) <= 0}
            {assign var="SkillRatingExpValue" value=$fields.rating.default_value}
        {else}
            {assign var="SkillRatingExpValue" value=$fields.rating.value}
        {/if}

        {assign var="ParentFieldValue" value='rating'}
        {assign var="SkillRatingExpInputType" value='hidden'}
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
        <tr>
            <td>
                <div class="clearfix">&nbsp;</div>
                <div class="panel-content">
                    <div class="tab-content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td scope="row" colspan="4"><span class="label">Business Rating</span></td>
                            </tr>
                            <tr>
                                <td scope="row"><span class="label">{$MOD.LBL_EMPLOYEE_INFORMATION_SKILL_RATING}</span><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
                                <td ><span>
                                <div class="col-xs-12 col-sm-8 edit-view-field" type="SkillRatingExperience" field="amount">
                                <script src="custom/include/SugarFields/Fields/SkillRatingExperience/js/rating.js"></script>
                                {if strlen($fields.rating.value) <= 0}
                                    {assign var="SkillBusinessRatingExpValue" value=$fields.rating.default_value}
                                {else}
                                    {assign var="SkillBusinessRatingExpValue" value=$fields.amount.value}
                                {/if}

                                    {assign var="ParentFieldValue" value='rating'}
                                    {assign var="SkillRatingExpInputType" value='hidden'}

                                    <div id='SkillRatingExpSelectorArea' style="width: 100%;float:left;{if $ParentFieldValue!='rating'}display:none;{/if}">
                                        <div style="float: inherit;" id="{$fields.amount.name}_rating" data-rating="{$SkillBusinessRatingExpValue}"></div>
                                    </div>
                                    <input type='{$SkillRatingExpInputType}' name='{$fields.amount.name}'
                                           id='{$fields.amount.name}' size='30'
                                           maxlength='255'
                                           value='{$SkillBusinessRatingExpValue}'
                                    />
                                    <div class="clearfix">&nbsp;</div>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td scope="row" colspan="4" style="background-color:#f5f5f5"><div class="clearfix">&nbsp;</div></td>
                            </tr>
                            <tr>
                                <td scope="row" colspan="4" style="padding: 15px 0px 0px 0px;"><span class="label">Employee Rating and Experience</span></td>
                            </tr>
                            <tr>
                                <td scope="row"><span class="label">{$MOD.LBL_EMPLOYEE_INFORMATION_SKILL_RATING}</span><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
                                <td ><span>
                                <div class="col-xs-12 col-sm-8 edit-view-field" type="SkillRatingExperience" field="amount">
                                    <div id='SkillRatingExpSelectorArea' style="width: 100%;float:left;{if $ParentFieldValue!='rating'}display:none;{/if}">
                                        <div style="float: inherit;" id="{$fields.rating.name}_rating" data-rating="{$SkillRatingExpValue}"></div>
                                    </div>
                                    <input type='{$SkillRatingExpInputType}' name='{$fields.rating.name}'
                                           id='{$fields.rating.name}' size='30'
                                           maxlength='255'
                                           value='{$SkillRatingExpValue}'
                                    />
                                </div>
                                </td>
                                <td scope="row"><span class="label">{$MOD.LBL_EMPLOYEE_INFORMATION_SKILL_YEARS}</span><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
                                <td ><span><input type='number' name='{$fields.years.name}' id='{$fields.years.name}' size='30' min="0" value='{$fields.years.value}'/></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
            </td>
        </tr>
    </table>
</form>

<script>
    {literal}
    $(document).ready(function(){
        $('#{/literal}{$fields.amount.name}_rating{literal}').starRating({
            starSize: 25,
            totalStars: 5,
            disableAfterRate: false,
            callback: function(currentRating, $el){
                let parent = $el.parent();
                $el.starRating('setRating', currentRating);
                $('#{/literal}{$fields.amount.name}{literal}').val(currentRating);
            }
        });
        $('#{/literal}{$fields.rating.name}_rating{literal}').starRating({
            starSize: 25,
            totalStars: 5,
            disableAfterRate: false,
            callback: function(currentRating, $el){
                let parent = $el.parent();
                $el.starRating('setRating', currentRating);
                $('#{/literal}{$fields.rating.name}{literal}').val(currentRating);
            }
        });
    });
    {/literal}
</script>
<!-- END: main -->
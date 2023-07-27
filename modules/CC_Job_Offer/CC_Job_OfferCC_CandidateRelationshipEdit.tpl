
<!-- BEGIN: main -->
<form action="index.php" method="post" name="EditView" onsubmit="return check_form('EditView')">
    <input type="hidden" name="module" value="CC_Job_Offer" />
    <input type="hidden" name="record" value="{$ID}" />
    <input type="hidden" name="return_module" value="{$RETURN_MODULE}" />
    <input type="hidden" name="return_action" value="{$RETURN_ACTION}" />
    <input type="hidden" name="return_id" value="{$RETURN_ID}" />
    <input type="hidden" name="action" value="" />
    <input type="hidden" name='previousStage' value='{$fields.stage.value}'>                  
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
    <td>
        <input id="save_btn_48516" type="submit" name="button" class="button"
            title="{$APP.LBL_SAVE_BUTTON_TITLE}"
            accesskey="{$APP.LBL_SAVE_BUTTON_KEY}"
            onclick="this.form.action.value='SaveCC_Job_OfferCC_CandidateRelationship';"
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
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
        <tr>
            <td>
            <div class="clearfix">&nbsp;</div>
            <div class="panel-content">
                <div class="tab-content">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
                        <tr>
                            <td scope="row"><div class="detail-view-row-item">
                                <div class="col-xs-12 col-sm-4 label col-1-label" style="padding-top:10px">
                                    {$MOD.LBL_CANDIDATE_JOB_OFFER_TYPE}:
                                </div>
                                <div class="col-xs-12 col-sm-8 inlineEdit">
                                    <select value='{$fields.type.value}' name='type' id="type" style="width:70%">{$APPLICANT_TYPE_OPTIONS}</select>
                                </div>
                            </div></td>
                            <td scope="row"><div class="detail-view-row-item">
                                <div class="col-xs-12 col-sm-4 label col-1-label" style="padding-top:10px">
                                    {$MOD.LBL_CANDIDATE_JOB_OFFER_STAGE}:
                                </div>
                                <div class="col-xs-12 col-sm-8 inlineEdit">
                                    <select value='{$fields.stage.value}' name='stage' id="stage" style="width:70%">{$APPLICANT_STAGE_LIST}</select>
                                </div>
                            </div></td>
                        </tr>
                    </table>
                </div>
            </div>
                        <div class="clearfix">&nbsp;</div>
            </td>
        </tr>
    </table>
</form>

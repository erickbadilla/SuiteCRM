<!-- BEGIN: main -->
<form action="index.php" method="post" name="EditView" onsubmit="return check_form('EditView')">
    <input type="hidden" name="module" value="CC_Qualification"/>
    <input type="hidden" name="record" value="{$ID}"/>
    <input type="hidden" name="return_module" value="{$RETURN_MODULE}"/>
    <input type="hidden" name="return_action" value="{$RETURN_ACTION}"/>
    <input type="hidden" name="return_id" value="{$RETURN_ID}"/>
    <input type="hidden" name="action" value=""/>
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tbody>
        <tr>
            <td class="buttons">
                <div class="buttons">
                    <input id="save_btn_48516" class="button primary" type="submit" name="button" class="button"
                           title="{$APP.LBL_SAVE_BUTTON_TITLE}"
                           accesskey="{$APP.LBL_SAVE_BUTTON_KEY}"
                           onclick="this.form.action.value='SaveCC_QualificationCC_Employee_InformationRelationship';"
                           value="  {$APP.LBL_SAVE_BUTTON_LABEL}  "
                    />
                    <input id="cancel_btn_48516" class="button" type="submit" name="button" class="button"
                           title="{$APP.LBL_CANCEL_BUTTON_TITLE}"
                           accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}"
                           onclick="this.form.action.value='{$RETURN_ACTION}'; this.form.module.value='{$RETURN_MODULE}'; this.form.record.value='{$RETURN_ID}'"
                           value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  "
                    />
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="clearfix">&nbsp;</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
        <tbody>
        <tr><td>
            <div class="clearfix">&nbsp;</div>
            <div class="panel-content">
                <div class="panel panel-default">
                    <div class="tab-content">
                        <div class="row detail-view-row">
                            <div class="col-xs-12 col-sm-6 detail-view-row-item">
                                <div class="col-xs-12 col-sm-4 label col-1-label">{$MOD.LBL_EMPLOYEE_INFORMATION_CC_QUALIFICATION_ACTUAL_QUALIFICATION}</div>
                                <div class="col-xs-12 col-sm-8"><select name='actual_qualification'>{$TYPE_OPTIONS}</select></div>
                                <div class="clearfix">&nbsp;</div>
                            </div>
                            <div class="col-xs-12 col-sm-6 detail-view-row-item">
                                <div class="col-xs-12 col-sm-4 label col-1-label">{$MOD.LBL_EMPLOYEE_INFORMATION_CC_QUALIFICATION_HAS_DIGITAL_SUPPORT}</div>
                                <div class="col-xs-12 col-sm-8" type="bool" field="has_digital_support"><input type="checkbox" name='has_digital_support'"  {$CHECKED} /></div>
                                <div class="clearfix">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
            </div>
        </td></tr>
        </tbody>
    </table>
</form>
{$JAVASCRIPT}
<!-- END: main -->
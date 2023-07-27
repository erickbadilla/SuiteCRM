<!-- BEGIN: main -->
<form action="index.php" method="post" name="EditView" onsubmit="return check_form('EditView')">
<input type="hidden" name="module" value="CC_Job_Offer" />
<input type="hidden" name="record" value="{$ID}" />
<input type="hidden" name="return_module" value="{$RETURN_MODULE}" />
<input type="hidden" name="return_action" value="{$RETURN_ACTION}" />
<input type="hidden" name="return_id" value="{$RETURN_ID}" />
<input type="hidden" name="action" value="" />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td>
	<input id="save_btn_48516" type="submit" name="button" class="button"
		title="{$APP.LBL_SAVE_BUTTON_TITLE}"
		accesskey="{$APP.LBL_SAVE_BUTTON_KEY}"
		onclick="this.form.action.value='SaveCC_Job_OfferCC_ProfileRelationship';"
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



<table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
   <tr>
      <td>
         <table width="50%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td scope="row"><span>&nbsp;</span></td>
            </tr>
            <tr>
               <td scope="row"><span class="label">{$MOD.LBL_CC_JOB_OFFER_CC_PROFILE_DEPENDENCY}</span><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
               <td ><span><select name='dependency' id='dependency'>{$TYPE_OPTIONS}</select></span></td>
               <td scope="row"><span>&nbsp;</span></td>
               <td ><span>&nbsp;</span></td>
            </tr>
            <tr>
                <td scope="row"><span>&nbsp;</span></td>
            </tr>
         </table>
      </td>
   </tr>
</table>

</form>
<!-- END: main -->
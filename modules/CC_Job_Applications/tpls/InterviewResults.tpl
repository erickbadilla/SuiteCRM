

		 <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading heading_new_styles">
                    <h4 class="panel-title" >
                        <a data-toggle="collapse" href="#collapse_results"><span class="suitepicon suitepicon-module-person subpanel-icon"></span>INTERVIEW RESULTS</a>
                      </h4>
                  </div>
                  <div id="collapse_results" class="panel-collapse collapse in">
                      <div class="panel-body" style="overflow-x: hidden;">
                        <div class="row">
                            <div class="col-md-12" style="height: auto;max-height: 500px;" >
                              <br>
							<button class="create-intresult" id="create-intresult"  onclick="quickCreateInterviewShow(this, 1)">Create</button>

                              <div id="QuickCreateResult"  style="display:none">

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        <input type="hidden" id="id_intresult" name="id_intresult" value="">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_SUBJECT">Subject:</label>
                                        <div class="col-sm-10"  type="name" field="name">
                                            <input type="text" name="nameInt" id="nameInt" size="50" maxlength="255" value="" title="">
                                        </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_APPROVED">Approved:<span class="required">*</span></label>
                                        <div class="col-xs-12 col-sm-8 edit-view-field " type="approved" field="approved">
                                            <select name="approved" id="approved" title="">
                                                <option label="Yes" value="Yes">Yes</option>
                                                <option label="No" value="No" selected="selected">No</option>
                                              </select>
                                        </div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_DESCRIPTION">Description:</label>
                                        <div class="col-sm-10"  type="text" field="descriptionInt">
									                          <textarea id="descriptionInt" name="descriptionInt" rows="4" cols="75" title="" tabindex="0"></textarea>
                                        </div>
                                        </div>
                                    </div>


                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                                                              
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_APPROVED">Interview Date:</label>
                                        <div class="col-sm-10"  type="name" field="name">
                                            <table border="0" cellpadding="0" cellspacing="0" class="dateTime">
                                            <tbody><tr valign="middle">
                                            <td nowrap="" class="dateTimeComboColumn">
                                            <input class="date_input" autocomplete="off" type="text" name="txt_interview_date" id="txt_interview_date"  maxlength="10"/>
                                            <button type="button" id="txt_interview_date_trigger" class="btn btn-danger" style="float: right" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
                                            </td>
                                            <td nowrap="" class="dateTimeComboColumn">
                                            <div id="interview_date_time_section" class="datetimecombo_time_section"><span><select class="datetimecombo_time" size="1" id="interview_date_hours" tabindex="0" onchange="combo_interview_date.update(); "><option></option><option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>
                                            </select>&nbsp;:&nbsp;<select class="datetimecombo_time" size="1" id="interview_date_minutes" tabindex="0" onchange="combo_interview_date.update(); ">
                                            <option></option>
                                            <option value="00">00</option>
                                            <option value="15">15</option>
                                            <option value="30">30</option>
                                            <option value="45">45</option>
                                            </select></span></div>
                                            </td>
                                            </tr>
                                            </tbody></table>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_ENGLISH">English Level:</label>
                                        <div class="col-sm-10"  type="english_level" field="english_level">
										                        <input type="text" name="english_level" id="english_level" size="50" maxlength="255" value="" title="">
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_Positive">Positive Aspects:</label>
                                        <div class="col-sm-10"  type="positive_aspects" field="positive_aspects">
											                      <textarea id="positive_aspects" name="positive_aspects" rows="4" cols="75" title="" tabindex="0"></textarea>
                                        </div>
                                        </div>
                                    </div>


                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_WHAT">What to Improve:</label>
                                        <div class="col-sm-10"   type="what_to_improve" field="what_to_improve">
											                      <textarea id="what_to_improve" name="what_to_improve" rows="4" cols="75" title="" tabindex="0"></textarea>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_RECOMMENDED">Recommended:</label>
                                        <div class="col-sm-10"  type="recommended" field="recommended">
											                      <textarea id="recommended" name="recommended" rows="4" cols="75" title="" tabindex="0"></textarea>
                                        </div>
                                        </div>
                                    </div>


                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_OTHER">Other Position:</label>
                                        <div class="col-sm-10"   type="other_position" field="what_to_improve">
											                      <textarea id="other_position" name="other_position" rows="4" cols="75" title="" tabindex="0"></textarea>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_OBSERVATION">Observations:</label>
                                        <div class="col-sm-10"  type="text" field="observation" colspan="3">
                                            <textarea id="observation" name="observation" rows="4" cols="75" title="" tabindex="0"></textarea>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_RESULT">Result:</label>
                                        <div class="col-sm-10"  type="number" field="result">
									                        	<input type="number" name="result" id="result" step=".1" value="" title="">
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_INTER_TYPE">Type:</label>
                                        <div class="col-sm-10"  type="type" field="type">
                                          <select name="type" id="type" title="">
                                            
                                          </select>
                                        </div>
                                        </div>
                                    </div>
                                    
                                    
                                     <div class="col-xs-12 col-sm-12 " style="margin-top:3vh;text-align: center;margin-bottom: 5vh;">
                                          <button type="button" class="button" onclick="createInterviewResult($(this))">Save</button>
                                          <button type="button" class="button" onclick="quickCreateInterviewShow(this, 0)">Cancel</button>
                                     </div>
                                              
                                   </div>


                                  <div class="col-xs-12 col-sm-12">
                                        <div class="table-responsive">
                                          <table class="table table-bordered table-striped table-hover" id="table_intresults" cellspacing="0" width="100%">
                                              <thead>
                                                  <tr>
                                                      <th>Name</th>
                                                      <th>Type</th>
                                                      <th>Result</th>
                                                      <th>Interview Date</th>
                                                      <th></th>
                                                  </tr>
                                              </thead>
                                              <tbody></tbody>
                                          </table>
                                        </div>
                                    </div>

                            </div>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
          </div>

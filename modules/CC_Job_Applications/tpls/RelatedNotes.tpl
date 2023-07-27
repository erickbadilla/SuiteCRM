

		 <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading heading_new_styles">
                      <h4 class="panel-title" >
                        <a data-toggle="collapse" href="#collapse_notes"><span class="suitepicon suitepicon-module-notes subpanel-icon"></span>NOTES</a>
                      </h4>
                  </div>
                  <div id="collapse_notes" class="panel-collapse collapse in">
                      <div class="panel-body" style="overflow-x: hidden;">
                        <div class="row">
                            <div class="col-md-12" style="height: auto;max-height: 500px;" >
                              <br>
                              <button class="create-note" id="create-note"  onclick="quickCreateShow(this, 1)">Create</button>

                              <div id="QuickCreate">

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        <input type="hidden" id="id_note" name="id_note" value="">
                                        
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_NOTE_SUBJECT">Subject:</label>
                                        <div class="col-sm-10"  type="name" field="name">
                                          <input type="text" name="name" id="name" size="50" maxlength="255" value="" title="">
                                        </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_NOTE_STEP">Step:<span class="required">*</span></label>
                                        <div class="col-xs-12 col-sm-8 edit-view-field ">
                                            <select id="slc_step" class="form-control">
                                              <option selected value="">Select an option</option>
                                                {foreach from=$STAGES item=actionStage name=actionStage}
                                                  <option value="{$actionStage->id}">{$actionStage->name}</option>
                                              {/foreach}
                                            </select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_NOTE_SECURITY">Security group:</label>
                                        <div class="col-xs-12 col-sm-8 edit-view-field ">
                                              <select id="slc_permissions" class="form-control"></select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 " style="margin-bottom:3vh">
                                        <div class="form-group row" style="margin-top: -50px;">
                                        <label>&nbsp;</label>
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_FILENAME">Attachment:</label>
                                        <div class="col-xs-12 col-sm-8 edit-view-field ">
                                                <input type="hidden" id="hasfile" name="hasfile" value=0>
                                                <input type="file" id="myfile" name="myfile">
                                                <div id="contect_attachement" style="display:none">
                                                    <a id="attachement_path" style="font-size: initial;" target="_black">See attached file&nbsp;&nbsp;&nbsp;
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle;" width="20" height="20" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                      <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                      <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                                    </svg>
                                                    </a>
                                                </div>
                                        </div>
                                        </div>
                                     </div>

                                      <div class="col-xs-12 col-sm-12 ">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label" data-label="LBL_DESCRIPTION">Note:</label>
                                        <div class="col-xs-12 col-sm-8 edit-view-field ">
                                              <textarea id="description" name="description" style="width:113%" rows="6" cols="75" title="" tabindex="0"></textarea>
                                        </div>
                                        </div>
                                     </div>
                                    
                                    
                                    
                                     <div class="col-xs-12 col-sm-12 " style="margin-top:3vh;text-align: center;margin-bottom: 5vh;">
                                          <button type="button" class="button" onclick="createRelatedNote($(this))">Save</button>
                                          <button type="button" class="button" onclick="quickCreateShow(this, 0)">Cancel</button>
                                     </div>
                                              
                                   </div>


                                  <div class="col-xs-12 col-sm-12">
                                        <div class="table-responsive">
                                          <table class="table table-bordered table-striped table-hover" id="table_notes" cellspacing="0" width="100%">
                                              <thead>
                                                  <tr>
                                                      <th>Subject</th>
                                                      <th>Date</th>
                                                      <th>Attachment</th>
                                                      <th>Note</th>
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

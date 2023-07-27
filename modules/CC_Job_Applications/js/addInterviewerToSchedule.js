if(typeof InterviewerSelectorHandler !== "function"){
  function InterviewerSelectorHandler() {

    this.tablaJobInterviewer = null;
    this.stageId = null;
    this.JobApplicationId = null;
    this.isHide = true;
    this.select2Instances = [];
    this.stageTables = [];

    this.init = function (id_stage, id_job_offer){
      this.stageId = id_stage;
      this.JobApplicationId = id_job_offer;
    };

    this.hideAddInterviewers = function() {
      const table = $(`#data_table_interviewers_${this.stageId}_wrapper`);
      const list = $(`#employee_list_${this.stageId}`);
      table.hide("fast");
      list.hide("fast");
      this.isHide = true;
      $("#jobApplicationPanelDetailsSelector").css("margin-top","0");
    };

    this.showHideAddInterviewers = function(id_stage){
      const table = $(`#data_table_interviewers_${id_stage}_wrapper`);
      const list = $(`#employee_list_${id_stage}`);
      $("td.td_role > select").css({"padding-right":"28px", "overflow":"hidden", "text-overflow":"ellipsis"});

      if(!this.isHide){
        table.hide("fast");
        list.hide("fast");
        $("#jobApplicationPanelDetailsSelector").css("margin-top","0");
      }else{
        this.moreSpace();
        list.show("slow");
        table.show("slow");
        $(".dataTables_scrollHeadInner > table").css("width","100%");
        $(".dataTables_scrollHeadInner").css("width","100%");
        $(".select2-container--default").css({"width":"100%", "padding-right":"0"});
      }

      this.isHide = !this.isHide;
    };

    this.moreSpace = function(){
      const selecteCount = $(".interviewer-interviewerselect_"+this.stageId).find("option:selected").length;
      const margin = `${selecteCount===0 ? 80 : selecteCount*70}px !important`;
      $("#jobApplicationPanelDetailsSelector").css("cssText", `margin-top: ${margin}`);
    };

    this.getInterviewersJobOffer = function(kanban=false){
      let data_send = {
        action : "GetInterviewersJobOffer",
        JobApplicationId: this.JobApplicationId
      }

      this.tablaJobInterviewer = kanban ? "" : this.getStageTables(this.stageId);

      if(this.tablaJobInterviewer === ""){
        this.tablaJobInterviewer = $(`#data_table_interviewers_${this.stageId}`).DataTable({
          "responsive": false,
          "ajax" :{
            "url": 'index.php?entryPoint=GetInterviewerJobOfferEntryPoint',
            "type": "POST",
            'data' : data_send,
          },
          "paging" : false,
          "ordering": false,
          "scrollY": "120px",
          "pageLength": 50,
          "order": [[ 0, "desc" ]],
          "info" : false,
          "filter" : false,
          "columns": [
            {
              "title": "Name",
              "targets": [0],
              "data": "id",
              "className": "colum-name",
              "render": function(data, type, row){
                let url = `index.php?module=${row.object_name}&offset=1&return_module=${row.object_name}&action=DetailView&record=${row.id}`;
                let name = row.name.split("/")[0].trim();
                return `<input class="hnd_id_interviewer" type="hidden" value="${data}" /> <a href="${url}" target="_blank">${name}</a>`;
              }
            },
            {
              "title": "Role",
              "targets": [1],
              "sortable": false,
              "data": "role_list",
              "className": "td_role colum-role",
              "render": (data, type, row) => {
                let disabled_select = "";
                let html = `<select class="form-control columRole" ${disabled_select}>`;
                html+= '<option value="">Selected</option>';

                Object.entries(data).forEach(([key, value]) => {
                  let selected = row.role == key ? "selected" : "";
                  html+=`<option value="${key}" ${selected}>${value}</option>`;
                });
                html+="</select>";

                return html;
              }
            },
            {
              "width": "15%",
              "targets": [2],
              "sortable": false,
              "data": "id",
              "className": "td_delete_profile",
              "render": function(data, type, row){
                return  `<span title="Delete Interviewer" class="suitepicon suitepicon-action-delete delete_interviewer" style="cursor: pointer;"></span>`;
              }
            }
          ],
          "initComplete": (settings, json) => {
            this.createSearchElement(
              `#employee_list_${this.stageId}`,
                `interviewer_list_${this.stageId}`,
                this.JobApplicationId,
                'index.php?entryPoint=GetInterviewerEntryPoint',
                'getInterviewer',
                'Select an Employee to Add',
                (e) => {
                  this.addAjaxInterviewer(this.JobApplicationId, e.id, e.name);
            });
          },  
          "drawCallback": (settings) => {            
            const self = this;
            $(`#data_table_interviewers_${this.stageId} tbody`).find('tr').each(function(i) {
              const interviewerId = $(this).find('td').eq(0).find('.hnd_id_interviewer').val();
              $(this).find('td').eq(1).find('.columRole').change(function(h) {
                self.change_role_interviewer(interviewerId, $(this).find('option:selected').val());
              });

              $(this).find('td').eq(2).find('.delete_interviewer').click(function(h) {
                self.deleteInterviewer(interviewerId, $(this));
              });
            });
          }

        });
      }else{
        this.tablaJobInterviewer.ajax.reload();
      }

      this.putStageTables(this.stageId, this.tablaJobInterviewer);
    };

    this.createSearchElement = function(parent, elementId, profileId, url, moduleAction, placeholder, functionActionSelect){
      
      $(parent+" > div:nth-child(1)").css({"margin-bottom": "5px"});
      $(parent+" > div:nth-child(1)").html("<div id='"+elementId+"' class='dataTables_select'><select class='js-select-"+elementId+"'></select></div>");

      let element = $('#' + elementId).select2({
        placeholder: placeholder,
        allowClear: true,
        ajax: {
          delay: 250,
          transport: function (params, success, failure) {
            const query = {
              action: moduleAction,
              searchTerm: params.data.term,
              type: 'public'
            };

            const $request = $.ajax({
              type: "POST",
              url: url,
              dataType: 'json',
              data: query
            });

            $request.then(success);
            $request.fail(failure);

            return $request;
          },
          processResults: function (data) {
            let mapData = $.map(data.results, function (obj) {
              obj.id = obj.id || obj.Id;
              obj.text = obj.text || obj.Name;
              obj.text = (obj.text)?obj.text:obj.name;
              return obj;
            });
            return {
              results: mapData
            };
          }
        },
      });

      $(element).on("select2:select", function(e){
        let data = e?.params?.data;
        functionActionSelect(data);
      });

    };


    this.addAjaxInterviewer = function(JobApplicationId, id, name){
      const update = {
        "action":"addInterviewer",
        "id_employee": id,
        "JobApplicationId": JobApplicationId
      };

      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=AddInterviewerJobOfferEntryPoint',
        data: update,
        success: (resp) => {
          let data_respu = JSON.parse(resp);
          this.putInterviewerAsSelecte(data_respu.results.data.interviewer, name);
          if(!data_respu.results){
            toastr.error("Could not make the recording", 'Oops!');
            return;
          }
          this.tablaJobInterviewer.ajax.reload();
        }

      });

    };

    this.putInterviewerAsSelecte = function(id_relation, name){
      const select2Instance = this.select2Instances[this.stageId];
      const newOption = new Option(name, id_relation, false, false);
      select2Instance.append(newOption).trigger('change');
      this.tablaJobInterviewer.ajax.reload();
    };

    this.setSelect2Intance = function(instance) {
      if(instance != null){
        this.select2Instances[this.stageId] = instance;
      }
    }

    this.change_role_interviewer = function (id_interviewer,role){
      const data_Send = {
        "action":"updateRoleInterviewerJobOffer",
        "id_interviewer": id_interviewer,
        "role": role,
        "JobApplicationId": this.JobApplicationId
      };

      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=UpdateRoleInterviewerJobOfferEntryPoint',
        data: data_Send,
        success: (resp) => {}
      });

    };


    this.deleteInterviewer = function (idInterviewer, elm){
      const data_send = {
        "action":"deleteInterviewer",
        "id_interviewer": idInterviewer,
        "JobApplicationId": this.JobApplicationId
      };

      $.ajax({
        type: "POST",
        url: 'index.php?entryPoint=DeleteInterviewerJobOfferEntryPoint',
        data: data_send,
        success: (resp) => {
          let data_respu = JSON.parse(resp);

          if(!data_respu.results){
            toastr.error("Could not make the transaction", 'Oops!');
            return;
          }
          elm.closest('tr').remove();
          $(`.interviewer-interviewerselect_${this.stageId}`).find('option').each(function (i, e){
            if(e.value === idInterviewer){
              e.remove()
            }
          })
          this.tablaJobInterviewer.ajax.reload();
        }
      });

    };

    this.putStageTables = function (id_stage, table_reference){
      const table = this.stageTables[id_stage];
      if(table == null){
        this.stageTables[id_stage] = table_reference;
      }
    };

    this.getStageTables = function (id_stage){
      const table = this.stageTables[id_stage];
      if(table == null){
        return "";
      }
      return table;
    };
  }
}
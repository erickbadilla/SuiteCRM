
<button id="infobutton" onclick="exportTableToExcel('tblData')"  style="border-radius: 5px; padding: 5px 10px; background-color: #4B97C4; color: white; font-weight: 800;float: right;" >All Information</button>


{literal}
<script>

    function exportTableToExcel (){
      $("#infobutton").text('Downloading....');

      window.open('index.php?entryPoint=EmployeeInformationEntryPoint&action=getAllInformation');
      const myTimeout =  setTimeout( $("#infobutton").text('All Information'), 1000);
    
    }


</script>
{/literal}
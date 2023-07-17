<?php 

$layout_defs["CC_Employee_Information"]["subpanel_setup"]["cc_employee_information_inven_licenses"] = array (
  'order' => 100,
  'module' => 'INVEN_License',
  'subpanel_name' => 'ForINVEN_Asset',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'Licenses',
  'get_subpanel_data' => 'function:getAssignedObjects',
  'function_parameters' => array(
    'import_function_file' => 'custom/application/Ext/Include/subPanelHandler.php', 
    'module' => 'INVEN_License' 
  ),
);
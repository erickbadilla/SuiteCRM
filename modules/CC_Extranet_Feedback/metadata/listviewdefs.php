<?php
$module_name = 'CC_Extranet_Feedback';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '30%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '30%',
    'default' => true,
  ),
  'RATING' => 
  array (
    'type' => 'FiveStarRating',
    'label' => 'LBL_RATING',
    'width' => '15%',
    'default' => true,
  ),
  'CC_EXTRANET_FEEDBACK_CC_EXTRANET_USERS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_EXTRANET_FEEDBACK_CC_EXTRANET_USERS_FROM_CC_EXTRANET_USERS_TITLE',
    'id' => 'CC_EXTRANET_FEEDBACK_CC_EXTRANET_USERSCC_EXTRANET_USERS_IDA',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>

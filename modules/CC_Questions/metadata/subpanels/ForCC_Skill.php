<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'CC_Questions';
$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
        array('widget_class' => 'SubPanelReorderCC_SkillCC_QuestionsButton'),
    ),

    'where' => '',

    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '65%',
        ),
        'date_modified' => array(
            'vname' => 'LBL_DATE_MODIFIED',
            'width' => '45%',
        ),
        'type' => 
        array (
          'vname' => 'LBL_TYPE',
          'width' => '10%',
        ),
        'category' => 
        array (
          'vname' => 'LBL_CATEGORY',
          'width' => '10%',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => $module_name,
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        ),
        
        'order'=>array(
            'vname' => 'LBL_QUESTION_ORDER',
            'width' => '5%',
            'widget_class' => 'SubPanelOrderCC_SkillCC_QuestionsButton',
        ),

    ),
);
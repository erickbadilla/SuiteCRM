<?php
$module_name = 'CC_Job_Offer';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
          4 => 
          array (
            'customCode' => '<input type="submit" class="button" title="Publish" name="Publish" value="Publish" />',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => 'Publish',
              'htmlOptions' => 
              array (
                'class' => 'button',
                'id' => 'Publish_button',
                'title' => 'Publish',
                'onclick' => 'this.form.action.value=\'publish\';this.form.return_action.value=\'DetailView\'',
                'name' => 'Publish',
              ),
            ),
          ),
          5 => 
          array (
            'customCode' => '<input type="submit" class="button" title="Unpublish" name="Unpublish" value="Unpublish" />',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => 'Unpublish',
              'htmlOptions' => 
              array (
                'class' => 'button',
                'id' => 'Unpublish_button',
                'title' => 'Unpublish',
                'onclick' => 'this.form.action.value=\'unpublish\';this.form.return_action.value=\'DetailView\'',
                'name' => 'Unpublish',
              ),
            ),
          ),
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 
          array (
            'name' => 'is_published',
            'label' => 'LBL_IS_PUBLISHED',
          ),
        ),
        1 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'expire_on',
            'label' => 'LBL_EXPIRE_ON',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'contract_type',
            'studio' => 'visible',
            'label' => 'LBL_CONTRACT_TYPE',
          ),
          1 => 
          array (
            'name' => 'assigned_location',
            'studio' => 'visible',
            'label' => 'LBL_ASSIGNED_LOCATION',
          ),
        ),
        3 => 
        array (
          0 => 'description',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>

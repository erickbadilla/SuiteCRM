<?php
/**
 * COPY this file into base suiteCRM folder manually.
 */
ini_set('display_errors', 0);
ini_set('error_reporting', 0);

define('sugarEntry', true);
require_once('include/entryPoint.php');
require_once('modules/CH_Country_Holidays/entryPointHandler.php');
$handler = new ch_country_holidays_entrypoint_handler();
$options = getopt("r:");
$record_id = $options['r'];
$response = (object) $handler->handleActivateRecord($record_id);
echo $response;

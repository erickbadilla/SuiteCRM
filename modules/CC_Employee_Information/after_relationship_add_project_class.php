<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once "modules/CC_Employee_Information/controller.php";

use CC_Employee_InformationController;
use \BeanFactory;

class after_relationship_add_class {

function after_relationship_add_method($bean, $event, $arguments)
{
  if($arguments["related_module"] === "Project"){
    $action = "assigned to the";
    $this->PMSNotification($arguments["related_id"], $arguments["related_bean"]->name, $bean->name, $action);
  }
}

public function PMSNotification($projectId, $projectName, $employeeName, $action)
{
  $email_tamplate_name = "Notifications PM";
  //get employees with role PM in the project
  $PMs = (new CC_Employee_InformationController)->getThoseWhoHavePMRoleByProjectId($projectId);

  //get email template
  $template = BeanFactory::getBean('EmailTemplates');
  $template = $template->retrieve_by_string_fields(
    array(
      'name' => $email_tamplate_name
    )
  );

  //For each employee PM or TL send a email notification
  foreach($PMs as $key => $pm){
    $beanEmailOutBound = BeanFactory::getBean('OutboundEmailAccounts');
    $BeanEmailOutBoundFields = $beanEmailOutBound->retrieve_by_string_fields(array("type" => "system"));
    
    $search = array('/<script[^>]*?>.*?<\/script>/si',          // Strip out javascript
        '/<[\/\!]*?[^<>]*?>/si',                                // Strip out HTML tags
        '/([\r\n])[\s]+/',                                      // Strip out white space
        '/&(quot|#34);/i',                                      // Replace HTML entities
        '/&(amp|#38);/i','/&(lt|#60);/i','/&(gt|#62);/i',
        '/&(nbsp|#160);/i','/&(iexcl|#161);/i','/<address[^>]*?>/si',
        '/&(apos|#0*39);/','/&#(\d+);/'
    );
    $replace = array('','','\1','"','&','<','>',' ',chr(161),'<br>',"'",'chr(%1)');
    $text = preg_replace($search, $replace, $template->body_html);
    $text = str_replace("<p><pagebreak /></p>", "<pagebreak />", $text);
    $text = str_replace("$"."employee_full_name", $employeeName, $text);
    $text = str_replace("$"."project_name", $projectName, $text);
    $text = str_replace("$"."action", $action, $text);

    $template->subject = str_replace("$"."employee_full_name", $employeeName, $template->subject);
    $template->subject = str_replace("$"."action", $action, $template->subject);
    $template->subject = str_replace("$"."project_name", $projectName, $template->subject);

    $printData = str_replace("\n", "<br />", $text);

    $mailer = new SugarPHPMailer();
    $mailer->prepForOutbound();
    $mailer->setMailerForSystem();
    $mailer->Subject = $template->subject;
    $mailer->Body = $printData;
    $mailer->isHTML(true);
    $mailer->AltBody = $printData;
    $mailer->From = $BeanEmailOutBoundFields->smtp_from_addr;
    isValidEmailAddress($mailer->From);
    $mailer->FromName = $BeanEmailOutBoundFields->smtp_from_name;
    $mailer->addAddress($pm->current_email);
    $send_ok = $mailer->send();
  }
}

}
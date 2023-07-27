<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'modules/CC_Skill/controller.php';
require_once 'modules/CC_Qualification/controller.php';
require_once 'modules/CC_Skill/CC_SkillCC_ProfileRelationship.php';
require_once 'modules/CC_Qualification/CC_QualificationCC_ProfileRelationship.php';
require_once 'custom/include/careersQueryBuilder.php';
require_once 'Profile_Rating_Area.php';

use Api\V8\Config\Common as Common;
use phpDocumentor\Reflection\Types\Boolean;

class CC_ProfileController extends SugarController
{

    public static $PROFILE_VIEW = 'cc_profile_cc_employee_information_rating';
    public static $PROFILE_CANDIDATE_SKILL_VIEW = 'cc_profile_cc_candidate_skill_information';
    public static $PROFILE_CANDIDATE_QUALIFICATION_VIEW = 'cc_profile_cc_candidate_qualification_information';
    public static $PROFILE_EMPLOYEE_QUALIFICATION_VIEW = 'cc_profile_cc_employee_qualification_information';
    public static $CCPROFILE_CCEMPLOYEE_GENERALRATING_VIEW = 'cc_profile_cc_employee_general_rating';
    public static $CCPROFILE_CCCANDIDATE_GENERALRATING_VIEW = 'cc_profile_cc_candidate_general_rating';

    public function __construct()
    {
        parent::__construct();

        $create_view_sql = "CREATE OR REPLACE VIEW " . self::$PROFILE_VIEW . " as
select `e`.`id`                                   AS `employee_id`,
       `cpt`.`cc_profile_cc_skillcc_profile_ida`  AS `profile_id`,
       `e`.`name`                                 AS `employee_name`,
       `e`.`active`                               AS `active`,
       `e`.`country_law`                          AS `country_law`,
       (SELECT jd.name from cc_job_description jd WHERE jd.id=`e`.cc_job_description_id_c ) AS `position`,
       `e`.`english_level`                        AS `english_level`,
       `e`.`project_id_c`                         AS `project_id`,
       `p`.`name`                                 AS `project_name`,
       `e`.`is_assigned`                          AS `is_assigned`,
       group_concat(concat('{ \"skillid\": \"', `cs`.`id`, '\", \"skillname\":\"', `cs`.`name`, '\", \"employeeyears\":\"', `rel`.`years`, '\", \"employeerating\":\"', `rel`.`rating`, '\", \"profileyears\":\"', `cpt`.`years`, '\", \"profilerating\":\"', `cpt`.`rating`, '\"}') separator ',') AS `skill`,
           sum((case
                   when `rel`.`rating` >= `cpt`.`rating` then 5
                   when `rel`.`rating` >= `cpt`.`rating` - 1 then 3.5
                   when `rel`.`rating` >= `cpt`.`rating` - 2 then 2.5
                   when `rel`.`rating` > 0 then 1.5
                   else 0 end)+(case
                   when `rel`.`years` >= `cpt`.`years` then 5
                   when `rel`.`years` >= `cpt`.`years` - 1 then 3.5
                   when `rel`.`years` >= `cpt`.`years` - 2 then 2.5
                   when `rel`.`years` > 0 then 1.5
                   else 0 end)
           ) * 10 / max((select count(0)
                   from `cc_profile_cc_skill_c` `cf`
                   where `cf`.`cc_profile_cc_skillcc_profile_ida` = `cpt`.`cc_profile_cc_skillcc_profile_ida`
                   and `cf`.`deleted` = 0)) AS `Matchs`,
           sum((case
                   when `rel`.`rating` >= `cpt`.`rating` then 5
                   when `rel`.`rating` >= `cpt`.`rating` - 1 then 3.5
                   when `rel`.`rating` >= `cpt`.`rating` - 2 then 2.5
                   when `rel`.`rating` > 0 then 1.5
                   else 0 end)+(case
                   when `rel`.`years` >= `cpt`.`years` then 5
                   when `rel`.`years` >= `cpt`.`years` - 1 then 3.5
                   when `rel`.`years` >= `cpt`.`years` - 2 then 2.5
                   when `rel`.`years` > 0 then 1.5
                   else 0 end)
               ) AS `Suma`, 
           max((select count(0) from `cc_profile_cc_skill_c` `cf`
                where `cf`.`cc_profile_cc_skillcc_profile_ida` = `cpt`.`cc_profile_cc_skillcc_profile_ida`
                and `cf`.`deleted` = 0)) AS `divisor`
           from ((((`cc_profile_cc_skill_c` `cpt` join `cc_employee_information_cc_skill_c` `rel` on (
                `cpt`.`cc_profile_cc_skillcc_skill_idb` =
                `rel`.`cc_employee_information_cc_skillcc_skill_idb`)) left join `cc_skill` `cs` on (`cs`.`id` = `cpt`.`cc_profile_cc_skillcc_skill_idb`)) join `cc_employee_information` `e` on (
                `e`.`id` = `rel`.`cc_employee_information_cc_skillcc_employee_information_ida`))
                 left join `project` `p` on (`p`.`id` = `e`.`project_id_c`))
           where `cpt`.`deleted` = 0 and `e`.`deleted` = 0 group by `e`.`id`, `cpt`.`cc_profile_cc_skillcc_profile_ida`;";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $db->query($create_view_sql);

        $this->getQualificationRatingFunctionQuery();
        $this->createCandidateSkillView();
        $this->createCandidateQualificationViews();
    }

    /**
     * Create the candidate skill view on the db
     */
    public function createCandidateSkillView()
    {

        $create_skillview_sql = "CREATE OR REPLACE VIEW " . self::$PROFILE_CANDIDATE_SKILL_VIEW . " AS SELECT 
                ca.id 'candidate_id', 
                ca.name 'candidate_name',
                ca.country 'country',
                ca.email 'email',
                ca.phone 'phone',
                ca.years_of_experience 'years_of_experience',
                cpt.cc_profile_cc_skillcc_profile_ida 'profile_id',        
        GROUP_CONCAT(CONCAT('{ \"skillid\": \"', cs.id, '\", \"skillname\":\"' , cs.name ,'\", \"candidateyears\":\"' , rel.years ,'\", \"candidaterating\":\"' , rel.rating ,'\", \"profilerating\":\"' , cpt.rating ,'\"}')) skill, 
         ((SUM((CASE
                  WHEN rel.rating >= cpt.rating THEN 5
                  WHEN rel.rating < cpt.rating AND (cpt.rating- rel.rating) <= 1 THEN 3.5
                  WHEN (cpt.rating- rel.rating) > 1 AND (cpt.rating- rel.rating) <= 2 THEN 2.5
                  WHEN rel.rating > 0 AND (cpt.rating- rel.rating) > 2 THEN 1.5
                  ELSE 0
            END)+(CASE
                  WHEN rel.years >= cpt.years THEN 5
                  WHEN rel.years < cpt.years AND (cpt.years - rel.years) <= 1 THEN 3.5
                  WHEN (cpt.years- rel.years) > 1 AND (cpt.years- rel.years) <= 2 THEN 2.5
                  WHEN rel.years > 0 AND (cpt.years- rel.years) > 2 THEN 1.5
                  ELSE 0
            END)))*10 /
                  MAX((SELECT count(*) FROM cc_profile_cc_skill_c as cf
                  WHERE cf.cc_profile_cc_skillcc_profile_ida= cpt.cc_profile_cc_skillcc_profile_ida and cf.deleted = 0))
            ) as 'Matchs'
          FROM cc_profile_cc_skill_c as cpt, cc_candidate_cc_skill_c as rel, cc_skill as cs, cc_candidate as ca
          WHERE (cpt.cc_profile_cc_skillcc_skill_idb = rel.cc_candidate_cc_skillcc_skill_idb and
          cs.id = cpt.cc_profile_cc_skillcc_skill_idb  and ca.id = rel.cc_candidate_cc_skillcc_candidate_ida and rel.deleted = 0 and cpt.deleted = 0 and ca.deleted = 0 and cs.deleted = 0)
          GROUP BY ca.id,cpt.cc_profile_cc_skillcc_profile_ida";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $db->query($create_skillview_sql);
    }

    /**
     * Create the candidate qualifications view on the db
     */
    public function createCandidateQualificationViews()
    {
        $cc_profile_cc_employee_general_rating = "CREATE OR REPLACE VIEW ". self::$CCPROFILE_CCEMPLOYEE_GENERALRATING_VIEW . " AS SELECT 
            `cei`.`id` AS `id`,  `cei`.`name` AS `Employee Name`, `cpceqi`.`profile_id`  AS `profile_id`, `cpceqi`.`name`  AS `profile_name`,
            CC_Get_Employee_Skill_Rating_Match(`cei`.`id`, `cpceqi`.`profile_id`) AS `skill_rating`,
            CC_Get_Employee_Qualification_Rating_Match(`cei`.`id`, `cpceqi`.`profile_id`) AS `qualification_rating`,
            round(((CC_Get_Employee_Skill_Rating_Match(`cei`.`id`, `cpceqi`.`profile_id`) + CC_Get_Employee_Qualification_Rating_Match(`cei`.`id`, `cpceqi`.`profile_id`)) / 2), 2) AS `general_rating`
        from `cc_employee_information` `cei`, ". self::$PROFILE_EMPLOYEE_QUALIFICATION_VIEW ." `cpceqi`
            where (`cei`.`id` = `cpceqi`.`employee_id`)  and `cei`.`deleted` = 0 GROUP BY `cei`.`id`,`cpceqi`.`profile_id`;";

        $cc_profile_cc_candidate_general_rating = "CREATE OR REPLACE VIEW ". self::$CCPROFILE_CCCANDIDATE_GENERALRATING_VIEW . " AS SELECT `cc`.`id`, `cpccqi`.`profile_id`,
                `cpccsi`.`Matchs` AS 'skill_rating', `cpccqi`.`Matchs` AS 'qualification_rating', ROUND((`cpccsi`.`Matchs` + `cpccqi`.`Matchs`)/2) AS 'general_rating'
            FROM `cc_candidate` `cc` 
                INNER JOIN ". self::$PROFILE_CANDIDATE_SKILL_VIEW ." `cpccsi` ON `cc`.`id` = `cpccsi`.`candidate_id` 
                INNER JOIN ". self::$PROFILE_CANDIDATE_QUALIFICATION_VIEW ." `cpccqi` ON `cc`.`id` = `cpccqi`.`candidate_id` 
            WHERE `cpccqi`.`profile_id` = `cpccsi`.`profile_id` and (`cc`.`deleted` = 0)";

        $create_employee_qualificationview_sql = "CREATE OR REPLACE VIEW ". self::$PROFILE_EMPLOYEE_QUALIFICATION_VIEW . " AS select `ce`.`id`  AS `employee_id`,
                `ce`.`name` AS `employee_name`, `cpt`.`cc_profile_cc_qualificationcc_profile_ida` AS `profile_id`, `pf`.`name`,
                group_concat(concat('{\n\t\"qualificationId\": \"', `cs`.`id`, '\",\n\t\"qualificationName\":\"', `cs`.`name`,
                                    '\",\n\t\"employeeActual\":\"', `rel`.`actual_qualification`,
                                    '\",\n\t\"Employee_score\":\"',
                                    convert(`CC_Get_Qualification_Rating_Value`(`rel`.`actual_qualification`) using utf8),
                                    '\",\n\t\"profileRequired\":\"', `cs`.`mininum_requiered`,
                                    '\",\n\t\"profile_score\":\"',
                                    convert(`CC_Get_Qualification_Rating_Value`(`cs`.`mininum_requiered`) using utf8),
                                    '\",\n\t\"HasDigitalSupport\":\"', `rel`.`has_digital_support`,
                                    '\",\n\t\"DigitalSupportRequired\":\"', `cs`.`digital_support_required`,
                                    '\",\n\t\"ProfileName\":\"', `pf`.`name`, '\"\n}')
                            separator ',\n') AS `qualifications`,
                ((sum(((case when ((`CC_Get_Qualification_Rating_Value`(`cs`.`mininum_requiered`) -
                                    `CC_Get_Qualification_Rating_Value`(`rel`.`actual_qualification`)) <= 0) then 8
                            when ((`CC_Get_Qualification_Rating_Value`(`cs`.`mininum_requiered`) -
                                    `CC_Get_Qualification_Rating_Value`(`rel`.`actual_qualification`)) = 1) then 6
                            else 4 end) + (case
                                                when (`cs`.`digital_support_required` = 0) then 2
                                                when ((`cs`.`digital_support_required` = 1) and (`rel`.`has_digital_support` = 1))
                                                    then 2
                                                else 0 end))) * 10) / max((select count(0)
                                                                            from `cc_profile_cc_qualification_c` `cq`
                                                                            where ((`cq`.`cc_profile_cc_qualificationcc_profile_ida` =
                                                                                    `cpt`.`cc_profile_cc_qualificationcc_profile_ida`) and
                                                                                (`cq`.`deleted` = 0))))) AS `Matchs`
        from `cc_profile_cc_qualification_c` `cpt` inner join `cc_employee_information_cc_qualification_c` `rel` on `cpt`.`cc_profile_cc_qualificationcc_qualification_idb` = `rel`.`cc_employee_information_cc_qualificationcc_qualification_idb`
                join `cc_qualification` `cs` join `cc_employee_information` `ce` join `cc_profile` `pf`
        where ((`cpt`.`cc_profile_cc_qualificationcc_profile_ida` = `pf`.`id`) and (`cs`.`id` = `cpt`.`cc_profile_cc_qualificationcc_qualification_idb`) and
                (`ce`.`id` = `rel`.`cc_employef198rmation_ida`) and (`cs`.`deleted` = 0) and (`rel`.`deleted` = 0) and (`cpt`.`deleted` = 0) and (`ce`.`deleted` = 0))
        group by `ce`.`id`, `cpt`.`cc_profile_cc_qualificationcc_profile_ida`;";

        $create_candidate_qualificationview_sql = "CREATE OR REPLACE VIEW ". self::$PROFILE_CANDIDATE_QUALIFICATION_VIEW . " AS select `ca`.`id`  AS `candidate_id`,
               `ca`.`name` AS `candidate_name`, `cpt`.`cc_profile_cc_qualificationcc_profile_ida` AS `profile_id`, `pf`.`name`,
               group_concat(concat('{\n\t\"qualificationId\": \"', `cs`.`id`, '\",\n\t\"qualificationName\":\"', `cs`.`name`,
                                   '\",\n\t\"candidateActual\":\"', `rel`.`actual_qualification`,
                                   '\",\n\t\"Candidate_score\":\"',
                                   convert(`CC_Get_Qualification_Rating_Value`(`rel`.`actual_qualification`) using utf8),
                                   '\",\n\t\"profileRequired\":\"', `cs`.`mininum_requiered`,
                                   '\",\n\t\"profile_score\":\"',
                                   convert(`CC_Get_Qualification_Rating_Value`(`cs`.`mininum_requiered`) using utf8),
                                   '\",\n\t\"HasDigitalSupport\":\"', `rel`.`has_digital_support`,
                                   '\",\n\t\"DigitalSupportRequired\":\"', `cs`.`digital_support_required`,
                                   '\",\n\t\"ProfileName\":\"', `pf`.`name`, '\"\n}')
                            separator ',\n') AS `qualifications`,
               ((sum(((case when ((`CC_Get_Qualification_Rating_Value`(`cs`.`mininum_requiered`) -
                                  `CC_Get_Qualification_Rating_Value`(`rel`.`actual_qualification`)) <= 0) then 8
                           when ((`CC_Get_Qualification_Rating_Value`(`cs`.`mininum_requiered`) -
                                  `CC_Get_Qualification_Rating_Value`(`rel`.`actual_qualification`)) = 1) then 6
                           else 4 end) + (case
                                              when (`cs`.`digital_support_required` = 0) then 2
                                              when ((`cs`.`digital_support_required` = 1) and (`rel`.`has_digital_support` = 1))
                                                  then 2
                                              else 0 end))) * 10) / max((select count(0)
                                                                         from `cc_profile_cc_qualification_c` `cq`
                                                                         where ((`cq`.`cc_profile_cc_qualificationcc_profile_ida` =
                                                                                 `cpt`.`cc_profile_cc_qualificationcc_profile_ida`) and
                                                                                (`cq`.`deleted` = 0))))) AS `Matchs`
        from `cc_profile_cc_qualification_c` `cpt` inner join `cc_candidate_cc_qualification_c` `rel` on `cpt`.`cc_profile_cc_qualificationcc_qualification_idb` = `rel`.`cc_candidate_cc_qualificationcc_qualification_idb`
              join `cc_qualification` `cs` join `cc_candidate` `ca` join `cc_profile` `pf`
        where ((`cpt`.`cc_profile_cc_qualificationcc_profile_ida` = `pf`.`id`) and (`cs`.`id` = `cpt`.`cc_profile_cc_qualificationcc_qualification_idb`) and
               (`ca`.`id` = `rel`.`cc_candidate_cc_qualificationcc_candidate_ida`) and (`rel`.`deleted` = 0) and (`cpt`.`deleted` = 0) and (`cs`.`deleted` = 0) and (`pf`.`deleted` = 0) and (`ca`.`deleted` = 0))
        group by `ca`.`id`, `cpt`.`cc_profile_cc_qualificationcc_profile_ida`;";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $db->query($create_candidate_qualificationview_sql);
        $db->query($create_employee_qualificationview_sql);
        $db->query($cc_profile_cc_employee_general_rating);
        $this->getQualificationEmployeeRatingFunctionQuery();
        $this->getSkillEmployeeRatingFunctionQuery();
        $db->query($cc_profile_cc_candidate_general_rating);
    }

    /**
     * Create a db function to calculate the values of the qualifications
     */
    private function getQualificationRatingFunctionQuery()
    {
        global $sugar_config;

        $db = DBManagerFactory::getInstance();
        switch ($sugar_config['dbconfig']['db_type']) {
            case 'mssql':
                $sql = '';
                break;
            case 'mysql':
            default:
                $sql = "SELECT CC_Get_Qualification_Rating_Value('degree')";
                if($db->query($sql,false,'Creating CC_Get_Qualification_Rating_Value',true)){
                    return;
                }

                $sql = "CREATE FUNCTION `CC_Get_Qualification_Rating_Value`(actualValue CHAR(40))
                   RETURNS INT DETERMINISTIC
                    RETURN CASE
                        WHEN FIND_IN_SET(actualValue ,'none') THEN 0
                        WHEN FIND_IN_SET(actualValue ,'other_related') THEN 1
                        WHEN FIND_IN_SET(actualValue ,'course') THEN 2
                        WHEN FIND_IN_SET(actualValue ,'diploma') THEN 3
                        WHEN FIND_IN_SET(actualValue ,'experience') THEN 4
                        WHEN FIND_IN_SET(actualValue ,'certification') THEN 5
                        WHEN FIND_IN_SET(actualValue ,'degree') THEN 6
                        ELSE 0
                    END;";
                break;
        }
        // Get an instance of the dabatabase manager
        // Perform the query
        $db->query($sql);
    }

    /**
     * Create a db function to calculate the values of the qualifications
     */
    private function getQualificationEmployeeRatingFunctionQuery()
    {
        global $sugar_config;

        $db = DBManagerFactory::getInstance();
        switch ($sugar_config['dbconfig']['db_type']) {
            case 'mssql':
                $sql = '';
                break;
            case 'mysql':
            default:
                $sql = "SELECT CC_Get_Employee_Qualification_Rating_Match('','')";
                if($db->query($sql,false,'CC_Get_Employee_Qualification_Rating_Match',true)){
                    return;
                }

                $sql = "CREATE FUNCTION CC_Get_Employee_Qualification_Rating_Match(employee varchar(40), profile varchar(40)) RETURNS DECIMAL(10,2)
                        BEGIN
                            DECLARE Temp DECIMAL(10,2);
                            SELECT Matchs INTO Temp FROM ".self::$PROFILE_EMPLOYEE_QUALIFICATION_VIEW." Where employee_id= employee and profile_id = profile LIMIT 1;
                            RETURN IFNULL(Temp, 0);
                        END;";
                break;
        }
        // Get an instance of the dabatabase manager
        // Perform the query
        $db->query($sql);
    }

    /**
     * Create a db function to calculate the values of the qualifications
     */
    private function getSkillEmployeeRatingFunctionQuery()
    {
        global $sugar_config;

        $db = DBManagerFactory::getInstance();
        switch ($sugar_config['dbconfig']['db_type']) {
            case 'mssql':
                $sql = '';
                break;
            case 'mysql':
            default:
                $sql = "SELECT CC_Get_Employee_Skill_Rating_Match('','')";
                if($db->query($sql,false,'CC_Get_Employee_Skill_Rating_Match',true)){
                    return;
                }

                $sql = "CREATE FUNCTION CC_Get_Employee_Skill_Rating_Match(employee varchar(40), profile varchar(40)) RETURNS DECIMAL(10,2)
                        BEGIN
                            DECLARE Temp DECIMAL(10,2);
                            SELECT Matchs INTO Temp FROM ".self::$PROFILE_VIEW." Where employee_id= employee and profile_id = profile LIMIT 1;
                            RETURN IFNULL(Temp, 0);
                        END;";
                break;
        }
        // Get an instance of the dabatabase manager
        // Perform the query
        $db->query($sql);
    }


    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_ProfileController()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    /**
     *
     * @param array $arrIds
     * @return array
     */
    public function getRecordsByIds(array $arrIds)
    {

        $sql = "SELECT p.id 'Id', p.name 'Name', p.description 'Description', p.published 'Published'  FROM " . Common::$profileTable . " p WHERE p.systemonly = 0 and p.deleted = 0";

        if (!empty($arrIds)) {
            $sql .= " AND p.id IN ('" . implode("', '", $arrIds) . "')";
        }

        $sql .= " ORDER BY p.name";

        // Get an instance of the database manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $skills = (new CC_SkillController)->getRecordsByProfileId($row['Id']);
            $quas = (new CC_QualificationController)->getRecordsByProfileId($row['Id'], false);
            $row['Skills'] = $skills;
            $row['Qualifications'] = $quas;
            $row["Published"] = boolval($row["Published"]);
            $result[] = $row;
        }
        return $result;
    }

    /**
     *
     * @param string $joId
     * @return array
     */
    public function getRecordsByJobOfferId(string $joId)
    {

        $sql = "SELECT pjo.dependency 'Dependency', p.id 'profileId' 
                  FROM " . Common::$pjoRelationTable . " pjo 
                    INNER JOIN " . Common::$joTable . " jo ON jo.id = pjo." . Common::$pjoFieldB . " AND jo.deleted = 0 
                    INNER JOIN " . Common::$profileTable . " p ON p.id = pjo." . Common::$pjoFieldA . " AND p.deleted = 0 
                  WHERE pjo.deleted = 0 AND jo.id = '" . $joId . "'";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $profile = $this->getRecordsByIds(explode(",", $row['profileId']));

            $row['Profile'] = $profile;
            unset($row["profileId"]);
            $result[] = $row;
        }

        return $result;
    }

    public function saveProfileRecord(object $profile)
    {
        $profile = \Api\V8\Utilities::keysToLower($profile);
        $lookForId = null;
        if(key_exists('id', $profile)){
            $lookForId = $profile->id;
        }

        $profileBean = BeanFactory::newBean('CC_Profile',$lookForId);

        if(!is_null($lookForId) && is_null($profileBean->id)){
            $profileBean->id= $lookForId;
            $profileBean->new_with_id = $lookForId;
        }

        $profileBean->description = $profile->description;
        $profileBean->name = $profile->name;

        $result = [];

        $result['Id'] = $profileBean->save();
        $result['Name'] = $profileBean->name;
        $result['Description'] = $profileBean->description;
        $result['Skills'] = CC_SkillCC_ProfileRelationship::saveProfileSkillRecord($profileBean, $profile->skills);
        $result['Qualifications'] = CC_QualificationCC_ProfileRelationship::saveProfileQualificationRecord(
            $profileBean, $profile->qualifications,
            ["id" => "Id", "name" => "Name", "description" => "Description", "published" => "Published", "mininum_requiered" => "MinimumRequired", "digital_support_required" => "DigitalSupportRequired"]);
        return $result;
    }


    /**
     * @return array
     */
    public function getAllProfiles()
    {

        $sql = "SELECT p.id 'Id', p.name 'Name'
                  FROM " . Common::$profileTable . " p 
                  WHERE p.deleted = 0 
                  ORDER BY p.name";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $result = array_merge($result, array($row["Id"] => $row["Name"]));
        }

        return $result;
    }

    /**
     *
     */
    public function remove_duplicate_skills($skills)
    {
        $actual_skills = array_map(function ($skill) {
            return $skill["Id"];
        }, $skills);

        $unique_skills = array_unique($actual_skills);

        return array_values(array_intersect_key($skills, $unique_skills));
    }

    /**
     * @param string $secondaryModule
     * @param string $secondaryId
     * @param string $profileId
     * @return array
     */
    public function rateProfile(string $secondaryModule, string $secondaryId, string $profileId)
    {
        $query_employees = 'SELECT cc_employee_information.id AS id, cc_employee_information.name  AS `Employee Name`, cc_profile.id  AS `profile_id`, `cc_profile`.`name`  AS `profile_name`,
                                   CC_Get_Employee_Skill_Rating_Match("{employee_id}", "{profile_id}") AS `skill_rating`,
                                   CC_Get_Employee_Qualification_Rating_Match("{employee_id}", "{profile_id}") AS `qualification_rating`,
                                   round(((CC_Get_Employee_Skill_Rating_Match("{employee_id}", "{profile_id}") + CC_Get_Employee_Qualification_Rating_Match("{employee_id}", "{profile_id}")) / 2), 2) AS `general_rating`
                            FROM cc_employee_information, cc_profile WHERE cc_employee_information.id = "{employee_id}" and cc_profile.id = "{profile_id}" LIMIT 1';

        $query_candidates = '';
        $modules = [
            "CC_Employee_Information" => ["sql"=>$query_employees ],
            "CC_Candidate" => ["sql"=>$query_candidates]
        ];

        // Initialize an array with the results
        $result = [
            "skill_rating" => 0,
            "qualification_rating" => 0,
            "general_rating" => 0,
        ];

        if(key_exists($secondaryModule,$modules)){

            $query = str_replace('{employee_id}', $secondaryId, $modules[$secondaryModule]["sql"]);
            $query = str_replace('{profile_id}', $profileId, $query);
            $db = DBManagerFactory::getInstance();
            // Perform the query
            $rows = $db->query($query);


            // Fetch the row
            while ($row = $db->fetchRow($rows)) {
                $result = [
                    "skill_rating" => $row["skill_rating"]/10,
                    "qualification_rating" => $row["qualification_rating"]/10,
                    "general_rating" => $row["general_rating"]/10,
                ];
            }
        }

        return $result;
    }

    /**
     * @param string $secondaryModule
     * @param string $secondaryId
     * @param string $profileId
     * @return array
     */
    public function matchProfile(string $secondaryModule, string $secondaryId, string $profileId)
    {
        // ************************************************************
        // *****************    Matching Skills Section   *************
        // ************************************************************
        switch ($secondaryModule) {
            case "CC_Employee_Information":
                $modSkills = (new CC_SkillController)->getBusinessRecordsByEmployeeId($secondaryId);
                $modQuas = (new CC_QualificationController)->getRecordsByEmployeeId($secondaryId);
                break;
            case "CC_Candidate":
                $modSkills = (new CC_SkillController)->getRecordsByCandidateId($secondaryId);
                $modQuas = (new CC_QualificationController)->getRecordsByCandidateId($secondaryId);
                break;
        }

        $proSkills = (new CC_SkillController)->getRecordsByProfileId($profileId);
        $proQuas = (new CC_QualificationController)->getRecordsByProfileId($profileId);

        $result = [];

        $matchedSkills = [];
        $unmatchedSkillsProfile = [];
        $unmatchedSkillsModule = [];
        foreach ($proSkills as $pSkill) {
            $found = false;
            foreach ($modSkills as $mSkill) {
                if ($pSkill["Skill"]["Id"] == $mSkill["Skill"]["Id"] && $pSkill["Type"] == 'rating') {
                    $found = true;
                    $row = array(
                        "Id" => $mSkill["Skill"]["Id"],
                        "Name" => $mSkill["Skill"]["Name"],
                        "ProfileRelation" => $pSkill["Type"],
                        "ProfileAmount" => $pSkill["Amount"],
                        "moduleType" => 'rating',
                        "moduleAmount" => (key_exists('Rating',$mSkill))?$mSkill["Rating"]:$mSkill["Amount"]
                    );
                }
                if ($pSkill["Skill"]["Id"] == $mSkill["Skill"]["Id"] && $pSkill["Type"] == 'years_of_experience') {
                    $found = true;
                    $row = array(
                        "Id" => $mSkill["Skill"]["Id"],
                        "Name" => $mSkill["Skill"]["Name"],
                        "ProfileRelation" => $pSkill["Type"],
                        "ProfileAmount" => $pSkill["Amount"],
                        "moduleType" => 'years_of_experience',
                        "moduleAmount" => (key_exists('Years',$mSkill))?$mSkill["Years"]:$mSkill["Amount"]
                    );
                }

            }
            if (!$found) {
                $row = array(
                    "Id" => $pSkill["Skill"]["Id"],
                    "Name" => $pSkill["Skill"]["Name"],
                    "ProfileRelation" => $pSkill["Type"],
                    "ProfileAmount" => $pSkill["Amount"],
                    "moduleType" => null,
                    "moduleAmount" => 0
                );
                $unmatchedSkillsProfile[] = $row;
            } else {
                $matchedSkills[] = $row;
            }
        }

        foreach ($modSkills as $mSkill) {
            $found = false;
            foreach ($proSkills as $pSkill) {
                if ($mSkill["Skill"]["Id"] == $pSkill["Id"]) {
                    $found = true;
                }
            }
            if (!$found) {
                $row = array(
                    "Id" => $mSkill["Skill"]["Id"],
                    "Name" => $mSkill["Skill"]["Name"],
                    "ProfileRelation" => null,
                    "ProfileAmount" => null,
                    "moduleType" => 'rating',
                    "moduleAmount" => $mSkill["Amount"]
                );
                $unmatchedSkillsModule[] = $row;
            }
        }

        $result['Skills'] = array_merge($matchedSkills, $unmatchedSkillsProfile, $unmatchedSkillsModule);
        $result['Skills'] = $this->remove_duplicate_skills($result['Skills']);

        // ************************************************************
        // *****************    Matching Quas Section    **************
        // ************************************************************

        $matchedQuas = [];
        $unmatchedQuasProfile = [];
        $unmatchedQuasModule = [];
        foreach ($proQuas as $pQua) {
            $found = false;
            $row;
            foreach ($modQuas as $mQua) {
                if ($pQua["Id"] == $mQua["Qualification"]["Id"]) {
                    $found = true;
                    $row = array(
                        "Id" => $mQua["Qualification"]["Id"],
                        "Name" => $mQua["Qualification"]["Name"],
                        "ProfileMinimumRequired" => $pQua["MinimumRequired"],
                        "ProfileDigitalSupportRequired" => $pQua["DigitalSupportRequired"],
                        "ModuleActualQualification" => $mQua["ActualQualification"],
                        "ModuleHasDigitalSupport" => $mQua["HasDigitalSupport"]
                    );
                }
            }
            if (!$found) {
                $row = array(
                    "Id" => $pQua["Id"],
                    "Name" => $pQua["Name"],
                    "ProfileMinimumRequired" => $pQua["MinimumRequired"],
                    "ProfileDigitalSupportRequired" => $pQua["DigitalSupportRequired"],
                    "ModuleActualQualification" => null,
                    "ModuleHasDigitalSupport" => null
                );

                $unmatchedQuasProfile[] = $row;

            } else {
                $matchedQuas[] = $row;
            }
        }

        foreach ($modQuas as $mQua) {
            $found = false;
            foreach ($proQuas as $pQua) {
                if ($mQua["Qualification"]["Id"] == $pQua["Id"]) {
                    $found = true;
                }
            }
            if (!$found) {
                $row = array(
                    "Id" => $mQua["Qualification"]["Id"],
                    "Name" => $mQua["Qualification"]["Name"],
                    "ProfileMinimumRequired" => null,
                    "ProfileDigitalSupportRequired" => null,
                    "ModuleActualQualification" => $mQua["ActualQualification"],
                    "ModuleHasDigitalSupport" => $mQua["HasDigitalSupport"]
                );

                $unmatchedQuasModule[] = $row;
            }
        }
        $result['Qualifications'] = array_merge($matchedQuas, $unmatchedQuasProfile, $unmatchedQuasModule);

        return $result;
    }

    /**
     * @param string $profileId
     * @param int $draw
     * @param int $limit
     * @param int $offset
     * @param string $sortby
     * @param string $order
     * @param bool $active_only
     * @param false $unassigned_only
     * @return object
     */
    public function matchEmployee(string $profileId, int $draw = 1, int $limit = 10, int $offset = 0,
                                  string $sortby = "per.Matchs", string $order = "DESC", $active_only = true,
                                  $unassigned_only = false)
    {

        require_once("modules/Currencies/Currency.php");
        list($tSeparator,$dSeparator) = explode(get_number_separators());

        if (strtolower($sortby) == "name") {
            $sortby = 'per.employee_name';
        } else if (strtolower($sortby) == "matches") {
            $sortby = 'per.Matchs';
        }

        $active_only_sql="";
        if($active_only){
            $active_only_sql = " AND per.active = 1";
        }

        $assigned_only_sql="";
        if($unassigned_only){
            $assigned_only_sql = " AND per.is_assigned = 0";
        }


        $sql = "SELECT per.employee_id 'id',per.employee_name 'name', per.Matchs,  per.profile_id,
                per.active, per.is_assigned, per.project_id, per.project_name,
                per.country_law, per.english_level, per.skill, per.position 
                FROM " . self::$PROFILE_VIEW . " as per 
                WHERE profile_id = '$profileId' $active_only_sql $assigned_only_sql
                ORDER BY $sortby $order";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();

        // Perform the query
        $rows = $db->query($sql);
        $total = ($rows)?$rows->num_rows:0;

        if ($limit && is_int($limit)) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $rows = $db->query($sql);
        $actual = ($rows)?$rows->num_rows:0;

        // Initialize an array with the results
        $employeesResult = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $row["skill"] = json_decode("[" . $row["skill"] . "]");
            $row["matches"] = number_format((float)$row["Matchs"], 1, $dSeparator, $tSeparator);
            $row["active"] = ($row["active"]==="1");
            $row["is_assigned"] = ($row["is_assigned"]==="1");
            $row["project_id"] = (!empty($row["project_id"]))?$row["project_id"]:null;
            $row["project_name"] = (!empty($row["project_name"]))?$row["project_name"]:null;
            unset($row["Matchs"]);
            $employeesResult[] = $row;
        }

        $result = (object) [
            "draw" => $draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            'data' => $employeesResult
        ];

        return $result;
    }

    /**
     * @param string $profileId
     * @param int $draw
     * @param bool $limit
     * @param int $offset
     * @param string $sortby
     * @param string $order
     * @return object
     */
    public function matchCandidate(string $profileId, int $draw = 1, int $limit = 10, int $offset = 0, string $sortby = "per.Matchs", string $order = "DESC")
    {

        if (strtolower($sortby) == "name") {
            $sortby = 'per.candidate_name';
        } else if (strtolower($sortby) == "matches") {
            $sortby = 'per.Matchs';
        }

        $sql = "SELECT * FROM " . self::$PROFILE_CANDIDATE_SKILL_VIEW . " as per 
                WHERE profile_id = '$profileId'
                ORDER BY $sortby $order";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();

        // Perform the query
        $rows = $db->query($sql);
        $total = ($rows)?$rows->num_rows:0;

        if ($limit && is_int($limit)) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();

        // Perform the query
        $rows = $db->query($sql);
        $actual = ($rows)?$rows->num_rows:0;

        // Initialize an array with the results
        $candidatesResult = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $row["skill"] = json_decode("[" . $row["skill"] . "]");
            $row["matches"] = number_format((float)$row["Matchs"], 1, '.', '');
            unset($row["Matchs"]);
            $candidatesResult[] = $row;
        }

        $result = (object) [
            "draw" => $draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            'data' => $candidatesResult
        ];

        return $result;
    }


    public function searchBy($stringNeedle, $fieldsFilter, $sort, $order, $offset, $limit)
    {

        $queryBuilder = new CareersQueryBuilder(Common::$profileTable, $fieldsFilter);
        $queryBuilder->withLimitOffset($limit, $offset);
        $queryBuilder->withSearch($stringNeedle);
        $queryBuilder->withSort($sort);
        $queryBuilder->withOrder($order);

        $sql = $queryBuilder->getSQL();

        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            if (key_exists("published", $row)) {
                $row["published"] = $row["published"] == "1";
            }
            $result[] = $row;
        }
        return $result;
    }

}
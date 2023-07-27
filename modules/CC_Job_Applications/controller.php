<?php
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once "modules/CC_Job_Applications/JobApplicationPDFFactory.php";
require_once 'modules/CC_Job_Offer/controller.php';
require_once 'modules/CC_Skill/controller.php';
require_once 'modules/CC_Qualification/controller.php';
require_once 'modules/CC_application_stage/StageActionHandler.php';
require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'custom/Extension/application/Include/careers_security_group.php';

use Api\V8\Config\Common as Common;
use Api\V8\Utilities;

class CC_Job_ApplicationsController extends SugarController
{

    public function __construct(){
        parent::__construct();
        self::createCC_Job_Offer_JSON_Array_Accounts();
		self::createViewJobApplicationsList();
        self::createViewJobApplicationsStageList();
    }

    /**
     * Create a db function to calculate the values of the qualifications
     */
    private function createCC_Job_Offer_JSON_Array_Accounts()
    {
        global $sugar_config;
        // Get an instance of the database manager
        $db = DBManagerFactory::getInstance();
        switch ($sugar_config['dbconfig']['db_type']) {
            case 'mssql':
                $sql = '';
                break;
            case 'mysql':
            default:
                $sql = "SELECT CC_Job_Offer_JSON_Array_Accounts('')";
                if($db->query($sql,false,'Creating CC_Job_Offer_JSON_Array_Accounts',true)){
                    return;
                }

                $sql = "CREATE FUNCTION CC_Job_Offer_JSON_Array_Accounts(job_offer_id varchar(40)) returns longtext
                        BEGIN
                            DECLARE result longtext;
                            SET result=null;
                            SELECT JSON_ARRAYAGG(JSON_OBJECT('id',id,'name',name)) into result FROM accounts where id in (
                            SELECT cc_job_offer_accountsaccounts_idb
                                    from cc_job_offer_accounts_c
                                    where cc_job_offer_accountscc_job_offer_ida = job_offer_id
                            );
                            RETURN result;
                        END;";
                break;
        }

        // Perform the query
        $db->query($sql);
    }


    public function createViewJobApplicationsStageList()
    {
        $sql = "CREATE OR REPLACE VIEW cc_job_applications_cc_application_stage_v as SELECT
                    jas.cc_job_applications_cc_application_stagecc_job_applications_ida application_id,
                    jas.date_modified,
                    jas.deleted,
                    jas.cc_job_applications_cc_application_stagecc_application_stage_idb,
                       aps.type,
                       aps.name,
                       aps.stageorder,
                       aps.settings,
                    jas.completed,
                    jas.cc_application_stagecc_employee_information_last_user,
                    usr.last_name,usr.first_name,
                    jas.data,
		            CASE jas.closed_state WHEN 2 then false WHEN 1 then true else NULL end as closed_won,
                    jas.cc_application_stage_note,
                    nt.description,
                    jas.id row_id
                FROM cc_job_applications_cc_application_stage_c jas
                    JOIN cc_application_stage aps on jas.cc_job_applications_cc_application_stagecc_application_stage_idb = aps.id
                    LEFT JOIN users usr on STRCMP(jas.cc_application_stagecc_employee_information_last_user, usr.id) = 0
                    LEFT JOIN notes nt on jas.cc_application_stage_note = nt.id
                WHERE jas.deleted = 0
                ORDER BY jas.cc_job_applications_cc_application_stagecc_job_applications_ida, jas.date_modified desc ,aps.stageorder";

        $db = DBManagerFactory::getInstance();
        $db->query($sql);

    }


    public function createViewJobApplicationsList()
    {
        $sql = "create or replace view cc_job_applications_list as
                select `jo`.`name`                                                                                                    AS `job_offer_name`,
                       `ja`.`actual_stage`                                                                                            AS `stage`,
                       `ja`.`actual_stage_id`                                                                                         AS `stage_id`,
                       `ja`.`skill_rating`                                                                                            AS `skill_rating`,
                       `ja`.`qualification_rating`                                                                                    AS `qualification_rating`,
                       `ja`.`general_rating`                                                                                          AS `general_rating`,
                       `ja`.`application_type`                                                                                        AS `application_type`,
                       `c`.`name`                                                                                                     AS `candidate_name`,
                       `jo`.`id`                                                                                                      AS `job_offer_id`,
                       cast(trim(both '\"' from json_extract(`CC_Job_Offer_JSON_Array_Accounts`(`jo`.`id`),
                                                            '$[0].id')) as char(36) charset utf8)                                     AS `account_id`,
                       cast(trim(both '\"' from json_extract(`CC_Job_Offer_JSON_Array_Accounts`(`jo`.`id`),
                                                            '$[0].name')) as char(150) charset utf8)                                  AS `account_name`,
                       json_remove(`CC_Job_Offer_JSON_Array_Accounts`(`jo`.`id`), '$[0]')                                             AS `other_accounts_json`,
                       json_length(`CC_Job_Offer_JSON_Array_Accounts`(`jo`.`id`))                                                     AS `accounts_total`,
                       `c`.`id`                                                                                                       AS `candidate_id`,
                       `ja`.`id`                                                                                                      AS `applications_id`,
                       `ja`.`date_entered`                                                                                            AS `creation_date`
                from (((`cc_job_applications` `ja` join `cc_candidate_cc_job_offer_c` `cjo` on (`cjo`.`id` = `ja`.`id`)) join `cc_job_offer` `jo` on (`cjo`.`cc_candidate_cc_job_offercc_job_offer_idb` = `jo`.`id`))
                         join `cc_candidate` `c` on (`cjo`.`cc_candidate_cc_job_offercc_candidate_ida` = `c`.`id`))
                where `cjo`.`deleted` = 0
                  and `jo`.`deleted` = 0
                  and `c`.`deleted` = 0;";

        $db = DBManagerFactory::getInstance();
        $db->query($sql);
    }

    /**
	 * Create the data output array for the DataTables rows
	 *
	 *  @param  array $columns Column information array
	 *  @param  array $data    Data from the SQL get
	 *  @return array          Formatted data in a row based format
	 */
    function data_output ( $columns, $data )
	{
		$out = [];
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = [];
			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				// Is there a formatter?
				if ( isset( $column['formatter'] ) ) {
                    if(empty($column['db'])){
                        $row[ $column['dt'] ] = $column['formatter']( $data[$i] );
                    }
                    else{
                        $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
                    }
				}
				else {
                    if(!empty($column['db'])){
                        $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
                    }
                    else{
                        $row[ $column['dt'] ] = "";
                    }
				}
			}
			$out[] = $row;
		}
		return $out;
	}

    /**
	 * Paging
	 *
	 * Construct the LIMIT clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL limit clause
	 */
	public function limit ( $request, $columns )
	{
		$limit = '';
		if ( isset($request['start']) && $request['length'] != -1 ) {
			$limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
		}
		return $limit;
	}

    /**
	 * Ordering
	 *
	 * Construct the ORDER BY clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL order by clause
	 */
	public function order ( $request, $columns )
	{
		$order = '';
		if ( isset($request['order']) && count($request['order']) ) {
			$orderBy = [];
			$dtColumns = self::pluck( $columns, 'dt' );
			for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];
				if ( $requestColumn['orderable'] == 'true' ) {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';
					$orderBy[] = '`'.$column['db'].'` '.$dir;
				}
			}
			if ( count( $orderBy ) ) {
				$order = 'ORDER BY '.implode(', ', $orderBy);
			}
		}
		return $order;
	}

    /**
	 * Searching / Filtering
	 *
	 * Construct the WHERE clause for server-side processing SQL query.
	 *
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here performance on large
	 * databases would be very poor
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @param  array $bindings Array of values for PDO bindings, used in the
	 *    sql_exec() function
	 *  @return string SQL where clause
	 */
	public function filter ( $request, $columns, &$bindings )
	{
		$globalSearch = [];
		$columnSearch = [];
		$dtColumns = self::pluck( $columns, 'dt' );
		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];
				if ( $requestColumn['searchable'] == 'true' ) {
					if(!empty($column['db'])){
						$globalSearch[] = "`".$column['db']."` LIKE '%".$str."%'";
					}
				}
			}
		}
		// Individual column filtering
		if ( isset( $request['columns'] ) ) {
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				$str = $requestColumn['search']['value'];

				if ( $requestColumn['searchable'] == 'true' &&
				 $str != '' ) {
					if(!empty($column['db'])){
						$columnSearch[] = "`".$column['db']."` LIKE '%".$str."%'";
					}
				}
			}
		}
		// Combine the filters into a single string
		$where = '';
		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}
		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}
		if ( $where !== '' ) {
			$where = 'WHERE '.$where;
		}
		return $where;
	}

    /**
	 * Perform the SQL queries needed for an server-side processing requested,
	 * utilising the helper functions of this class, limit(), order() and
	 * filter() among others. The returned array is ready to be encoded as JSON
	 * in response to an SSP request, or can be modified if needed before
	 * sending back to the client.
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array|PDO $conn PDO connection resource or connection parameters array
	 *  @param  string $table SQL table to query
	 *  @param  string $primaryKey Primary key of the table
	 *  @param  array $columns Column information array
	 *  @return array          Server-side processing response array
	 */
	public function simple ( $request, $table, $primaryKey, $columns )
	{
		$bindings = [];
        $db = DBManagerFactory::getInstance();
		// Build the SQL query string from the request
		$limit = self::limit( $request, $columns );
		$order = self::order( $request, $columns );
		$where = self::filter( $request, $columns, $bindings );
		// Main query to actually get the data
        $mainSql = "SELECT * FROM `$table` $where $order $limit";
        $dataSql = $db->query($mainSql);
        $data = [];
        while ($row = $db->fetchRow($dataSql)) {
            $data[] = $row;
        }
		// Data set length after filtering
        $countSql = "SELECT COUNT(`{$primaryKey}`)FROM `$table` $where";
		$resFilterLength = $db->query($countSql);
        $recordsFiltered = 0;
        while ($row = $db->fetchRow($resFilterLength)) {
            $recordsFiltered = $row["COUNT(`job_offer_id`)"];
        }
		// Total data set length
		$totalLengthSql = "SELECT COUNT(`{$primaryKey}`) FROM   `$table`";
        $resTotalLength = $db->query($totalLengthSql);
		$recordsTotal = 0;
        while ($row = $db->fetchRow($resTotalLength)) {
            $recordsTotal = $row["COUNT(`job_offer_id`)"];
        }
		/*
		 * Output
		 */
		$result = array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => self::data_output( $columns, $data )
		);
        return $result;
	}

    /**
	 * Pull a particular property from each assoc. array in a numeric array, 
	 * returning and array of the property values from each item.
	 *
	 *  @param  array  $a    Array to get data from
	 *  @param  string $prop Property to read
	 *  @return array        Array of property values
	 */
	public function pluck ( $a, $prop )
	{
		$out = [];
		for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            if(empty($a[$i][$prop])){
                continue;
			}
			//removing the $out array index confuses the filter method in doing proper binding,
			//adding it ensures that the array data are mapped correctly
			$out[$i] = $a[$i][$prop];
		}
		return $out;
	}

    public function action_CreatePDF($jobApplication_id = null, $templateId = null){
        $applicationPDF = new JobApplicationPDFFactory($jobApplication_id,$templateId);
    }

    public function get_history($jobApplication_id)
    {
		$userGroupSecurityWhere = (new CareersSecurityGroup())->userGroupSecurityWhere('v.cc_application_stage_note');
        
		$sql = "SELECT 
						DATE_FORMAT(v.date_modified, '%M %d,%Y') date_modified, v.name, v.description
				from 
						cc_job_applications_cc_application_stage_v v 
				JOIN 
						cc_application_stage s
				         ON s.id=v.cc_job_applications_cc_application_stagecc_application_stage_idb
				WHERE 
						application_id ='".$jobApplication_id."' ".$userGroupSecurityWhere." ORDER BY s.stageorder,v.date_modified ASC ";
  
        
        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);
        // Initialize an array with the results
        $result = [];
        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $result[] = $row;
        }
        return $result;
    }

    public function getNotesByJobApplicationsId($jaId)
    {
        
		$userGroupSecurityWhere = (new CareersSecurityGroup())->recordWithoutGroupSecurityWhere('n.id');
       
        $sql = "SELECT 
		              jan.cc_job_applications_notescc_job_applications_ida AS application_id, 
					  n.id AS notes_id, n.assigned_user_id, n.date_entered, n.name, n.description, n.contact_id 
                FROM 
				     notes n 
				INNER JOIN 
				    cc_job_applications_notes_c jan
					 ON jan.cc_job_applications_notescc_job_applications_ida = '".$jaId."' AND jan.cc_job_applications_notesnotes_idb = n.id AND n.deleted = 0 AND jan.deleted = 0 
		        WHERE
				    1  ".$userGroupSecurityWhere." ";
			
        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);
        // Initialize an array with the results
        $result = [];
        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $result[] = $row;
        }
        return $result;
    }

	public function search($request)
	{
		$globalSearch = [];
		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				if ( $requestColumn['searchable'] == 'true' ) {
					$globalSearch[] = "`".$requestColumn['data']."` LIKE '%".$str."%'";					
				}
			}
		}
		// Individual column filtering
		if ( isset( $request["filterColumData"] ) ) {
			for ( $i=0, $ien=count($request["filterColumData"]) ; $i<$ien ; $i++ ) {
				$requestColumn = $request["filterColumData"][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $request["filterColumData"][$i]["column"];
				
				$str = $request["filterColumData"][$i]["text"];
				
				$columnSearch[] = "`".$column."` LIKE '%".$str."%'";
			}
		};
		// Combine the filters into a single string
		$where = '';
		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}
		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
			implode(' AND ', $columnSearch) :
			$where .' AND '. implode(' AND ', $columnSearch);
		}
		$where = $where!=''? ("AND ".$where) : $where;
		
		return $where;
	}
	
	public function getJobApplicationsList($request, $table, $primaryKey)
	{
		$hide = $request["hideStage"];
		$length = !isset($request["length"]) ? -1 : $request["length"];
		$start = $request["start"];
		$draw   = $request['draw'];
		$application_type = $request['application_type'];

        $column_orden       = (isset($request['order']))?$request['order'][0]['column']:0;
        $column_orden_type  = $draw == "1" ? 'desc' : ((isset($request['order']))?$request['order'][0]['dir']:'asc');
        $column_order_data  = $draw == "1" ? 'creation_date' : ((isset($request['columns']))?$request['columns'][$column_orden]['data']: 'creation_date');

		$limit = ($length != -1 ? "LIMIT $start,$length": "");
		$order_by =  $column_order_data ." ". $column_orden_type ;
		
		$searchText = self::search($request);
		$where = "WHERE tb.stage != '$hide' $searchText AND tb.application_type = '$application_type'";
		// Main query to actually get the data
		$mainSql = "SELECT * FROM `$table` AS tb $where ORDER BY $order_by $limit";
		$db = DBManagerFactory::getInstance();

        $dataSql = $db->query($mainSql);
        $data = [];
		$recordsFiltered = 0;
		
        while ($row = $db->fetchRow($dataSql)) {
			$recordsFiltered = $recordsFiltered + 1;
            $row['other_accounts_json'] = json_decode($row['other_accounts_json']);
			$data[] = $row;
        }
		
		// Data set length after filtering
        $countSql = "SELECT COUNT(`{$primaryKey}`) FROM `$table` AS tb $where ";
		$resFilterLength = $db->query($countSql);

        $recordsFiltered = 0;
        while ($row = $db->fetchRow($resFilterLength)) {
			$recordsFiltered = $row["COUNT(`job_offer_id`)"];
        }

		// Total data set length
		$totalLengthSql = "SELECT COUNT(`{$primaryKey}`) FROM   `$table`";
        $resTotalLength = $db->query($totalLengthSql);
		$recordsTotal = 0;
        while ($row = $db->fetchRow($resTotalLength)) {
			$recordsTotal = $row["COUNT(`job_offer_id`)"];
        }
		/*
		* Output
		*/
		$result = array(
			"draw"            => isset ( $request['draw'] ) ?
			intval( $request['draw'] ) :
			0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => $data
		);
        return $result;
	}

	public function getSkillsSummary($jobOfferId, $candidateId)
	{
		$profileSkills = (new CC_Job_OfferController())->GetProfileSkills($jobOfferId)["data"];
		$tempCandidateSkills = (new CC_SkillController())->getRecordsByCandidateId($candidateId);
    	$candidateSkills = (new CC_SkillController())->linkingRatingAndYears($tempCandidateSkills);
		$tempCandidateSkills = $candidateSkills;

		foreach($profileSkills as $key => $profileSkill){
			foreach($candidateSkills as $index => $candidateSkill){
				if(array_search($profileSkill["id_skills"], $candidateSkill["Skill"])){
					$profileSkills[$key]["rating_candidate"] = $candidateSkill["rating"];
					$profileSkills[$key]["years_candidate"] = $candidateSkill["years"];
					$profileSkills[$key]["name_skill_candidate"] = $candidateSkill["Skill"]["Name"];
					unset($tempCandidateSkills[$index]);
					break;
				}
			}
		}

		$tempCandidateSkills = array_values($tempCandidateSkills);
		$actualSkills = count($profileSkills);
		foreach($tempCandidateSkills as $index => $candidateSkill){
			$key = $actualSkills + $index;
			$profileSkills[$key]["others_candidate_skills"] = "Other Candidate Skills";
			$profileSkills[$key]["rating_candidate"] = $candidateSkill["rating"];
			$profileSkills[$key]["years_candidate"] = $candidateSkill["years"];
			$profileSkills[$key]["name_skill_candidate"] = $candidateSkill["Skill"]["Name"];
		}

		$result = array(
            "data" => $profileSkills
		);
		return $result;
	}

	public function getQualificationsSummary($jobOfferId, $candidateId)
	{
		$profileQualifications = (new CC_Job_OfferController())->GetProfileQualifications($jobOfferId)["data"];		
        $candidateQualifications = (new CC_QualificationController)->getRecordsByCandidateId($candidateId);
		$tempCandidateQualifications = $candidateQualifications;

		foreach($profileQualifications as $key => $profileQualification){
			foreach($candidateQualifications as $index => $candidateQualification){
				if($profileQualification["id_qualifications"] === $candidateQualification["Qualification"]["Id"]){
					$profileQualifications[$key]["name_qualification_candidate"] = $candidateQualification["Qualification"]["Name"];
					$profileQualifications[$key]["minimum_reuiered_candidate"] = $candidateQualification["Qualification"]["Minimum_Required"];
					unset($tempCandidateQualifications[$index]);
					break;
				}
			}
		}

		$tempCandidateQualifications = array_values($tempCandidateQualifications);
		$actualQualification = count($profileQualifications);
		foreach($tempCandidateQualifications as $index => $candidateQualification){
			$key = $actualQualification + $index;
			$profileQualifications[$key]["others_candidate_qualifications"] = "Other Candidate Qualifications";
			$profileQualifications[$key]["name_qualification_candidate"] = $candidateQualification["Qualification"]["Name"];
			$profileQualifications[$key]["minimum_reuiered_candidate"] = $candidateQualification["Qualification"]["Minimum_Required"];
		}        
		
		$result = array(
            "data" => $profileQualifications
		);
		return $result;
	}


	///// related notes //////////////

	function getRelatedNotes($applicationId){

		$userGroupSecurityWhere = (new CareersSecurityGroup())->userGroupSecurityWhere('n.id','Notes');
       
		$sql = "SELECT 
		                 n.id,n.name,n.date_entered,n.filename,n.description,s.completed stage
					FROM 
						 notes n
					LEFT JOIN 
					      cc_job_applications_cc_application_stage_c  s
					       ON s.cc_application_stage_note=n.id
					WHERE
						  n.deleted = 0 AND
					      n.parent_id  = '".$applicationId."' 
						  ".$userGroupSecurityWhere."
					ORDER BY 
					     n.date_modified DESC";

			$db = DBManagerFactory::getInstance();

			$rows = $db->query($sql);

			$results = [];

			while ($row = $db->fetchRow($rows)) {
				$results[] = $row;
			}
	
		 $json_data = array(
			"data" => $results
		 );
	
		  return $json_data;
	  }

	  public function createNote($notesData,$notesFile){
        
        global  $sugar_config;
		$time = new \DateTime();

		if(strlen($notesData['id_note']) == 0 || (!empty($notesFile['file']['tmp_name']) && strlen($notesData['id_note']) != 0 && $notesData['hasfile'] != 0)){

			$notesBean = BeanFactory::newBean('Notes');
			$notesBean->date_entered     = $time->format('Y-m-d H:i:s');
			$notesBean->date_modified    = $time->format('Y-m-d H:i:s');
			$notesBean->parent_id        = $notesData['applicationId'];
			$notesBean->parent_type      = 'CC_Job_Applications';
			$notesBean->assigned_user_id = $_SESSION['authenticated_user_id'];
			$new = 1;
		}else{

			$notesBean = BeanFactory::getBean('Notes', $notesData['id_note']);
			$notesBean->date_modified = $time->format('Y-m-d H:i:s');
		}

		$name_note = (!empty($notesData['name_status'])) ? " / ".$notesData['name_status'] : "";

		$notesBean->name        = $notesData['name'].$name_note;
		$notesBean->description = $notesData['description']; 
		$flag = 0; 


            if(!empty($notesFile['file']['tmp_name'])){ 
              
                $name_file_attached = $notesFile['file']['name'];
                $type_file_attached = $notesFile['file']['type'];
                $ext_file_attached  = explode('/',$notesFile['file']['type']);
                $size_file_attached = $notesFile['file']['size'];
                $tmp_file_attached  = $notesFile['file']['tmp_name'];
                $new_name_attached  = $notesData['applicationId']."_".date('Y-m-d_H:m:s').".".end($ext_file_attached);

                //$path_invoice = $sugar_config['upload_dir']."invoice_attachments/";
                $path_invoice = $sugar_config['upload_dir'];


                $attached_file = move_uploaded_file($tmp_file_attached,$path_invoice.$new_name_attached);
                if($attached_file){
                    chmod($path_invoice.$new_name_attached, 0777);
                    $notesBean->file_mime_type = $type_file_attached;
                    $notesBean->filename = $notesFile['file']['name'];   
					$flag = 1;                
                }else{
                    echo "attached error";
                }   
				
            }

			$noteID = $notesBean->save();

			if($noteID !== false && intval($flag) == 1){
				// rename file of folder,to use the suitecrm methods
				rename($path_invoice.$new_name_attached, $sugar_config['upload_dir'] . $notesBean->id);
			}

			if($new == 1){
				$relation1 = 'cc_job_applications_notes';
				$jobApplicationRecord = new CC_Job_Applications();
				$jobApplicationRecord->retrieve($notesData['applicationId']);

				$jobApplicationRecord->load_relationship($relation1);
				$jobApplicationRecord->$relation1->add($notesBean->id);
				$jobApplicationRecord->save();
			}
          
			// if a step note is added
			$stageCompletedRecord = new CC_Job_Applications_CC_application_stage();
			if($notesData['id_status'] != ""){ 
				if($new == 1){
					$stageCompletedRecord->cc_job_applications_cc_application_stagecc_job_applications_ida = $notesData['applicationId'];
					$stageCompletedRecord->cc_job_applications_cc_application_stagecc_application_stage_idb = $notesData['id_status'];
					$stageCompletedRecord->completed = 1;
					$stageCompletedRecord->data = json_encode(['note'=>$notesData['description'] ." / ".$notesData['name_status']]);
					$stageCompletedRecord->cc_application_stagecc_employee_information_last_user = $_SESSION['authenticated_user_id'];
					$stageCompletedRecord->cc_application_stage_note = $notesBean->id;
					$stageCompletedRecord->save();
				}else{
					 $stageCompletedRecord->retrieve_by_string_fields(array('cc_application_stage_note' => $notesBean->id));
					 $stageCompletedRecord->cc_job_applications_cc_application_stagecc_job_applications_ida = $notesData['applicationId'];
					 $stageCompletedRecord->cc_job_applications_cc_application_stagecc_application_stage_idb = $notesData['id_status'];
					 $stageCompletedRecord->completed = 1;
					 $stageCompletedRecord->data = json_encode(['note'=>$notesData['description'] ." / ".$notesData['name_status'] ]);
					 $stageCompletedRecord->cc_application_stagecc_employee_information_last_user = $_SESSION['authenticated_user_id'];
					 $stageCompletedRecord->cc_application_stage_note = $notesBean->id;
					 $stageCompletedRecord->save();
				}
			}else{
					 $sql_exixts = "DELETE FROM
							               cc_job_applications_cc_application_stage_c 
					                WHERE
									        cc_application_stage_note = '".$notesBean->id."' ";

					  $db = DBManagerFactory::getInstance();
					  $result = $db->query($sql_exixts);
			}

			// add permissions if exists

			$SecurityGroups = "SecurityGroups";
			$notesBean->load_relationship($SecurityGroups);
			$SecurityGroupData = $notesBean->$SecurityGroups->get();
			$SecurityGroupId   = !empty($SecurityGroupData[0]) ? $SecurityGroupData[0] : "0";

			
			if($SecurityGroupId != "0"){ 

				$sql_exixts = "DELETE FROM
				                        securitygroups_records 
				 			   WHERE
									record_id = '".$notesBean->id."' ";

					$db = DBManagerFactory::getInstance();
                    $result = $db->query($sql_exixts);

			}

			if(!empty($notesData['group_security'])){ 
					$sql = "INSERT INTO 
							    securitygroups_records (id,securitygroup_id,record_id,module,date_modified,created_by)
						    VALUES
							    (UUID(),'".$notesData['group_security']."','".$notesBean->id."','Notes',NOW(),'".$_SESSION['authenticated_user_id']."')";

					$db = DBManagerFactory::getInstance();
					$db->query($sql);
			}
					

			
            $json_respu = array("module" => $notesBean->object_name, "id" => $notesBean->id);
        

        return $json_respu;
    }

	public function getNoteSingle($idNote){

        $getNote  = BeanFactory::getBean('Notes', $idNote);
		//get group of security
		$SecurityGroups = "SecurityGroups";
		$getNote->load_relationship($SecurityGroups);
		$SecurityGroupData = $getNote->$SecurityGroups->get();
		$SecurityGroupId   = !empty($SecurityGroupData[0]) ? $SecurityGroupData[0] : "0";
		// get step assigned
		$stageRecord = new CC_Job_Applications_CC_application_stage();
		$stageRecord->retrieve_by_string_fields(array('cc_application_stage_note' => $idNote));

         if($getNote){
            $results[] = (object) [
                'id'              => $getNote->id,
                'name'            => $getNote->name,
                'description'     => $getNote->description,
                'filename'        => $getNote->filename,
				'group_id'        => $SecurityGroupId,
				'state'           => $stageRecord->cc_job_applications_cc_application_stagecc_application_stage_idb
            ];         
               
          }
   
          return $results;
    }


	public function getStatusJA($idApplications){

		$sql = "SELECT
                     DISTINCT(s.stageorder),s.settings
				FROM 
					cc_job_applications_cc_application_stage_c j_s
				INNER JOIN 
					cc_application_stage s 
					ON j_s.cc_job_applications_cc_application_stagecc_application_stage_idb = s.id AND s.deleted = 0
				WHERE 
					j_s.cc_job_applications_cc_application_stagecc_job_applications_ida = '".$idApplications."'
					AND j_s.deleted  = 0
				ORDER BY 
					s.stageorder";

		$db = DBManagerFactory::getInstance();
		$dataSql = $db->query($sql);
		$data = [];
		while ($row = $db->fetchRow($dataSql)) {
			$data[] = $row;
		}
		return $data;

	}

	

	function getIntResultType(){
		global $app_list_strings;

		 $json_data = array(
			"types" => $app_list_strings['interview_type_list']
		 );
	
		  return $json_data;
	  }

	  function getJobInformation($applicationId){
		$result = array();
	
		$sql_tot = "SELECT *
					FROM 
						cc_candidate_cc_job_offer_c
					WHERE
						deleted = 0 AND
					id = '".$applicationId."'";
	
			$db = DBManagerFactory::getInstance();
			$result_total = $db->fetchOne($sql_tot);
			
			$array = array();
	
			$array['id']         = $applicationId;
			$array['candidate']  = $result_total['cc_candidate_cc_job_offercc_candidate_ida'];
			$array['jobOffer']   = $result_total['cc_candidate_cc_job_offercc_job_offer_idb'];
		   
			$result = $array;
			
			return $result;
		}
		public function getResumeCandidate($candidateId){

			$sql = "SELECT
						n.*
					FROM 
						notes n
					JOIN cc_candidate_notes_c c ON n.id=c.cc_candidate_notesnotes_idb
					WHERE c.deleted = 0 AND
					c.cc_candidate_notescc_candidate_ida  = '".$candidateId."'
					AND	(description like '%PDF Upload%' or description like '%resume%')";
	
			$db = DBManagerFactory::getInstance();
			$data = $db->fetchOne($sql);
			return $data;
	
		}
	
}

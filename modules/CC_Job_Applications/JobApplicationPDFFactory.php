<?php
use CC_Job_OfferCC_CandidateRelationship;

require_once 'modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php';
require_once('modules/CC_Job_Applications/controller.php');
require_once('modules/CC_Interviews/controller.php');
require_once('modules/CC_Candidate/controller.php');
require_once('modules/CC_Profile/controller.php');

$errorLevelStored = error_reporting();
error_reporting(0);
require_once('modules/AOS_PDF_Templates/PDF_Lib/mpdf.php');
require_once('modules/AOS_PDF_Templates/templateParser.php');
// require_once('modules/AOS_PDF_Templates/sendEmail.php');
// require_once('modules/AOS_PDF_Templates/AOS_PDF_Templates.php');
error_reporting($errorLevelStored);

class JobApplicationPDFFactory {

    public string $STAR = "&#9733;";

    public function __construct($jobApplications_id, $templateId){
        self::createPDF($jobApplications_id, $templateId);
    }
 
    public function createPDF($jobApplications_id, $templateId) 
    {  
        // JobApplication relationship information
        $JARelation = new CC_Job_OfferCC_CandidateRelationship();
        $JARelation->retrieve($jobApplications_id);
        foreach ($JARelation->column_fields as $field) {
            safe_map($field, $JARelation, true);
        }
        if (!$JARelation) {
            sugar_die("Invalid Record");
        }
        $relationship = (new CC_CandidateController)->getRecordsByIds([$JARelation->cc_candidate_cc_job_offercc_candidate_ida])[0];
        $profiles = (new CC_ProfileController)->getRecordsByJobOfferId($JARelation->cc_candidate_cc_job_offercc_job_offer_idb);
        if(count($profiles)>1){
            $profileSkills = $this->merge_profiles_skills($profiles);
            $profileQualifications = $this->merge_profiles_qualifications($profiles);
        }else {
            $profileSkills = $profiles[0]["Profile"][0]["Skills"];
            $profileQualifications = $profiles[0]["Profile"][0]["Qualifications"];
        }

        // Skills
        $candidateSkills = $relationship["CandidateSkills"];
        $relateSkills = $this->get_relate_skills($profileSkills, $candidateSkills);
        
        //Qualifications
        $candidateQualificatios = $relationship["CandidateQualifications"];
        $realteCandQualifications = $this->format_qualifications($candidateQualificatios);

        //Notes
        $notes = (new CC_Job_ApplicationsController)->getNotesByJobApplicationsId($jobApplications_id);

        //Interview Results
        $interviewResults = (new CC_InterviewsController)->getInterviewResultsByApplicationsId($jobApplications_id);
        
        //Application History
        $applicationHistory = (new CC_Job_ApplicationsController)->get_history($jobApplications_id);

        //Personality Test
        $personalityTests = $relationship["PersonalityTest"];
        $personalityTestsWithPatternData = $this->get_patterns_data($personalityTests);


        $file_name = str_replace(" ", "_", $JARelation->table_name) . ".pdf";
        $template = BeanFactory::newBean('AOS_PDF_Templates');
        // We should use a pop-up for select this
        $template->retrieve($templateId);

        $object_arr['CC_Job_OfferCC_CandidateRelationship'] = $JARelation->id;
        $object_arr['CC_Candidate'] = $JARelation->cc_candidate_cc_job_offercc_candidate_ida;
        $object_arr['CC_Job_Offer'] = $JARelation->cc_candidate_cc_job_offercc_job_offer_idb;

        $search = array('/<script[^>]*?>.*?<\/script>/si',          // Strip out javascript
            '/<[\/\!]*?[^<>]*?>/si',                                // Strip out HTML tags
            '/([\r\n])[\s]+/',                                      // Strip out white space
            '/&(quot|#34);/i',                                      // Replace HTML entities
            '/&(amp|#38);/i','/&(lt|#60);/i','/&(gt|#62);/i',
            '/&(nbsp|#160);/i','/&(iexcl|#161);/i','/<address[^>]*?>/si',
            '/&(apos|#0*39);/','/&#(\d+);/'
        );
        $JARelation->load_relationships();

        $replace = array('','','\1','"','&','<','>',' ',chr(161),'<br>',"'",'chr(%1)');

        // Prepare header / footer and text
        $header = preg_replace($search, $replace, $template->pdfheader);
        $footer = preg_replace($search, $replace, $template->pdffooter);
        $text = preg_replace($search, $replace, $template->description);
        $text = str_replace("<p><pagebreak /></p>", "<pagebreak />", $text);
        $text = preg_replace_callback('/\{DATE\s+(.*?)\}/', function ($matches) {  return date($matches[1]); }, $text );

        $converted = templateParser::parse_template($text, $object_arr);
        $header = templateParser::parse_template($header, $object_arr);
        $footer = templateParser::parse_template($footer, $object_arr);

        //Name of variable use in the PDF Template
        $relationshipName = "$$JARelation->table_name";

        //Replacing the variables in the PDF template
        if (!$JARelation->skill_rating) $JARelation->skill_rating = 0;
        $skillRating = round($JARelation->skill_rating, 2);
        $converted = str_replace( $relationshipName.'_skill_rating', "$skillRating%",$converted);
        if (!$JARelation->qualification_rating) $JARelation->qualification_rating = 0;
        $qualificationRating = round($JARelation->qualification_rating, 2);
        $converted = str_replace( $relationshipName.'_qualification_rating', "$qualificationRating%",$converted);
        if (!$JARelation->generalrating) {
            $generalRating = $qualificationRating == 0 ? 0 : round(($JARelation->skill_rating/$JARelation->qualification_rating), 2);
        }
        $converted = str_replace( $relationshipName.'_general_rating', "$generalRating%",$converted);
        $converted = str_replace( $relationshipName.'_pattern', $personalityTest[0]["Pattern"],$converted);
        $skillsTable = $this->get_table_data($relateSkills,["Name","AmountExperience","AmountRating"], ["Name", "Years of Experience","Rating"]);
        $converted = str_replace( $relationshipName.'_skills_table', $skillsTable ,$converted);
        $qualificationsTable = $this->get_table_data($realteCandQualifications,["Name","Minimum_Required"], ["Name", "Type"]);
        $converted = str_replace( $relationshipName.'_qualifications_table', $qualificationsTable ,$converted);
        $notesTable = $this->get_table_data($notes,["date_entered","assigned_user_id", "description"], ["Date","User", "Note"]);
        $converted = str_replace( $relationshipName.'_notes_table', $notesTable ,$converted);
        $interviewTable = $interviewTable == "" ? "There aren't interviews yet" : $this->format_interview($interviewResults);
        $converted =  str_replace( $relationshipName."_interview_table", $interviewTable ,$converted);
        $applicationHistoryTable = $this->get_table_data($applicationHistory,["date_modified","name","description"], ["Date", "Stage", "Note" ]);
        $converted = str_replace( $relationshipName.'_application_history_table', $applicationHistoryTable ,$converted);
        $personality = $this->format_personality($personalityTestsWithPatternData);
        $converted = str_replace( $relationshipName."_personality", $personality ,$converted);

        $printable = str_replace("\n", "<br />", $converted);
        $orientation = ($template->orientation == "Landscape") ? "-L" : "";

        $pdf = new mPDF("en", $template->page_size . $orientation, 10, "Lato", $template->margin_left, $template->margin_right, $template->margin_top, $template->margin_bottom, $template->margin_header, $template->margin_footer);
        $pdf->use_kwt = true;
        $pdf->attr['KEEP-WITH-TABLE'] = true;
        $pdf->SetAutoFont();
        $pdf->SetHTMLHeader($header);
        $pdf->SetHTMLFooter($footer);
        $pdf->WriteHTML($printable);

        global $sugar_config;

        if($_REQUEST["uid"]){
            ob_clean();
            $pdf->Output($sugar_config["upload_dir"] .  $file_name, "D");
        } else {
            $created = $pdf->Output($sugar_config["upload_dir"] . "email_".$jobApplications_id.".pdf", "F");
            chmod($sugar_config["upload_dir"] . "email_".$jobApplications_id.".pdf", 0777);
        }

    }

    private function format_personality($personality)
    {
        $text = "";
        foreach ($personality as $item){
            $patternName = ucwords($item['Pattern']);
            $text .= "<p style='font-size: x-large;'><strong>Personality: $patternName</strong></p>";
            $keys = array_keys($item["pattern_data"]);
            foreach ($keys as $key){
                if($key != 'translation') {
                    $subTitle = $subTitle = ucwords(str_replace("_", " ", $key));
                    $text .= "<p style='font-size: large;'><strong>$subTitle</strong></p>";
                    $textRows = $item['pattern_data'][$key];
                    foreach ($textRows as $rowtext) {
                        $text .= "<p>{$rowtext}</p>";
                    }
                }
            }
        }
        return $text;
    }

    private function get_patterns_data($personalityTests)
    {
        foreach ($personalityTests as $key=>$value){
            $fileName = strtolower($value['Pattern']);
            $fileName .= ".json";
            $jsonData = file_get_contents("modules/CC_Personality_Test/pattern_data/$fileName");
            $jsonData = json_decode($jsonData, true);
            $personalityTests[$key]["pattern_data"] = $jsonData;
        }
        return $personalityTests;
    }

    private function format_interview($interviews)
    {
        $section = "";
        foreach ($interviews as $interview){
            $section .= $this->get_section(["Type","Date","Approved","Result"],$interview);

        }
        return $section;
    }
    
    private function get_section($headerTitles, $interview)
    {
        $rows = [
            str_replace("_"," ",$interview["type"]),
            (new DateTime($interview["interview_date"]))->format('Y-m-d'),
            $interview["approved"],
            round($interview["result"])."%"
        ];
        foreach ($headerTitles as $title){
            $headers[] = "<th style='width: 25%;'>{$title}</th>";
        }
        foreach ($rows as $key=>$row) {
            $content[] = "<td style='width: 25%; text-align: center;'>{$row}</td>";
        }
        $result = "";
        $items = [$headers, $content];
        foreach ($items as $key=>$item){
            $result .= "<table style='width: 100%;'><tr>" . implode("", $item) . "</tr></table>";
            if(count($items)>($key+1)){
                $result .= "<hr>";
            }
        }
        $general_titles = [
            "recommended" => "Recommended position in the Company according to the Positions Manual",
            "english_level" => "English level? Lessons required?",
            "positive_aspects" => "Positive aspects?",
            "what_to_improve" => "What to improve?",
            "observation" => "Additional Comments",
            "approved" => "Do you recommend to introduce this candidate to the client?",
            "description" => "Recommended for the position?<br />Justify",
            "other_position" => "In case that is not recommended for the position, do you recommend the candidate for another position in the company?<br />Which one?"
        ];
        $fields =[
            "recommended",
            "english_level",
            "positive_aspects",
            "what_to_improve",
            "observation",
            "approved",
            "description",
            "other_position"
        ];
        $result .= $this->get_table_interview($interview, $fields, ["General", "Comments"], $general_titles);
        return $result;
    }

    private function get_table_interview($data, $fields, $headers, $general_titles)
    {
        $td = "<td style='width: 50%; '>";
        $tr = "<tr style='background-color: #fff;'>";

        foreach ($headers as $hItem){
            $cells[] = "<th align='left' style='width: 50%; background-color: #8cc540;'>{$hItem}</th>";
        }

        $rows[] = "$tr" . implode("", $cells) . "</tr>";
        foreach ($fields as $fieldName) {
            $cells = [];
            $cells[] = "$td{$general_titles[$fieldName]}</td>$td{$data[$fieldName]}</td>";
            $rows[] = "$tr" . implode("", $cells) . "</tr>";
        }
        return "<br /><table style='width: 100%; background-color: #435d21; border: solid 0.05px #435d21;page-break-inside: avoid;'>" . implode("", $rows) . "</table><br />";
    }

    private function merge_profiles_skills($profiles)
    {
        $allSkills = $this->get_all_records($profiles, "Skills");
        $skills = $this->remove_duplicate_skills($allSkills);
        return $skills;
    }

    private function merge_profiles_qualifications($profiles)
    {
        $allQualifications = $this->get_all_records($profiles, "Qualifications");
        $qualifications = $this->remove_duplicate_qualifications($allQualifications);
        return $qualifications;
    }

    private function get_all_records($profiles, $type)
    {
        $result = [];
        foreach($profiles as $profile) {
            foreach($profile["Profile"][0][$type] as $element) {
                $result[] = $element;
            }
        }
        return $result;
    }

    private function remove_duplicate_qualifications($allQualifications)
    {
        $provisiQuali = $allQualifications;
        $leakedQuali = [];
        foreach($allQualifications as $qualification) {
            if(!in_array($qualification,$leakedQuali)) {
                $leakedQuali[] = $qualification;
            }
        }
        return $leakedQuali;
    }

    private function remove_duplicate_skills($allRecords)
    {
        $provisionalSkills = $allRecords;
        $leakedSkills = [];
        foreach ($provisionalSkills as $ability) {
            $duplicates = 0;
            $mskill = null;
            foreach ($provisionalSkills as $provitionalAbility) {
                if (
                    $ability["Skill"]["Name"] == $provitionalAbility["Skill"]["Name"] &&
                    $ability["Type"] == $provitionalAbility["Type"] && 
                    $provitionalAbility != $ability
                ) {
                    $duplicates += 1;
                    if($ability['Amount'] > $provitionalAbility["Amount"]) {
                        $mskill = $ability;
                    } else {
                        $mskill = $provitionalAbility;
                    }
                }
            }            
            if($duplicates == 0) {
                $leakedSkills[] = $ability;
            } else if(!in_array($mskill,$leakedSkills)) {
                $leakedSkills[] = $mskill;
            }
        }
        return $leakedSkills;
    }

    private function get_relate_skills($profileSkills, $candidateSkills) 
    {
        $result = [];
        $indexSaved = [];
        $allIndex = array_keys($profileSkills);
        $indexUnsaved = [];
        //Add to result the Skills that the Candidate have
        foreach ($profileSkills as $iPSkill => $profileSkill) {
            foreach ($candidateSkills as $candidateSkill) {
                if(
                    $profileSkill["Type"]===$candidateSkill["Type"] && 
                    $profileSkill["Skill"]["Name"]===$candidateSkill["Skill"]["Name"]
                ) {
                    $this->put_skill_information($result, $profileSkill["Skill"]["Name"], $profileSkill["Type"], $candidateSkill["Amount"]);
                    array_push($indexSaved, $iPSkill);
                }
            }
        }
        //Add to result the Skills that the Candidate does not have
        $indexsUnsaved = array_diff($allIndex, $indexSaved);
        foreach ($indexsUnsaved as $unsaved) {
            $this->put_skill_information($result, $profileSkills[$unsaved]["Skill"]["Name"], $profileSkills[$unsaved]["Type"], 0);//Amount is 0
        }
        return $result;
    }

    private function put_skill_information(&$result,$profileSkillName,$profileSkillType,$candidateSkillAmount)
    {
        $index = null;
        foreach ($result as $key => $value) {
            if (array_search($profileSkillName,$value,true)) $index = $key;
        }
        $type = $profileSkillType==="rating" ? "Rating" : "Experience";
        $amount = $profileSkillType==="rating" ? "AmountRating" : "AmountExperience";
        if (is_null($index)) {
            array_push($result, [
                $type => $profileSkillType,
                "Name" => $profileSkillName,
                $amount => $candidateSkillAmount
            ]);
        } else {            
            $result[$index][$type] = $profileSkillType;
            $result[$index][$amount] = $candidateSkillAmount;
        }
    }

    private function get_table_data($data, $fields, $headers)
    {
        $td = "<td style=''>";
        $tr = "<tr style='background-color: #fff;'>";
        $filtered_data = $data;
        foreach ($headers as $hItem){
            $cells[] = "<th align='left' style='background-color: #8cc540;'>{$hItem}</th>";
        }
        $rows[] = "$tr" . implode("", $cells) . "</tr>";
        foreach ($filtered_data as $row) {
            $cells = array();
            foreach ($fields as $fieldName) {
                if (array_key_exists($fieldName, $row)) {
                    if($fieldName=="AmountRating"){
                        $value = str_repeat($this->STAR, $row[$fieldName]);
                        $cells[] = "$td{$value}</td>";
                    } else {
                        $cells[] = "$td{$row[$fieldName]}</td>";
                    }

                } else{
                    $cells[] = "$td</td>";
                }
            }
            $rows[] = "$tr" . implode("", $cells) . "</tr>";
        }
        return "<table style='width: 100%; background-color: #435d21; border: solid 0.05px #435d21;page-break-inside: avoid;'>" . implode("", $rows) . "</table>";
    }

    private function format_qualifications($qualifications)
    {
        $result = [];
        foreach($qualifications as $qualification) {
            $result[] = $qualification["Qualification"];
        }
        return $result;
    }

}

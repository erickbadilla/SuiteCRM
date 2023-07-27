<?php

require_once('modules/CC_Job_Offer/controller.php');
require_once('modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php');
require_once('modules/CC_Profile/controller.php');
require_once('modules/CC_Skill/controller.php');
require_once('modules/CC_Qualification/controller.php');

class RatingCalculationUtility {

    public function calculateGeneralRating(\SugarBean $beanRelationCandidateJobOffer) {

        $generalRating = ($beanRelationCandidateJobOffer->skill_rating + $beanRelationCandidateJobOffer->qualification_rating) / 2;
        $beanRelationCandidateJobOffer->general_rating = $generalRating;
        $beanRelationCandidateJobOffer->save();
    }

    private function validateFloat($value){
        $options = array( 'options' => array(  'default' => 0 ) );
        $calculatedValue = round(filter_var($value, FILTER_VALIDATE_FLOAT, $options),2);
        if($calculatedValue>0){
            $calculatedValue = $calculatedValue * 100;
        }
        return $calculatedValue;
    }

    public function calculateJobOfferCandidateRating(string $idCandidate, string $idJobOffer) {

        $jobProfiles = (new CC_ProfileController)->getRecordsByJobOfferId($idJobOffer);

        //Bringing in the candidate's skills.
        $allCandidateSkills = (new CC_SkillController)->getRecordsByCandidateId($idCandidate);
        //repeating skills with qualification and years_of_experience then delete the type of years_of_experience.
        $candidateSkills = $this->filerCandidateSkills($allCandidateSkills);

        $jobSkillsProfiles = $this->getProfilesSkills($jobProfiles);
        $ratingSkill = $this->getSkillRating($jobSkillsProfiles, $candidateSkills);

        $candidateQualifications = (new CC_QualificationController)->getRecordsByCandidateId($idCandidate);

        $jobQualificationsProfiles = $this->getProfilesQualifications($jobProfiles);
        $qualificationRating = $this->getQualificationRating($candidateQualifications, $jobQualificationsProfiles);

        $generalRating = ($ratingSkill + $qualificationRating) / 2;

        return array(
            'skills' => $this->validateFloat($ratingSkill),
            'qualifications' => $this->validateFloat($qualificationRating),
            'general' => $this->validateFloat($generalRating)
        );

    }


    public function calculateJobAplicationRating(string $idCandidate, string $modulo) {

        $jobApplications = (new CC_Job_OfferController)->getRecordsByCandidateId($idCandidate);
        //Validating the jobApplications
        $jobProfiles = [];
        foreach ($jobApplications as $jobApplication) {

            $jobProfiles = [];
            $jobProfiles = (new CC_ProfileController)->getRecordsByJobOfferId($jobApplication['job_offer_id']);
            $relationshipBeanJOC = BeanFactory::getBean('CC_Job_Applications', $jobApplication["id"]);

            foreach ($relationshipBeanJOC->column_fields as $field) {
                safe_map($field, $relationshipBeanJOC, true);
            }

            if ($modulo == 'skill') {
                //Bringing in the candidate's skills.
                $allCandidateSkills = (new CC_SkillController)->getRecordsByCandidateId($jobApplication['candidate_id']);
                //repeating skills with qualification and years_of_experience then delete the type of years_of_experience.
                $candidateSkills = $this->filerCandidateSkills($allCandidateSkills);

                $jobSkillsProfiles = $this->getProfilesSkills($jobProfiles);
                $ratingSkill = $this->getSkillRating($jobSkillsProfiles, $candidateSkills);
                $relationshipBeanJOC->skill_rating = $this->validateFloat($ratingSkill);

            } else if ($modulo == 'qualification') {
                $candidateQualifications = (new CC_QualificationController)->getRecordsByCandidateId($jobApplication['candidate_id']);

                $jobQualificationsProfiles = $this->getProfilesQualifications($jobProfiles);
                $qualificationRating = $this->getQualificationRating($candidateQualifications, $jobQualificationsProfiles);
                $relationshipBeanJOC->qualification_rating = $this->validateFloat($qualificationRating);
            }

            $relationshipBeanJOC->save();
            $this->calculateGeneralRating($relationshipBeanJOC);
        }
    }

    public function filerCandidateSkills(array $candidateSkills) {

        $provitionalSkill = [];
        $leakedCandidateSkills = [];
        $account = 0;

        foreach ($candidateSkills as $candidateSkill) {

            $account += 1;
            $sameIds = $provitionalSkill["Skill"]["Id"] == $candidateSkill["Skill"]["Id"];
            if (count($provitionalSkill) != 0) {
                if ($sameIds) {
                    $leakedCandidateSkills[] = ($candidateSkill["Type"] == 'rating')? $candidateSkill : $provitionalSkill;
                }elseif (!in_array($provitionalSkill, $leakedCandidateSkills)) {
                    $leakedCandidateSkills[] = $provitionalSkill;
                }
            }

            $provitionalSkill = $candidateSkill;
            if ($account == count($candidateSkills) && !in_array($candidateSkill, $leakedCandidateSkills)) {
                $leakedCandidateSkills[] = $candidateSkill;
            }
        }

        return $leakedCandidateSkills;
    }

    public function getProfilesSkills(array $jobProfiles) {

        $allSkills = [];
        foreach ($jobProfiles as $jobProfile) {
            foreach ($jobProfile["Profile"][0]["Skills"] as $skill) {

                if ($skill['Type'] == 'rating'){
                    $allSkills[] = $skill;
                }
            }

        }

        $provisionalSkills = $allSkills;
        $leakedSkills = [];
        foreach ($provisionalSkills as $ability) {
            $duplicates = 0;
            $mskill = null;
            foreach ($provisionalSkills as $provitionalAbility) {

                if ($ability["Skill"]["Name"] == $provitionalAbility["Skill"]["Name"] && $provitionalAbility != $ability) {
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

    public function getSkillRating(array $profileSkills, array $candidateSkills) {

        $score = 0;
        foreach ($profileSkills as $profileSkill) {
            foreach ($candidateSkills as $candidateSkill) {

                $idsCompare =  $profileSkill["Skill"]["Id"] ==  $candidateSkill["Skill"]["Id"] ? true : false;
                if ($idsCompare) {
                    $score += $this->getEvaluateSkill($profileSkill, $candidateSkill);
                }
            }
        }

        $result = 0;

        if (count($profileSkills)>0){
            $result = $score / (10 * count($profileSkills));
        }

        return $result;
    }

    public static function getEvaluateSkill($valSkillToRate, $valActualSkill){

        $vResult = 0;

        if($valActualSkill != null) {
            $vSkillAmount = $valSkillToRate["Amount"];
            $vAmount = $valActualSkill["Amount"];
            $vSameType = $valSkillToRate["Skill"]["Type"] == $valActualSkill["Skill"]["Type"];
            $vValues = array( ($vSameType ? 10 : 7), ($vSameType ? 7 : 5), ($vSameType ? 5 : 3) );

            $vResult = ($vAmount >= $vSkillAmount) ? $vValues[0] : ( ($vSkillAmount - $vAmount <= 1) ? $vValues[1] : $vValues[2] );
        }

        return $vResult;
    }

    public function getProfilesQualifications(array $jobProfiles) {

        $leakedQualifications = [];
        foreach ($jobProfiles as $jobProfile) {
            foreach ($jobProfile["Profile"][0]["Qualifications"] as $qualification) {

                if (!array_search($qualification, $leakedQualifications)){
                    $leakedQualifications[] = $qualification;
                }
            }

        }

        return $leakedQualifications;
    }

    public function getQualificationRating (array $candidateQualifications, array $profileQualifications) {

        $score = 0;
        foreach ($profileQualifications as $profileQualification) {
            foreach ( $candidateQualifications as $candidateQualification) {

                if ($profileQualification["Id"] == $candidateQualification["Qualification"]["Id"]) {
                    $isQualificationRequired = $profileQualification["DigitalSupportRequired"];
                    $hasQualificationRequired = $candidateQualification["HasDigitalSupport"];
                    $minimunRequired = strtolower($profileQualification["MinimumRequired"]);
                    $actualRequired = strtolower($candidateQualification["Qualification"]["Minimum_Required"]);

                    $score += $isQualificationRequired && $hasQualificationRequired ? 2 : 0;
                    $score += !$isQualificationRequired && $hasQualificationRequired ? 2 : 0;
                    $score += !$isQualificationRequired && !$hasQualificationRequired ? 2 : 0;

                    if ($actualRequired != '' && $minimunRequired != '') {
                        $rateActualRequired = $this->getRateMap($actualRequired);
                        $rateMinimunRequired = $this->getRateMap($minimunRequired);

                        if ($rateActualRequired >= $rateMinimunRequired) {
                            $score += 8;
                        }else if ($rateMinimunRequired - $rateActualRequired == 1) {
                            $score += 6;
                        }else {
                            $score += 4;
                        }
                    }
                }
            }
        }

        $result = $score / (10 * count($profileQualifications));
        return $result;
    }

    public function getRateMap(string $option) {

        $optiones = [
            "none" => 1,
            "other related" => 2,
            "course" => 2,
            "diploma" => 3,
            "experience" => 4,
            "certification" => 5,
            "degree" => 6
        ];

        if (array_key_exists($option, $optiones)) {
            return $optiones[$option];
        }else {
            return 1;
        }
    }

}
<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ProfileRatingArea {
    private $skillRating;
    private $qualificationRating;
    private $generalRating;
    private static $baseStyleName = "ApplicationRating";

    public function __construct($skillRating,$qualificationRating,$generalRating)
    {
        $this->generalRating = $generalRating;
        $this->qualificationRating = $qualificationRating;
        $this->skillRating = $skillRating;
    }

    private function calculateValueStyle($value){
        $styles = [ "Expired", "Warning", "Active", "Base" ];
        $result = floor($value / (100/count($styles) ) );
        return self::$baseStyleName.$styles[$result];
    }

    public function createRatingItem($text="",$value=0){
        $tpl = new Sugar_Smarty();
        $tpl->assign("related_text", $text);
        $tpl->assign("rating_value", $value);
        $tpl->assign("rating_class", self::calculateValueStyle($value));
        return $tpl->fetch('modules/CC_Profile/ProfileRatingItem.tpl');
    }

    public function RatingArea(){
        return $this->createRatingArea($this->skillRating, $this->qualificationRating, $this->generalRating);
    }

    public function createRatingArea($skillRating, $qualificationRating, $generalRating){
        $layoutArea = new Sugar_Smarty();
        $ratings = [
            (object) ['class'=>'Skill', 'tpl' =>  $this->createRatingItem("Skill Rating",$skillRating)],
            (object) ['class'=>'Qualification', 'tpl' =>  $this->createRatingItem("Qualification Rating",$qualificationRating)],
            (object) ['class'=>'General', 'tpl' =>  $this->createRatingItem("General Rating",$generalRating)]
        ];
        $layoutArea->assign("ratings", $ratings);
        return $layoutArea->fetch('modules/CC_Profile/ProfileRatingArea.tpl');
    }

}

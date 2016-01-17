<?php
/* Class [VO_TblEvaluationCriteria] for table [evaluation_criteria] */

class VO_TblEvaluationCriteria extends VO_TblAbstract { 

    const TABLE_NAME = 'evaluation_criteria';

    public $id;
    public $isDeleted;
    public $evaluationId;
    public $evalTemplateId;
    public $label;
    public $description;
    public $evaluationTypeDVCode;
    public $isOptional;
    public $weight;
    
}  // VO_TblEvaluationCriteria
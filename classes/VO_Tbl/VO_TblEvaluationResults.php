<?php
/* Class [VO_TblEvaluationResults] for table [evaluation_results] */

class VO_TblEvaluationResults extends VO_TblAbstract { 

    const TABLE_NAME = 'evaluation_results';

    public $id;
    public $isDeleted;
    public $evaluationId;
    public $evaluationCriteriaId;
    public $theValue;
    public $theCount;
    
}  // VO_TblEvaluationResults
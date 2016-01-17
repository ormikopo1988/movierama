<?php
	/* Class [VO_TblFlagItems] for table [flag_items] */
	
	class VO_TblFlagItems extends VO_TblAbstract { 
	
	    const TABLE_NAME = 'flag_items';
	
	    public $id;
	    public $isDeleted;
	    public $whatType;
	    public $whatId;
	    public $flaggedByUserId;
	    public $flaggedDateTime;
	    public $flagText;
	    public $flagStatus;
	    public $assignedToUserId;
	    public $assignedDateTime;
	    public $closedDateTime;
	    public $systemComments;
	
	}  // VO_TblFlagItems
<?php
/* Class [VO_TblPersonProfile] for table [person_profiles] */

class VO_TblPersonProfile extends VO_TblAbstract { 

    const TABLE_NAME = 'person_profiles';

    public $id;
    public $isDeleted;
    public $createdDateTime;
    public $firstName;
    public $lastName;
    public $email;
    public $updatedDateTime;

}  // VO_TblPersonProfile
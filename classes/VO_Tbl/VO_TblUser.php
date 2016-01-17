<?php
/* Class [VO_TblUser] for table [movierama_users] */

class VO_TblUser extends VO_TblAbstract { 

    const TABLE_NAME = 'movierama_users';

    public $id;
    public $isDeleted;
    public $username;
    public $avatarImg;
    public $isActive;
    public $isVerified;
    public $verificationToken;
    public $createdDateTime;
    public $updatedDateTime;
    public $userId;
    public $personProfileId;

}  // VO_TblUser
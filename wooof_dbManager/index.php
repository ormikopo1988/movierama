<?php

header("Location: dbManager.php");
exit;


/*
$content = '
<br/>
<!--a href="chooseProject.php" class="normalTextWhite">Choose Project</a><br/-->
<a href="dbManager.php" class="normalTextWhite">Enter Database Manager</a><br/>
';


class Foo
{
    public function __call($method, $args)
    {
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }
}

$wo = new stdClass();
$wo->db = new Foo();
$wo->db->getDataBaseName = function()
{
	return "No database selected.";
};

require('template.php');
*/


?>
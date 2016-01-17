<?php


$__isSiteBuilderPage = true;
$theDir = dir('../');
$content = '
<br/>
';
while(false !== ($entry = $theDir->read()))
{
	if (substr($entry, 0,5)=='setup' && $entry!='setup.inc.php')
	{
		$content.='<a href="dbManager.php?project='. urlencode($entry) .'" class="normalTextWhite">'. $entry .'</a><br/>
';
	}
}

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
?>
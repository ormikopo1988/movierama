<?php
$menuOutput = '<ul id="navmenu-h">
        <li><a href="#">Home</a></li>
        <li><a href="#">Work +</a>
          <ul>
            <li><a href="#">Websites +</a>
              <ul>
                <li><a href="#">qrayg.com</a></li>
                <li><a href="#">craigerskine.com</a></li>
                <li><a href="#">dt.qrayg.com</a></li>
                <li><a href="#">legendofmana.info</a></li>
              </ul>
            </li>
            <li><a href="#">Sketchbook</a></li>
            <li><a href="#">Free Interfaces</a></li>
          </ul>
        </li>
        <li><a href="#">Learn +</a>
          <ul>
            <li><a href="#">Fireworks +</a>
              <ul>
                <li><a href="#">Aqua Button</a></li>
                <li><a href="#">Aqua Button 2</a></li>
                <li><a href="#">Water Drop</a></li>
                <li><a href="#">Light FX</a></li>
                <li><a href="#">Light FX2</a></li>
              </ul>
            </li>
            <li><a href="#">CSS +</a>
              <ul>
                <li><a href="#">CSS Menus</a></li>
                <li><a href="#">Sprite Nav</a></li>
                <li><a href="#">@import</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li><a href="#">Contact</a></li>
';
/*
$menuItemsR = $wo->query('SELECT * FROM __administrationMenu WHERE parentId IS NULL');
while($mI = $wo->db->fetchAssoc($menuItemsR))
{
    $menuOutput .= fetchSubmenus($mI);
}
*/

$menuOutput .= '      </ul>
';

if (isset($activateFirstMenu) && $activateFirstMenu == true)
{
    $menuAClass = 'selected';
}else
{
    $menuAClass = 'menuLink';
}

$menuOutput ='        <div class="menuItem"><a href="administration.php?tm='. WOOOF::getCurrentDateTime() .'" class="'. $menuAClass .'">Home</a></div>
';
$mR = $wo->db->query('select * from __tableMetaData where appearsInAdminMenu=\'1\' and tableName not in (\'__tableMetaData\', \'__columnMetaData\') ');

while($m=$wo->db->fetchAssoc($mR))
{
  if (isset($addressItems[1]) && $addressItems[1]==$m['id'])
  {
      $menuAClass = 'selected';
  }else
  {
      $menuAClass = 'menuLink';
  }
  $menuOutput.='<div class="menuItem"><a href="administration.php?__address=1_'. $m['id'] .'&action=read" class="'. $menuAClass .'">'. $m['description'] .'</a></div>';
}

if (basename($_SERVER['SCRIPT_FILENAME'])=='optionManagement.php')
{
  $optClass='selected';
}else
{
  $optClass='menuLink';
}

$menuOutput .='        <div class="menuItem"><a href="optionManagement.php?tm='. WOOOF::getCurrentDateTime() .'" class="'. $optClass .'">Options</a></div>
        <div class="menuItem"><a href="backUpDataBase.php?tm='. WOOOF::getCurrentDateTime() .'" class="menuLink">DB Backup</a></div>
        <div class="menuItem"><a href="logOut.php?tm='. WOOOF::getCurrentDateTime() .'" class="menuLink">Log Out</a></div>
';
?>
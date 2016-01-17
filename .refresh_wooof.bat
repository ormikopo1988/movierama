pause Make sure you are at the right directory (e.g. C:\xampp\htdocs\some_app) and that wooof files exist in ../wooof 

rmdir /S /Q wooof_administration
rmdir /S /Q wooof_fragments
rmdir /S /Q wooof_dbManager
rmdir /S /Q wooof_classes
rmdir /S /Q wooof_docs

robocopy ../wooof/wooof_classes wooof_classes /e /NFL /NDL /NJH /NJS /nc /ns /np
robocopy ../wooof/wooof_administration wooof_administration /e /NFL /NDL /NJH /NJS /nc /ns /np
robocopy ../wooof/wooof_fragments wooof_fragments /e /NFL /NDL /NJH /NJS /nc /ns /np
robocopy ../wooof/wooof_dbManager wooof_dbManager /e /NFL /NDL /NJH /NJS /nc /ns /np
robocopy ../wooof/wooof_docs wooof_docs /e /NFL /NDL /NJH /NJS /nc /ns /np

copy ..\wooof\setup.common.inc.php /Y

echo Manually copy latest wooof.php wherever you prefer.
echo For a freshly created database, run wooof_dbManager\initDB.php

pause Bye...
 

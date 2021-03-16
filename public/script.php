<?php

CONST PLAYERNAME = 'QQplayer.exe';

CONST POTPLAYER = 'PotPlayerMini64.exe';

// exec("taskkill /F /im ". PLAYERNAME);
// $command = PLAYERNAME . ' ' .$_POST['path'];
// exec($command);




exec("taskkill /F /im ". POTPLAYER);
$command = 'start'. ' ' .POTPLAYER . ' ' .$_POST['path'];
exec($command);
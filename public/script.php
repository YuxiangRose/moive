<?php

CONST PLAYERNAME = 'QQplayer.exe';

exec("taskkill /F /im ". PLAYERNAME);
$command = PLAYERNAME . ' ' .$_POST['path'];
exec($command);

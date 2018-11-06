<?php

exec("taskkill /F /im QQplayer.exe");
$command = 'QQplayer '.$_POST['path'];
exec($command);

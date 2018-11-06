<?php
header('Content-Type:text/plain;charset=GB2312');
//echo('我的锅');
echo iconv("UTF-8","GB2312",'中文');
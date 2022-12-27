<?php 
// /opt/php74/bin/php -f /var/www_back/backup.php eliz eliz.beregmira.ru /var/www_back/


if (!file_exists($argv[3] . $argv[2]) . "/") 
{
	mkdir($argv[3] . $argv[2] . "/", 0700);
}

define(SITE_USER, $argv[1]);
define(SITE_BACKUP, $argv[2]);
define(SITE_DUMP_PATH, $argv[3] . $argv[2] . "/");

include '/var/www/'. SITE_USER .'/data/www/'. SITE_BACKUP . '/bitrix/php_interface/dbconn.php';

var_dump($DBLogin, SITE_USER, SITE_BACKUP);



$filename=SITE_BACKUP .'_'.date('y_m_d-H_i_s').'.sql';

$arDBHost = explode(':', $DBHost);
$Host = $arDBHost[0];
$Port = $arDBHost[1];

$result=exec('mysqldump '. $DBName .' --password='. $DBPassword .' --user=' . $DBLogin . ' --host=' . $Host . ' --port=' . $Port . ' --single-transaction >' . SITE_DUMP_PATH . $filename,$output);

if(empty($output)){
	echo "БЭКАП ЗАВЕРШЁН \n";
	
}
else {/* we have something to log the output here*/
	echo "ОШИБКА! \n";
}
$site_path='/var/www/'. SITE_USER .'/data/www/'. SITE_BACKUP . "/";
$tarball= SITE_DUMP_PATH . SITE_BACKUP .'_'.date('y_m_d-H_i_s').'.tar.gz';
var_dump($site_path,$tarball);
$result=exec('tar -czf ' . $tarball . ' ' . $site_path ,$output);
	echo "$ filename: ".$filename." \n";
	echo "$ tarball: ".$tarball." \n";
	echo "$ site_path: ".$site_path." \n";
	echo "$ Host: ".$Host." \n";
	echo "$ Port: ".$Port." \n";
	echo "SITE_USER: ".SITE_USER." \n";
	echo "SITE_BACKUP: ".SITE_BACKUP." \n";
	echo "SITE_DUMP_PATH: ".SITE_DUMP_PATH." \n";
?>




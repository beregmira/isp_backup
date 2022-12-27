<?php 
// /opt/php74/bin/php -f backup.php eliz eliz.beregmira.ru /var/www/eliz/data/www/eliz.beregmira.ru/
define(SITE_USER, $argv[1]);
define(SITE_BACKUP, $argv[2]);
define(SITE_DUMP_PATH, $argv[3]);

include '/var/www/'. SITE_USER .'/data/www/'. SITE_BACKUP . '/bitrix/php_interface/dbconn.php';

var_dump($DBLogin, SITE_USER, SITE_BACKUP);

$filename=SITE_BACKUP .'_'.date('G_a_m_d_y').'.sql';

$result=exec('mysqldump '. $DBName .' --password='. $DBPassword .' --user=' . $DBLogin . ' --host=127.0.0.1 --port=3310' . ' --single-transaction >' . SITE_DUMP_PATH . $filename,$output);

if(empty($output)){
	echo "БЭКАП ЗАВЕРШЁН \n";
}
else {/* we have something to log the output here*/
	echo "ОШИБКА! \n";
}
$site_path='/var/www/'. SITE_USER .'/data/www/'. SITE_BACKUP;
$tarball=SITE_BACKUP .'_'.date('G_a_m_d_y').'.tar.gz';
var_dump($site_path,$tarball);
$result=exec('tar -czf ' . $tarball . ' ' . $site_path ,$output);
?>

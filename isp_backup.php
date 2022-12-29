<?php 
/*
  Команда запуска:
  /opt/php74/bin/php -f /var/www_back/backup.php
*/

$arSites = array(
	"mysite1" => array("NAME" => "mysite1","USER" => "mysite1","SITE" => "mysite1","BACKUP_DIR" => "/var/www_back/",),
	"mysite2" => array("NAME" => "mysite2","USER" => "mysite2","SITE" => "mysite2","BACKUP_DIR" => "/var/www_back/",),
	"mysite3" => array("NAME" => "mysite3","USER" => "mysite3","SITE" => "mysite3","BACKUP_DIR" => "/var/www_back/OTHERDIR/",),
);

foreach ($arSites as $arSite) {
	echo "\nИмя сайта: " . $arSite["NAME"];
	echo "\nПользователь: " . $arSite["USER"];
	echo "\nСайт: " . $arSite["SITE"];
	echo "\nДиректория для сохранения бекапа: " . $arSite["BACKUP_DIR"];
	echo "\nСтатус: процесс создания резервной копии начат";
	echo "\n";

	$backup_dir = $arSite["BACKUP_DIR"] . $arSite["SITE"] . "/" ;



		if (!file_exists($backup_dir)) 
		{
			mkdir($backup_dir, 0700);
			echo "\n Целевая директория ( " . $backup_dir . " ) не была обнаружена и создана";
		}


		$arSet = include '/var/www/'. $arSite["USER"] .'/data/www/'. $arSite["SITE"] . '/bitrix/.settings.php';

		echo "\nШаг №1: создание дампа БД...";
		
		$filename=$arSite["SITE"] .'_'.date('y_m_d-H_i_s').'.sql';

		$arDBHost = explode(':', $arSet["connections"]["value"]["default"]["host"]);
		$Host = $arDBHost[0];
		$Port = $arDBHost[1];
		$DBName = $arSet["connections"]["value"]["default"]["database"];
		$DBPassword = $arSet["connections"]["value"]["default"]["password"];
		$DBLogin = $arSet["connections"]["value"]["default"]["login"];

		$result=exec('mysqldump '. $DBName .' --password='. $DBPassword .' --user=' . $DBLogin . ' --host=' . $Host . ' --port=' . $Port . ' --single-transaction >' . $backup_dir . $filename,$output);

		if(empty($output)){
			echo "\n\n-------------- \n";
			echo "Статус: Создание резервной копии БД завершено успешно \n";
			echo "-------------- \n\n\n";
		}
		else { // we have something to log the output here
			echo "\n\n-------------- \n";
			echo "Статус: ОШИБКА создания резервной копии БД! \n";
			echo '( mysqldump '. $DBName .' --password='. $DBPassword .' --user=' . $DBLogin . ' --host=' . $Host . ' --port=' . $Port . ' --single-transaction >' . $backup_dir . $filename . ') \n';
			echo "-------------- \n\n\n";
		}
		
		echo "\nШаг №2: создание архива файлов...\n";
		$site_path='/var/www/'. $arSite["USER"] .'/data/www/'. $arSite["SITE"] . "/";
		$tarball= $backup_dir . $arSite["SITE"] .'_'.date('y_m_d-H_i_s').'.tar.gz';
		//var_dump($site_path,$tarball);
		
		$result=exec('tar -czf ' . $tarball . ' ' . $site_path ,$output);

		if(empty($output)){
			echo "\n\n-------------- \n";
			echo "Статус: Создание резервной копии директории сайта завершено успешно \n";
			echo "-------------- \n\n\n";
		}
		else { // we have something to log the output here
			echo "\n\n-------------- \n";
			echo "Статус: ОШИБКА создания резервной копии директории сайта! \n";

			echo "$ filename: ".$filename." \n";
			echo "$ tarball: ".$tarball." \n";
			echo "$ site_path: ".$site_path." \n";
			echo "$ Host: ".$Host." \n";
			echo "$ Port: ".$Port." \n";
			echo "$ arSite[USER]: ".$arSite["USER"]." \n";
			echo "$ arSite[SITE]: ".$arSite["SITE"]." \n";
			echo "$ backup_dir: ".$backup_dir." \n";

			echo "-------------- \n\n\n";
		}

}

?>
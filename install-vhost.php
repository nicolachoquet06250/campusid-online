<?php

$enable_systems = [
	'win' => function($default_messages) {
		$vhosts_path = 'C:\wamp64\bin\apache\apache2.4.35\conf\extra\httpd-vhosts.conf';
		$hosts_path = 'C:\Windows\System32\drivers\etc\hosts';

		$vhosts = file_get_contents($vhosts_path);
		var_dump(strstr($vhosts, 'campusid.local'));
		if(!strstr($vhosts, 'campusid.local')) {
			$vhosts .= '
		<VirtualHost *:80>
				#nom de domaine
				ServerName api.campusid.local
				
				#logs d\'erreur
				ErrorLog '.__DIR__.'/logs/error.log 
				
				#logs de connexion
				CustomLog '.__DIR__.'/logs/access.log common
				
				#Définition de la racine des sources php
				DocumentRoot "'.__DIR__.'/"
				<directory '.__DIR__.'/>
						Options -Indexes +FollowSymLinks +MultiViews
						AllowOverride All
						Require all granted
				</Directory>
		</VirtualHost>';
			file_put_contents($vhosts_path, $vhosts);

			$hosts = file_get_contents($hosts_path);

			$hosts .= '127.0.0.1 campusid.local';

			file_put_contents($hosts_path, $hosts);

			return $default_messages['success'];
		}
		else return $default_messages['error'];
	},
	'lnx' => function($default_messages) {
		$vhosts_path = '/etc/apache2/sites-available/campusid-online.conf';
		$hosts_path = '/etc/hosts';

		if(!is_file($vhosts_path)) {
			$vhosts = '
		<VirtualHost *:80>
				#nom de domaine
				ServerName api.campusid.local
				
				#logs d\'erreur
				ErrorLog '.__DIR__.'/logs/error.log 
				
				#logs de connexion
				CustomLog '.__DIR__.'/logs/access.log common
				
				#Définition de la racine des sources php
				DocumentRoot "'.__DIR__.'/"
				<directory '.__DIR__.'/>
						Options -Indexes +FollowSymLinks +MultiViews
						AllowOverride All
						Require all granted
				</Directory>
		</VirtualHost>';
			file_put_contents($vhosts_path, $vhosts);
			exec('cd /etc/apache2/sites-enabled && ln -s ../sites-available/campusid-online.conf campusid-online.conf');
			exec('service apache2 restart && service apache2 reload');

			$hosts = file_get_contents($hosts_path);

			$hosts .= '127.0.0.1 campusid.local';

			file_put_contents($hosts_path, $hosts);
			return 'L\'installation du VHost s\'est effectuée avec succes.'.
				   "\n".
				   'Apache à été redémarré.';
		}
		else return $default_messages['error'];
	},
	'osx' => function($default_messages) {
		$vhosts_path = '/Applications/MAMP/conf/apache/extra/httpd-vhosts.conf';
		$hosts_path = '/etc/hosts';

		$vhosts = file_get_contents($vhosts_path);
		if(strstr($vhosts, 'campusid.local')) {
			$vhosts .= '
		<VirtualHost *:80>
				#nom de domaine
				ServerName api.campusid.local
				
				#logs d\'erreur
				ErrorLog '.__DIR__.'/logs/error.log 
				
				#logs de connexion
				CustomLog '.__DIR__.'/logs/access.log common
				
				#Définition de la racine des sources php
				DocumentRoot "'.__DIR__.'/"
				<directory '.__DIR__.'/>
						Options -Indexes +FollowSymLinks +MultiViews
						AllowOverride All
						Require all granted
				</Directory>
		</VirtualHost>';
			file_put_contents($vhosts_path, $vhosts);

			$hosts = file_get_contents($hosts_path);

			$hosts .= '127.0.0.1 campusid.local';

			file_put_contents($hosts_path, $hosts);
			return $default_messages['success'];
		}
		else return $default_messages['error'];
	},
];

$default_messages = [
	'success' => 'L\'installation du VHost s\'est effectuée avec succes.'."\n".'Veuillez redémarrer Apache maintenant.',
	'error' => 'Le site existe déja dans tes VHosts !',
];

if(!isset($argv[1])) $argv[1] = '';
$os = $argv[1];

try {
	if(in_array($os, array_keys($enable_systems))) {
		$valid = $enable_systems[$os]($default_messages);
		echo $valid."\n";
	}
	else {
		if($os === '') exit('Error: Vous devez entrer un Système d\'exploitation ( win, lnx, osx ).'."\n");
		exit('Error: Le system `'.$os.'` n\'est pas connue.'."\n");
	}
}
catch (Exception $e) {
	exit('Fatal Error: '.$e->getMessage()."\n");
}
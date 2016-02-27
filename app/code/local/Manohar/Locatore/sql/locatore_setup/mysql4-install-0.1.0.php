<?php
	/*$installer = $this;

	$installer->startSetup();

	$installer->'create table contact(
	id int  not null auto_increment,
	name varchar(255)  not null,
	phone int not null,
	email varchar(255) not null)';
	$installer->run();

	$installer->endSetup();*/

$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table locatore
(id int not null auto_increment, 
	store_name varchar(100),
	 city varchar(255),
	 store_address varchar(255),
	 link varchar(255),
	  primary key(id)
	  );

		
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
?>
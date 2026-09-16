<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'dwixrmqu_moinalam6644mt',
	'password' => 'qil4e82e7zy8embx',
	'database' => 'dwixrmqu_moinalam6644mt',
	'dbdriver' => 'mysqli',
	'dbprefix' => 'pref_',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => FALSE,
	'init_command' => "SET SESSION sql_mode=''"
);

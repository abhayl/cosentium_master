<?php
 
class EmailConfig {
	public $smtp = array(
		'transport' => 'Smtp',
		'from' => array('no-reply@cosentium.com' => 'Cosentium Support'),
		'host' => '172.27.172.202',
		'port' => 25,
		'timeout' => 120,
		'username' => 'abhayl@evolvingsols.com',
		'client' => null,
		'log' => true,
		'tls' => true
	);
}
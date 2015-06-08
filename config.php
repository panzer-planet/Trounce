<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */
 
 /* This is an example configuration file,
  * the settings must be in a one dimensional
  * array format. By convention all setting names
  * must contain two parts seperated by at least one 
  * underscore to insure uniqueness but can be anything. 
  * You may add custom settings to your config file to use
  * through the Settings class.
  */
  
$system_config = array(

    # System
    'system_rewrite' 			=> 	true,	#requires mod_rewrite
	'system_debug' 				=> 	true,
	
	'system_log_directory' 		=> 	ROOT . DS . 'logs' . DS,
	'system_logging'			=> true,	# Ensure php has permissions to write here
	'system_error_logging'		=> true,
	'system_controller_default' => 'Default',
	
	'system_querystring_holder' => 	'url',
	'system_action_default' => 'default',
	
	# Security

	
	# App
	'app_folder' =>			'default',
	
	# DB
	'db_host' => 			'localhost',
	'db_username' => 		'web',
	'db_password' => 		'pw',

	
	# Custom example
	'my_setting' => 'some value',
	/* This value is then readable using
	 * 	
	 *	Settings::my_setting()
	 *
	 * and writable using
	 *
	 *	Settings::my_settings('some other value');
	 *
	 * settings only support a single value per label
	 * but his can be an array
	 */
);

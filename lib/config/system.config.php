<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

/**
 * These are the default fallbacks for anything that is
 * not specified in app.config.php in the app folder
 */
$system_config = array(
    #Core
    'rewrite'               =>  true,	#requires mod_rewrite #deprecated (mod_rewrite)
    'debug'                 =>  true,
    'load_libraries'        =>  ['core','mvc'],
    'log_directory'         =>  ROOT . DS . 'logs' . DS,
    'enable_system_logging' =>  true,	# Ensure php has permissions to write here
    'enable_error_logging'  =>  true,
    
    # Query string
    'querystring_holder'    =>  'url',
    'enable_get'            => false,
    'filter_querystring'    => true,
    'action_default'        => 'default',
    'controller_default'    => 'Default',
  
    # Database
    'db_host'       => 'localhost',
    'db_username'   => 'default',
    'db_password'   => 'default',
    
    # 
    'cookie_default_expire' => '',
    'cookie_default_path'   => '/',
    'cookie_default_domain' => '',
    'cookie_secure'      => true, #no js access
    'cookie_https_only'         => false, # https only

);

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
    'app_name' => 'default',
    'rewrite' => true,	#requires mod_rewrite #deprecated (mod_rewrite)
    'debug' => true,
    'load_libraries' => array('core','mvc'),
    'log_directory' => ROOT . DS . 'logs' . DS,
    'enable_system_logging' => true,	# Ensure php has permissions to write here
    'enable_error_logging' => true,
    
    
    'querystring_holder' => 'url',
    'action_default' => 'default',
    'controller_default' => 'Default',
  #  'app_name' => 'default', defined in app config
    
    'db_host' => 'localhost',
    'db_username' => 'default',
    'db_password' => 'default',

);

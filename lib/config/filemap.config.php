 <?php
 /**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */
 
 /* A map of all classes to be loaded.
  * this is the fastest way to run in 
  * production mode https://mwop.net/blog/245-Autoloading-Benchmarks.html
  * This only applies to libraries
  */
  /*
  TODO: Create generator for the class map
  */


$lib_files = array(
ROOT . DS . 'lib' . DS . 'core' . DS . 'App.php',
ROOT . DS . 'lib' . DS . 'core' . DS . 'Controller.php',
ROOT . DS . 'lib' . DS . 'core' . DS . 'Loc.php',
ROOT . DS . 'lib' . DS . 'core' . DS . 'Layout.php',
ROOT . DS . 'lib' . DS . 'core' . DS . 'Logger.php',
ROOT . DS . 'lib' . DS . 'core' . DS . 'Registry.php',
ROOT . DS . 'lib' . DS . 'core' . DS . 'Router.php',
ROOT . DS . 'lib' . DS . 'core' . DS . 'Security.php',
ROOT . DS . 'lib' . DS . 'core' . DS . 'Session.php',

ROOT . DS . 'lib' . DS . 'db' . DS . 'T_DB.php',
ROOT . DS . 'lib' . DS . 'db' . DS . 'Model.php',

ROOT . DS . 'lib' . DS . 'http' . DS . 'Cookie.php',
ROOT . DS . 'lib' . DS . 'http' . DS . 'Request.php',
ROOT . DS . 'lib' . DS . 'http' . DS . 'Response.php',

);
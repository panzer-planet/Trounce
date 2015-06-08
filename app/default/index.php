<?php
/**
 * index.php
 *
 * Kobolt
 * General purpose web development library.
 *
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */
/*
# Here the App object is created
$app = new App;

# Some app settings 
$app->setTheme('default');
$app->setTitle('Welcome to lib-cobolt');
$app->setName('default'); #This is required and must match the folder name

#  Run application
$app->runApp();	#Should be static

*/


App::setName('default');
App::run();
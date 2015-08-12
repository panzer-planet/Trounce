Trounce - Rapid Development PHP Framework 
==========

Trounce is a web development framework for PHP with emphasis on **speed**, **security** and **simplicity**.

###The project is still in early stages 
Your contributions and feedback are welcome!

**WARNING!:** I am writing this software to learn more about PHP, web servers, security and framework design. I have not written this software with the expectation that it will be usable on production environments.

```
    Copyright (C) 2015  Werner Roets <cobolt.exe@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

```

### Features

- **MBLTVC** - Powerful, flexible code seperation and layout rendering system with convention based update and fallback (read more further down)
- **Snide ORM** - Almost zero configuration setup. Easy to learn, intuitive object based database manipulation with function chaining to build more complex statements.
- **Serious security** - Not only does Trounce provide, industry current, recommended methods for encryption, XSS and SQL injection filtering but also encourages the programmer to implement good practices like input validation.

### Planned Features
- Caching and output compression
- Translations
- Database migrations

###### Requirements

- PHP 5.6+
- Apache 2.4 (may work on older versions)
- MySQL 5.5 (may work on older versions)

###### Installation

1. Place the files in a web accessible directory (e.g `/var/www/html`)
2. Enable mod_rewrite

###### Folder Structure

```
+-/
 |+ app/   -- Your application goes here
  |- controllers/
  |- error_pages/  
  |- layouts/
  |- models/         
  |- themes/
  |- views/
  |- app.config.php   -- Your app config
  |- index.php        -- Application entry point
 |- docs              -- Generated API docs
 |+ lib               -- Trounce core files
 |- logs
 |+ public
  |- js/        -- Servable JS goes here
  |- css/       -- Servable CSS goes here
  |- index.php  -- Framework entry point
```

# A Different Approach

After trying many PHP frameworks including CakePHP, CodeIgniter, Symphony, Drupal, Wordpress, Joomla and Magento I became interested in framework development and decided to make my own. Although I have taken much inspiration from the above mentioned frameworks Trounce has some new ideas in it too.

### MVC with Layout XML

Trounce makes use of Layout XML to give the developer more flexibility and save time. For example here is a layout `home.xml` that is to be rendered using the `dark_blue` theme. When an action is run in the default controller , the `default.xml` Layout file is loaded by convention. The relevant blocks will be automatically rendered if they exist for the current action:
```
<?xml version="1.0"?>
<layouts theme='dark_blue'>

  <layout action="default">

    <block name="header">
      <view>header</view>
    </block>
    
    <block name="content">
      <view>home_page</view>
    </block>
    
    <block name="footer">
      <view>site_footer</view>
    </block>
  </layout>

</layouts>
```
The framework them loads the specified theme file `dark_blue.php` (in this case) as indicated in the attributes of the top level element **layouts**. When `$this->showBlock('header')` is encountered in the theme, Trounce renders all the views and other assets found in that block of the layout file. Trounce effectively utilises something like **MLTBVC** ( Model, Layout, Theme, Block, View, Controller)

#### What's the advantage of MLTBVC vs MVC?

The layout file shown above is a simple example including only one action with a single layout. If we wanted to add another page to our website we **only specify changes to the default**. In other words: any block that is called to render will look in the default layout if it isn't found in it's own layout. If nothing at all is specified for the action's layout, trounce assumes it is a non-layout route and renders nothing. This is useful for, say, JSON output. What this means is, if we wanted to add another page here, all we need to do is add an update for when a different action is called:

```
<?xml version="1.0"?>

<layouts theme='dark_blue'>

  <layout action="default">

    <block name="header">
      <view>header</view>
    </block>
    
    <block name="content">
      <view>home_page</view>
    </block>
    
    <block name="footer">
      <view>site_footer</view>
    </block>
  </layout>

  <!-- ADD ANOTHER LAYOUT HERE -->
  <layout action="about">
    <block name="content">
        <view>about</view>
    </block>
  </layout>

</layouts>
```

Just make sure the template `about.php` is available in **app/views/** and add the `about` action to a controller:
```
<?php
class DefaultController extends Controller{
    public function defaultAction(){
  
    }
    public function aboutAction(){
     
    }
}
```

And that's it! You now have another page available with everything the original page had but with the **content block** changed to display the **about view**. Additionally the new page has it's own route and action so it's functionality is in no way limited.

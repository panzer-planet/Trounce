<html lang="en">
    <head>
        <title>Trounce - Rapid development PHP framework</title>
        <?php echo $this->addCss('bootstrap.min.css'); ?>
        <?php echo $this->addCss('app.css'); ?>
        <?php echo $this->addJs('bootstrap.min.js'); ?>
    </head>
    <body>
        
        <div>
            <?php echo $this->showBlock('header'); ?>
        </div>
        
        <div>
            <?php echo $this->showBlock('content'); ?>
        </div>
        
        <div>
            <?php echo $this->showBlock('footer'); ?>
        </div>
        
    </body>
</html>

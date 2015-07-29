<html lang="en">
    <head>
        <title>Trounce [admin]- Rapid development PHP framework</title>
        <?php echo $this->addCss('bootstrap.min.css'); ?>
        <?php echo $this->addCss('app.css'); ?>
        <?php echo $this->addJs('bootstrap.min.js'); ?>
    </head>
    <body>
        
        <div>
            <?php echo $this->showBlock('menu'); ?>
        </div>
        <div>
            <?php echo $this->showBlock('content'); ?>
        </div>
        
    </body>
</html>

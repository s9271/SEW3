<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>SEW - System Ewidencji Wojskowej<?php echo $this->tpl_title ? " - {$this->tpl_title}" : ''; ?></title>
            
            <?php if($this->scripts){ foreach($this->scripts as $script){ ?>
<?php echo $script."\n"; ?>
            <?php }} ?>
<!-- Custom -->
            <link href="/asset/css/custom.css" rel="stylesheet">
            <link href="/css/simple-sidebar.css" rel="stylesheet">
            <link href="/asset/css/mariusz.css" rel="stylesheet">
        </head>
        <body>

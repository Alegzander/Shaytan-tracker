<?php
/**
 * @var Controller $this
 */
?>
<!-- tracker.anime.uz -->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title><?=CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" href="<?=$this->resources["resources"]."/css/bootstrap.min.css" ?>" />
    <link rel="stylesheet" href="<?=$this->resources["resources"]."/css/bootstrap-responsive.min.css" ?>" />
    <link rel="stylesheet" href="<?=$this->resources["resources"]."/css/backend.css" ?>" />
    <script type="text/javascript" src="<?=$this->resources["resources"]."/js/jquery.js" ?>"></script>
    <script type="text/javascript" src="<?=$this->resources["resources"]."/js/bootstrap.min.js" ?>"></script>
    <script type="text/javascript" src="<?=$this->resources["resources"]."/js/bootstrap-typeahead.js" ?>"></script>
    <script type="text/javascript" src="<?=$this->resources["resources"]."/js/bootstrap-collapse.js" ?>"></script>
    <script type="text/javascript" src="<?=$this->resources["resources"]."/js/backend.js" ?>"></script>
</head>
<body>
<?=$content;?>
</body>
</html>
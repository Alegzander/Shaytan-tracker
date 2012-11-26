<!-- tracker.anime.uz -->
<html>
<head>
    <meta charset="UTF-8">
    <title><?=CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" href="<?=$this->resources["resources"]."/css/bootstrap.min.css" ?>" />
    <link rel="stylesheet" href="<?=$this->resources["resources"]."/css/bootstrap-responsive.min.css" ?>" />
    <link rel="stylesheet" href="<?=$this->resources["resources"]."/css/backend.css" ?>" />
    <script type="text/javascript" src="<?=$this->resources["resources"]."/js/bootstrap.min.js" ?>"></script>
    <script type="text/javascript" src="<?=$this->resources["resources"]."/js/bootstrap-typeahead.js" ?>"></script>
</head>
<body>
    <ul class="backendMenu">
        <li>
            <div>Test 1</div>
            <div>Test 2</div>
            <div>Test 3</div>
            <div>Test 4</div>
        </li>
        <li>
            <div id="content">
                <?=$content;?>
            </div>
        </li>
    </ul>
</body>
</html>
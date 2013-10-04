<?php
/**
 * @var Controller $this
 */
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title><?=CHtml::encode($this->pageTitle); ?></title>
<?php
    $styles = $this->resourceFiles['styles'];
    $scripts = $this->resourceFiles['scripts'];

    foreach ($styles as $style)
        echo "\t<link rel=\"stylesheet\" href=\"".$this->resources["resources"].'/css/'.$style."\" />\n";

    foreach ($scripts as $script)
        echo "\t<script type=\"text/javascript\" src=\"".$this->resources["resources"].'/js/'.$script."\"></script>\n";
    ?>
</head>
<body>
<?=$content;?>
</body>
</html>
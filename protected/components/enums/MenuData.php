<?php
/**
 * User: alegz
 * Date: 10/6/13
 * Time: 1:33 PM
 */
\Yii::import('bootstrap.widgets.TbMenu');

class MenuData {
    public function mainMenu(){
//        die(var_dump(\Yii::app()->authManager->isAssigned('TorrentCreate', \Yii::app()->user->getId())));

        $items = array(
            'site.index' => array(
                'label' => \Yii::t('app', 'Home'),
                'url' => \Yii::app()->createUrl('/site/index'),
                'active' => \Yii::app()->getController()->id == 'site' && \Yii::app()->getController()->action->id == 'index',
                'enable' => \Yii::app()->authManager->isAssigned('SiteIndex', \Yii::app()->user->getId())
            ),
            'torrent.create' => array(
                'label' => \Yii::t('app', 'Add torrent'),
                'url' => \Yii::app()->createUrl('/torrent/create'),
                'active' => \Yii::app()->getController()->id == 'site' && \Yii::app()->getController()->action->id == 'addTorrent',
                'enable' => \Yii::app()->authManager->isAssigned('TorrentCreate', \Yii::app()->user->getId())
            )
        );

        return array(
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'type' => TbMenu::TYPE_PILLS,
                'items' => $this->filterMenuItems($items)
            )
        );
    }

    private function filterMenuItems(array $menuItems){
        $srbacDelimeter = Helper::findModule('srbac')->delimeter;
        $alwaysAllowedList = Helper::findModule('srbac')->alwaysAllowed;
        $items = $menuItems;

        foreach ($items as $key => $menuItem){
            $rawKey = explode('.', $key);

            if (count($rawKey) === 1) {
                continue;
            } else if (count($rawKey) === 2) {
                list($controller, $action) = $rawKey;
                $task = ucfirst($controller).ucfirst($action);
            } else if (count($rawKey) === 3) {
                list($module, $controller, $action) = $rawKey;
                $task = $module.$srbacDelimeter.ucfirst($controller).ucfirst($action);
            }

            if (array_search($task, $alwaysAllowedList) === false && !\Yii::app()->authManager->isAssigned($task, \Yii::app()->user->getId())){
                unset($items[$key]);
                continue;
            }

            $appController = \Yii::app()->controller->getId();
            $appAction = \Yii::app()->controller->action->getId();

            if (isset(\Yii::app()->controller->module))
                $appModule = \Yii::app()->controller->module->getId();

            if (
                !isset($module)
                && strcmp($controller, $appController) === 0
                && strcmp($action, $appAction) === 0
            ) {
                $items[$key]['active'] = true;
            } else if (
                isset($module, $appModule)
                && strcmp($module, $appModule) === 0
                && strcmp($controller, $appController) === 0
                && strcmp($action, $appAction) === 0
            ){
                $items[$key]['active'] = true;
            }
        }

        return $items;
    }
}
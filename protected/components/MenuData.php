<?php
/**
 * User: alegz
 * Date: 10/6/13
 * Time: 1:33 PM
 */
\Yii::import('bootstrap.widgets.TbMenu');

class MenuData {
    public static function getSearchForm(){
        $searchForm = new TorrentSearchForm();
        $searchQuery = \Yii::app()->request->getQuery(get_class($searchForm));

        if (isset($searchQuery))
            $searchForm->setAttributes($searchQuery);

        return $searchForm;
    }

    public function mainMenu(BaseController $controller){
        $supportedLanguages = \Yii::app()->getParams()->supportedLanguages;
        $languageItems = array();

        foreach ($supportedLanguages as $code => $label)
            array_push($languageItems, array(
                'label' => $label,
                'url' => \Yii::app()->createUrl($this->getCurrentUrlPath(),
                        CMap::mergeArray($_GET, array('language' => $code))),
                'active' => strcmp(\Yii::app()->getLanguage(), $code) === 0,
                'disable' => strcmp(\Yii::app()->getLanguage(), $code) === 0,
            ));

        $items = array(
            'site.index' => array(
                'label' => \Yii::t('app', 'Home'),
                'url' => \Yii::app()->createUrl('/site/index'),
            ),
            'torrent.create' => array(
                'label' => \Yii::t('app', 'Add torrent'),
                'url' => \Yii::app()->createUrl('/torrent/create')
            ),
            '---'
        );

//        die(var_dump($this->filterMenuItems($items)));
        $this->registerAssets();

        return array(
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'type' => TbMenu::TYPE_PILLS,
                'items' => $this->filterMenuItems($items)
            ),
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'htmlOptions' => array('class' => 'pull-right'),
                'items' => array(
                    '---',
                    array(
                        'label' => \Yii::t('form-label', 'Language: {language}',
                            array('{language}' => $supportedLanguages[\Yii::app()->getLanguage()])),
                        'items' => $languageItems
                    )
                )
            ),
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'htmlOptions' => array('class' => 'pull-right'),
                'items' => array(
                    array('label' => 'Search', 'url' => '#', 'linkOptions' => array('class' => 'search-button'))
                )
            ),
            $controller->renderPartial('//layouts/torrent-search', array('searchForm' => static::getSearchForm()), true),
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'htmlOptions' => array('class' => 'pull-right'),
                'items' => array(
                    array('label' => 'Tags, name', 'url' => '#', 'items' => array(
//                        array('label' => 'Test1', 'icon' => 'search', 'url' => '#', 'htmlOptions' => array('class' => 'huy')),
//                        array('label' => 'Test1', 'icon' => 'search', 'url' => '#', 'htmlOptions' => array('class' => 'huy')),
//                        '---',
//                        array('label' => 'Test1', 'icon' => 'search', 'url' => '#', 'htmlOptions' => array('class' => 'huy'))
                    )),
                    '---',
                )
            ),
        );
    }

    public function adminMenu(){
        $items = array(
            array('label' => 'Permissions', 'htmlOptions' => array('class' => 'nav-header')),
            'shaytan.user.create' => array(
                'label' => \Yii::t('app', 'Create user'),
                'url' => \Yii::app()->createUrl('/shaytan/user/create')
            ),

            'srbac.manage.index' => array(
                'label' => \Yii::t('app', 'Manage permission items'),
                'url' => \Yii::app()->createUrl('/srbac/manage/index')
            ),

            'srbac.assign.users' => array(
                'label' => \Yii::t('app', 'Assign roles'),
                'url' => \Yii::app()->createUrl('/srbac/assign/users')
            ),

            array('label' => 'Torrents', 'htmlOptions' => array('class' => 'nav-header')),
            'shaytan.torrent.index' => array(
                'label' => \Yii::t('app', 'Torrents list'),
                'url' => \Yii::app()->createUrl('/shaytan/torrent/index')
            ),
            'shaytan.torrent.new' => array(
                'label' => \Yii::t('app', 'New torrents'),
                'url' => \Yii::app()->createUrl('/shaytan/torrent/index')
            ),

            array('label' => 'Abuses', 'htmlOptions' => array('class' => 'nav-header')),
            'shaytan.abuse.index' => array(
                'label' => \Yii::t('app', 'Abuses'),
                'url' => \Yii::app()->createUrl('/shaytan/abuse/index')
            ),
            'shaytan.abuse.archive' => array(
                'label' => \Yii::t('app', 'Abuse archive'),
                'url' => \Yii::app()->createUrl('/shaytan/abuse/archive')
            ),

            array('label' => 'Network', 'htmlOptions' => array('class' => 'nav-header')),
            'shaytan.network.create' => array(
                'label' => \Yii::t('app', 'Add network zone'),
                'url' => \Yii::app()->createUrl('/shaytan/network/create')
            ),
            'shaytan.network.index' => array(
                'label' => \Yii::t('app', 'Manage network zones'),
                'url' => \Yii::app()->createUrl('/shaytan/network/index')
            ),

            array('label' => 'Logout', 'htmlOptions' => array('class' => 'nav-header')),
            'shaytan.login.logout' => array(
                'label' => \Yii::t('app', 'Logout'),
                'url' => \Yii::app()->createUrl('/shaytan/login/logout')
            )
        );

        return $items;
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
            } else {
                throw new CException(\Yii::t('app', 'Invalid key "{key}".', array('{key}' => $rawKey))); //For normal autocomplete
            }

            $tmp = explode('?', $action);

            if (count($tmp) === 2){
                list($action, $GET_data) = $tmp;
                unset($GET_data);
            }

            unset($tmp);

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
                $items[$key]['url'] = '#';
            } else if (
                isset($module, $appModule)
                && strcmp($module, $appModule) === 0
                && strcmp($controller, $appController) === 0
                && strcmp($action, $appAction) === 0
            ){
                $items[$key]['active'] = true;
                $items[$key]['url'] = '#';
            }
        }

        return $items;
    }

    private function getCurrentUrlPath(){
        $controller = \Yii::app()->controller->id;
        $action = \Yii::app()->controller->action->id;
        $SP = '/';

        if (isset(\Yii::app()->controller->module))
            $path = $SP.\Yii::app()->controller->module->name.$SP.$controller.$SP.$action;
        else
            $path = $SP.$controller.$SP.$action;

        return $path;
    }

    private function registerAssets(){
        AssetsHelper::register(array('/js/search-form.js'));
    }
}
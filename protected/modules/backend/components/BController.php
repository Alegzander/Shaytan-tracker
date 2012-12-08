<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/4/12
 * Time: 2:25 PM
 * To change this template use File | Settings | File Templates.
 */
class BController extends Controller
{
    public $layout = '//layouts/backend-menu';

    public $resourceFiles = array(
        'styles' => array(
            100 => 'bootstrap.min.css',
            200 => 'bootstrap-responsive.min.css',
            300 => 'backend.css'
        ),
        'scripts' => array(
            100 => 'jquery.js',
            200 => 'bootstrap.min.js',
            300 => 'bootstrap-typeahead.js',
            400 => 'bootstrap-collapse.js',
            500 => 'backend.js'
        )
    );

    /*public function accessRules()
    {
        //Yii::app()->user
    }*/

    public function init()
    {
        //TODO Сделать формирование массива меню

        if (!Yii::app()->user->isGuest)
        {
            /**
             * @var CAuthManager $authManager
             */
            $authManager = Yii::app()->authManager;

            $authItems = $authManager->getAuthItems(CAuthItem::TYPE_ROLE, Yii::app()->user->id);

            $menu = array();

            foreach ($authItems as $roleName => $role)
            {
                /**
                 * @var CAuthItem $role
                 */
                foreach ($role->getChildren() as $taskName => $task)
                {
                    /**
                     * @var CAuthItem $tmpTask
                     * @var CAuthItem $task
                     * @var CAuthItem $operation
                     * @var CAuthItem $parentTask
                     */
                    if ($task->data['system'] === true)
                        $tmpTask = $task;
                    else
                        $tmpTask = $authManager->getAuthItem($task->data['base']);

                    if (!isset($tmpTask))
                        continue;

                    $operations = $task->getChildren();
                    $menuParams = $tmpTask->data['menu'];
                    $queue = 0;

                    if (!isset($menuParams['parent']))
                    {
                        $queue = $menuParams['queue'];
                        $menu[$queue]['label'] = $tmpTask->description;
                        $menu[$queue]['name'] = $tmpTask->name;
                        $menu[$queue]['icon'] = $menuParams['icon'];
                    }
                    else
                    {
                        $parentTask = $authManager->getAuthItem($menuParams['parent']);
                        $queue = $parentTask->data['menu']['queue'];
                    }

                    foreach ($operations as $operation)
                    {
                        if (!isset($menuParams['items'][$operation->name]))
                            continue;

                        $itemQueue = $menuParams['items'][$operation->name]['queue'];

                        $menu[$queue]['items'][$itemQueue] = array(
                            'url' => '/'.$this->module->id.'/'.$tmpTask->name.'/'.$operation->name,
                            'icon' => $menuParams['items'][$operation->name]['icon'],
                            'title' => $menuParams['items'][$operation->name]['title']
                        );
                    }

                    $params = array(
                        'name' => $task->description,
                    );
                }
            }

            ksort($menu);
            //echo '<pre>';
            //die(print_r($menu));

            $devider = array('itemOptions' => array('class' => 'divider'), 'template' => '', 'label' => '', );

            $this->menu = array(
                'htmlOptions' => array('class' => 'nav nav-list'),
                'encodeLabel' => false,
                'items' => array(
                    array('itemOptions' => array('class' => 'nav-header'), 'label' => '', 'template' => ''),
                    //Заголовок с надписью главная
                    array(
                        'itemOptions' => array('class' => 'active'),
                        'label' => '<i class="icon-home icon-white"></i>&nbsp;'.Yii::t('app', 'Главная'),
                        'url' => '/backend/'
                    ),
                        $devider
                ),
            );

            foreach ($menu as $item)
            {
                if (!isset($item['name'], $item['items']) || count($item['items']) === 0)
                    continue;

                $this->menu['items'][] = array(
                    'itemOptions' => array(
                        'data-target' => '#'.$item['name'].'-data',
                        'data-toggle' => 'collapse'
                    ),
                    'label' => '<i class="icon-'.$item['icon'].'"></i>'.Yii::t('app', $item['label']).'<!-- span class="badge badge-important pull-right">14</span -->',
                    'url'   => '#',
                );

                ksort($item['items']);

                $subItem = array(
                    'itemOptions' => array('class' => 'collapse in', 'id' => $item['name'].'-data'),
                    'template' => '', 'label' => '',
                    'submenuOptions' => array('class' => 'nav nav-list'),
                );

                foreach ($item['items'] as $menuItem)
                    $subItem['items'][] = array(
                        'label' => '<i class="icon-'.$menuItem['icon'].'"></i>'.Yii::t('app', $menuItem['title']),
                        'url' => $menuItem['url'],
                    );

                $this->menu['items'][] = $subItem;

                $this->menu['items'][] = $devider;
            }

            $this->menu['items'][] = array(
                'itemOptions' => array(
                ),
                'label' => '<i class="icon-off"></i>'.Yii::t('app', 'Выйти'),
                'url'   => '/backend/login/logout',
            );
            $this->menu['items'][] = $devider;
        }

        /**
         * @var WebUser $user
         */
        Yii::app()->user->loginUrl = '/backend/login/authenticate';

        parent::init();
    }

    public function addResourceFiles(array $resourcesArray)
    {
        foreach ($resourcesArray as $resourceGroup => $resource)
            foreach ($resource as $queue => $file)
                $this->resourceFiles[$resourceGroup][$queue] = $file;

        ksort($this->resourceFiles['styles']);
        ksort($this->resourceFiles['scripts']);
    }

    public function filters()
    {
        return array('accessControl');
    }
}

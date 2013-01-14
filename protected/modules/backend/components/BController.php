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

    /**
     * @return array
     * @desc Это шайтан механизм который на основе данных из
     * authManager формирует список ролей и операцией который могут
     * осуществлять действия в данном контроллере.
     * При необходимости метод переопределяется.
     */
    public function accessRules()
    {
        /**
         * @var CAuthManager $authManager
         */
        $authManager = Yii::app()->authManager;

        $roles = $authManager->getAuthItems(CAuthItem::TYPE_ROLE);

        $allowedRoles = array();
        $rules = array();

        foreach ($roles as $roleName => $role)
        {
            /**
             * @var CAuthItem $role
             */
            $tasks = $role->getChildren();

            if (array_key_exists(Yii::app()->controller->id, $tasks) === true)
            {
                $operations = $tasks[Yii::app()->controller->id]->getChildren();

                if (!isset($allowedRoles[$roleName]))
                    $allowedRoles[$roleName] = array();

                foreach ($operations as $operation)
                    if (array_search($operation->name, $allowedRoles[$roleName]) === false)
                        array_push($allowedRoles[$roleName], $operation->name);
            }
            else
            {
                foreach ($tasks as $taskName => $task)
                {
                    if ($task->data['system'] !== true && $task->data['base'] == Yii::app()->controller->id)
                    {
                        $operations = $task->getChildren();

                        if (!isset($allowedRoles[$roleName]))
                            $allowedRoles[$roleName] = array();

                        foreach ($operations as $operation)
                            if (array_search($operation->name, $allowedRoles[$roleName]) === false)
                                array_push($allowedRoles[$roleName], $operation->name);
                    }
                }
            }
        }

        foreach ($allowedRoles as $role => $actions)
        {
            array_push($rules, array('allow', 'actions' => $actions, 'roles' => array($role)));
        }

        $task = $authManager->getAuthItem(Yii::app()->controller->id);
        $operations = $task->getChildren();
        $actions = array();

        foreach ($operations as $operation)
            array_push($actions, $operation->name);

        array_push($rules, array('deny', 'actions' => $actions, 'users' => array('*')));

        return $rules;
    }

    public function init()
    {
        if (!Yii::app()->user->isGuest)
        {
            /**
             * Здесь на основе authManager-а формируется набор меню
             * которое доступно пользователю в зависимости от его прав.
             */

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

                $class = 'collapse in';

                if ($item['name'] == Yii::app()->controller->id)
                    $class = 'collapse';

                $subItem = array(
                    'itemOptions' => array('class' => $class, 'id' => $item['name'].'-data'),
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

        $uri = explode('/', Yii::app()->user->returnUrl);

        /**
         * Преобразуем returnUrl при необходимости
         */
        if ($uri[1] != $this->module->id)
            Yii::app()->user->returnUrl = '/backend/';

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

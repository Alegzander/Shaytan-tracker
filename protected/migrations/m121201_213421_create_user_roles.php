<?php

class m121201_213421_create_user_roles extends CDbMigration
{
	public function safeUp()
	{
        /**
         * @var CAuthManager $manager
         */
        $manager = Yii::app()->authManager;

        //Базовые операции
        $manager->createOperation('add', 'Добавиление'); //Добавление
        $manager->createOperation('view', 'Просмотр'); //Просмотр
        $manager->createOperation('delete', 'Удаление'); //Удаление
        $manager->createOperation('update', 'Редактирование'); //Обновление данных
        $manager->createOperation('configure', 'Настройка'); //Настройка, используется в нотификациях и.т.п.
        $manager->createOperation('save', 'Сохранение'); //Сохранение
        $manager->createOperation('send', 'Отправка'); //Отправить, используется в нотификациях
        $manager->createOperation('close', 'Закрытие'); //Закрыть используется в жалобах
        $manager->createOperation('reply', 'Ответ'); //Ответить, используется в жалобах
        $manager->createOperation('assign', 'Назначение'); //Назначить, используется в ролях
        $manager->createOperation('translate', 'Перевод'); //Перевести, используется в шаблонах

        //Базовые задачи
        $torrent = $manager->createTask('torrent', 'Торренты', null, array(
                'actions' => array('add', 'view', 'update', 'delete'),
                'system' => true,
                'menu' => array(
                    'queue' => 0,
                    'icon'  => 'wrench',
                    'items' => array(
                        'add' => array('icon' => 'plus-sign', 'title' => 'Добавить', 'queue' => 0),
                        'view' => array('icon' => 'list', 'title' => 'Список', 'queue' => 1),
                    )
                )
            ));
        $abuse = $manager->createTask('abuse', 'Жалобы', null, array(
                'actions' => array('view', 'reply', 'close'),
                'system' => true,
                'menu' => array(
                    'parent' => 'torrent',
                    'items' => array(
                        'view' => array('icon' => 'fire', 'title' => 'Жалобы', 'queue' => 2)
                    )
                )
            ));

        $user = $manager->createTask('user', 'Пользователи', null, array(
                'actions' => array('add', 'view', 'update', 'delete'),
                'system' => true,
                'menu' => array(
                    'queue' => 1,
                    'icon' => 'user',
                    'items' => array(
                        'add'   => array('icon' => 'plus-sign', 'title' => 'Добавить', 'queue' => 0),
                        'view'  => array('icon' => 'list', 'title' => 'Просмотреть', 'queue' => 1),
                    )
                )
            ));

        $role = $manager->createTask('role', 'Роли', null, array(
                'actions' => array('add', 'view', 'update', 'assign', 'delete'),
                'system' => true,
                'menu' => array(
                    'parent' => 'user',
                    'items' => array(
                        'view' => array('icon' => 'folder-close', 'title' => 'Роли', 'queue' => 2)
                    )
                )
            ));

        $templates = $manager->createTask('template', 'Шаблоны', null, array(
                'actions' => array('add', 'view', 'update', 'delete', 'translate'),
                'system' => true,
                'menu' => array(
                    'parent' => 'notification',
                    'items' => array(
                        'view' => array('icon' => 'asterisk', 'title' => 'Шаблоны', 'queue' => 0)
                    )
                )
            ));

        $notification = $manager->createTask('notification', 'Уведомления', null, array(
                'actions' => array('add', 'view', 'configure', 'save', 'update', 'delete'),
                'system' => true,
                'menu' => array(
                    'queue' => 2,
                    'icon' => 'inbox',
                    'items' => array(
                        'configure' => array('icon' => 'wrench', 'title' => 'Настройка', 'queue' => 1),
                        'send' => array('icon' => 'envelope', 'title' => 'Отправить', 'queue' => 3)
                    )
                )
            ));

        $system = $manager->createTask('system', 'Система', null, array(
                'actions' => array('view', 'save'),
                'system' => true,
                'menu'  => array(
                    'queue' => 4,
                    'icon' => 'cog',
                    'items' => array(
                        'view' => array('icon' => 'wrench', 'title' => 'Настройки', 'queue' => 0)
                    )
                )
            ));

        $queue = $manager->createTask('queue', 'Очередь', null, array(
                'actions' => array('view', 'update'),
                'system' => true,
                'menu' => array(
                    'parent' => 'notification',
                    'items'  => array(
                        'view' => array ('icon' => 'list', 'title' => 'Очередь', 'queue' => 2)
                    )
                )
            ));

        $languages = $manager->createTask('i18n', 'Языки', null, array(
                'actions' => array('view', 'update'),
                'system' => true,
                'menu' => array(
                    'queue' => 3,
                    'icon' => 'pencil',
                    'items' => array(
                        'view' => array('icon' => 'wrench', 'title' => 'Переводы', 'queue' => 0)
                    )
                )
            ));

        $profile = $manager->createTask('profile', "Профиль", null, array(
                'actions' => array('view', 'update'),
                'system' => true,
                'menu' => array(
                    'queue' => 5,
                    'icon' => 'folder-close',
                    'items' => array('icon' => 'file', 'title' => 'Просмотр', 'queue' => 0)
                )
            ));

        $this->setOperationsToTask($torrent);
        $this->setOperationsToTask($abuse);
        $this->setOperationsToTask($user);
        $this->setOperationsToTask($role);
        $this->setOperationsToTask($templates);
        $this->setOperationsToTask($notification);
        $this->setOperationsToTask($system);
        $this->setOperationsToTask($queue);
        $this->setOperationsToTask($languages);
        $this->setOperationsToTask($profile);

        $adminRole = $manager->createRole('admin', 'Администратор', null, array('system' => true));
        $adminRole->addChild('torrent');
        $adminRole->addChild('abuse');
        $adminRole->addChild('user');
        $adminRole->addChild('role');
        $adminRole->addChild('template');
        $adminRole->addChild('notification');
        $adminRole->addChild('system');
        $adminRole->addChild('queue');
        $adminRole->addChild('i18n');
        $adminRole->addChild('profile');

        $miniRole = $manager->createRole('mini-role', 'Вася', null, array('system' => true));
        $miniRole->addChild('profile');

        $adminEmail = \Yii::app()->getParams()->adminEmail;

        if (!isset($adminEmail))
            throw new CException(Yii::t('app', 'Не удалось получить электронную почту администратора. Убедитесь что в конфигурации в разделе params задан параметр adminEmail'));

        $admin = new User();

        $admin->name = 'Верховный админ';
        $admin->role = 'admin';
        $admin->email = $adminEmail;
        $admin->setPassword('medved');
        $admin->save();

        $adminRole->assign($admin->email, null, array('system' => true));

        $manager->save();
	}

	public function safeDown()
	{

	}

    private function setOperationsToTask($task)
    {
        /**
         * @var CAuthItem $task
         */
        if ($task->type !== CAuthItem::TYPE_TASK)
            throw new CException(Yii::t('app', 'Данный метод может принимать только задачи.'));

        if (!isset($task->data["actions"]) || !is_array($task->data["actions"]))
            throw new CException(Yii::t('app', 'Данный метод может принимать только задачи.'));

        if (count($task->data["actions"]) > 0)
            foreach ($task->data['actions'] as $action)
                $task->addChild($action);
    }
}
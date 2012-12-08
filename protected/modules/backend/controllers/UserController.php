<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/8/12
 * Time: 12:12 AM
 */
class UserController extends BController
{
    public function actionAdd()
    {
        $extraResources = array(
            'styles' => array(
                250 => 'chosen.css'
            ),
            'scripts' => array(
                450 => 'chosen.jquery.min.js'
            )
        );

        $this->addResourceFiles($extraResources);

        $userForm = new MongoUserAddForm();
        $formData = Yii::app()->request->getParam('MongoUserAddForm');

        if (isset($formData))
        {
            $userForm->attributes = $formData;

            if ($userForm->validate())
            {
                $user = new MongoUser();
                $user->email = $userForm->email;
                $user->name = $userForm->name;
                $user->setPassword($userForm->password);
                $user->phone = $userForm->phone;
                $user->SNContacts = $userForm->socialNetworks;
                $userForm->IM = $userForm->IM;

                /**
                 * @var CAuthManager $authManager
                 */
                $authManager = Yii::app()->authManager;

                foreach ($userForm->roles as $role)
                    $authManager->assign($role, $user->email);

                if (!$user->save())
                    throw new CException(Yii::t('app', 'Не удалось сохранить данные.'));

                $authManager->save();

                $this->redirect('/'.$this->module->id);
            }
        }

        $this->render('user-add', array(
                'model'  => $userForm,
                'labels' => $userForm->attributeLabels(),
                'roles'  => $userForm->getRoles(),
                'phone' => '998901234567'
        ));
    }
}

<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace app\controllers;

use app\components\UserHelper;
use Da\User\Event\FormEvent;
use Da\User\Factory\MailFactory;
use Da\User\Form\RegistrationForm;
use Da\User\Model\Profile;
use Da\User\Model\User;
use Da\User\Query\ProfileQuery;
use Da\User\Service\UserRegisterService;
use Da\User\Validator\AjaxRequestModelValidator;
use Yii;
use yii\web\NotFoundHttpException;
use Da\User\Controller\RegistrationController as BaseRegistrationController;

class RegistrationController extends BaseRegistrationController
{
    /**
     * Register user action
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }
        /** @var RegistrationForm $form */
        $form = $this->make(RegistrationForm::class);
        /** @var FormEvent $event */
        $event = $this->make(FormEvent::class, [$form]);

        $this->make(AjaxRequestModelValidator::class, [$form])->validate();

        //Load and validate form data
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->trigger(FormEvent::EVENT_BEFORE_REGISTER, $event);
            $data = [
                'username' => $form->attributes['email'],
                'email' => $form->attributes['email'],
                'password' => $form->attributes['password'],
                'gdpr_consent' => $form->attributes['gdpr_consent']
            ];
            /** @var User $user */
            //Fill user data
            $user = $this->make(User::class, [], $data);
            $user->setScenario('register');
            $mailService = MailFactory::makeWelcomeMailerService($user);

            //Run user register method
            if ($this->make(UserRegisterService::class, [$user, $mailService])->run()) {
                if ($this->module->enableEmailConfirmation) {
                    Yii::$app->session->setFlash(
                        'info',
                        Yii::t(
                            'usuario',
                            'Your account has been created and a message with further instructions has been sent to your email'
                        )
                    );
                } else {
                    Yii::$app->session->setFlash('info', Yii::t('usuario', 'Your account has been created'));
                }
                $this->trigger(FormEvent::EVENT_AFTER_REGISTER, $event);

                //Add role
                if($form->attributes['user_type'] == 'entity') {
                    $this->assignRole($user, 'entity');
                } else {
                    $this->assignRole($user, 'individual');
                }

                $this->fillProfile($user, $form);

                return $this->render(
                    '/shared/message',
                    [
                        'title' => Yii::t('usuario', 'Your account has been created'),
                        'module' => $this->module,
                    ]
                );
            }
            Yii::$app->session->setFlash('danger', Yii::t('usuario', 'User could not be registered.'));
        }
        return $this->render('register', ['model' => $form, 'module' => $this->module]);
    }

    protected function assignRole(User $user, $role)
    {
        $auth = Yii::$app->getAuthManager();

        $userRole = $auth->getRole($role);
        if (null === $userRole) {
            $userRole = $auth->createRole($role);
            $auth->add($userRole);
        }
        $auth->assign($userRole, $user->id);
    }

    /**
     * @param User $user
     * @param \app\forms\RegistrationForm $form
     * @return void
     */
    public function fillProfile($user, $form)
    {
        //Fill user profile
        $profileQuery = new ProfileQuery(Profile::class);

        $profile = $profileQuery->whereUserId($user->getId())->one();
        $profile->name = $form->attributes['name'];
        $profile->last_name = $form->attributes['last_name'];
        $profile->middle_name = $form->attributes['middle_name'];
        $profile->itn = $form->attributes['itn'];

        if(UserHelper::hasUserRole($user, 'entity')) {
            $profile->company_name = $form->attributes['company_name'];
        }

        $profile->save();
    }
}

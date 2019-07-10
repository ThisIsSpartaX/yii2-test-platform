<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace app\forms;

use app\components\ItnValidator;
use Da\User\Model\User;
use Da\User\Traits\ContainerAwareTrait;
use Da\User\Traits\ModuleAwareTrait;
use kartik\password\StrengthValidator;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class RegistrationForm extends Model
{
    use ModuleAwareTrait;
    use ContainerAwareTrait;

    /**
     * @var string User email address
     */
    public $email;
    /**
     * @var string Username
     */
    public $username;
    /**
     * @var string Password
     */
    public $password;
    /**
     * @var bool Data processing consent
     */
    public $gdpr_consent;
    /**
     * @var string User first name
     */
    public $name;
    /**
     * @var string User last name
     */
    public $last_name;
    /**
     * @var string User middle name
     */
    public $middle_name;
    /**
     * @var string User  Individual Tax-payer Number
     */
    public $itn;
    /**
     * @var string User company name
     */
    public $company_name;
    /**
     * @var string Repeat password
     */
    public $repeat_password;
    /**
     * @var integer User type, default 1
     */
    public $user_type = 'individual';

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function rules()
    {
        /** @var User $user */
        $user = $this->getClassMap()->get(User::class);

        $passwordMessage = Yii::t('custom', 'Поле {attribute} должно быть не меньше 8 символов и не больше 72. Содержать хотя бы 1 заглавную букву, 1 цифру и 1 специальный символ.');

        return [
            // email rules
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailUnique' => [
                'email',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('usuario', 'This email address has already been taken'),
            ],
            // password rules
            'passwordRequired' => ['password', 'required'],
            [['password'], StrengthValidator::class, 'min' => 8, 'max' => 72, 'digit' => 1, 'special' => 1, 'upper' => 1, 'lower' => 1,
                'message' => $passwordMessage,
                'minError' => $passwordMessage,
                'maxError' => $passwordMessage,
                'lowerError' => $passwordMessage,
                'upperError' => $passwordMessage,
                'digitError' => $passwordMessage,
                'specialError' => $passwordMessage,
            ],
            ['repeat_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли различаются'],
            'gdprType' => ['gdpr_consent', 'boolean'],
            'gdprDefault' => ['gdpr_consent', 'default', 'value' => 0,'skipOnEmpty' => false],
            'gdprRequired' => ['gdpr_consent',
                'compare',
                'compareValue' => true,
                'message' => Yii::t('usuario', 'Your consent is required to register'),
                'when' => function () {
                    return $this->module->enableGdprCompliance;
                }],
            [['company_name'], 'required', 'when' => function($model) {
                return $model->user_type == 'entity';
            }],
            [['name', 'last_name', 'middle_name', 'itn', 'user_type', 'repeat_password'], 'required'],
            [['name', 'last_name', 'middle_name'], 'string', 'min' => 2, 'max' => 60,
                'message' => Yii::t('custom', 'Поле {attribute} должно быть не меньше 2 символов и не больше 60.'),
                'tooLong' => Yii::t('custom', 'Поле {attribute} должно быть не меньше 2 символов и не больше 60.'),
                'tooShort' => Yii::t('custom', 'Поле {attribute} должно быть не меньше 2 символов и не больше 60.'),
            ],
            [['itn'], ItnValidator::className()]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('usuario', 'Email'),
            'username' => Yii::t('usuario', 'Username'),
            'password' => Yii::t('usuario', 'Password'),
            'gdpr_consent' => Yii::t('usuario', 'Data processing consent'),
            'name' => Yii::t('usuario','Name'),
            'last_name' => Yii::t('custom','Last Name'),
            'middle_name' => Yii::t('custom','Middle Name'),
            'itn' => Yii::t('custom', 'ITN'),
            'company_name' => Yii::t('custom','Company Name'),
            'repeat_password' => Yii::t('custom','Repeat Password'),
            'user_type' => Yii::t('custom','Choose type'),
        ];
    }

    public function attributeHints()
    {
        return [
            'gdpr_consent' => Yii::t('usuario', 'I agree processing of my personal data and the use of cookies to facilitate the operation of this site. For more information read our {privacyPolicy}',
                [
                    'privacyPolicy' => Html::a(Yii::t('usuario', 'privacy policy'),
                        $this->module->gdprPrivacyPolicyUrl,
                        ['target' => '_blank']
                    )
                ])
        ];
    }
}

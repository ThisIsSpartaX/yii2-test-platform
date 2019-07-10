<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace app\models;

use app\components\ItnValidator;
use app\components\UserHelper;
use Da\User\Event\UserEvent;
use Da\User\Model\Profile as BaseProfile;
use Da\User\Helper\GravatarHelper;
use Da\User\Query\ProfileQuery;
use Da\User\Traits\ContainerAwareTrait;
use Da\User\Traits\ModuleAwareTrait;
use Da\User\Validator\TimeZoneValidator;
use DateTime;
use DateTimeZone;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;

/**
 * @property int $user_id
 * @property string $name
 * @property string $public_email
 * @property string $gravatar_email
 * @property string $gravatar_id
 * @property string $location
 * @property string $website
 * @property string $bio
 * @property string $timezone
 * @property string $last_name
 * @property string $middle_name
 * @property string $itn
 * @property string $company_name
 * @property User $user
 */
class Profile extends BaseProfile
{
    use ModuleAwareTrait;
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @throws InvalidParamException
     * @throws InvalidConfigException
     */
    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('gravatar_email')) {
            $this->setAttribute(
                'gravatar_id',
                $this->make(GravatarHelper::class)->buildId(trim($this->getAttribute('gravatar_email')))
            );
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidConfigException
     */
    public function rules()
    {
        return [
            'bioString' => ['bio', 'string'],
            'timeZoneValidation' => [
                'timezone',
                function ($attribute) {
                    if ($this->make(TimeZoneValidator::class, [$this->{$attribute}])->validate() === false) {
                        $this->addError($attribute, Yii::t('usuario', 'Time zone is not valid'));
                    }
                },
            ],
            'publicEmailPattern' => ['public_email', 'email'],
            'gravatarEmailPattern' => ['gravatar_email', 'email'],
            'websiteUrl' => ['website', 'url'],
            'nameLength' => ['name', 'string', 'min' => 2, 'max' => 60],
            'publicEmailLength' => ['public_email', 'string', 'max' => 255],
            'gravatarEmailLength' => ['gravatar_email', 'string', 'max' => 255],
            'locationLength' => ['location', 'string', 'max' => 255],
            'websiteLength' => ['website', 'string', 'max' => 255],
            'nameRequired' => ['name', 'required'],
            'lastNameLength' => ['last_name', 'string', 'min' => 2, 'max' => 60],
            'lastNameRequired' => ['last_name', 'required'],
            'middleNameLength' => ['middle_name', 'string', 'min' => 2, 'max' => 60],
            'middleNameRequired' => ['middle_name', 'required'],
            'itnLength' => ['itn', ItnValidator::className()],
            'itnRequired' => ['itn', 'required'],
            [['company_name'], 'required', 'when' => function() {
                return UserHelper::hasUserRole(Yii::$app->getUser(), 'entity');
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('usuario', 'Name'),
            'last_name' => Yii::t('custom', 'Last Name'),
            'middle_name' => Yii::t('custom', 'Middle Name'),
            'itn' => Yii::t('custom', 'ITN'),
            'company_name' => Yii::t('custom', 'Company Name'),
            'public_email' => Yii::t('usuario', 'Email (public)'),
            'gravatar_email' => Yii::t('usuario', 'Gravatar email'),
            'location' => Yii::t('usuario', 'Location'),
            'website' => Yii::t('usuario', 'Website'),
            'bio' => Yii::t('usuario', 'Bio'),
            'timezone' => Yii::t('usuario', 'Time zone'),
        ];
    }
}

<?php

namespace app\components;

use Da\User\Model\User;
use Yii;
use yii\base\Component;


class UserHelper extends Component
{
    /**
     * Check if user has a role
     *
     * @param User $user
     * @param string $role
     * @return bool
     */
    public static function hasUserRole($user, $role)
    {
        $userRoles = Yii::$app->getAuthManager()->getRolesByUser($user->getId());

        if(array_key_exists($role, $userRoles)) {
            return true;
        }
        return false;
    }
}
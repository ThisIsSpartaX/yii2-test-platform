<?php

namespace app\tests\_fixtures;

use yii\test\ActiveFixture;

class TokenFixture extends ActiveFixture
{
    public $modelClass = 'Da\User\Model\Token';

    public $depends = [
        'app\tests\_fixtures\UserFixture',
    ];
}

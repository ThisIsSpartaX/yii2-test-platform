<?php


use Da\User\Model\Token;
use Da\User\Model\User;
use Da\User\Module;
use app\tests\_fixtures\UserFixture;
use yii\helpers\Html;
use yii\swiftmailer\Message;

class RegistrationCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures(['user' => UserFixture::className()]);
    }

    public function _after(FunctionalTester $I)
    {
        Yii::$app->getModule('user')->enableEmailConfirmation = true;
        Yii::$app->getModule('user')->generatePasswords = true;
    }

    /**
     * Tests registration when confirmation message is sent.
     *
     * @param FunctionalTester $I
     */
    public function testIndividualUserRegistrationWithConfirmation(FunctionalTester $I)
    {
        Yii::$app->getModule('user')->enableEmailConfirmation = true;
        $I->amOnRoute('/user/registration/register');

        $this->register($I,
            'tester1@example.com',
            'Tester2019@',
            'Tester2019@',
            'Name',
            'LastName',
            'MiddleName',
            '500100732259',
            null,
            'individual'
        );

        $I->see('Your account has been created and a message with further instructions has been sent to your email');
        $user = $I->grabRecord(User::className(), ['email' => 'tester3@example.com']);
        $token = $I->grabRecord(Token::className(), ['user_id' => $user->id, 'type' => Token::TYPE_CONFIRMATION]);
        /** @var yii\swiftmailer\Message $message */
        $message = $I->grabLastSentEmail();
        $I->assertArrayHasKey($user->email, $message->getTo());
        $I->assertContains(Html::encode($token->getUrl()), utf8_encode(quoted_printable_decode($message->getSwiftMessage()->toString())));
        $I->assertFalse($user->isConfirmed);
    }

    /**
     * Tests registration when confirmation message is sent.
     *
     * @param FunctionalTester $I
     */
    public function testRegistrationEntityUserWithConfirmation(FunctionalTester $I)
    {
        Yii::$app->getModule('user')->enableEmailConfirmation = true;
        $I->amOnRoute('/user/registration/register');

        $this->register($I,
            'tester2@example.com',
            'Tester2019@',
            'Tester2019@',
            'Name',
            'LastName',
            'MiddleName',
            '500100732259',
            'CompanyName',
            'entity'
        );

        $I->see('Your account has been created and a message with further instructions has been sent to your email');
        $user = $I->grabRecord(User::className(), ['email' => 'tester3@example.com']);
        $token = $I->grabRecord(Token::className(), ['user_id' => $user->id, 'type' => Token::TYPE_CONFIRMATION]);
        /** @var yii\swiftmailer\Message $message */
        $message = $I->grabLastSentEmail();
        $I->assertArrayHasKey($user->email, $message->getTo());
        $I->assertContains(Html::encode($token->getUrl()), utf8_encode(quoted_printable_decode($message->getSwiftMessage()->toString())));
        $I->assertFalse($user->isConfirmed);
    }

    protected function register(FunctionalTester $I, $email, $password = null, $repeatPassword = null, $name = null, $lastName = null, $middleName = null, $itn = null, $companyName = null, $userType = null) {
        $I->fillField('#registrationform-email', $email);
        if ($password !== null) {
            $I->fillField('#registrationform-password', $password);
        }
        $I->fillField('#registrationform-repeat_password', $repeatPassword);
        $I->fillField('#registrationform-name', $name);
        $I->fillField('#registrationform-last_name', $lastName);
        $I->fillField('#registrationform-middle_name', $middleName);
        $I->fillField('#registrationform-user_type', $userType);
        $I->fillField('#registrationform-itn', $itn);
        if ($userType == 'entity') {
            $I->fillField('#registrationform-company_name', $companyName);
        }
        $I->click('Sign up');

    }
}

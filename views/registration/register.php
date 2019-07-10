<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                   $this
 * @var \Da\User\Form\RegistrationForm $model
 * @var \Da\User\Model\User            $user
 * @var \Da\User\Module                $module
 */

$this->title = Yii::t('usuario', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(
                    [
                        'id' => $model->formName(),
                        'enableAjaxValidation' => true,
                        'options' => ['autocomplete' => 'off'],
                        'enableClientValidation' => false,
                    ]
                ); ?>

                <?= $form->errorSummary($model, ['header' => 'Исправьте, пожалуйста, следующие ошибки:']) ?>

                <div class="form-check pl-0">
                    <label for="registrationform-usertype-toggle" class="form-check-label">Физ. лицо</label>
                    <input id="registrationform-usertype-toggle" class="form-check-input" type="checkbox" data-toggle="toggle" data-on="<span></span>" data-off="<span></span>" <?php echo ($model->user_type && $model->user_type == 'entity') ? 'checked' : '' ?>>
                    <label for="registrationform-usertype-toggle" class="form-check-label">Юр. лицо</label>
                </div>

                <?= $form->field($model, 'user_type')->hiddenInput()->label(false) ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'last_name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'middle_name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'itn')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'company_name')->textInput(['autofocus' => true]) ?>

                <?php if ($module->generatePasswords == false): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif ?>

                <?= $form->field($model, 'repeat_password')->passwordInput() ?>

                <?php if ($module->enableGdprCompliance): ?>
                    <?= $form->field($model, 'gdpr_consent')->checkbox(['value' => 1]) ?>
                <?php endif ?>

                <?= Html::submitButton(Yii::t('usuario', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <p class="text-center">
            <?= Html::a(Yii::t('usuario', 'Already registered? Sign in!'), ['/user/security/login']) ?>
        </p>
    </div>
</div>
# Yii Test Platform
####1. Clone project
`git clone https://github.com/ThisIsSpartaX/yii2-test-platform.git project`
####2. Install vendors
`composer install`

####3. Run migrations

`php yii migrate`

####4. create admin user:

`php yii user/create admin@admin.com admin Admin2019@`

####5. go to project folder and start server
`cd project`

`php yii serve --port=8080`

####Go to your website

http://localhost:8080

####URLs:

Регистрация http://localhost:8080/index.php?r=user%2Fregistration%2Fregister

_При регистрации в базе значению логин присваивается значение email. Вход производится по логину. Его можно сменить в настройках аккаунта._

Валидный ИНН **500100732259**



Вход http://localhost:8080/index.php?r=user%2Fsecurity%2Flogin

Профиль http://localhost:8080/index.php?r=user%2Fsettings%2Fprofile

Аккаунт http://localhost:8080/index.php?r=user%2Fsettings%2Faccount

Админ панель http://localhost:8080/index.php?r=user%2Fadmin

**Полный список URL** https://yii2-usuario.readthedocs.io/en/latest/installation/available-actions/
<?php

use common\models\User;
use yii\db\Migration;

class m161013_083605_add_super_admin extends Migration
{
    public function up()
    {
        $this->insert('{{%user}}', [
            'username' => 'Super Admin',
            'status' => User::STATUS_ACTIVE,
            'email' => 'super@super.com',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('7r2CEza$Ewre'),
            'auth_key' => getenv('SUPER_USER_TOKEN_ANSIBLE')
        ]);
    }

    public function down()
    {
        echo "remove from database!";
    }
}

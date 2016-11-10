<?php

use yii\db\Migration;

class m161011_092710_deployment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%deployment}}',
            [
                'id' => $this->primaryKey(),
                'status' => $this->boolean()->defaultValue(false),
                'idDeployment' => $this->integer(),
                'createdAt' => $this->dateTime()->notNull(),
                'updatedAt' => $this->dateTime()->notNull(),
            ],
            $tableOptions
        );
    }

    public function down()
    {
        $this->dropTable('{{%deployment}}');
    }
}

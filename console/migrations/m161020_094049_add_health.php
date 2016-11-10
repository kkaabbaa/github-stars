<?php

use yii\db\Migration;

class m161020_094049_add_health extends Migration
{
    public function up()
    {
        $this->addColumn('{{%deployment}}', 'health', $this->boolean()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('{{%deployment}}', 'health');
    }
}

<?php

use yii\db\Migration;

class m161014_115125_add_status_deployment extends Migration
{
    public function up()
    {
        $this->addColumn('{{%deployment}}', 'statusDeployment', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%deployment}}', 'statusDeployment');
    }
}

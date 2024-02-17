<?php

use yii\db\Migration;

/**
 * Class m240216_140605_insert_field_iseq_to_box
 */
class m240216_140605_insert_field_iseq_to_box extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('box', 'iseq', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('box', 'iseq');
        return false;
    }
}

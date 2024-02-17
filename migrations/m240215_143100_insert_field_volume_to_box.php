<?php

use yii\db\Migration;

/**
 * Class m240215_143100_insert_field_volume_to_box
 */
class m240215_143100_insert_field_volume_to_box extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('box', 'volume', $this->decimal(8, 3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('box', 'volume');

        return false;
    }

}

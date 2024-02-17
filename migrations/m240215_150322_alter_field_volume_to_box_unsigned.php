<?php

use yii\db\Migration;

/**
 * Class m240215_150322_alter_field_volume_to_box_unsigned
 */
class m240215_150322_alter_field_volume_to_box_unsigned extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('box', 'volume', $this->decimal(8, 3)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('box', 'volume', $this->decimal(8, 3));
        return false;
    }

    
}

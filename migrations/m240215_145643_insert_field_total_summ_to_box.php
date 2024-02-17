<?php

use yii\db\Migration;

/**
 * Class m240215_145643_insert_field_total_summ_to_box
 */
class m240215_145643_insert_field_total_summ_to_box extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('box', 'total_summ', $this->decimal(8, 2)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('box', 'total_summ');

        return false;
    }

}

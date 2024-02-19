<?php

use yii\db\Migration;

/**
 * Class m240219_083008_create_box_fk_status
 */
class m240219_083008_create_box_fk_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-box-status_id',
            'box',
            'status_id',
            'status',
            'id',
            'SET NULL',
            'CASCADE',
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-box-status_id',
            'box',
        );

    }

   
}

<?php

use yii\db\Migration;

/**
 * Class m240219_092253_create_fk_product_to_box
 */
class m240219_092253_create_fk_product_to_box extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-product-box_id',
            'product_to_box',
            'box_id',
            'box',
            'id',
            'CASCADE',
            'CASCADE',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropForeignKey(
            'fk-product-box_id',
            'product_to_box',
        );
    }
    
}

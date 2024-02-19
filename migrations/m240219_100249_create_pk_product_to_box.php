<?php

use yii\db\Migration;

/**
 * Class m240219_100249_create_pk_product_to_box
 */
class m240219_100249_create_pk_product_to_box extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addPrimaryKey ( 'pk_prod_to_box', 'product_to_box', 'sku' );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey ( 'pk_prod_to_box', 'product_to_box' );
    }

}

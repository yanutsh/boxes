<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_to_box}}`.
 */
class m240219_090109_create_product_to_box_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_to_box}}', [
            'sku' => $this->string(),
            'box_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'shipped_qty' => $this->integer()->notNull(),
            'received_qty' => $this->integer()->notNull()->defaultExpression('0'),
            'price' => $this->decimal(8, 2)->notNull()->defaultExpression('0'),            
        ]);

        $this->createIndex('idx_prod_to_sku', 'product_to_box', 'sku', true );
        $this->createIndex('idx_prod_to_box_id', 'product_to_box', 'box_id', false );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product_to_box}}');
    }
}

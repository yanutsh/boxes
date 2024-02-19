<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%boxes}}`.
 */
class m240219_064401_create_box_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%box}}', [
            'id' => $this->primaryKey(),
            'weight' => $this->decimal(8, 2)->notNull(),
            'width' => $this->decimal(8, 2)->notNull(),
            'length' => $this->decimal(8, 2)->notNull(),
            'height' => $this->decimal(8, 2)->notNull(),
            'reference' => $this->string()->notNull(),
            'status_id' => $this->integer(3),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'volume' => $this->decimal(8, 3),
            'total_summ' => $this->decimal(8, 2),
            'iseq' => $this->integer(1),
           
        ]);

        $this->createIndex('idx_box-status', 'box', 'status_id', false );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%box}}');
    }
}

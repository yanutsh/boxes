<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status}}`.
 */
class m240219_082435_create_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%status}}', [
            'id' => $this->primaryKey(3),
            'name' => $this->string(50)->notNull(),
        ]); 

        //$this->upsert( 'status', ['id'=> '1', 'id'=>'2'], , ['name'=>'aaaaaa', , 'name'=>'bbbb'] ); 
        $this->batchInsert ( 'status', ['id', 'name'], [
            ['1','Expected'], 
            ['2', 'At warehouse'],
            ['3','Prepared'], 
            ['4', 'Shipped'],
        ] );    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%status}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m200421_082050_appartment
 */
class m200421_082050_appartment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%appartment}}', [
            'id' => $this->primaryKey(),
            'level_id' => $this->integer()->notNull(),
            'num' => $this->string()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-appartment-level_id',
            '{{%appartment}}', 'level_id',
            '{{%level}}', 'id',
            'CASCADE'
        );
        $this->createTable('{{%appartment_coordinate}}', [
            'id' => $this->primaryKey(),
            'appartment_id' => $this->integer()->notNull(),
            'x' => $this->float()->notNull(),
            'y' => $this->float()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-appartment_coordinate-appartment_id',
            '{{%appartment_coordinate}}', 'appartment_id',
            '{{%appartment}}', 'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-appartment_coordinate-appartment_id', '{{%appartment_coordinate}}');
        $this->dropForeignKey('fk-appartment-level_id', '{{%appartment}}');
        $this->dropTable('{{%appartment_coordinate}}');
        $this->dropTable('{{%appartment}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200421_082050_appartment cannot be reverted.\n";

        return false;
    }
    */
}

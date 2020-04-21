<?php

use yii\db\Migration;

/**
 * Class m200419_092045_init
 */
class m200419_092045_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'guid' => $this->string()->notNull(),
        ]);
        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'latitude' => $this->float()->notNull(),
            'longitude' => $this->float()->notNull(),
        ]);
        $this->createTable('{{%build}}', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer()->notNull(),
            'latitude' => $this->float()->notNull(),
            'longitude' => $this->float()->notNull(),
            'address' => $this->string()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-build-city_id',
            '{{%build}}', 'city_id',
            '{{%city}}', 'id',
            'CASCADE'
        );
        $this->createTable('{{%level}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'build_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-level-build_id',
            '{{%level}}', 'build_id',
            '{{%build}}', 'id',
            'CASCADE'
        );
        $this->createTable('{{%level_file}}', [
            'id' => $this->primaryKey(),
            'level_id' => $this->integer()->notNull(),
            'file_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-level_file-level_id',
            '{{%level_file}}', 'level_id',
            '{{%level}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-level_file-file_id',
            '{{%level_file}}', 'file_id',
            '{{%file}}', 'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-build-city_id', '{{%build}}');
        $this->dropForeignKey('fk-level-build_id', '{{%level}}');
        $this->dropForeignKey('fk-level_file-level_id', '{{%level_file}}');
        $this->dropForeignKey('fk-level_file-file_id', '{{%level_file}}');
        $this->dropTable('{{%file}}');
        $this->dropTable('{{%city}}');
        $this->dropTable('{{%build}}');
        $this->dropTable('{{%level}}');
        $this->dropTable('{{%level_file}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200419_092045_init cannot be reverted.\n";

        return false;
    }
    */
}

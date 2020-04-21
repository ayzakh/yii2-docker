<?php

use yii\db\Migration;

/**
 * Class m200421_053503_podjezd
 */
class m200421_053503_podjezd extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%porch}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'build_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-porch-build_id',
            '{{%porch}}', 'build_id',
            '{{%build}}', 'id',
            'CASCADE'
        );
        $this->addColumn('{{%level}}', 'porch_id', $this->integer()->notNull());
        $this->addForeignKey(
            'fk-level-porch_id',
            '{{%level}}', 'porch_id',
            '{{%porch}}', 'id',
            'CASCADE'
        );
        $this->dropForeignKey('fk-level-build_id', '{{%level}}');
        $this->dropColumn('{{%level}}', 'build_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-porch-bulid_id', '{{%porch}}');
        $this->dropTable('{{%porch}}');
        $this->dropForeignKey('fk-level-porch_id', '{{%level}}'); 
        $this->dropColumn('{{%level}}', 'porch_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200421_053503_podjezd cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m190710_170815_add_profile_fields
 */
class m190710_170815_add_profile_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%profile}}', 'last_name', $this->string()->notNull());
        $this->addColumn('{{%profile}}', 'middle_name', $this->string()->notNull());
        $this->addColumn('{{%profile}}', 'itn', $this->string()->notNull());
        $this->addColumn('{{%profile}}', 'company_name', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190710_170815_add_profile_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190710_170815_add_profile_fields cannot be reverted.\n";

        return false;
    }
    */
}

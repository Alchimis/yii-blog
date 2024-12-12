<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m241210_132504_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->unique()->notNull(),
            'email' => $this->string(255)->unique()->notNull(),
            'password' => $this->string(255)->notNull(),
            'hash' => $this->string(255)->notNull(),
            'role' => $this->string(63)->notNull(),
            'createdAt' => $this->timestamp()->defaultExpression('now()'), 
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}

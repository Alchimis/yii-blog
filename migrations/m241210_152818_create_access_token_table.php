<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%accessToken}}`.
 */
class m241210_152818_create_access_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%accessToken}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'token' => $this->string(255)->notNull(),
            'createdAt' => $this->timestamp()->defaultExpression('now()'),
            'expiredAt' => $this->timestamp()->defaultValue(null),
        ]);

        $this->createIndex(
            'idxAccessTokenUserId',
            '{{%accessToken}}',
            'userId',
        );

        $this->addForeignKey(
            'fkAccessTokenUserId',
            '{{%accessToken}}',
            'userId',
            '{{%user}}',
            'id',
            'CASCADE',
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%accessToken}}');
    }
}

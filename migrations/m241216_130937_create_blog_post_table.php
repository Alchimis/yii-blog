<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blogPost}}`.
 */
class m241216_130937_create_blog_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blogPost}}', [
            'id' => $this->primaryKey(),
            'authorId' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'content' => $this->string()->notNull(),
            'createdAt' => $this->timestamp()->defaultExpression('now()'),
        ]);

        $this->createIndex(
            'idxBlogPostAuthorId',
            '{{%blogPost}}',
            'authorId',
        );

        $this->addForeignKey(
            'fkBlogPostAuthorId',
            '{{%blogPost}}',
            'authorId',
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
        $this->dropTable('{{%blogPost}}');
    }
}

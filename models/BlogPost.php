<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\User;

class BlogPost extends ActiveRecord
{
    private $id;
    private $authorId;
    private $title;
    private $content;
    private $createdAt;

    /**
     * this function does not call [[save()]] on post. 
     * 
     * @param User $author
     * @param string $title
     * @param string $content
     * 
     * @return BlogPost
     */
    public static function makePostFromUser($author, $title, $content)
    {
        $post = new BlogPost();
        $post->setAttribute('authorId', $author->getId());
        $post->setAttribute('title', $title);
        $post->setAttribute('content', $content);
        return $post;
    }

    /**
     * this function does not call [[save()]] on post. 
     * 
     * @param User $author
     * @param string $title
     * @param string $content
     * 
     */
    public function makeFromUser($author, $title, $content)
    {
        $this->authorId = $author->getId();
        $this->title = $title;
        $this->content = $content;
        return $this;
    }

    public function getId()
    {
        if (is_null($this->id))
        {
            return $this->getAttribute('id');
        }
        return $this->id;
    }

    public function getTitle()
    {
        if (is_null($this->title))
        {
            return $this->getAttribute('title');
        }
        return $this->title;
    }

    public static function tableName()
    {
        return '{{%blogPost}}';
    }
}
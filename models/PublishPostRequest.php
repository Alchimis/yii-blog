<?php

namespace app\models;

use yii\base\Model;

class PublishPostRequest extends Model 
{
    public $title;
    public $content;

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            ['title', 'string', 'min' => 3],
            ['content', 'string', 'min' => 3],
        ];
    }
}
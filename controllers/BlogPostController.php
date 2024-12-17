<?php

namespace app\controllers;

use yii\rest\Controller;
use Yii;

class BlogPostController extends Controller
{

    /**
     * @return \app\services\BlogPostService
    */
    public static function getBlogPostService()
    {
        return Yii::$container->get(\app\services\BlogPostService::class);
    }

    public function actionPostBlog()
    {
        return BlogPostController::getBlogPostService()->publishPost($this->request);
    }  

    public function actionGetPosts()
    {
        return BlogPostController::getBlogPostService()->getPosts($this->request);
    }

    public function actionGetMyPosts()
    {
        return BlogPostController::getBlogPostService()->getMyPosts($this->request);
    }
}
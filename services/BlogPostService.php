<?php

namespace app\services;

use app\exceptions\EntityNotFound;
use app\helpers\JsonProcessor;
use app\models\BlogPost;
use app\models\GetBlogsRequest;
use app\models\PublishPostRequest;
use app\exceptions\HeaderNotSetException;
use app\models\User;
use Exception;
use InvalidArgumentException;
use Yii;
use yii\base\BaseObject;
use yii\web\Request;

class BlogPostService extends BaseObject
{
    public static function getUserAuthenticationService()
    {
        return Yii::$container->get(\app\services\AuthenticateUserServiceBase::class);
    }

    /**
     * @param Request $request
    */
    public function publishPost($request)
    {
        if (!$request->isPost)
        {
            return JsonProcessor::processJson(['error' => 'method not allowed', 405]);
        }
        try {
            $user = BlogPostService::getUserAuthenticationService()::authenticateUserFromRequest($request);
        } 
        catch (HeaderNotSetException $e)
        {
            return JsonProcessor::processJson(['error'=>'header not set', 'details'=>$e->getMessage()], 400);
        }
        catch (EntityNotFound $e)
        {
            return JsonProcessor::processJson([ 'error' => 'access denied' ], 401);
        } 
        catch (InvalidArgumentException $e)
        {
            return JsonProcessor::processJson([ 'error' => 'access denied' ], 401);
        }

        if (is_null($user))
        {
            return JsonProcessor::processJson([ 'error' => 'access denied' ], 401);
        }
        $publishPostRequest = new PublishPostRequest(); 
        $publishPostRequest->setAttributes($request->post());
        if (!$publishPostRequest->validate()) {
            return JsonProcessor::processJson([
                'error' => 'bad request',
                'details' => $publishPostRequest->getErrors(),
            ], 400);
        }
        $post = BlogPost::makePostFromUser($user, $publishPostRequest->getTitle(), $publishPostRequest->getContent());
        $post->setAttribute('authorId', $user->getId());
    
        try {
            if (!$post->save())
            {
                return JsonProcessor::processJson(['errors'=> $post->getErrors()], 400);
            }
        } 
        catch (Exception $e)
        {
            return JsonProcessor::processJson(['error'=>'internal server error'], 500);
        }

        return JsonProcessor::processJson(['postId'=>$post->getId()]);
    }

    /**
     * @param Request $request
    */
    public function getPosts($request)
    {
        if (!$request->isGet)
        {
            return JsonProcessor::processJson([
                'error' => 'method not allowed'
            ], 405);
        }
        $getPostRequest = new GetBlogsRequest();
        //return JsonProcessor::processJson($request->post());
        $getPostRequest->setAttributes($request->post());
        if (!$getPostRequest->validate()) 
        {
            return JsonProcessor::processJson([
                'errors' => $getPostRequest->getErrors(),
            ], 400);
        }
        //return JsonProcessor::processJson($getPostRequest);
        $posts = BlogPost::applyQueryFilter($getPostRequest);
        return JsonProcessor::processJson(['posts' => $posts]);
    }
}
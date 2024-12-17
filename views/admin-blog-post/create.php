<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BlogPost $model */

$this->title = 'Create Blog Post';
$this->params['breadcrumbs'][] = ['label' => 'Blog Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AccessToken $model */

$this->title = 'Create Access Token';
$this->params['breadcrumbs'][] = ['label' => 'Access Tokens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-token-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

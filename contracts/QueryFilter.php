<?php

namespace app\contracts;

use yii\db\ActiveQuery;

interface QueryFilter
{
    /**
     * @param ActiveQuery $query
    */
    public function apply(ActiveQuery $query);
}
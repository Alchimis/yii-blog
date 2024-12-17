<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveQuery;
use app\contracts\QueryFilter; 


class GetBlogsRequest extends Model implements QueryFilter
{
    public $dateFrom;
    public $dateTo;
    public $sortBy;
    public $items;
    public $offset;
    public $authorId;

    public function rules()
    {
        return [
            [['dateFrom','dateTo'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            ['sortBy', 'default', 'value' => null],
            [['sortBy'], 'in', 'range' => ['createdAt', 'title']],
            
            [['items', 'offset', 'authorId'],'integer'],
            ['items','default', 'value' => 100],
            [['offset'], 'default', 'value' => 0],
        ];
    } 

    /**
     * @param ActiveQuery $query
    */
    public function apply($query)
    {
        if (!empty($this->dateFrom)) 
        {
            $query->andWhere(['>=', 'createdAt', $this->dateFrom]);
        }
        if (!empty($this->dateTo)) 
        {
            $query->andWhere(['<=', 'createdAt', $this->dateTo]);
        }
        if (!empty($this->sortBy)) 
        {   
            $query->orderBy([$this->sortBy => SORT_ASC]);
        }
        if (!empty($this->authorId))
        {   
            $query->andWhere(['=', 'authorId', $this->authorId]);
        }
        $query->offset($this->offset);
        $query->limit($this->items);
        return $query;
    }


}
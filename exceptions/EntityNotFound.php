<?php

namespace app\exceptions;

class EntityNotFound extends \Exception{
    public $entityName;
    public $searchParams;

    /**
     * @param string $entityName
     * @param array $searchParams
     * @param int $code
     * @param \Throwable $previous
    */
    /*public function __construct($entityName, $searchParams, int $code = 0, \Throwable $previous = null) {
        parent::__construct("Entity ".$entityName." not found. Searched by ".json_encode($searchParams), $code, $previous);
        $this->entityName = $entityName;
        $this->searchParams = $searchParams;
    }*/

    /**
     * @param string $entityName
     * @param array $searchParams
     * @param int $code
     * @param \Throwable $previous
     * @return EntityNotFound
    */
    public static function entity($entityName, $searchParams, int $code = 0, \Throwable $previous = null){
        $e = new EntityNotFound("Entity ".$entityName." not found. Searched by ".json_encode($searchParams), $code, $previous);
        $e->entityName = $entityName;
        $e->searchParams = $searchParams;
        return $e;
    } 
}
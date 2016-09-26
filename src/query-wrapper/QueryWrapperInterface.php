<?php

namespace Athens\Core\QueryWrapper;

use Athens\Core\ObjectWrapper\ObjectWrapperInterface;

interface QueryWrapperInterface
{
    /**
     * @param mixed $pk
     * @return ObjectWrapperInterface
     */
    public function findOneByPk($pk);

    /**
     * @return ObjectWrapperInterface[]
     */
    public function find();

    /**
     * @param string $columnName
     * @param mixed $condition
     * @return ObjectWrapperInterface
     */
    public function orderBy($columnName, $condition);

    /**
     * @param string $columnName
     * @param string $criteria
     * @param mixed $criterion
     * @return QueryWrapperInterface
     */
    public function filterBy($columnName, $criteria, $criterion);

    /**
     * @param integer $offset
     * @return QueryWrapperInterface
     */
    public function offset($offset);

    /**
     * @param integer $limit
     * @return QueryWrapperInterface
     */
    public function limit($limit);

    /**
     * @return ObjectWrapperInterface
     */
    public function createObject();
    
    public function getTitleCasedObjectName();
    
    public function getPascalCasedObjectName();
    
}
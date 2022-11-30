<?php

declare(strict_types = 1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Symfony;

use Doctrine\DBAL\Query\QueryBuilder;
use Hiberus\Skeleton\Shared\Domain\Criteria\Criteria;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filter;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterField;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterOperator;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filters;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterValue;

class DbalCriteriaConverter
{
    private string $tableName;
    private Criteria $criteria;
    private QueryBuilder $queryBuilder;

    public function convert(string $tableName, Criteria $criteria, QueryBuilder $queryBuilder): QueryBuilder
    {
        $this->setTableName($tableName);
        $this->setCriteria($criteria);
        $this->setQueryBuilder($queryBuilder);

        return $this->execute();
    }

    private function setCriteria(Criteria $criteria): void
    {
        $this->criteria = $criteria;
    }

    private function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    private function setQueryBuilder(QueryBuilder $queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }

    //TODO: Only support AND simple filters.
    private function execute(): QueryBuilder
    {
        //init query
        $partQueryBuilder = $this->queryBuilder
            ->select('*')
            ->from($this->tableName, 'alias');

        // get where clauses
        $filters = $this->criteria->filters();
        $index = 0;
        $params = [];
        /** @var Filter $filter */
        foreach ($filters->filters() as $filter) {
            $field = $filter->field()->value();
            $value = $filter->value()->value();
            $operator = $filter->operator()->value;
            $param_key = 'param_' . $field . '_' . $index;
            $params[$param_key] = $value;

            if ($index === 0) {
                $partQueryBuilder = $partQueryBuilder
                    ->where('alias.' . $field . ' ' . $operator . ' :' . $param_key);
            } else {
                $partQueryBuilder = $partQueryBuilder
                    ->andWhere('alias.' . $field . ' ' . $operator . ' :' . $param_key);
            }
            ++$index;
        }

        // set parameters
        $partQueryBuilder = $partQueryBuilder
            ->setParameters($params);

        // set order
        if ($this->criteria->hasOrder()) {
            $partQueryBuilder = $partQueryBuilder
                ->orderBy(
                    $this->criteria->order()->orderBy()->value(),
                    $this->criteria->order()->orderType()->value
                );
        }

        // set offset
        if ($this->criteria->hasOffset()) {
            $partQueryBuilder = $partQueryBuilder
                ->setFirstResult($this->criteria->offset());
        }

        // set limit
        if ($this->criteria->hasLimit()) {
            $partQueryBuilder = $partQueryBuilder
                ->setMaxResults($this->criteria->limit());
        }

        // execute
        return $partQueryBuilder;
    }
}

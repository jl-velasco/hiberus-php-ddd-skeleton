<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Criteria;

enum FilterOperator: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case GT = '>';
    case LT = '<';
    case CONTAINS = 'CONTAINS';
    case NOT_CONTAINS = 'NOT_CONTAINS';
}

<?php

namespace Dekalee\Cdn77\Query;

use Dekalee\Cdn77\Exception\QueryErrorException;

/**
 * Interface QueryInterface
 */
interface QueryInterface
{
    /**
     * @return mixed|null
     * @throws QueryErrorException
     */
    public function execute();
}

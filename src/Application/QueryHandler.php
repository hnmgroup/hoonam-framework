<?php

namespace Hoonam\Framework\Application;

/**
 * @template TQuery of Query<TResult>
 * @template TResult
 */
interface QueryHandler
{
    /**
     * @param TQuery $query
     * @return TResult
     */
    public function handle($query);
}

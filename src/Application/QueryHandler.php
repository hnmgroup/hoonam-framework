<?php

namespace Hoonam\Framework\Application;

/**
 * @template TResult
 * @template TQuery of Query<TResult>
 */
interface QueryHandler
{
    /**
     * @param TQuery $query
     * @return TResult
     */
    public function handle($query);
}

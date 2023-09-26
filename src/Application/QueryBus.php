<?php

namespace Hoonam\Framework\Application;

interface QueryBus
{
    /**
     * @template TResult
     * @param Query<TResult> $query
     * @return TResult
     */
    public function handle(Query $query);
}

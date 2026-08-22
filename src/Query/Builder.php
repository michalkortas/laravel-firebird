<?php

namespace HarryGulliford\Firebird\Query;

use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder extends QueryBuilder
{
    /**
     * Determine if any rows exist for the current query.
     *
     * @return bool
     */
    public function exists()
    {
        return parent::count() > 0;
    }

    /**
     * Set the stored procedure which the query is targeting.
     *
     * @param  string  $procedure
     * @param  array  $values
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function procedure(string $procedure, array $values = [])
    {
        $compiledProcedure = $this->grammar->compileProcedure($this, $procedure, $values);

        $this->fromRaw($compiledProcedure, $this->cleanBindings($values));

        return $this;
    }

    /**
     * Add a from stored procedure clause to the query builder.
     *
     * @param  string  $procedure
     * @param  array  $values
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function fromProcedure(string $procedure, array $values = [])
    {
        return $this->procedure($procedure, $values);
    }
}

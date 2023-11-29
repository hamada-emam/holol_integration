<?php

namespace App\Traits;

use Closure;
use Illuminate\Support\Facades\DB;

/**
 * For models that generates code
 */
trait GenerateCode
{
    private function generateCode(Closure $alterQuery = null)
    {
        if ($this->code) return $this->code;

        $query = self::select('code')
            ->whereRaw("REGEXP_LIKE (code, '^[[:alpha:]]+-[[:digit:]]+$')")
            ->orderByDesc("code");

        if ($alterQuery) $alterQuery($query);

        $lastEntry = $query->first();

        $lastCode = substr(@$lastEntry->code, 3) ?? 0;

        return $this->getCodePrefix() . (string) ++$lastCode;
    }

    private function getCodePrefix()
    {
        return strtoupper(substr((new \ReflectionClass($this))->getShortName(), 0, 2)) . "-";
    }
}

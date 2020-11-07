<?php

namespace KitLoong\MigrationsGenerator\Transformers;

use Illuminate\Support\Collection;
use KitLoong\MigrationsGenerator\MigrationMethod\ColumnType;

class IncrementsTransformer
{
    protected $typesMap = [
        ColumnType::BIG_INTEGER => ColumnType::BIG_INCREMENTS,
        ColumnType::INTEGER => ColumnType::BIG_INCREMENTS,
        ColumnType::MEDIUM_INTEGER => ColumnType::BIG_INCREMENTS,
        ColumnType::SMALL_INTEGER => ColumnType::BIG_INCREMENTS,
        ColumnType::TINY_INTEGER => ColumnType::BIG_INCREMENTS,
    ];

    public function transformFields(array $fieldDefinitions): array
    {
        $fieldDefCollection = new Collection($fieldDefinitions);
        $fieldDefCollection = $this->replaceIncrements($fieldDefCollection);
        return $fieldDefCollection->values()->all();

    }

    protected function replaceIncrements(Collection $fieldDefCollection): Collection
    {
        /** @var \Illuminate\Support\Collection $incrementKeys */
        $incrementKeys = $fieldDefCollection
            ->whereIn('type', array_keys($this->typesMap))
            ->where('args', [true])
            ->where('decorators', [])
            ->keys();
        $fieldDefCollection->transform(function ($fieldDef, $key) use ($incrementKeys) {
            if (!$incrementKeys->contains($key)) {
                return $fieldDef;
            }
            $fieldDef['type'] = $this->typesMap[$fieldDef['type']];
            $fieldDef['args'] = [];
            return $fieldDef;
        });
        return $fieldDefCollection;
    }
}

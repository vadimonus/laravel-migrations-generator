<?php

namespace KitLoong\MigrationsGenerator\Transformers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use KitLoong\MigrationsGenerator\MigrationMethod\ColumnType;

class UnsignedBigIntegerTransformer
{
    public function transformFields(array $fieldDefinitions): array
    {
        $fieldDefCollection = new Collection($fieldDefinitions);

        $types = [
            ColumnType::UNSIGNED_INTEGER,
            ColumnType::UNSIGNED_MEDIUM_INTEGER,
            ColumnType::UNSIGNED_SMALL_INTEGER,
            ColumnType::UNSIGNED_TINY_INTEGER,
            ColumnType::INTEGER,
            ColumnType::MEDIUM_INTEGER,
            ColumnType::SMALL_INTEGER,
            ColumnType::TINY_INTEGER,
        ];
        /** @var \Illuminate\Support\Collection $keys */
        $keys = $fieldDefCollection
            ->whereIn('type', $types)
            ->filter(function ($fieldDef) {
                return Str::endsWith($fieldDef['field'], '_id');
            })
            ->keys();
        $fieldDefCollection->transform(function ($fieldDef, $key) use ($keys) {
            if (!$keys->contains($key)) {
                return $fieldDef;
            }
            $fieldDef['type'] = ColumnType::UNSIGNED_BIG_INTEGER;
            return $fieldDef;
        });

        return $fieldDefCollection->values()->all();
    }
}

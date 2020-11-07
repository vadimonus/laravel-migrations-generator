<?php

namespace KitLoong\MigrationsGenerator\Transformers;

use Illuminate\Support\Collection;
use KitLoong\MigrationsGenerator\MigrationMethod\ColumnType;

class TimestampsPrecisionTransformer
{
    public function transformFields(array $fieldDefinitions): array
    {
        $fieldDefCollection = new Collection($fieldDefinitions);

        $types = [
            ColumnType::TIMESTAMP,
            ColumnType::TIMESTAMPS,
            ColumnType::TIMESTAMP_TZ,
            ColumnType::TIMESTAMPS_TZ,
        ];
        /** @var \Illuminate\Support\Collection $keys */
        $keys = $fieldDefCollection
            ->whereIn('type', $types)
            ->keys();
        $fieldDefCollection->transform(function ($fieldDef, $key) use ($keys) {
            if (!$keys->contains($key)) {
                return $fieldDef;
            }
            $fieldDef['args'] = [6];
            return $fieldDef;
        });

        /** @var \Illuminate\Support\Collection $keys */
        $keys = $fieldDefCollection
            ->where('type', ColumnType::SOFT_DELETES)
            ->keys();
        $fieldDefCollection->transform(function ($fieldDef, $key) use ($keys) {
            if (!$keys->contains($key)) {
                return $fieldDef;
            }
            if (empty($fieldDef['args'][0])) {
                $fieldDef['args'][0] = '\'deleted_at\'';
            }
            $fieldDef['args'][1] = 6;
            return $fieldDef;
        });

        return $fieldDefCollection->values()->all();
    }
}

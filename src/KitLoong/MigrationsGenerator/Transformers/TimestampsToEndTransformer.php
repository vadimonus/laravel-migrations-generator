<?php

namespace KitLoong\MigrationsGenerator\Transformers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use KitLoong\MigrationsGenerator\MigrationMethod\ColumnType;
use KitLoong\MigrationsGenerator\MigrationMethod\IndexType;

class TimestampsToEndTransformer
{
    protected $order = [
        ColumnType::BIG_INCREMENTS => 0,
        ColumnType::INCREMENTS => 0,
        ColumnType::MEDIUM_INCREMENTS => 0,
        ColumnType::SMALL_INCREMENTS => 0,
        ColumnType::TINY_INCREMENTS => 0,

        ColumnType::TIMESTAMPS => 98,
        ColumnType::TIMESTAMPS_TZ => 98,

        ColumnType::SOFT_DELETES => 99,

        IndexType::PRIMARY => 100,
        IndexType::UNIQUE => 100,
        IndexType::INDEX => 100,
        IndexType::SPATIAL_INDEX => 100,
    ];

    public function transformFields(array $fieldDefinitions): array
    {
        $fieldDefCollection = new Collection($fieldDefinitions);

        $fieldDefCollection->sortBy(function ($fieldDef, $key){
            return Arr::get($this->order, $fieldDef['type'], 10);
        });

        return $fieldDefCollection->values()->all();
    }
}

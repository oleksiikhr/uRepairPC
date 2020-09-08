<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $perPage = 25;

    /**
     * @return array
     */
    public function getChangesWithoutHiddenAttrs(): array
    {
        $data = $this->getChanges();

        foreach ($this->getHidden() as $attr) {
            unset($data[$attr]);
        }

        return $data;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new static())->getTable();
    }
}

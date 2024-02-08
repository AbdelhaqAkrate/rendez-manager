<?php

namespace App\Models;

use Carbon\Carbon;

abstract class AbstractModel extends Model
{
  //  use SoftDeletes;
    
    public const ID_COLUMN = 'id';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Override the primary key type.
     *
     * @var string
     */
    protected $keyType = 'string';

    public function getId(): string
    {
        return $this->getAttribute(self::ID_COLUMN);
    }

    public function getCreatedAt(): Carbon
    {
        return $this->getAttribute(self::CREATED_AT);
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->getAttribute(self::UPDATED_AT);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Base model with common functionality for all models.
 * All application models should extend this class.
 */
class BaseModel extends Model
{
    /**
     * Get the table name without prefix.
     */
    public static function getTableName(): string
    {
        return (new static)->getTable();
    }

    /**
     * Scope to get records by ID or UUID.
     */
    public function scopeByIdOrUuid($query, $id)
    {
        if (is_numeric($id)) {
            return $query->where('id', $id);
        }

        return $query->where('uuid', $id);
    }

    /**
     * Scope to get active records.
     */
    public function scopeActive($query)
    {
        if (in_array('is_active', $this->getFillable())) {
            return $query->where('is_active', true);
        }

        return $query;
    }

    /**
     * Scope to get records sorted by sort_order and created_at.
     */
    public function scopeSorted($query)
    {
        if (in_array('sort_order', $this->getFillable())) {
            return $query->orderBy('sort_order')->orderBy('created_at');
        }

        return $query->orderBy('created_at');
    }

    /**
     * Scope to search by name or title.
     */
    public function scopeSearch($query, $search)
    {
        if (in_array('name', $this->getFillable())) {
            return $query->where('name', 'like', '%'.$search.'%');
        }
        if (in_array('title', $this->getFillable())) {
            return $query->where('title', 'like', '%'.$search.'%');
        }

        return $query;
    }

    /**
     * Get the model's fillable attributes as an array.
     */
    public function getFillableAttributes(): array
    {
        return $this->getAttributes();
    }

    /**
     * Check if the model has a specific attribute.
     */
    public function hasAttribute($attribute): bool
    {
        return array_key_exists($attribute, $this->getAttributes());
    }
}

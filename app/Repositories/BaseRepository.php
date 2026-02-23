<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository
{

    protected Model $model;

    protected int $INT_PER_PAGE = 15;


    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Retrieves all records, ordered by the latest creation date
    public function all(): Collection
    {
        return $this->model->latest()->get();
    }

    // Handles pagination, allowing custom queries and per-page limits
    public function paginate($perPage = 15, $columns = ['*'])
    {
        $perPage = $perPage ?? request('per_page', $this->INT_PER_PAGE);

        return $this->model->paginate($perPage, $columns);
    }

    // Finds a specific record by its ID or fails (throws 404)
    public function find(int|string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    // Allows passing flexible where clauses to the query builder
    public function where(...$args)
    {
        return $this->model->where(...$args);
    }

    // Retrieves a collection of records matching a specific column and value
    public function findWhere(string $column, mixed $value): Collection
    {
        return $this->model->where($column, $value)->get();
    }

    // Creates and returns a new record using the provided data array
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    // Updates an existing record by ID and returns a boolean success state
    public function update(int|string $id, array $data): bool
    {
        return $this->find($id)->update($data);
    }

    // Soft deletes (or permanently deletes if no soft deletes setup) a record by ID
    public function delete(int|string $id): bool
    {
        return $this->find($id)->delete();
    }

    // Restores a soft-deleted record by its ID
    public function restore(int|string $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    // Permanently removes a record from the database
    public function forceDelete(int|string $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->forceDelete();
    }

    // Finds a record by ID, including those that have been soft-deleted
    public function findWithTrashed(int|string $id): Model
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    // Updates an existing record or creates a new one if it doesn't exist
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    // Finds the first record matching a condition or fails (throws 404)
    public function findFirstWhere(string $column, mixed $value): Model
    {
        return $this->model->where($column, $value)->firstOrFail();
    }

    // Eager loads relationships to prevent N+1 query problems
    public function with($relations): Builder
    {
        return $this->model->with($relations);
    }

    /* * ========================================================================
     * NEWLY ADDED COMMON METHODS
     * ========================================================================
     */

    // Finds the first record matching attributes or creates it instantly
    public function firstOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    // Retrieves a list of specific column values (useful for dropdowns/selects)
    public function pluck(string $column, ?string $key = null): \Illuminate\Support\Collection
    {
        return $this->model->pluck($column, $key);
    }

    // Mass inserts multiple records efficiently without triggering Eloquent events
    public function insert(array $data): bool
    {
        return $this->model->insert($data);
    }

    // Returns the total count of records matching an optional query builder
    public function count(?Builder $query = null): int
    {
        $query = $query ?? $this->model->query();
        return $query->count();
    }

    // Quickly checks if any record exists matching a specific column and value
    public function existsWhere(string $column, mixed $value): bool
    {
        return $this->model->where($column, $value)->exists();
    }
}

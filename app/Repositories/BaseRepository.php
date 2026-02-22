<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected Model $model;
    protected int $perPage = 15;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    public function findById(int|string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function all(): Collection
    {
        return $this->model->latest()->get();
    }

    public function paginate(int|Request $request = null, $query = null): LengthAwarePaginator
    {
        $query = $query ?? $this->model->query()->latest();
        $perPage = $this->perPage;

        if ($request instanceof Request) {
            $perPage = $request->get('per_page', $this->perPage);

            // Auto-handle Soft Deletes trashed filter
            if (method_exists($this->model, 'withTrashed')) {
                $query->when($request->input('trashed'), function ($q, $trashed) {
                    return $trashed === 'with' ? $q->withTrashed() : ($trashed === 'only' ? $q->onlyTrashed() : $q);
                });
            }

            // Auto-handle Model Filter scope
            if (method_exists($this->model, 'scopeFilter')) {
                $query->filter($request->all());
            }

            // Auto-handle Model Sort scope
            if (method_exists($this->model, 'scopeSort')) {
                $query->sort(
                    $request->input('sort_field', 'created_at'),
                    $request->input('sort_order', -1)
                );
            }
        } else {
            $perPage = $request;
        }

        return $query->paginate($perPage);
    }



    public function find(int|string $id): Model
    {
        return $this->model->findOrFail($id);
    }


    public function where(...$args)
    {
        return $this->model->where(...$args);
    }



    public function findWhere(string $column, mixed $value): Collection
    {
        return $this->model->where($column, $value)->get();
    }



    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): bool
    {
        return $this->find($id)->update($data);
    }

    // Standard Soft Delete (or permanent if SoftDeletes trait is not used)
    public function delete(int|string $id): bool
    {
        return $this->find($id)->delete();
    }

    // Restore a soft-deleted record
    public function restore(int|string $id): bool
    {
        // findOrFail doesn't see trashed items, so we use withTrashed()
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    // Permanently remove from database
    public function forceDelete(int|string $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->forceDelete();
    }

    // Find a record with trashed items
    public function findWithTrashed(int|string $id): Model
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    // Update or create a record
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    // Find first record by column OR throw 404
    public function findFirstWhere(string $column, mixed $value): Model
    {
        return $this->model->where($column, $value)->firstOrFail();
    }


    public function with($relations)
    {
        return $this->model->with($relations);
    }
}

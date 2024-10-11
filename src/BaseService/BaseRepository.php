<?php

namespace Denmarty\BaseServiceRepository\BaseService;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * The model instance.
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Create a new BaseRepository instance.
     *
     * @param Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get a new query builder for the model's table.
     *
     * @param array|null $attr
     * @return Builder
     */
    public function index(array $attr = null): Builder
    {
        return $attr ? $this->model->query()->select($attr) : $this->model->query();
    }

    /**
     * Create a new instance of the model.
     *
     * @param array $data
     * @return Model|Builder
     */
    public function create(array $data): Model|Builder
    {
        $model = $this->index()->create($data);
        return $model->refresh();
    }

    /**
     * Update the model in the database.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $model = $this->index()->find($id);

        if (!$model) {
            return false;
        }

        return $model->fill($data)->save();
    }

    /**
     * @param string $uuid
     * @param array $data
     * @return Model|null
     */
    public function updateByUUID(string $uuid, array $data): Model|null
    {
        return $this->updateBy('uuid', $uuid, $data);
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param array $attr
     * @param array $data
     * @return Model|Builder
     */
    public function updateOrCreate(array $attr, array $data): Model|Builder
    {
        return $this->model->query()->updateOrCreate($attr, $data);
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data): int
    {
        return $this->model->query()->insert($data);
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param array $data
     * @param array $uniqueFields
     * @param $updatedFields
     * @return int
     */
    public function upsert(array $data, array $uniqueFields, $updatedFields): int
    {
        return $this->model->query()->upsert($data, $uniqueFields, $updatedFields);
    }

    /**
     * Delete a record from the database.
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        $model = $this->index()->find($id);

        if (!$model) {
            return false;
        }

        return $model->delete();
    }

    /**
     * Find a model by its primary key.
     *
     * @param int $id
     * @return Model|Collection|Builder|array|null
     */
    public function getById(int $id): Model|Collection|Builder|array|null
    {
        return $this->index()->find($id);
    }

    /**
     * @param string $uuid
     * @return Model|Collection|Builder|array|null
     */
    public function getByUuid(string $uuid): Model|Collection|Builder|array|null
    {
        return $this->index()->where('uuid', $uuid)->first();
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function deleteByUuid(string $uuid): bool
    {
        $model = $this->index()->where('uuid', $uuid)->first();

        if (!$model) {
            return false;
        }

        return $model->delete();
    }

    /**
     * Получаем строку из бд по названию колонки и значению
     * @param mixed $value
     * @param string $columnName
     * @param array|null $relations
     * @param string $operator
     * @return Model|null
     */
    public function getRowByColumn(
        mixed $value,
        string $columnName = 'id',
        ?array $relations = null,
        string $operator = '='
    ): ?Model {
        return $this->index()
            ->where($columnName, $operator, $value)
            ->when($relations, function ($query) use ($relations) {
                return $query->with($relations);
            })
            ->first();
    }

    /**
     * Create or ignore a record matching the attributes, and fill it with values.
     *
     * @param array $attr
     * @param array $data
     * @return Model|Builder
     */
    public function firstOrCreate(array $attr, array $data): Model|Builder
    {
        return $this->model->query()->firstOrCreate($attr, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function updateById(int $id, array $data): Model|null
    {
        return $this->updateBy('id', $id, $data);
    }

    /**
     * @param int $id
     * @return string|null
     */
    public function getUuidById(int $id): string|null
    {
        return $this->index(['uuid'])->where('id', $id)->first()?->uuid;
    }

    /**
     * @param string $uuid
     * @return int|null
     */
    public function getIdByUuid(string $uuid): int|null
    {
        return $this->index(['id'])->where('uuid', $uuid)->first()?->id;
    }

    /**
     * @param $columnName
     * @param $value
     * @param $data
     * @return Model|null
     */
    private function updateBy($columnName, $value, $data): Model|null
    {
        $model = $this->index()->where($columnName, '=', $value)->first();

        if (!$model) {
            return null;
        }

        $result = $model->fill($data)->save();
        if ($result) {
            return $model->refresh();
        } else {
            return null;
        }
    }
}

<?php

namespace Denmarty\BaseServiceRepository\BaseService;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

/**
 *
 */
abstract class BaseDtoService
{
    /**
     * @var BaseRepository
     */
    public BaseRepositoryInterface $baseRepository;

    public Data $dto;

    /**
     * @param array|null $attr
     * @return Model|Builder
     */
    public function index(array $attr = null): Model|Builder
    {
        return $this->baseRepository->index($attr);
    }

    /**
     * @param $id
     * @return Model|Collection|Builder|array|null
     */
    public function getById($id, $isDto = true): Model|Collection|Builder|array|null|Data
    {
        $data = $this->baseRepository->getById($id);

        if ($isDto) {
            $data = $this->dto::from($data);
        }

        return $data;
    }

    /**
     * @param array $data
     * @return Model|Builder
     */
    public function create(array $data, $isDto = true): Model|Builder|Data
    {
        $result = $this->baseRepository->create($data);

        if ($isDto) {
            $result = $this->dto::from($result);
        }

        return $result;
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data): bool
    {
        return $this->baseRepository->update($id, $data);
    }

    /**
     * @param string $uuid
     * @param array $data
     * @return Model|null
     */
    public function updateByUUID(string $uuid, array $data, $isDto = true): Model|null|Data
    {
        $result = $this->baseRepository->updateByUUID($uuid, $data);

        if ($isDto) {
            $result = $this->dto::from($result);
        }

        return $result;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function updateById(int $id, array $data, $isDto = true): Model|null|Data
    {
        $result = $this->baseRepository->updateById($id, $data);

        if ($isDto) {
            $result = $this->dto::from($result);
        }

        return $result;
    }

    /**
     * @param array $attr
     * @param array $data
     * @return Model|Builder
     */
    public function updateOrCreate(array $attr, array $data, $isDto = true): Model|Builder|Data
    {
        $result = $this->baseRepository->updateOrCreate($attr, $data);

        if ($isDto) {
            $result = $this->dto::from($result);
        }

        return $result;
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data): int
    {
        return $this->baseRepository->insert($data);
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
        return $this->baseRepository->upsert($data, $uniqueFields, $updatedFields);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteById($id): bool
    {
        return $this->baseRepository->deleteById($id);
    }


    /**
     * @param string $uuid
     * @return Model|Collection|Builder|array|null
     */
    public function getByUuid(string $uuid, $isDto = true): Model|Collection|Builder|array|null|Data
    {
        $result = $this->baseRepository->getByUuid($uuid);

        if ($isDto) {
            $result = $this->dto::from($result);
        }

        return $result;
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function deleteByUuid(string $uuid): bool
    {
        return $this->baseRepository->deleteByUuid($uuid);
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
        string $operator = '=',
        $isDto = true
    ): null|Model|Data {
        $result = $this->index()
            ->where($columnName, $operator, $value)
            ->when($relations, function ($query) use ($relations) {
                return $query->with($relations);
            })
            ->first();

        if ($isDto) {
            $result = $this->dto::from($result);
        }

        return $result;
    }

    /**
     * @param int $id
     * @return string|null
     */
    public function getUuidById(int $id): string|null
    {
        return $this->baseRepository->getUuidById($id);
    }

    /**
     * @param string $uuid
     * @return int|null
     */
    public function getIdByUuid(string $uuid): int|null
    {
        return $this->baseRepository->getIdByUuid($uuid);
    }
}

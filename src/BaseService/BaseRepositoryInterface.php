<?php

namespace Denmarty\BaseServiceRepository\BaseService;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
interface BaseRepositoryInterface
{
    /**
     * @param array|null $attr
     * @return Builder
     */
    public function index(array $attr = null): Builder;

    /**
     * @param array $data
     * @return Model|Builder
     */
    public function create(array $data): Model|Builder;

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * @param string $uuid
     * @param array $data
     * @return Model|null
     */
    public function updateByUUID(string $uuid, array $data): Model|null;

    /**
     * @param array $attr
     * @param array $data
     * @return Model|Builder
     */
    public function updateOrCreate(array $attr, array $data): Model|Builder;

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data): int;

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param array $data
     * @param array $uniqueFields
     * @param $updatedFields
     * @return int
     */
    public function upsert(array $data, array $uniqueFields, $updatedFields): int;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;

    /**
     * @param int $id
     * @return Model|Collection|Builder|array|null
     */
    public function getById(int $id): Model|Collection|Builder|array|null;

    /**
     * @param string $uuid
     * @return Model|Collection|Builder|array|null
     */
    public function getByUuid(string $uuid): Model|Collection|Builder|array|null;

    /**
     * @param string $uuid
     * @return bool
     */
    public function deleteByUuid(string $uuid): bool;

    /**
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function updateById(int $id, array $data): Model|null;

    /**
     * @param int $id
     * @return string|null
     */
    public function getUuidById(int $id): string|null;

    /**
     * @param string $uuid
     * @return int|null
     */
    public function getIdByUuid(string $uuid): int|null;
}

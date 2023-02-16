<?php

namespace Contrive\Deployer\Models;

use Carbon\Carbon;
use Contrive\Deployer\Libs\DB;

class Model extends DB
{
    public $table;

    public bool $timestamps = false;

    protected array $columns = [];

    protected string $pk = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array|null
     */
    public function all(): ?array
    {
        return $this->getQueryBuilder()->select('*')->from($this->table)->fetchAllAssociative();
    }

    /**
     * @param array $data
     * @return boolean
     */
    public function insert(array $data): bool
    {
        if ($this->timestamps === true) {
            $data += [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        return $this->getConn()->insert($this->table, $data);
    }

    /**
     * @param array $data
     * @param integer $id
     * 
     * @return boolean
     */
    public function update(array $data, int $id): bool
    {
        if ($this->timestamps === true) {
            $data += [
                'updated_at' => Carbon::now()
            ];
        }
        return $this->getConn()->update($this->table, $data, [$this->getPk() => $id]);
    }

    /**
     * @param integer $id
     *
     * @return array|null
     */
    public function find(int $id): ?array
    {
        return $this->getQueryBuilder()->select('*')->where($this->getPk() . ' = ' . $id)->from($this->table)->fetchAssociative();
    }

    /**
     * @param integer $id
     *
     * @return boolean
     */
    public function delete(int $id): bool
    {
        return $this->getConn()->delete($this->table, [$this->getPk() => $id]);
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->pk;
    }
}

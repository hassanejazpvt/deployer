<?php

namespace Contrive\Deployer\Models;

class Command extends Model
{
    public $table = 'commands';

    public bool $timestamps = true;

    protected array $columns = ['server_id', 'name', 'command', 'sorting'];

    /**
     * @param integer $serverId
     * 
     * @return array|null
     */
    public function getByServerId(int $serverId): ?array
    {
        return $this->where(['server_id' => $serverId])->orderBy('sorting', 'ASC')->get();
    }
}

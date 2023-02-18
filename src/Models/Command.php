<?php

namespace Contrive\Deployer\Models;

class Command extends Model
{
    public $table = 'commands';

    public bool $timestamps = true;

    protected array $columns = ['ssh_id', 'name', 'command'];

    /**
     * @param integer $sshId
     * 
     * @return array|null
     */
    public function getBySshId(int $sshId): ?array
    {
        return $this->where(['ssh_id' => $sshId])->get();
    }
}

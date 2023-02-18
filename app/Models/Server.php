<?php

namespace Contrive\Deployer\Models;

class Server extends Model
{
    public $table = 'servers';

    public bool $timestamps = true;

    protected array $columns = ['name', 'hostname', 'username', 'port', 'public_key', 'private_key', 'identities_only'];
}
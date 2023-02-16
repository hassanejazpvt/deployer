<?php

namespace Contrive\Deployer\Models;

class Ssh extends Model
{
    public $table = 'ssh';

    public bool $timestamps = true;

    protected array $columns = ['hostname', 'username', 'port', 'public_key', 'private_key', 'identities_only'];
}
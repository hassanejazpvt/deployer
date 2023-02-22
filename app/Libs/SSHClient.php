<?php

namespace Contrive\Deployer\Libs;

use Exception;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class SSHClient
{
    /**
     * @return SSH2|null
     */
    public static function connect($server) : ?SSH2
    {
        try {
            if (! $server['use_password']) {
                $tmpname = tempnam('tmp', '');
                @file_put_contents($tmpname, $server['private_key']);
            }
            if ($server['use_password']) {
                $key = $server['password'];
            } else {
                $key = PublicKeyLoader::load(file_get_contents($tmpname));
                @unlink($tmpname);
            }
            $sshClient = new SSH2($server['hostname'], $server['port']);
            $sshClient->login($server['username'], $key);
            return $sshClient;
        } catch (Exception $e) {
            @unlink($tmpname);
            throw $e;
        }
    }
}

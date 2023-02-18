<?php

namespace Contrive\Deployer\Controllers;

use Carbon\Carbon;
use Contrive\Deployer\Libs\Request;
use Contrive\Deployer\Models\Command;
use Contrive\Deployer\Models\Server;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class ServerController extends Controller
{
    private $server;
    private $command;

    public function __construct()
    {
        $this->server = new Server();
        $this->command = new Command();
        $this->ValidateRequestMethods([
            'index' => 'GET',
            'edit' => 'GET',
            'update' => 'POST',
            'store' => 'POST',
            'delete' => 'POST',
            'verify' => 'POST'
        ]);
    }

    /**
     * @return void
     */
    public function index() : void
    {
        $server = array_map(function ($record) {
            $record['identities_only'] = $record['identities_only'] ? 'Yes' : 'No';
            $record['created_at'] = Carbon::parse($record['created_at'])->format('M d, Y - h:i A');
            $record['updated_at'] = Carbon::parse($record['updated_at'])->format('M d, Y - h:i A');
            $record['commands'] = $this->command->getByServerId($record['id']);
            return $record;
        }, $this->server->all());
        $this->JsonResponse(['data' => $server]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function edit(Request $request) : void
    {
        $server = $this->server->find($request->id);
        include VIEWS_PATH.'/servers/form.php';
        exit();
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function update(Request $request) : void
    {
        $this->server->update($request->only($this->server->getColumns()), $request->id);

        $this->JsonResponse(['status' => 1]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function store(Request $request) : void
    {
        $this->server->insert($request->only($this->server->getColumns()));

        $this->JsonResponse(['status' => 1]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function delete(Request $request) : void
    {
        $this->server->delete($request->id);
        $this->JsonResponse(['status' => 1]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function verify(Request $request) : void
    {
        $server = $this->server->find($request->id);
        $tmpname = tempnam('tmp', '');
        @file_put_contents($tmpname, $server['private_key']);
        try {
            $key = PublicKeyLoader::load(file_get_contents($tmpname));
            @unlink($tmpname);
            $sshClient = new SSH2($server['hostname'], $server['port']);
            if (! $sshClient->login($server['username'], $key)) {
                $errors = array_map(function ($error) {
                    return '- '.$error;
                }, $sshClient->getErrors());
                $this->JsonResponse([
                    'status' => 0,
                    'message' => implode("\n", $errors)
                ]);
            }
        } catch (\Exception $e) {
            @unlink($tmpname);
            $this->JsonResponse([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }

        $this->JsonResponse([
            'status' => 1,
            'message' => 'Connection successful!'
        ]);
    }
}

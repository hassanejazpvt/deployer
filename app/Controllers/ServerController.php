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
            'verify' => 'POST',
            'export' => 'GET',
            'import' => 'POST'
        ]);
    }

    /**
     * @return void
     */
    public function index() : void
    {
        $servers = array_map(function ($record) {
            $record['identities_only'] = $record['identities_only'] ? 'Yes' : 'No';
            $record['created_at'] = Carbon::parse($record['created_at'])->format('M d, Y - h:i A');
            $record['updated_at'] = Carbon::parse($record['updated_at'])->format('M d, Y - h:i A');
            $record['commands'] = $this->command->getByServerId($record['id']);
            return $record;
        }, $this->server->all());
        $this->JsonResponse(['data' => $servers]);
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
        $this->server->update($request->all(), $request->id);

        $this->JsonResponse([
            'status' => 1,
            'message' => 'Server updated successfully.'
        ]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function store(Request $request) : void
    {
        $this->server->insert($request->all());

        $this->JsonResponse([
            'status' => 1,
            'message' => 'Server created successfully.'
        ]);
    }

    /**
     * @return void
     */
    public function export() : void
    {
        $servers = array_map(function ($server) {
            $server['commands'] = array_map(function ($command) {
                return array_filter($command, function ($key) {
                    return ! in_array($key, [$this->command->getPk(), 'server_id']);
                }, ARRAY_FILTER_USE_KEY);
            }, $this->command->getByServerId($server['id']));

            return array_filter($server, function ($key) {
                return $key != $this->server->getPk();
            }, ARRAY_FILTER_USE_KEY);
            return $server;
        }, $this->server->all());
        $servers['deployerExported'] = sha1('deployer');

        header('Content-disposition: attachment; filename=deployer-export.json');
        header('Content-type: application/json');
        echo json_encode($servers);
        exit();
    }

    public function import(Request $request) : void
    {
        $file = $request->file('file');
        $data = json_decode(file_get_contents($file['tmp_name']), true);
        if (isset($data['deployerExported']) && $data['deployerExported'] == sha1('deployer')) {
            if ($data && is_array($data)) {
                foreach ($data as $server) {
                    if (is_array($server)) {
                        $serverId = $this->server->insert(only($server, $this->server->getColumns()));
                        if (! empty($server['commands'])) {
                            $commands = array_map(function ($command) use ($serverId) {
                                $command['server_id'] = $serverId;
                                return $command;
                            }, $server['commands']);
                            foreach ($commands as $command) {
                                $this->command->insert(only($command, $this->command->getColumns()));
                            }
                        }
                    }
                }
            }
        }
        $this->JsonResponse([
            'status' => 1,
            'message' => 'Collection imported successfully.'
        ]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function delete(Request $request) : void
    {
        $this->server->delete($request->id);
        $this->JsonResponse([
            'status' => 1,
            'message' => 'Server deleted successfully.'
        ]);
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

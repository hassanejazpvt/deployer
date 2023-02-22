<?php

namespace Contrive\Deployer\Controllers;

use Contrive\Deployer\Libs\Request;
use Contrive\Deployer\Libs\SSHClient;
use Contrive\Deployer\Models\Command;
use Contrive\Deployer\Models\Server;

class CommandController extends Controller
{
    private $server;
    private $command;

    public function __construct()
    {
        $this->server = new Server();
        $this->command = new Command();
        $this->ValidateRequestMethods([
            'store' => 'POST',
            'edit' => 'GET',
            'execute' => 'GET',
            'update' => 'POST',
            'delete' => 'POST'
        ]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function store(Request $request) : void
    {
        $data = $request->all();
        $data['sorting'] = $this->command->where(['server_id' => $request->server_id])->max('sorting') + 1;
        $this->command->insert($data);

        $this->JsonResponse([
            'status' => 1,
            'message' => 'Command created successfully.'
        ]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function edit(Request $request) : void
    {
        $command = $this->command->find($request->id);
        include VIEWS_PATH.'/commands/form.php';
        exit();
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function executeAll(Request $request) : void
    {
        $server = $this->server->find($request->serverId);
        $commands = $this->command->getByServerId($request->serverId);
        foreach ($commands as $command) {
            echo "<h1>Executing: {$command['name']}</h1>";
            $this->executeCommand($server, $command);
        }
        exit();
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function execute(Request $request) : void
    {
        $ids = explode(',', $request->id);
        foreach ($ids as $id) {
            $command = $this->command->find($id);
            $server = $this->server->find($command['server_id']);
            echo "<h1>Executing: {$command['name']}</h1>";
            $this->executeCommand($server, $command);
        }
        exit();
    }

    /**
     * @param array $server
     * @param array $command
     *
     * @return void
     */
    private function executeCommand(array $server, array $command) : void
    {
        $sshClient = SSHClient::connect($server);
        if ($sshClient->getErrors()) {
            echo 'Connection failed!';
            die;
        }

        echo '<pre>';
        $sshClient->exec($command['command'], function ($str) {
            echo $str;
            flush();
            ob_flush();
        });
        echo '</pre>';
        echo '<hr>';
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function update(Request $request) : void
    {
        $this->command->update($request->all(), $request->id);

        $this->JsonResponse([
            'status' => 1,
            'message' => 'Command updated successfully.'
        ]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function delete(Request $request) : void
    {
        $this->command->delete($request->id);
        $this->JsonResponse([
            'status' => 1,
            'message' => 'Command deleted successfully.'
        ]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function sort(Request $request) : void
    {
        foreach ($request->sort as $index => $id) {
            $this->command->update(['sorting' => ($index + 1)], $id);
        }
        $this->JsonResponse([
            'status' => 1,
            'message' => 'Sorted successfully.'
        ]);
    }
}

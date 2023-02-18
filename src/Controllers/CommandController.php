<?php

namespace Contrive\Deployer\Controllers;

use Contrive\Deployer\Libs\Request;
use Contrive\Deployer\Models\Command;
use Contrive\Deployer\Models\Ssh;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class CommandController extends Controller
{
    private $ssh;
    private $command;

    public function __construct()
    {
        $this->ssh = new Ssh();
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
        $this->command->insert($request->only($this->command->getColumns()));

        $this->JsonResponse(['status' => 1]);
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
        $ssh = $this->ssh->find($request->sshId);
        $commands = $this->command->getBySshId($request->sshId);
        foreach ($commands as $command) {
            echo "<h1>Executing: {$command['name']}</h1>";
            $this->executeCommand($ssh, $command);
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
        $command = $this->command->find($request->id);
        $ssh = $this->ssh->find($command['ssh_id']);
        $this->executeCommand($ssh, $command);
        exit();
    }

    /**
     * @param array $ssh
     * @param array $command
     *
     * @return void
     */
    private function executeCommand(array $ssh, array $command) : void
    {
        $tmpname = tempnam('tmp', '');
        file_put_contents($tmpname, $ssh['private_key']);
        $key = PublicKeyLoader::load(file_get_contents($tmpname));
        unlink($tmpname);
        $sshClient = new SSH2($ssh['hostname'], $ssh['port']);
        if (! $sshClient->login($ssh['username'], $key)) {
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
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function update(Request $request) : void
    {
        $this->command->update($request->only($this->command->getColumns()), $request->id);

        $this->JsonResponse(['status' => 1]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function delete(Request $request) : void
    {
        $this->command->delete($request->id);
        $this->JsonResponse(['status' => 1]);
    }
}

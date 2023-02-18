<?php

namespace Contrive\Deployer\Controllers;

use Carbon\Carbon;
use Contrive\Deployer\Libs\Request;
use Contrive\Deployer\Models\Command;
use Contrive\Deployer\Models\Ssh;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class ServerController extends Controller
{
    private $ssh;
    private $command;

    public function __construct()
    {
        $this->ssh = new Ssh();
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
        $ssh = array_map(function ($record) {
            $record['identities_only'] = $record['identities_only'] ? 'Yes' : 'No';
            $record['created_at'] = Carbon::parse($record['created_at'])->format('M d, Y - h:i A');
            $record['updated_at'] = Carbon::parse($record['updated_at'])->format('M d, Y - h:i A');
            $record['commands'] = $this->command->getBySshId($record['id']);
            return $record;
        }, $this->ssh->all());
        $this->JsonResponse(['data' => $ssh]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function edit(Request $request) : void
    {
        $ssh = $this->ssh->find($request->id);
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
        $this->ssh->update($request->only($this->ssh->getColumns()), $request->id);

        $this->JsonResponse(['status' => 1]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function store(Request $request) : void
    {
        $this->ssh->insert($request->only($this->ssh->getColumns()));

        $this->JsonResponse(['status' => 1]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function delete(Request $request) : void
    {
        $this->ssh->delete($request->id);
        $this->JsonResponse(['status' => 1]);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function verify(Request $request) : void
    {
        $ssh = $this->ssh->find($request->id);
        $tmpname = tempnam('tmp', '');
        file_put_contents($tmpname, $ssh['private_key']);
        $key = PublicKeyLoader::load(file_get_contents($tmpname));
        unlink($tmpname);
        $sshClient = new SSH2($ssh['hostname'], $ssh['port']);
        if (! $sshClient->login($ssh['username'], $key)) {
            $this->JsonResponse([
                'status' => 0,
                'message' => 'Connection failed!'
            ]);
        }
        $this->JsonResponse([
            'status' => 1,
            'message' => 'Connection successful!'
        ]);
    }
}

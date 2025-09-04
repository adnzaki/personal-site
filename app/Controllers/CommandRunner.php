<?php namespace App\Controllers;

class CommandRunner extends BaseController
{
    public function run()
    {
        $command = $this->request->getPost(['command', 'username', 'password']);
        if ($command['username'] === env('dev_username') && $command['password'] === env('dev_password')) {
            return $this->response->setJSON(command($command['command']));
        } else {
            return $this->response->setJSON(['status' => 'failed', 'message' => 'Invalid username or password']);
        }
    }
}
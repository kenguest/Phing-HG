<?php
require_once 'HgBaseTask.php';
class HgTagTask extends HgBaseTask
{
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setUser($user)
    {
        $this->user = $user;
    }
    public function main()
    {
    }
}

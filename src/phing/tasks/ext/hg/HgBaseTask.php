<?php
require '/home/kguest/vendor/autoload.php';
abstract class HgBaseTask extends Task
{
    private $repository = "";

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function setQuiet($quiet)
    {
        $this->quiet = $quiet;
    }

    public function getQuiet()
    {
        return $this->quiet;
    }

    public function getRepository()
    {
        return $this->repository;
    }

}


?>

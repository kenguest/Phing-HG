<?php
require_once 'HgBaseTask.php';
use Siad007\VersionControl\HG\Factory;

class HgUpdateTask extends HgBaseTask
{
    private $repository = "";

    public function setClean($value)
    {
        $this->clean = $value;
    }
    public function getClean()
    {
        return $this->clean;
    }

    public function setBranch($value)
    {
        $this->branch = $value;
    }
    public function getBranch()
    {
        return $this->branch;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setInsecure($insecure)
    {
        $this->insecure = $insecure;
    }

    public function getInsecure()
    {
        return $this->insecure;
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function main()
    {
        $pull = Factory::getInstance('pull');
        $pull->setInsecure($this->getInsecure());
        $pull->setQuiet($this->getQuiet());

        $cwd = getcwd();

        if ($this->repository === '') {
            $prog = $this->getProject();
            $dir = $prog->getProperty('application.startdir');
        } else {
            $dir = $this->repository;
        }

        chdir($dir);
        try {
            $output = $pull->execute();
            if ($output != '') {
                $this->log($output);
            }
        } catch(Exception $ex) {
            $msg = $ex->getMessage();
            $p = strpos($msg, 'hg returned:');
            if ($p !== false) {
                $msg = substr($msg, $p + 13);
            }
            chdir($cwd);
            throw new BuildException($msg);
        }
        chdir($cwd);
    }
}


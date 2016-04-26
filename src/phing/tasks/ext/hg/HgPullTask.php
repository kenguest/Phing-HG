<?php
require_once 'HgBaseTask.php';
use Siad007\VersionControl\HG\Factory;

class HgPullTask extends HgBaseTask
{
    /**
     * Path to target directory
     * @var string
     */
    private $targetPath;

    private $repository = "";

    /**
     * Set path to source repo
     *
     * @param  string $targetPath Path to repository used as source
     * @return void
     */
    public function setTargetPath($targetPath)
    {
        $this->targetPath = $targetPath;
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function setInsecure($insecure)
    {
        $this->insecure = $insecure;
    }


    public function getInsecure()
    {
        return $this->insecure;
    }


    public function main()
    {
        $clone = Factory::getInstance('pull');
        $clone->setInsecure($this->getInsecure());
        $clone->setQuiet($this->getQuiet());

        $cwd = getcwd();

        if ($this->repository === '') {
            $prog = $this->getProject();
            $dir = $prog->getProperty('application.startdir');
        } else {
            $dir = $this->repository;
        }
        chdir($dir);

        try {
            $output = $clone->execute();
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


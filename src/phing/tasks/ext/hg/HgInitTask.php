<?php
require_once 'HgBaseTask.php';
use Siad007\VersionControl\HG\Factory;

class HgInitTask extends HgBaseTask
{
    /**
     * Path to target directory
     * @var string
     */
    private $targetPath;

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
        $clone = Factory::getInstance('init');
        $msg = sprintf("Initializing");
        $this->log($msg, Project::MSG_INFO);
        $clone->setQuiet($this->getQuiet());
        $prog = $this->getProject();
        $dir = $prog->getProperty('application.startdir');
        $cwd = getcwd();
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


<?php
require_once 'HgBaseTask.php';
use Siad007\VersionControl\HG\Factory;

class HgPushTask extends HgBaseTask
{
    protected $haltonerror = false;

    public function setInsecure($insecure)
    {
        $this->insecure = $insecure;
    }


    public function getInsecure()
    {
        return $this->insecure;
    }

    /**
     * Set haltonerror attribute.
     *
     * @param string $halt 'yes', or '1' to halt.
     *
     * @return void
     */
    public function setHaltonerror($halt)
    {
        $doHalt = false;
        if (strtolower($halt) == 'yes' || $halt == 1) {
            $doHalt = true;
        }
        $this->haltonerror = $doHalt;
    }

    public function main()
    {
        $clone = Factory::getInstance('push');
        $msg = sprintf("Pushing...");
        $this->log($msg, Project::MSG_INFO);
        $clone->setInsecure($this->getInsecure());
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
            if ($this->haltonerror) {
                throw new BuildException($msg);
            }
            $this->log($msg, Project::MSG_ERR);
        }
        chdir($cwd);
    }
}


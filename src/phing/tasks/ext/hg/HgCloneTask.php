<?php
require_once 'HgBaseTask.php';
use Siad007\VersionControl\HG\Factory;

class HgCloneTask extends HgBaseTask
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

    public function getTargetPath()
    {
        return $this->targetPath;
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
        $clone = Factory::getInstance('clone');
        $repository = $this->getRepository();
        if ($repository == '') {
            throw new BuildException('"repository" is a required parameter');
        }
        $target = $this->getTargetPath();
        if ($target == '') {
            throw new BuildException('"targetPath" is a required parameter');
        }
        // Is target path empty?
        if (file_exists($target)) {
            $files = scandir($target);
            if (is_array($files) && count($files) > 2) {
                throw new BuildException("Directory \"$target\" is not empty");
            }
        }
        $msg = sprintf("hg cloning %s to %s", $repository, $target);
        $this->log($msg, Project::MSG_INFO);
        $clone->setSource($repository);
        $clone->setDestination($target);
        $clone->setInsecure($this->getInsecure());
        $clone->setQuiet($this->getQuiet());
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
            throw new BuildException($msg);
        }
    }
}

<?php
require_once 'HgBaseTask.php';
use Siad007\VersionControl\HG\Factory;
class HgLogTask extends HgBaseTask
{
    protected $cwd = null;

    protected $maxCount = null;

    protected $format = null;

    protected $rev = '';

    protected $repository = '';

    /**
     * @param $count
     */
    public function setMaxcount($count)
    {
        $this->maxCount = (int) $count;
    }

    /**
     * @return int
     */
    public function getMaxcount()
    {
        return $this->maxCount;
    }

    /**
     * @param $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param $prop
     */
    public function setOutputProperty($prop)
    {
        $this->outputProperty = $prop;
    }

    public function setCwd($cwd)
    {
        $this->cwd = $cwd;
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function setRev($revision)
    {
        $this->rev = $revision;
    }

    public function main()
    {
        $clone = Factory::getInstance('log');
        // Is target path empty?
        if (file_exists($target)) {
            $files = scandir($target);
            if (is_array($files) && count($files) > 2) {
                throw new BuildException("Directory \"$target\" is not empty");
            }
        }

        if ($this->repository === '') {
            $clone->setCwd($this->cwd);
        } else {
            $clone->setCwd($this->repository);
        }

        if (!is_null($this->maxCount)) {
            $clone->setLimit("" . $this->maxCount);
        }

        if (!is_null($this->format)) {
            $clone->setTemplate($this->format);
        }

        if ($this->rev !== '') {
            $clone->setRev($this->rev);
        }

        try {
            $output = $clone->execute();
            if (!is_null($this->outputProperty)) {
                $this->project->setProperty($this->outputProperty, $output);
            } else {
                if ($output != '') {
                    $this->log(PHP_EOL . $output);
                }
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

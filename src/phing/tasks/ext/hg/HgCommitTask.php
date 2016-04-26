<?php
require_once 'HgBaseTask.php';
use Siad007\VersionControl\HG\Factory;

class HgCommitTask extends HgBaseTask
{
    /**
     * message
     *
     * @var string
     */
    protected $message = null;
    protected $user = null;
    protected $repository = '';

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
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

        $message = $this->getMessage();
        if ($message == '') {
            throw new BuildException('"message" is a required parameter');
        }

        $user = $this->getUser();

        $clone = Factory::getInstance('commit');
        $msg = sprintf("Commit: '$message'");
        $this->log($msg, Project::MSG_INFO);
        //$clone->setInsecure($this->getInsecure());
        $clone->setQuiet($this->getQuiet());
        $clone->setMessage($message);

        if ($user !== null) {
            $clone->setUser($user);
            $this->log("Commit: user = '$user'", Project::MSG_VERBOSE);
        }

        if ($this->repository == '') {
            $prog = $this->getProject();
            $dir = $prog->getProperty('application.startdir');
        } else {
            $dir = $this->repository;
        }
        $this->log("DIR:" . $dir, Project::MSG_INFO);
        $this->log("REPO: " . $this->repository, Project::MSG_INFO);
        $cwd = getcwd();
        return;
        chdir($dir);

        try {
            $this->log("Committing...", Project::MSG_INFO);
            $output = $clone->execute();
            if ($output != '') {
                $this->log($output);
            }
        } catch(Exception $ex) {
            $msg = $ex->getMessage();
            $this->log("Exception: $msg", Project::MSG_INFO);
            $p = strpos($msg, 'hg returned:');
            if ($p !== false) {
                $msg = substr($msg, $p + 13);
            }
            throw new BuildException($msg);
        }
    }
}


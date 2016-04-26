<?php
require_once 'HgBaseTask.php';
class HgArchiveTask extends HgBaseTask
{
    private $revision = null;
    private $destination = null;
    public function setRevision($revision)
    {
        $this->revision = $revision;
    }

    public function setDestination($destination)
    {
        $this->destination = $destination;
    }
    public function main()
    {
    }
}


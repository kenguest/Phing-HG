<?php
/**
 * Utilise Mercurial from within Phing.
 *
 * PHP Version 5.4
 *
 * @category Tasks
 * @package  phing.tasks.ext
 * @author   Ken Guest <ken@linux.ie>
 * @license  LGPL (see http://www.gnu.org/licenses/lgpl.html)
 * @link     https://github.com/kenguest/Phing-HG
 */

/**
 * Pull in Base class.
 */
require_once 'HgBaseTask.php';

/**
 * Integration/Wrapper for hg archive
 *
 * @category Tasks
 * @package  phing.tasks.ext.hg
 * @author   Ken Guest <ken@linux.ie>
 * @license  LGPL (see http://www.gnu.org/licenses/lgpl.html)
 * @link     HgArchiveTask.php
 */
class HgArchiveTask extends HgBaseTask
{
    /**
     * Which revision to archive.
     *
     * @var string
     */
    protected $revision = '';

    /**
     * Name of destination archive file.
     *
     * @var string
     */
    protected $destination = null;

    /**
     * Set revision attribute
     *
     * @param string $revision Revision
     *
     * @return void
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
    }

    /**
     * Set Destination attribute
     *
     * @param string $destination Destination filename
     *
     * @return void
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * The main entry point for the task.
     *
     * @return void
     */
    public function main()
    {
        if ($this->revision !== '') {
            $clone->setRev($this->revision);
        }
    }
}


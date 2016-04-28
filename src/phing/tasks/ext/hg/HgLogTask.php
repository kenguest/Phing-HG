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
 * Pull in and use https://packagist.org/packages/siad007/versioncontrol_hg
 */
use Siad007\VersionControl\HG\Factory;

/**
 * Integration/Wrapper for hg log
 *
 * @category Tasks
 * @package  phing.tasks.ext.hg
 * @author   Ken Guest <ken@linux.ie>
 * @license  LGPL (see http://www.gnu.org/licenses/lgpl.html)
 * @link     HgLogTask.php
 */
class HgLogTask extends HgBaseTask
{
    /**
     * Current working directory
     *
     * @var string
     */
    protected $cwd = null;

    /**
     * Maximum number of changes to get. See --limit
     *
     * @var int
     */
    protected $maxCount = null;

    /**
     * Commit format/template. See --template
     *
     * @var string
     */
    protected $format = null;

    /**
     * Revision
     *
     * @var string
     */
    protected $revision = '';

    /**
     * Propery name to set the output to.
     *
     * @var string
     */
    protected $outputProperty = null;

    /**
     * Set maximum number of changes to get.
     *
     * @param int $count Maximum number of log entries to retrieve.
     *
     * @return void
     */
    public function setMaxcount($count)
    {
        $this->maxCount = (int) $count;
    }

    /**
     * Retrieve max count of commits to limit to.
     *
     * @return int
     */
    public function getMaxcount()
    {
        return $this->maxCount;
    }

    /**
     * Template/log format.
     *
     * @param string $format Log format
     *
     * @return string
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Get the log format/template
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Property to assign output to.
     *
     * @param string $property name of property to assign output to.
     *
     * @return void
     */
    public function setOutputProperty($property)
    {
        $this->outputProperty = $property;
    }

    /**
     * Set current working directory.
     *
     * @param string $cwd current working directory
     *
     * @return void
     */
    public function setCwd($cwd)
    {
        $this->cwd = $cwd;
    }

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
     * Main entry point for this task
     *
     * @return void
     */
    public function main()
    {
        $clone = Factory::getInstance('log');

        if ($this->repository === '') {
            $clone->setCwd($this->cwd);
        } else {
            $clone->setCwd($this->repository);
        }

        if ($this->maxCount !== null) {
            $clone->setLimit('' . $this->maxCount);
        }

        if ($this->format !== null) {
            $clone->setTemplate($this->format);
        }

        if ($this->revision !== '') {
            $clone->setRev($this->revision);
        }

        try {
            $output = $clone->execute();
            if ($this->outputProperty !== null) {
                $this->project->setProperty($this->outputProperty, $output);
            } else {
                if ($output !== '') {
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

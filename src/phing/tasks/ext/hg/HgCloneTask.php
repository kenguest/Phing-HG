<?php
/**
 * Utilise Mercurial from within Phing.
 *
 * PHP Version 5.4
 *
 * @category Tasks
 * @package  phing.tasks.ext
 * @author   Ken Guest <kguest@php.net>
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
 * Integration/Wrapper for hg clone
 *
 * @category Tasks
 * @package  phing.tasks.ext.hg
 * @author   Ken Guest <kguest@php.net>
 * @license  LGPL (see http://www.gnu.org/licenses/lgpl.html)
 * @link     HgCloneTask.php
 */
class HgCloneTask extends HgBaseTask
{
    /**
     * Path to target directory
     *
     * @var string
     */
    protected $targetPath;

    /**
     * Insecure argument
     *
     * @var bool
     */
    protected $insecure = false;

    /**
     * Set path to source repo
     *
     * @param string $targetPath Path to repository used as source
     *
     * @return void
     */
    public function setTargetPath($targetPath)
    {
        $this->targetPath = $targetPath;
    }

    /**
     * Get path to the target directory/repo.
     *
     * @return string
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }

    /**
     * Set insecure attribute
     *
     * @param string $insecure 'yes', etc.
     *
     * @return void
     */
    public function setInsecure($insecure)
    {
        $this->insecure = StringHelper::booleanValue($insecure);
    }

    /**
     * Get 'insecure' attribute value.
     *
     * @return bool
     */
    public function getInsecure()
    {
        return $this->insecure;
    }

    /**
     * The main entry point.
     *
     * @return void
     * @throws BuildException
     */
    public function main()
    {
        $clone = Factory::getInstance('clone');
        $repository = $this->getRepository();
        if ($repository === '') {
            throw new BuildException('"repository" is a required parameter');
        }
        $target = $this->getTargetPath();
        if ($target === '') {
            throw new BuildException('"targetPath" is a required parameter');
        }
        // Is target path empty?
        if (file_exists($target)) {
            $files = scandir($target);
            if (is_array($files) && count($files) > 2) {
                throw new BuildException("Directory \"$target\" is not empty");
            }
        }
        $msg = sprintf('hg cloning %s to %s', $repository, $target);
        $this->log($msg, Project::MSG_INFO);
        $clone->setSource($repository);
        $clone->setDestination($target);
        $clone->setInsecure($this->getInsecure());
        $clone->setQuiet($this->getQuiet());
        try {
            $this->log("Executing: " . $clone->asString(), Project::MSG_INFO);
            $output = $clone->execute();
            if ($output !== '') {
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

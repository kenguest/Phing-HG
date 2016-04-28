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
 * Integration/Wrapper for hg update
 *
 * @category Tasks
 * @package  phing.tasks.ext.hg
 * @author   Ken Guest <ken@linux.ie>
 * @license  LGPL (see http://www.gnu.org/licenses/lgpl.html)
 * @link     HgUpdateTask.php
 */
class HgUpdateTask extends HgBaseTask
{
    /**
     * User argument
     *
     * @var string
     */
    protected $user = '';

    /**
     * Branch argument
     *
     * Defaults to 'default'
     *
     * @var string
     */
    protected $branch = 'default';

    /**
     * Clean argument
     *
     * @var string
     */
    protected $clean = '';

    /**
     * Set 'clean' attribute.
     *
     * @param string $value Clean attribute value
     *
     * @return void
     */
    public function setClean($value)
    {
        $this->clean = $value;
    }

    /**
     * Get 'clean' attribute.
     *
     * @return string
     */
    public function getClean()
    {
        return $this->clean;
    }

    /**
     * Set branch attribute
     *
     * @param string $value Branch name
     *
     * @return void
     */
    public function setBranch($value)
    {
        $this->branch = $value;
    }

    /**
     * Get branch attribute
     *
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * The main entry point method.
     *
     * @throws BuildException
     * @return void
     */
    public function main()
    {
        $pull = Factory::getInstance('pull');
        $pull->setInsecure($this->getInsecure());
        $pull->setQuiet($this->getQuiet());

        $cwd = getcwd();

        if ($this->repository === '') {
            $prog = $this->getProject();
            $dir = $prog->getProperty('application.startdir');
        } else {
            $dir = $this->repository;
        }

        chdir($dir);
        try {
            $output = $pull->execute();
            if ($output !== '') {
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


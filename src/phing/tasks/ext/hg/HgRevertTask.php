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
 * Integration/Wrapper for hg revert
 *
 * @category Tasks
 * @package  phing.tasks.ext.hg
 * @author   Ken Guest <ken@linux.ie>
 * @license  LGPL (see http://www.gnu.org/licenses/lgpl.html)
 * @link     HgRevertTask.php
 */
class HgRevertTask extends HgBaseTask
{
    /**
     * Name of file to be reverted.
     *
     * @var string
     */
    protected $file = null;

    /**
     * Set filename to be reverted.
     *
     * @param string $file Filename
     *
     * @return void
     */
    public function setFile($file)
    {
        $this->file = $file;

    }
    /**
     * The main entry point method.
     *
     * @throws BuildException
     * @return void
     */
    public function main()
    {

    }
}

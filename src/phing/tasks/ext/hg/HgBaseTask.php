<?php
require 'vendor/autoload.php';

/**
 * Base task for integrating phing and mercurial.
 *
 * @category Tasks
 * @package  phing.tasks.ext.hg
 * @author   Ken Guest <ken@linux.ie>
 * @license  LGPL (see http://www.gnu.org/licenses/lgpl.html)
 * @link     HgBaseTask.php
 */
abstract class HgBaseTask extends Task
{
    /**
     * Insecure argument
     *
     * @var string
     */
    protected $insecure = '';

    /**
     * Repository directory
     *
     * @var string
     */
    protected $repository = '';

    /**
     * Whether to be quiet... --quiet argument.
     *
     * @var string
     */
    protected $quiet = '';

    /**
     * Set repository attribute
     *
     * @param string $repository Repository
     *
     * @return void
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }


    /**
     * Set the quiet attribute --quiet
     *
     * @param string $quiet
     *
     * @return void
     */
    public function setQuiet($quiet)
    {
        $this->quiet = $quiet;
    }

    /**
     * Get the quiet attribute value.
     *
     * @return string
     */
    public function getQuiet()
    {
        return $this->quiet;
    }

    /**
     * Get Repository attribute/directory.
     *
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
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
        $this->insecure = $insecure;
    }

    /**
     * Get 'insecure' attribute value. (--insecure or null)
     *
     * @return string
     */
    public function getInsecure()
    {
        return $this->insecure;
    }

    /**
     * Set user attribute
     *
     * @param string $user username/email address.
     *
     * @return void
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get username attribute.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

}

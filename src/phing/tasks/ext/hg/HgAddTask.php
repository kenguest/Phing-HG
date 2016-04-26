<?php
require_once 'HgBaseTask.php';
use Siad007\VersionControl\HG\Factory;

class HgAddTask extends HgBaseTask
{
    /**
     * message
     *
     * @var string
     */
    protected $filesets = [];
    protected $ignoreFile = [];

    public function setInsecure($insecure)
    {
        $this->insecure = $insecure;
    }

    public function getInsecure()
    {
        return $this->insecure;
    }

    /**
     * Adds a fileset of files to add to the repository.
     *
     * @param FileSet $fs Set of files to add to the repository.
     *
     * @return void
     */
    public function addFileSet(FileSet $fileset)
    {
        $this->filesets[] = $fileset;
    }

    public function main()
    {
        $clone = Factory::getInstance('add');
        //$clone->setInsecure($this->getInsecure());
        $clone->setQuiet($this->getQuiet());

        $project = $this->getProject();
        $dir = $project->getProperty('application.startdir');
        $cwd = getcwd();
        //chdir($dir);

        if (file_exists(".hgignore")) {
            $this->loadIgnoreFile();
        }
        if (count($this->filesets)) {
            $this->log("filesets set", Project::MSG_INFO);
            foreach ($this->filesets as $fs) {
                $ds = $fs->getDirectoryScanner($project);
                $fromDir = $fs->getDir($project);
                $srcDirs = $ds->getIncludedDirectories();
                $srcFiles = $ds->getIncludedFiles();
                foreach ($srcFiles as $file) {
                    $relPath = $fromDir . DIRECTORY_SEPARATOR . $file;
                    $msg .= $relPath . "\n";
                    if (strpos($relPath, "./.hg") === false) {
                        if (!$this->fileIsIgnored($relPath)) {
                            $clone->addFile($relPath);
                            //var_dump ("$relPath is added.");
                        } else {
                            //var_dump ("$relPath is ignored.");
                        }
                    }
                }
            }
        }

        // No files added...
        /*
        if (empty($clone->getFile())) {
            var_dump ("No files added.");
            return;
        }
        */

        try {
            $this->log("Adding: command: $clone", Project::MSG_INFO);
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
            var_dump ($ex);
            throw new BuildException($msg);
        }
    }

    public function loadIgnoreFile()
    {
        $ignores = [];
        $lines = file(".hgignore");
        foreach($lines as $line) {
            $nline =  trim($line);
            $nline = preg_replace('/\/\*$/','/', $nline);
            $ignores[] = $nline;
        }
        $this->ignoreFile = $ignores;
    }

    public function fileIsIgnored($file)
    {
        $line = $this->ignoreFile[0];
        $mode = 'regexp';
        if (preg_match('#^syntax\s*:\s*(glob|regexp)$#', $line, $matches)) {
            if ($matches[1] == 'glob') {
                $mode = 'glob';
            }
        }
        if ($mode == 'glob') {
            $ignored = $this->ignoredByGlob($file);
        } elseif ($mode == 'regexp') {
            $ignored = $this->ignoredByRegex($file);
        }
        return $ignored;
    }

    public function ignoredByGlob($file)
    {
        $lfile = $file;
        if (strpos($lfile, "./") === 0) {
            $lfile = substr($lfile, 2);
        }
        foreach($this->ignoreFile as $line) {
            if (strpos($lfile, $line) === 0) {
                return true;
            }
            /*
            var_dump ("line: $line");
            var_dump ("file: $lfile");
            */
        }
        return false;
    }

    public function ignoredByRegex($file)
    {
        return true;
    }
}


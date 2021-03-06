<?php namespace Pingpong\Modules\Publishing;

use Illuminate\Console\Command;
use Pingpong\Modules\Contracts\PublisherInterface;
use Pingpong\Modules\Module;
use Pingpong\Modules\Repository;

abstract class Publisher implements PublisherInterface {

    /**
     * The name of module will used.
     *
     * @var string
     */
    protected $module;

    /**
     * The modules repository instance.
     *
     * @var \Pingpong\Modules\Repository
     */
    protected $repository;

    /**
     * The laravel console instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $console;

    /**
     * The success message will displayed at console.
     *
     * @var string
     */
    protected $success;

    /**
     * The error message will displayed at console.
     *
     * @var string
     */
    protected $error = '';

    /**
     * The constructor.
     *
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Get module instance.
     *
     * @return \Pingpong\Modules\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set modules repository instance.
     *
     * @param \Pingpong\Modules\Repository $repository
     * @return $this
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Get modules repository instance.
     *
     * @return \Pingpong\Modules\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set console instance.
     *
     * @param \Illuminate\Console\Command $console
     * @return $this
     */
    public function setConsole(Command $console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Get console instance.
     *
     * @return \Illuminate\Console\Command
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Get laravel filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->repository->getFiles();
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    abstract public function getDestinationPath();

    /**
     * Get source path.
     *
     * @return string
     */
    abstract public function getSourcePath();

    /**
     * Publish something.
     *
     * @return void
     */
    public function publish()
    {
        if ( ! $this->console instanceof Command)
        {
            $message = "The 'console' property must instance of \\Illuminate\\Console\\Command.";

            throw new \RuntimeException($message);
        }

        if ( ! $this->getFilesystem()->isDirectory($sourcePath = $this->getSourcePath()))
        {
            return;
        }

        if ( ! $this->getFilesystem()->isDirectory($destinationPath = $this->getDestinationPath()))
        {
            $this->getFilesystem()->makeDirectory($destinationPath, 0775, true);
        }

        if ($this->getFilesystem()->copyDirectory($sourcePath, $destinationPath))
        {
            $this->console->line("<info>Published</info>: {$this->module->getStudlyName()}"); 
        }
        else
        {
            $this->console->error($this->error);
        }
    }

}

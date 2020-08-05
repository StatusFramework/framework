<?php
namespace Status\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateController
 * @package Status\Command
 */
class CreateController extends Command
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $controller;
    /**
     * @var string
     */
    private $class;
    /**
     * @var string
     */
    private $namespace = 'App\\Controllers';

    /**
     *
     */
    protected function configure()
    {
        $this->path = dirname(__DIR__,6).'/app/controllers';

        $this
            ->setName("create:controller")
            ->addArgument("controller", InputArgument::REQUIRED)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(!$this->checkApp()){
            $output->write(">>>\t[error]: directory \"app/controllers\" is missing");
            exit;
        }

        $this->setController($input->getArgument('controller'));

        if($this->checkController()){
            $output->write(">>>\t[error]: controller already exists");
            exit;
        }

        $this->createController();
        $output->write(">>>\t[status]: controller created\n\r");
    }

    /**
     * @return bool
     */
    private function checkApp()
    {
        return (file_exists($this->path)) ? true : false;
    }

    /**
     * @param string $controller
     */
    private function setController(string $controller)
    {
        $this->controller = str_replace('\\','/', $controller);
    }

    /**
     * @return bool
     */
    private function checkController()
    {
        return (file_exists($this->path.'/'.$this->controller.'.php')) ? true : false;
    }

    private function createController()
    {
        $this->createDir();
        file_put_contents($this->path.'/'.$this->controller.'.php', $this->getData());
    }

    private function createDir()
    {
        $dir = explode("/", $this->controller);

        $path = $this->path;

        for ($i = 0; $i < count($dir) - 1; $i++)
        {
            $this->setNamespace($dir[$i]);
            $path .= "/".$dir[$i];

            if (file_exists($path))
            {
                continue;
            }

            @mkdir($path, 0777);
        }

        $this->setClass($dir[$i]);
    }
    /**
     * @param string $class
     */
    private function setNamespace(string $dir)
    {
        if(!empty($dir))
        {
            $this->namespace .= '\\'.$dir;
        }
    }

    /**
     * @param string $class
     */
    private function setClass(string $class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    private function getData()
    {
        return <<<HEREDATA
        <?php  
        namespace {$this->namespace};\n
        /**
         * Class {$this->class}
         * @package {$this->namespace}
         */
        class {$this->class}\n{\n\n}
        HEREDATA;
    }
}
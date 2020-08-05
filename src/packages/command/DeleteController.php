<?php
namespace Status\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteController
 * @package Status\Command
 */
class DeleteController extends Command
{
    /**
     * @var string
     */
    private $path;

    /**
     *
     */
    protected function configure()
    {
        $this->path = dirname(__DIR__,6).'/app/controllers';

        $this
            ->setName("delete:controller")
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

        if(!$this->checkController()){
            $output->write(">>>\t[error]: controller not found");
            exit;
        }

        $this->deleteController();
        $output->write(">>>\t[status]: controller deleted");
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
    private function checkApp()
    {
        return (file_exists($this->path)) ? true : false;
    }

    /**
     * @return bool
     */
    private function checkController()
    {
        return (file_exists($this->path.'/'.$this->controller.'.php')) ? true : false;
    }

    /**
     *
     */
    private  function deleteController()
    {
        unlink($this->path.'/'.$this->controller.'.php');
    }
}
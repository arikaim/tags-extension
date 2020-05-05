<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
 */
namespace Arikaim\Extensions\Tags\Console;

use Arikaim\Core\Console\ConsoleCommand;
use Arikaim\Core\Arikaim;

/**
 * Translate tags command
 */
class TranslateTags extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('tags:translate')->setDescription('Translate tags.'); 
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function executeCommand($input, $output)
    {       
        $this->style->writeLn('');
        $this->style->writeLn('Translate tags');
        $this->style->writeLn('');
        $hasPackage = Arikaim::packages()->create('extensions')->hasPackage('translations');
        if ($hasPackage == false) {
            $this->showError('Translations extension not installed!');
            return;
        }

        $job = Arikaim::queue()->create("translateTags");
        $result = $job->execute();
        echo "res:" . $result;
        
        $this->showCompleted();
    }
}

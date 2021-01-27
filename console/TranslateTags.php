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
use Arikaim\Core\Console\ConsoleHelper;

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
        $this->addOptionalArgument('language','Language code');
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
        $this->showTitle();

        $language = $input->getArgument('language');
        if (empty($language) == true) {
            $language = Arikaim::options()->get('tags.job.translate.language');
        }

        $job = Arikaim::queue()->create("translateTags");

        $this->writeFieldLn('Language',$language);

        $job = Arikaim::queue()->executeJob($job,function($title) {
            $this->writeLn(ConsoleHelper::checkMark() . ' ' . $title);
        },function($error) {
            $this->writeLn(ConsoleHelper::errorMark() . ' ' . $error);
        });
     
        if ($job->hasSuccess() == false) {
            $this->showErrors($job->getErrors());
            return;
        } 
        
        $this->showCompleted();
    }
}

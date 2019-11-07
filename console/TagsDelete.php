<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2017-2019 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
 */
namespace Arikaim\Extensions\Tags\Console;

use Arikaim\Core\System\Console\ConsoleCommand;
use Arikaim\Core\Db\Model;

/**
 * Delete tags command
 */
class TagsDelete extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('tags:delete')->setDescription('Delete tags.'); 
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
        $tags = Model::Tags('tags')->all();
        $relations = Model::TagsRelations('tags');

        $this->style->writeLn('Total tags: ' . $tags->count());
        $this->style->writeLn('');

        $deleted = 0;
        foreach ($tags as $item) {
            $this->style->writeLn('');
            $this->style->writeLn('Tag: ' . $item->translation('en')->word );    
            $count = $item->translations()->count();
            $rows = $relations->getRows($item->id);

            if ($count == 0) {
                $this->style->writeLn('delete ...');
                $item->remove($item->id);
                $deleted++;
            }
            
            if ($rows->count() == 0) {               
                $this->style->writeLn('No relations delete tag ...');
                $item->remove($item->id);
                $deleted++;
            }
        }
        $this->style->writeLn('Deleted tags: ' . $deleted);
        $this->style->writeLn('');
        
        $this->showCompleted();
    }
}

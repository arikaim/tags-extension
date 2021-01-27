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
    protected function configure(): void
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
        $this->showTitle();
       
        $tags = Model::Tags('tags')->all();
        $relations = Model::TagsRelations('tags');

        $this->writeFieldLn('Total',$tags->count());

        $deleted = 0;
        foreach ($tags as $item) {           
            $count = $item->translations()->count();
            $rows = $relations->getRows($item->id);

            if ($count == 0) {             
                $item->remove($item->id);
                $deleted++;
            }
            
            if ($rows->count() == 0) {                              
                $item->remove($item->id);
                $deleted++;
            }
        }
        $this->writeFieldLn('Deleted',$deleted);
        
        $this->showCompleted();
    }
}

<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Tags\Jobs;

use Arikaim\Core\Queue\Jobs\CronJob;
use Arikaim\Core\Arikaim;
use Arikaim\Core\Db\Model;
use Arikaim\Core\Utils\Factory;
use Arikaim\Extensions\Tags\Classes\Tags;
use Arikaim\Core\Collection\Properties;

use Arikaim\Core\Interfaces\Job\RecurringJobInterface;
use Arikaim\Core\Interfaces\Job\JobInterface;
use Arikaim\Core\Interfaces\Job\JobLogInterface;
use Arikaim\Core\Interfaces\Job\JobProgressInterface;
use Arikaim\Core\Interfaces\Job\SaveJobConfigInterface;
use Arikaim\Core\Interfaces\ConfigPropertiesInterface;

use Arikaim\Core\Queue\Traits\JobLog;
use Arikaim\Core\Queue\Traits\JobProgress;
use Arikaim\Core\Collection\Traits\ConfigProperties;

/**
 * Translate tags cron job
 */
class TranslateTagsJob extends CronJob implements 
    RecurringJobInterface,
    JobInterface, 
    JobLogInterface, 
    ConfigPropertiesInterface,
    SaveJobConfigInterface,
    JobProgressInterface
{
    use 
        JobLog,
        JobProgress,
        ConfigProperties;

    /**
     * Constructor
     *
     * @param string|null $extension
     * @param string|null $name
     * @param array $params
     */
    public function __construct(?string $extension = null, ?string $name = null, array $params = [])
    {
        parent::__construct($extension,$name,$params);
        
        $this->runEveryMinute(10);
    }

    /**
     * Run job
     *
     * @return integer
     */
    public function execute()
    {
        $model = Model::Tags('tags');
        $config = $this->getConfigProperties();  
        $language = (string)$config->getValue('language');
        if (empty($language) == true) {
            $this->setLogMessage('Tag translation error, language not set.');
            return false;
        }
        $maxTranslations = (int)$config->getValue('max_translations');
        $lastId = (int)$config->getValue('current_tag_id');      
        if ($lastId >= $model->latest('id')->first()->id) {
            // reset last Id
            $lastId = 0;
        }
             
        $translated = 0;
        $transalte = Factory::createInstance('Classes\\Translations',[],'translations');
        if (empty($transalte) == true) {
            $this->setLogMessage('Tag translation error, translation extension not instaled.');
            return false;
        }

        $tags = $model->where('id','>',$lastId)->take($maxTranslations)->get();
              
        foreach ($tags as $tag) {     
            $result = Tags::translate($tag,$language,$transalte);
            if ($result === false) {
                $this->jobProgressError('Error translating tag ' . $tag->word);    
                $this->setLogMessage('Error translating tag ' . $tag->word); 
            } else {
                $this->jobProgress($tag->word);
                $translated++;
            }           
        }

        if ($translated > 0) {
            $this->setLogMessage("Translated $translated tags.");
        }       
        // save config  
        $this->configProperties->setPropertyValue('current_tag_id',$tag->id);

        return $translated;
    }

     /**
     * Init config properties
     *
     * @param Properties $properties
     * @return void
     */
    public function initConfigProperties(Properties $properties): void
    {
        $properties->property('language',function($property) {
            $property
                ->title('Translate to Language')
                ->description('language')
                ->type('language-dropdown')
                ->required(true)
                ->readonly(false)            
                ->default('en');
        });     
        $properties->property('max_translations',function($property) {
            $property
                ->title('Max tags translations')
                ->description('Maximum tags translations per job run')
                ->type('number')
                ->required(true)
                ->readonly(false)            
                ->default(10);
        });   
        $properties->property('current_tag_id',function($property) {
            $property
                ->title('Current Tag Id')
                ->description('Current tag Id.')
                ->type('number')
                ->required(true)
                ->readonly(false)            
                ->default(0);
        });         
    }
}

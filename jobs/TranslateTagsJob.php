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

use Arikaim\Core\Interfaces\Job\RecuringJobInterface;
use Arikaim\Core\Interfaces\Job\JobInterface;

/**
 * Translate tags cron job
 */
class TranslateTagsJob extends CronJob implements RecuringJobInterface,JobInterface
{
    /**
     * Constructor
     *
     * @param string|null $extension
     * @param string|null $name
     * @param integer $priority
     */
    public function __construct($extension = null, $name = null, $priority = 0)
    {
        parent::__construct($extension,$name,$priority);
        
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
        $language = Arikaim::options()->get('tags.job.translate.language');
        if (empty($language) == true) {
            return false;
        }

        $lastId = (int)Arikaim::options()->get('tags.job.translate.last.id',0);
        if ($lastId >= $model->latest('id')->first()->id) {
            // reset last Id
            Arikaim::options()->set('tags.job.translate.last.id',0);
            $lastId = 0;
        }

        $hasPackage = Arikaim::packages()->create('extensions')->hasPackage('translations');
        if ($hasPackage == false) {
            return false;
        }
        $maxTranslations = 10;
        $createdTranslations = 0;
        $transalte = Factory::createController(Arikaim::getContainer(),'TranslationsControlPanel','translations');
    
        $tags = $model->where('id','>',$lastId)->take($maxTranslations)->get();
        foreach ($tags as $tag) {          
            $translation = $tag->translation($language);
            if ($translation === false) {
                // get english translation
                $defaultTranslation = $tag->translation('en');
                if ($defaultTranslation !== false) { 
                    $translatedFields = $transalte->translateFields('word',$defaultTranslation->toArray(),$language);

                    if ($model->hasTag($translatedFields['word']) == false) {
                        if ($defaultTranslation->word != $translatedFields['word']) {
                            $tag->saveTranslation($translatedFields,$language);
                            $createdTranslations++;                      
                        }     
                    }              
                }
            }
        }

        Arikaim::options()->set('tags.job.translate.last.id',$tag->id);
        Arikaim::logger()->info("Translated $createdTranslations tags to language '" . $language . "'.");  

        return $createdTranslations;
    }
}

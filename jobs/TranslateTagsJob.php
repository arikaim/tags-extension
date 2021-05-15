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

use Arikaim\Core\Interfaces\Job\RecurringJobInterface;
use Arikaim\Core\Interfaces\Job\JobInterface;
use Arikaim\Core\Interfaces\Job\JobLogInterface;
use Arikaim\Core\Interfaces\Job\JobProgressInterface;

use Arikaim\Core\Queue\Traits\JobLog;
use Arikaim\Core\Queue\Traits\JobProgress;

/**
 * Translate tags cron job
 */
class TranslateTagsJob extends CronJob implements 
    RecurringJobInterface,
    JobInterface, 
    JobLogInterface, 
    JobProgressInterface
{
    use 
        JobLog,
        JobProgress;

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
       
        $maxTranslations = 10;
        $translated = 0;
        $transalte = Factory::createInstance('Classes\\Translations',[],'translations');
        if (empty($transalte) == true) {
            return false;
        }

        $tags = $model->where('id','>',$lastId)->take($maxTranslations)->get();
        foreach ($tags as $tag) {              
            $result = Tags::translate($tag,$language,$transalte);
            if ($result === false) {
                $this->jobProgressError('Error translating tag ' . $tag->word);     
            } else {
                $this->jobProgress($tag->word);
                $translated++;
            }           
        }

        Arikaim::options()->set('tags.job.translate.last.id',$tag->id);
      
        return $translated;
    }
}

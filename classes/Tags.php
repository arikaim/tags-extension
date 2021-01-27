<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Tags\Classes;

/**
 * Tags  
*/
class Tags
{
    /**
     * Translate category
     *
     * @param object $model
     * @param string $language
     * @param object $transalte    
     * @return bool
     */
    public static function translate($model, string $language, $transalte): bool 
    {
        $translation = $model->translation($language);
        if ($translation !== false) {
            // translation exists
            return false;
        }

        // get english translation
        $defaultTranslation = $model->translation('en');
        if ($defaultTranslation === false) { 
            // missing default translation
            return false;
        }

        $translated = $transalte->traslateArray('word',$defaultTranslation->toArray(),$language);  
        if ($translated === false) {
            // error translation
            return false;
        }

        if ($model->hasTag($translated['word']) == true) {
            return false;
        }

        if ($defaultTranslation->word != $translated['word']) {
            $result = $model->saveTranslation($translated,$language);    
            return ($result !== false);                          
        }     

        return false;                                                                                                                        
    }
}

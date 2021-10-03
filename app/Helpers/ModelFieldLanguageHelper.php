<?php
namespace App\Helpers;

trait ModelFieldLanguageHelper
{
    public function getFieldByLanguage($field)
    {
        $fieldName = $field . '_' . \App::getLocale();

        if ($this->$fieldName != '') {
            return $this->$fieldName;
        }

        $languages = ['en', 'it', 'es'];
        foreach ($languages as $lang) {
            $fieldName = $field . '_' . $lang;

            if($this->$fieldName != '') {
                return $this->$fieldName;
            }
        }
    }
}

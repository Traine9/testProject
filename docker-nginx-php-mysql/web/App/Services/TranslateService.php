<?php


namespace App\Services;

class TranslateService
{
    /**
     * Get locale from session
     *
     * @return string
     */
    public function getLocale()
    {
        $lang = @$_SESSION['Language'];
        $localeArray = [
            'ru' => 'ru_RU',
            'en' => 'en_US',
        ];
        if (!isset($localeArray[$lang])) {
            $lang = 'ru'; //default lang
        }
        $locale = $localeArray[$lang];

        return $locale;
    }

    /**
     * Get translate by key
     *
     * @param $key
     * @return string
     */
    public function getTranslateSecure($key)
    {
        if (!isset($this->_translateArray)) {
            $this->makeTranslateArray();
        }
        if (isset($this->_translateArray[$key])) {
            return $this->_translateArray[$key];
        } else {
            return $key;
        }
    }

    private function makeTranslateArray()
    {
        $locale = $this->getLocale();
        $path = __DIR__ . "/../../translates/$locale.php";
        $translate = @include($path);

        if ($translate) {
            $this->_translateArray = $translate;
        } else {
            $this->_translateArray = [];
        }
    }

    private $_translateArray;

    /**
     * Get the Auth
     *
     * @return TranslateService
     */
    public static function Get()
    {
        if (!self::$_Instance) {
            self::$_Instance = new self();
        }
        return self::$_Instance;
    }

    private static $_Instance;
}
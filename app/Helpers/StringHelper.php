<?php
namespace App\Helpers;

class StringHelper
{
    public static function filterContactInfos($content) {
        //Filter emails
        $pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
        $content = preg_replace($pattern, '', $content);

        //Filter links
        $pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
        preg_replace($pattern, "", $content);

        //Filter numbers
        $pattern = '/[0-9]{5}/';
        $content = preg_replace($pattern, '', $content);

        return $content;
    }
}
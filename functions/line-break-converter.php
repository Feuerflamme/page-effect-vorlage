<?php
/**
 * Line Break Converter Function
 * 
 * Konvertiert #-Zeichen in HTML-Zeilenumbrüche (<br>) für CMS-Manager
 * Analog zum bold-converter mit *-Zeichen
 * 
 * @param string $text Der zu konvertierende Text
 * @return string Der konvertierte Text mit <br>-Tags
 */

if (!function_exists('convert_hash_to_linebreak')) {
    function convert_hash_to_linebreak($text) {
        if (empty($text)) {
            return $text;
        }
        
        // Konvertiere # in <br>-Tags
        // Das #-Zeichen wird durch einen Zeilenumbruch ersetzt
        $converted_text = str_replace('#', '<br>', $text);
        
        return $converted_text;
    }
}
<?php
class ContentFilter {
    private static $badWords = [
        // Liste de mots interdits
        'suicide', 'meurtre', 'tuer', 'violence', 'haine',
        'racisme', 'hitler', 'nazi', 'con', 'salope',
        'pute', 'fuck', 'merde', 'connard', 'enculé',
        'kill', 'murder', 'rape', 'shit', 'bitch'
    ];

    public static function containsBadWords($content) {
        if (empty($content)) return false;
        
        $contentLower = strtolower($content);
        
        foreach (self::$badWords as $word) {
            if (strpos($contentLower, $word) !== false) {
                return true;
            }
        }
        
        return false;
    }
}

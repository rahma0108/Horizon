<?php
class ContentFilter {
    private static $badWords = [
        // Liste de mots interdits en français
        'suicide', 'meurtre', 'tuer', 'violence', 'haine', 
        'racisme', 'hitler', 'nazi', 'con', 'salope',
        'pute', 'fuck', 'merde', 'connard', 'enculé',
        
        // Liste de mots interdits en anglais
        'kill', 'murder', 'suicide', 'rape', 'fuck', 
        'shit', 'bitch', 'asshole', 'cunt', 'nigger'
    ];

    public static function containsBadWords($content) {
        $contentLower = strtolower($content);
        
        foreach (self::$badWords as $word) {
            if (strpos($contentLower, strtolower($word)) !== false) {
                return $word; // Retourne le mot interdit trouvé
            }
        }
        
        return false; // Aucun mot interdit trouvé
    }
}
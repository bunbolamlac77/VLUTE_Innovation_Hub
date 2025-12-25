<?php

namespace App\Helpers;

use Parsedown;

class MarkdownHelper
{
    /**
     * Parse markdown to HTML
     * Handles both markdown and HTML content
     * 
     * @param string $text Markdown or HTML text
     * @return string HTML
     */
    public static function parse(string $text): string
    {
        if (empty(trim($text))) {
            return '';
        }

        // Check if content already contains HTML tags (from CKEditor or other rich text editor)
        // If it's already HTML, return as is (but ensure proper formatting)
        if (preg_match('/<[a-z][\s\S]*>/i', $text)) {
            // Content is HTML, return with proper whitespace handling
            return $text;
        }

        // Try to use Parsedown if available
        if (class_exists('Parsedown')) {
            try {
                $parsedown = new Parsedown();
                // Enable safe mode to prevent XSS
                $parsedown->setSafeMode(true);
                
                return $parsedown->text($text);
            } catch (\Exception $e) {
                // Fallback to basic markdown parsing
                return self::parseBasicMarkdown($text);
            }
        }

        // Fallback: basic markdown parsing without library
        return self::parseBasicMarkdown($text);
    }

    /**
     * Basic markdown parser (fallback when Parsedown is not available)
     * 
     * @param string $text Markdown text
     * @return string HTML
     */
    private static function parseBasicMarkdown(string $text): string
    {
        $lines = explode("\n", $text);
        $html = [];
        $inList = false;
        $currentParagraph = [];
        
        foreach ($lines as $line) {
            $line = rtrim($line);
            
            // Headings
            if (preg_match('/^#### (.*)$/', $line, $matches)) {
                self::flushParagraph($html, $currentParagraph, $inList);
                $html[] = '<h4>' . htmlspecialchars($matches[1]) . '</h4>';
                continue;
            }
            if (preg_match('/^### (.*)$/', $line, $matches)) {
                self::flushParagraph($html, $currentParagraph, $inList);
                $html[] = '<h3>' . htmlspecialchars($matches[1]) . '</h3>';
                continue;
            }
            if (preg_match('/^## (.*)$/', $line, $matches)) {
                self::flushParagraph($html, $currentParagraph, $inList);
                $html[] = '<h2>' . htmlspecialchars($matches[1]) . '</h2>';
                continue;
            }
            if (preg_match('/^# (.*)$/', $line, $matches)) {
                self::flushParagraph($html, $currentParagraph, $inList);
                $html[] = '<h1>' . htmlspecialchars($matches[1]) . '</h1>';
                continue;
            }
            
            // List items
            if (preg_match('/^[-*] (.+)$/', $line, $matches)) {
                if (!$inList) {
                    $html[] = '<ul>';
                    $inList = true;
                }
                $html[] = '<li>' . self::parseInlineMarkdown($matches[1]) . '</li>';
                continue;
            }
            
            // Empty line
            if (empty($line)) {
                self::flushParagraph($html, $currentParagraph, $inList);
                continue;
            }
            
            // Regular text
            $currentParagraph[] = $line;
        }
        
        self::flushParagraph($html, $currentParagraph, $inList);
        
        return implode("\n", $html);
    }
    
    /**
     * Flush current paragraph and close list if needed
     */
    private static function flushParagraph(array &$html, array &$paragraph, bool &$inList): void
    {
        if ($inList) {
            $html[] = '</ul>';
            $inList = false;
        }
        
        if (!empty($paragraph)) {
            $text = implode(' ', $paragraph);
            $html[] = '<p>' . self::parseInlineMarkdown($text) . '</p>';
            $paragraph = [];
        }
    }
    
    /**
     * Parse inline markdown (bold, italic, etc.)
     */
    private static function parseInlineMarkdown(string $text): string
    {
        // Escape HTML first
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        
        // Bold **text** or __text__
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $text);
        
        // Italic *text* or _text_ (but not if it's part of bold)
        $text = preg_replace('/(?<!<[^>]*)\*(.+?)\*(?![^<]*>)/', '<em>$1</em>', $text);
        $text = preg_replace('/(?<!<[^>]*)_(.+?)_(?![^<]*>)/', '<em>$1</em>', $text);
        
        return $text;
    }

    /**
     * Parse markdown inline (for single line)
     * 
     * @param string $text Markdown text
     * @return string HTML
     */
    public static function parseInline(string $text): string
    {
        if (empty(trim($text))) {
            return '';
        }

        // Try to use Parsedown if available
        if (class_exists('Parsedown')) {
            try {
                $parsedown = new Parsedown();
                $parsedown->setSafeMode(true);
                
                return $parsedown->line($text);
            } catch (\Exception $e) {
                // Fallback to basic parsing
            }
        }

        // Fallback: basic inline markdown parsing
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
        $text = preg_replace('/_(.+?)_/', '<em>$1</em>', $text);
        
        return $text;
    }
}


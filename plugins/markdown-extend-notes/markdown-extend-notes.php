<?php
namespace Grav\Plugin;

use \Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class MarkdownExtendNotesPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onMarkdownInitialized' => ['onMarkdownInitialized', 0]
        ];
    }

    public function onMarkdownInitialized(Event $event)
    {
        $markdown = $event['markdown'];

        $markdown->addInlineType('[', 'Sidenote');
        $markdown->addInlineType('[', 'Marginnote');
        #error_log("Tufte Plugin", 0);

        // Handle the Sidenotes annotated with [sn]<text>[/sn]
        $markdown->inlineSidenote = function($excerpt) {
            if (preg_match('/^\[sn\]([^{]+)\[\/sn\]/', $excerpt['text'], $matches))
            {                
                $extent = strlen($matches[0]);
                $text = ltrim($matches[1]);

                $Sidenote = '<div class=sidenote>'.$matches[1].'</div>';

                $Anchor = [
                    'name' => 'snanchor',
                    'handler' => 'line',
                    'text' => '<snanchor class=sidenote-number></snanchor>'.$Sidenote,
                ];

                $Container = [
                    'extent' => $extent,
                    'element' => [
                        'name' => 'e',
                        'handler' => 'element',
                        'text' => $Anchor,
                    ]
                ];
                return $Container;
            }
        };

        // Handle the Sidenotes annotated with [mn]<text>[/sn]
        $markdown->inlineMarginnote = function($excerpt) {
            if (preg_match('/^\[mn\]([^{]+)\[\/mn\]/', $excerpt['text'], $matches))
            {                
                $extent = strlen($matches[0]);
                $text = ltrim($matches[1]);

                $Marginnote = '<div class=marginnote>'.$matches[1].'</div>';

                $Anchor = [
                    'name' => 'snanchor',
                    'handler' => 'line',
                    'text' => $Marginnote,
                ];

                $Container = [
                    'extent' => $extent,
                    'element' => [
                        'name' => 'e',
                        'handler' => 'element',
                        'text' => $Anchor,
                    ]
                ];
                return $Container;
            }
        };


    }
}
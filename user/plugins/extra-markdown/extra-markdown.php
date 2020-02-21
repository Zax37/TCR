<?php
namespace Grav\Plugin;

use \Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class ExtraMarkdownPlugin extends Plugin
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
        if ($this->isAdmin()) {
            return;
        }

        $markdown = $event['markdown'];

        // Initialize Text example
        $markdown->addInlineType('{', 'FileEmbed');

        // Add function to handle this
        $markdown->inlineFileEmbed = function($excerpt) use ($event) {
            if (preg_match('/^{file:([\w\.]+)\.(\w+)}/', $excerpt['text'], $matches))
            {
                $media = $event['page']->getMedia();
				$filename = $matches[1].'.'.$matches[2];
				if ($media[$filename]) {
                    return array(
                        'extent' => strlen($matches[0]),
                        'element' => array(
                            'name' => 'a',
                            'text' => $filename,
                            'attributes' => array(
                                'href' => $media[$filename]->relativePath(),
                                'style' => 'color: red',
                            ),
                        ),
                    );
                }
            }
        };
    }
}
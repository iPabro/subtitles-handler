<?php

namespace Ipabro\SubtitlesConverter\Converters;

class MyWebConverter
{
    public function internalFormatToFileContent(array $internal_format)
    {
        $file_content = '';

        foreach ($internal_format as $block) {
            $lines = implode('<br>', $block['lines']);
            $file_content .= $lines . '<br><br>';
        }

        return trim($file_content);
    }
}
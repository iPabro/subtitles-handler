<?php namespace Done\Subtitles;

use Done\Subtitles\Exceptions\BadSubFormatException;

class SrtConverter implements ConverterContract {

    /**
     * Converts file's content (.srt) to library's "internal format" (array)
     *
     * @param string $file_content      Content of file that will be converted
     * @return array                    Internal format
     */
    public function fileContentToInternalFormat($file_content)
    {
        $internal_format = []; // array - where file content will be stored

        $blocks = explode("\n\n", trim($file_content)); // each block contains: start and end times + text
        foreach ($blocks as $k => $block) {
           // preg_match('/(?<orig_line_number>\d+)?(?:^|\n)(?<start>.*) ?--> (?<end>.*)\n(?<text>(\n*.*)*)/m', $block, $blockMatches);
            preg_match('/(?:^|\n)(?<start>.*) ?--> (?<end>.*)\n(?<text>(\n*.*)*)/m', $block, $blockMatches);
//            preg_match('/\n(?<start>.*) ?--> (?<end>.*)\n(?<text>(\n*.*)*)/m', $block, $blockMatches);

            // if block doesn't contain text (invalid srt file given)
            if (empty($blockMatches)) {
                continue;
            }

            $internal_format[$k] = [
//                'orig_line_number' => (int)$blockMatches['orig_line_number'] ?: null,
                'start' => static::srtTimeToInternal($blockMatches['start']) ?? throw new \InvalidArgumentException("Incorrect time - {$blockMatches['start']}"),
                'end' => static::srtTimeToInternal($blockMatches['end']) ?? static::srtTimeToInternal($blockMatches['start'])+1,
                'lines' => explode("\n", $blockMatches['text']),
            ];
        }
//        dd($internal_format);
        if(empty($internal_format)){
            throw new BadSubFormatException('Invalid .srt format');
        }

        return $internal_format;
    }

    /**
     * Convert library's "internal format" (array) to file's content
     *
     * @param array $internal_format    Internal format
     * @return string                   Converted file content
     */
    public function internalFormatToFileContent(array $internal_format)
    {
        $file_content = '';

        foreach ($internal_format as $k => $block) {
            $nr = $k + 1;
            $start = static::internalTimeToSrt($block['start']);
            $end = static::internalTimeToSrt($block['end']);
            $lines = implode("\r\n", $block['lines']);

            $file_content .= $nr . "\r\n";
            $file_content .= $start . ' --> ' . $end . "\r\n";
            $file_content .= $lines . "\r\n";
            $file_content .= "\r\n";
        }

        $file_content = trim($file_content);

        return $file_content;
    }

    // ------------------------------ private --------------------------------------------------------------------------

    /**
     * Convert .srt file format to internal time format (float in seconds)
     * Example: 00:02:17,440 -> 137.44
     *
     * @param $srt_time
     *
     * @return float
     */
    protected static function srtTimeToInternal($srt_time)
    {
        if(empty($srt_time)) return null;

        //suggesting, that between seconds and milliseconds ','
        $parts = explode(',', $srt_time);
        if (count($parts) === 1) {
            //suggesting, that between seconds and milliseconds '.'
            $parts = explode('.', $srt_time);
            if (count($parts) === 1) {
                //suggesting, seconds and milliseconds devided by last ':' (total of 4) - format 00:00:00:105
                $parts = explode(':', $srt_time);
                if (count($parts) === 4) {
                    $last = array_pop($parts);
                    $parts = array(implode(':', $parts), $last);
                }
            }
        }

        $only_seconds = strtotime("1970-01-01 {$parts[0]} UTC");
//        if(!isset($parts[1])) dd($parts, $only_seconds, $srt_time);
        $milliseconds = (float)('0.' . $parts[1]);

        $time = $only_seconds + $milliseconds;
        return $time;
    }

    /**
     * Convert internal time format (float in seconds) to .srt time format
     * Example: 137.44 -> 00:02:17,440
     *
     * @param float $internal_time
     *
     * @return string
     */
    protected static function internalTimeToSrt($internal_time)
    {
        $parts = explode('.', $internal_time); // 1.23
        $whole = $parts[0]; // 1
        $decimal = isset($parts[1]) ? substr($parts[1], 0, 3) : 0; // 23

        $srt_time = gmdate("H:i:s", floor($whole)) . ',' . str_pad($decimal, 3, '0', STR_PAD_RIGHT);

        return $srt_time;
    }
}

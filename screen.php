<?php

// https://gist.github.com/jkoop/8f64fab12605eaeae1dcc329235cb6e3
class Screen {
    public function __construct() {
        $this->clear();
        $this->fgColour = 'white';
        $this->bgColour = 'black';
    }

    public function clear(): void {
        echo "\033[2J"; // clear screen
        $this->x = 0;
        $this->y = 0;
    }

    public function print(string $string, ?int $x = null, ?int $y = null, $fgColour = 'white', $bgColour = 'black'): void {
        static $fgColours = [
            'black' => '0;30',
            'red' => '0;31',
            'green' => '0;32',
            'brown' => '0;33',
            'blue' => '0;34',
            'purple' => '0;35',
            'cyan' => '0;36',
            'light-gray' => '0;37',
            'dark-gray' => '1;30',
            'light-red' => '1;31',
            'light-green' => '1;32',
            'yellow' => '1;33',
            'light-blue' => '1;34',
            'light-purple' => '1;35',
            'light-cyan' => '1;36',
            'white' => '1;37',
        ];
        static $bgColours = [
            'black' => '40',
            'red' => '41',
            'green' => '42',
            'brown' => '43',
            'blue' => '44',
            'purple' => '45',
            'cyan' => '46',
            'white' => '47',
        ];

        $width = exec('tput cols'); // width of terminal
        // $height = exec('tput lines');

        // avoid printing funky characters
        $string = str_replace("\n", '', $string);
        $string = str_replace("\r", '', $string);
        $string = str_replace("\t", '', $string);

        // move cursor if needed
        if ($x !== null || $y !== null) {
            $this->x = $x ?? $this->x;
            $this->y = $y ?? $this->y;

            echo "\033[" . ($y ?? $this->y) . ';' . ($x ?? $this->x) . 'H';
        }

        // change forground colour if needed
        if ($fgColour != $this->fgColour) {
            $this->fgColour = $fgColour;
            echo "\033[" . ($fgColours[$fgColour] ?? '0;37') . 'm';
        }

        // change background colour of needed
        if ($bgColour != $this->bgColour) {
            $this->bgColour = $bgColour;
            echo "\033[" . ($bgColours[$bgColour] ?? '40') . 'm';
        }

        echo $string;

        $this->x += strlen($string);
        $this->y += floor($this->x / $width);
        $this->x = $this->x % $width;
    }
}

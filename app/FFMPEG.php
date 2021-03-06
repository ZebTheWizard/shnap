<?php

namespace App;

class FFMPEG
{
    private $params = [];

    private function push(...$values) {
        array_push($this->params, ...$values);
        return $this;
    }

    public function __construct() {
        $this->push(env('FFMPEG_PATH', 'ffmpeg'));
    }

    public function input($source) {
        return $this->push("-i", $source);
    }

    public function start($time) {
        return $this->push("-ss", $time);
    }

    public function end($time) {
        return $this->push("-to", $time);
    }

    public function length($time) {
        return $this->start("00:00:00")->end($time);
    }

    public function copy() {
        return $this->push("-c", "copy");
    }

    public function output($file) {
        $this->push($file);
        $params = $this->params;
        $cmd = implode(" ", $params);
        exec($cmd . " 2>&1", $output, $code);
        return [
            "params" => $params,
            "cmd" => $cmd,
            "output" => $output,
            "code" => $code
        ];
    }
}

<?php

namespace Bowling\Linked;

class BowlingGame {

    protected $frame;

    public function __construct()
    {
        $this->frame = new Frame(1);
    }

    public function roll(int $pins): self
    {
        if (!$this->frame->isOpen()) {
            $newFrame = new Frame($this->frame->frameNumber() + 1);

            $this->frame->next($newFrame);

            $this->frame = $newFrame;
        }

        $this->frame->addRoll($pins);

        return $this;
    }

    public function score(): int
    {
        return $this->frame->score();
    }
}
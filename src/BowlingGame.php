<?php

namespace Bowling;

final class BowlingGame {

    /**
     * Linked list of frames.  Reference to the last frame.
     * 
     * @var Frame
     */
    private $frame;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $this->frame = new Frame(1);
    }

    /**
     * Roll the ball
     * 
     * @param int $pins
     * @return self
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function roll(int $pins): self
    {
        if ($this->frame->isClosed() && !$this->frame->isLastFrame()) {
            $this->frame = $this->frame->createNextFrame();
        }

        $this->frame->roll($pins);

        return $this;
    }

    /**
     * Get the frame number
     * 
     * @return int
     */
    public function frameNumber(): int
    {
        return $this->frame->frameNumber();
    }

    /**
     * Get the score
     * 
     * @return int
     * @throws RuntimeException
     */
    public function score(): int
    {
        return $this->frame->score();
    }
}
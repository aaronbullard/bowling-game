<?php

namespace Bowling\Linked;

use RuntimeException;
use Bowling\Array\Frame as ArrayFrame;

class Frame extends ArrayFrame {

    /**
     * @var Frame|null
     */
    private $previous = null;

    /**
     * @var Frame|null
     */
    private $next = null;

    /**
     * Create and return the next frame
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public function createNextFrame(): self
    {
        $this->next = new Frame($this->frameNumber() + 1);

        $this->next->previous = $this;

        return $this->next;
    }

    /**
     * Returns the total points from this frame publicly.  Prevents
     * returning score if the frame is not completed.
     *
     * @return integer
     * @throws RuntimeException
     */
    public function score(): int
    {
        if ($this->isOpen()) {
            throw new RuntimeException("Frame cannot be scored until it is completed");
        }

        $score = $this->totalPins();

        if ($this->previous) {
            $score += $this->previous->score();
        }

        if ($this->isLastFrame() || $this->next === null) {
            return $score;
        }
        
        if ($this->isSpare()) {
            $score += $this->next->firstRoll();
        }

        if ($this->isStrike()) {
            $score += $this->next->firstRoll();
            $score += $this->next->isStrike() && !$this->next->isLastFrame()
                ? $this->next->next->firstRoll() 
                : $this->next->secondRoll();
        }

        return $score;
    }
}
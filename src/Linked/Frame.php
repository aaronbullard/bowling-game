<?php

namespace Bowling\Linked;

use RuntimeException;
use Bowling\Array\Frame as ArrayFrame;

class Frame extends ArrayFrame {

    /**
     * @var Frame\null
     */
    protected $previous = null;

    /**
     * @var Frame\null
     */
    protected $next = null;

    /**
     * Append the following Frame
     *
     * @param Frame $next
     * @return self
     */
    public function next(Frame $next): self
    {
        $this->next = $next;

        $next->setPrevious($this);

        return $this;
    }

    /**
     * Prepend the previous Frame
     *
     * @param Frame $previous
     * @return self
     */
    private function setPrevious(Frame $previous): self
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * Pins dropped in second roll
     *
     * @return integer|null
     */
    public function secondRoll(): ?int
    {
        if ($this->isStrike() && !$this->isLastFrame()) {
            return $this->next->firstRoll();
        }

        return parent::secondRoll();
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

        if ($this->isLastFrame()) {
            return $score;
        }
        
        if ($this->isSpare()) {
            $score += $this->next->firstRoll();
        }

        if ($this->isStrike()) {
            $score += $this->next->firstRoll();
            $score += $this->next->secondRoll();
        }

        return $score;
    }
}
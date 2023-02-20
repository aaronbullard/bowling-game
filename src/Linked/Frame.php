<?php

namespace Bowling\Linked;

use Bowling\Frame as BaseFrame;

class Frame extends BaseFrame {

    protected $previous = null;

    protected $next = null;

    public function next(Frame $next): self
    {
        $this->next = $next;

        $next->setPrevious($this);

        return $this;
    }

    public function setPrevious(Frame $previous): self
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

    public function score(): int
    {
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
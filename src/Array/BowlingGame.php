<?php

namespace Bowling\Array;

final class BowlingGame {

    private array $frames = [];

    public function __construct()
    {
        $this->frames[] = new Frame(1);
    }

    /**
     * Number of pins dropped in a roll
     *
     * @param integer $pins
     * @return self
     */
    public function roll(int $pins): self
    {
        $this->currentFrame()->addRoll($pins);
        
        return $this;
    }

    /**
     * Return the current frame.  The last frame is always returned
     * even if it is over.
     *
     * @return Frame
     */
    public function currentFrame(): Frame
    {
        $frame = end($this->frames);

        if ($frame->isLastFrame()) {
            return $frame;
        }

        if ($frame->isOpen()) {
            return $frame;
        }

        // Current frame is closed, initialize a new Frame
        $newFrame = new Frame($frame->frameNumber() + 1);

        $this->frames[] = $newFrame;

        return $newFrame;
    }

    /**
     * Get a frame by its number
     *
     * @param integer $frameNumber
     * @return Frame
     */
    public function getFrame(int $frameNumber): Frame
    {
        return $this->frames[$frameNumber - 1];
    }

    /**
     * Game score
     *
     * @return integer
     */
    public function score(): int
    {
        return array_reduce($this->frames, function($score, $frame){
            $score += $frame->score();

            if ($frame->isLastFrame()) {
                return $score;
            }

            if ($frame->isSpare() || $frame->isStrike()) {
                $score += $this->getFrame($frame->frameNumber() + 1)->firstRoll();
            }

            if ($frame->isStrike()) {
                $roll = $this->getFrame($frame->frameNumber() + 1)->secondRoll();
                
                if (is_null($roll)) {
                    $roll = $this->getFrame($frame->frameNumber() + 2)->firstRoll();
                }
                
                $score += $roll;
            }

            return $score;
        }, 0);
    }
}
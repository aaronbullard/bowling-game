<?php

namespace Bowling\Array;

use InvalidArgumentException;
use RuntimeException;

class Frame {

    /**
     * Frame Number
     *
     * @var integer
     */
    private int $frameNumber;

    /**
     * Array of pins dropped per roll
     *
     * @var array
     */
    private array $rolls = [];

    /**
     * Frame number
     *
     * @param integer $frameNumber
     * @throws InvalidArgumentException
     */
    public function __construct(int $frameNumber) 
    {
        if ($frameNumber < 1 || $frameNumber > 10) {
            throw new InvalidArgumentException("A frame number must be between 1 and 10 inclusive");
        }

        $this->frameNumber = $frameNumber;
    }

    /**
     * Frame number
     *
     * @return integer
     */
    public function frameNumber(): int
    {
        return $this->frameNumber;
    }

    /**
     * Pins dropped in first roll
     *
     * @return integer|null
     */
    public function firstRoll(): ?int
    {
        return empty($this->rolls[0]) ? null : $this->rolls[0];
    }

    /**
     * Pins dropped in second roll
     *
     * @return integer|null
     */
    public function secondRoll(): ?int
    {
        return empty($this->rolls[1]) ? null : $this->rolls[1];
    }

    /**
     * Pins dropped in third roll.  Only applicable in the tenth frame
     * after a strike or spare.
     *
     * @return integer|null
     */
    public function thirdRoll(): ?int
    {
        return empty($this->rolls[2]) ? null : $this->rolls[2];
    }
    
    /**
     * Is this the tenth and last frame of the game?
     *
     * @return boolean
     */
    public function isLastFrame(): bool
    {
        return $this->frameNumber === 10;
    }

    /**
     * Is this frame still open.  Can we roll again?
     *
     * @return boolean
     */
    public function isOpen(): bool
    {
        if ($this->isLastFrame()) {
            if ($this->rollCount() === 3) {
                return false;
            }

            if ($this->rollCount() === 2 && $this->totalPins() < 10) {
                return false;
            }

            return true;
        }

        if ($this->rollCount() === 2) {
            return false;
        }

        if ($this->isStrike()) {
            return false;
        }

        return true;
    }

    /**
     * The number of rolls so far in this frame.
     *
     * @return integer
     */
    private function rollCount(): int
    {
        return count($this->rolls);
    }

    /**
     * Was the first roll a strike?
     *
     * @return boolean
     */
    public function isStrike(): bool
    {
        return $this->firstRoll() === 10;
    }

    /**
     * Do the first two rolls equal ten? (a spare)
     *
     * @return boolean
     */
    public function isSpare(): bool
    {
        if ($this->isStrike()) {
            return false;
        }

        return ($this->firstRoll() + $this->secondRoll()) === 10;
    }

    /**
     * Record number of pins dropped in a roll
     *
     * @param integer $pins
     * @return self
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function addRoll(int $pins): self
    {
        if ($pins < 0 || $pins > 10) {
            throw new InvalidArgumentException("Number of pins cannot be less than 0 or exceed 10");
        }

        if ($this->isOpen() === false) {
            throw new RuntimeException("Frame is completed");
        }

        $this->rolls[] = $pins;

        return $this;
    }

    /**
     * Calculates the total points from this frame
     *
     * @return integer
     */
    protected function totalPins(): int
    {
        return array_sum($this->rolls);
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

        return $this->totalPins();
    }
}
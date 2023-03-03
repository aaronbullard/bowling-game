<?php

namespace Bowling;

use RuntimeException;
use InvalidArgumentException;

final class Frame {

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
     * @var Frame|null
     */
    private $previous = null;

    /**
     * @var Frame|null
     */
    private $next = null;

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
     * Frame number
     *
     * @return integer
     */
    public function frameNumber(): int
    {
        return $this->frameNumber;
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
     * Record number of pins dropped in a roll
     *
     * @param integer $pins
     * @return self
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function roll(int $pins): self
    {
        if ($pins < 0 || $pins > 10) {
            throw new InvalidArgumentException("Number of pins cannot be less than 0 or exceed 10");
        }

        if ($this->isClosed()) {
            throw new RuntimeException("Frame is completed");
        }

        $this->rolls[] = $pins;

        return $this;
    }

    /**
     * Pins dropped in first roll
     *
     * @return integer|null
     */
    private function firstRoll(): ?int
    {
        return empty($this->rolls[0]) ? null : $this->rolls[0];
    }

    /**
     * Pins dropped in second roll
     *
     * @return integer|null
     */
    private function secondRoll(): ?int
    {
        return empty($this->rolls[1]) ? null : $this->rolls[1];
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
     * Calculates the total points from this frame.
     * Does not include bonus points for strikes or spares.
     *
     * @return integer
     */
    private function totalPins(): int
    {
        return array_sum($this->rolls);
    }

    /**
     * Was the first roll a strike?
     *
     * @return boolean
     */
    private function isStrike(): bool
    {
        return $this->firstRoll() === 10;
    }

    /**
     * Do the first two rolls equal ten? (a spare)
     *
     * @return boolean
     */
    private function isSpare(): bool
    {
        if ($this->isStrike()) {
            return false;
        }

        return ($this->firstRoll() + $this->secondRoll()) === 10;
    }

    /**
     * 
     *
     * @return boolean
     */
    public function isClosed(): bool
    {
        if ($this->isLastFrame()) {
            if ($this->rollCount() === 3) {
                return true;
            }

            if ($this->rollCount() === 2 && $this->totalPins() < 10) {
                return true;
            }

            return false;
        }

        if ($this->rollCount() === 2) {
            return true;
        }

        if ($this->isStrike()) {
            return true;
        }

        return false;
    }

    /**
     * Is this frame still open.  Can we roll again?
     *
     * @return boolean
     */
    private function isOpen(): bool
    {
        return !$this->isClosed();
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

        $score = 0;

        // Add the score from the previous frame
        if ($this->previous) {
            $score += $this->previous->score();
        }

        // Add the score from this frame
        $score += $this->totalPins();

        // Add bonus points for strikes and spares
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
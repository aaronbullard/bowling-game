<?php

namespace Tests\Unit;

use Tests\TestCase;
use Bowling\Frame;

class FrameTest extends TestCase {

    /**
     * @dataProvider dataProviderFrames
     *
     * @return void
     */
    public function test_frame_closes_properly
    (
        int $frameNumber,
        array $rolls, 
        int $score, 
        bool $isStrike, 
        bool $isSpare, 
        bool $isOpen
    )
    {
        $frame = new Frame($frameNumber);

        foreach($rolls as $roll) {
            $frame->addRoll($roll);
        }

        if (isset($rolls[0])) {
            $this->assertEquals($rolls[0], $frame->firstRoll());
        }

        if (isset($rolls[1])) {
            $this->assertEquals($rolls[1], $frame->secondRoll());
        }

        if (isset($rolls[2])) {
            $this->assertEquals($rolls[2], $frame->thirdRoll());
        }

        if (!$frame->isOpen()) {
            $this->assertEquals($score, $frame->score());
        }

        $this->assertEquals($isStrike, $frame->isStrike());
        $this->assertEquals($isSpare, $frame->isSpare());
        $this->assertEquals($isOpen, $frame->isOpen());
    }

    private function dataProviderFrames()
    {
        yield 'no rolls' => [
            'frame' => 1,
            'rolls' => [],
            'score' => 0,
            'isStrike' => false,
            'isSpare' => false,
            'isOpen' => true
        ];

        yield 'one roll' => [
            'frame' => 1,
            'rolls' => [5],
            'score' => 5,
            'isStrike' => false,
            'isSpare' => false,
            'isOpen' => true
        ];

        yield 'two rolls' => [
            'frame' => 1,
            'rolls' => [5, 3],
            'score' => 8,
            'isStrike' => false,
            'isSpare' => false,
            'isOpen' => false
        ];

        yield 'strike' => [
            'frame' => 1,
            'rolls' => [10],
            'score' => 10,
            'isStrike' => true,
            'isSpare' => false,
            'isOpen' => false
        ];

        yield 'spare' => [
            'frame' => 1,
            'rolls' => [3,7],
            'score' => 10,
            'isStrike' => false,
            'isSpare' => true,
            'isOpen' => false
        ];

        yield '10th frame' => [
            'frame' => 10,
            'rolls' => [3, 3],
            'score' => 6,
            'isStrike' => false,
            'isSpare' => false,
            'isOpen' => false
        ];

        yield '1 strike in 10th' => [
            'frame' => 10,
            'rolls' => [10],
            'score' => 10,
            'isStrike' => true,
            'isSpare' => false,
            'isOpen' => true
        ];

        yield '2 strikes in 10th' => [
            'frame' => 10,
            'rolls' => [10, 10],
            'score' => 20,
            'isStrike' => true,
            'isSpare' => false,
            'isOpen' => true
        ];

        yield '3 strikes in 10th' => [
            'frame' => 10,
            'rolls' => [10, 10, 10],
            'score' => 30,
            'isStrike' => true,
            'isSpare' => false,
            'isOpen' => false
        ];

        yield 'spare in 10th' => [
            'frame' => 10,
            'rolls' => [4, 6],
            'score' => 10,
            'isStrike' => false,
            'isSpare' => true,
            'isOpen' => true
        ];

        yield 'spare in 10th with 3 rolls' => [
            'frame' => 10,
            'rolls' => [4, 6, 1],
            'score' => 11,
            'isStrike' => false,
            'isSpare' => true,
            'isOpen' => false
        ];

    }
}
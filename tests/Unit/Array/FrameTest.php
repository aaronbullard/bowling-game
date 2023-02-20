<?php

namespace Tests\Unit\Array;

use Tests\TestCase;
use Bowling\Array\Frame;
use InvalidArgumentException;
use RuntimeException;

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

    /**
     * @dataProvider dataProviderInitialFrameNumbers
     *
     * @return void
     */
    public function test_frame_number_exception(int $frameNumber)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("A frame number must be between 1 and 10 inclusive");
        new Frame($frameNumber);
    }

    /**
     * @dataProvider dataProviderInitialFrameNumbers
     *
     * @return void
     */
    public function test_pin_number_exception(int $pins)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Number of pins cannot be less than 0 or exceed 10");
        $frame = new Frame(1);
        $frame->addRoll($pins);
    }

    public function test_adding_roll_to_closed_frame_exception()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Frame is completed");
        $frame = new Frame(1);
        $frame
            ->addRoll(2)
            ->addRoll(2)
            ->addRoll(2);
    }

    public function test_scoring_an_open_frame_exception()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Frame cannot be scored until it is completed");
        $frame = new Frame(1);
        $frame->addRoll(2);
        $frame->score();
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

    private function dataProviderInitialFrameNumbers()
    {
        yield [
            'frame' => -1
        ];

        yield [
            'frame' => 11
        ];
    }
}
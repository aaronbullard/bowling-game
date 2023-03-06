<?php

namespace Tests\Unit;

use Tests\TestCase;
use Bowling\BowlingGame;

class BowlingGameTest extends TestCase {

    protected function getNewGame()
    {
        return new BowlingGame();
    }

    /**
     *
     * @dataProvider dataScoringSamples
     * 
     * @return void
     */
    public function test_scoring(int $score, array $sheet)
    {
        $game = $this->getNewGame();

        foreach($sheet as $roll) {
            $game->roll($roll);
        }

        $this->assertEquals($score, $game->score());
    }

    /**
     *
     * @dataProvider dataExceptionSamples
     * 
     * @return void
     */
    public function test_exceptions(array $sheet, string $exception, string $message)
    {
        $game = $this->getNewGame();

        $this->expectException($exception);
        $this->expectExceptionMessage($message);

        foreach($sheet as $roll) {
            $game->roll($roll);
        }

        $game->score();
    }

    private function dataScoringSamples()
    {
        yield [
            'score' => 300,
            'sheet' => [10,10,10,10,10,10,10,10,10,10,10,10]
        ];

        yield [
            'score' => 181,
            'sheet' => [9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,0]
        ];

        yield [
            'score' => 193,
            'sheet' => [9,1,10,9,1,10,9,1,10,9,1,10,9,1,10,0,3]
        ];

        yield [
            'score' => 87,
            'sheet' => [5,0,3,4,5,2,9,0,3,3,6,3,7,3,9,0,3,4,9,0]
        ];

        yield [
            'score' => 191,
            'sheet' => [9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,10]
        ];

        yield [
            'score' => 0,
            'sheet' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
        ];
    }

    private function dataExceptionSamples()
    {
        yield 'too few pins' => [
            'sheet' => [-1],
            'exception' => 'InvalidArgumentException',
            'message' => 'Number of pins cannot be less than 0 or exceed 10',
        ];

        yield 'too many pins' => [
            'sheet' => [11],
            'exception' => 'InvalidArgumentException',
            'message' => 'Number of pins cannot be less than 0 or exceed 10',
        ];

        yield 'too many rolls' => [
            'sheet' => [10,10,10,10,10,10,10,10,10,10,10,10,10],
            'exception' => 'RuntimeException',
            'message' => 'Frame is completed',
        ];

        yield 'scoring an incomplete frame' => [
            'sheet' => [1, 5, 3],
            'exception' => 'RuntimeException',
            'message' => 'Frame cannot be scored until it is completed',
        ];
    }
}
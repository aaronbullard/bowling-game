<?php

namespace Tests\Unit\Array;

use Tests\TestCase;
use Bowling\Array\BowlingGame;

class BowlingGameTest extends TestCase {

    protected function getNewGame()
    {
        return new BowlingGame();
    }

    public function test_getting_frame()
    {
        $game = $this->getNewGame();

        $game->roll(1)
            ->roll(1)
            ->roll(1)
            ->roll(1)
            ->roll(1);

        $this->assertEquals(3, $game->currentFrame()->frameNumber());
        $this->assertEquals(2, $game->getFrame(2)->frameNumber());
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

    private function dataScoringSamples()
    {
        return [
            [
                'score' => 300,
                'sheet' => [10,10,10,10,10,10,10,10,10,10,10,10]
            ],
            [
                'score' => 181,
                'sheet' => [9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,0]
            ],
            [
                'score' => 193,
                'sheet' => [9,1,10,9,1,10,9,1,10,9,1,10,9,1,10,0,3]
            ],
            [
                'score' => 87,
                'sheet' => [5,0,3,4,5,2,9,0,3,3,6,3,7,3,9,0,3,4,9,0]
            ],
            [
                'score' => 191,
                'sheet' => [9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,9,1,10]
            ],
            [
                'score' => 0,
                'sheet' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            ]
        ];
    }
}
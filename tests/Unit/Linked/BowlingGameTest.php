<?php

namespace Tests\Unit\Linked;

use Bowling\Linked\BowlingGame;
use Tests\Unit\Array\BowlingGameTest as UnitBowlingGameTest;

class BowlingGameTest extends UnitBowlingGameTest {

    protected function getNewGame()
    {
        return new BowlingGame();
    }

    public function test_getting_frame()
    {
        $this->assertTrue(true);
    }

    public function test_preventing_score_before_frame_is_completed()
    {
        $game = $this->getNewGame();

        $game->roll(5);

        $this->expectException(\RuntimeException::class);

        $game->score();
    }

    public function test_a_spare_in_the_last_frame()
    {
        $game = $this->getNewGame();

        $game->roll(1);
        $game->roll(9);
        $game->roll(5);
        $game->roll(5);

        $this->assertEquals(25, $game->score());
    }
}
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
}
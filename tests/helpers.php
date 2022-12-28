<?php

if (!function_exists('dd')) {
    function dd($payload){
        print_r($payload);
        echo PHP_EOL;
        die;
    }
}

if (!function_exists('disp')) {
    function disp($payload){
        print_r($payload);
        echo PHP_EOL;
    }
}

/**
 * From Chat GPT
 *
 * @param array $rolls
 * @return void
 */
function score_game($rolls) {
    // Initialize the score to 0
    $score = 0;
    // Initialize the frame and roll indexes to 0
    $frame_index = 0;
    $roll_index = 0;
    // Loop through the frames
    while ($frame_index < 10) {
      // Check if the current frame is a strike
      if ($rolls[$roll_index] == 10) {
        // Add 10 plus the next two rolls to the score
        $score += 10 + $rolls[$roll_index+1] + $rolls[$roll_index+2];
        // Increment the frame index
        $frame_index++;
        // Increment the roll index
        $roll_index++;
      }
      // Check if the current frame is a spare
      else if ($rolls[$roll_index] + $rolls[$roll_index+1] == 10) {
        // Add 10 plus the next roll to the score
        $score += 10 + $rolls[$roll_index+2];
        // Increment the frame index
        $frame_index++;
        // Increment the roll index
        $roll_index += 2;
      }
      // Otherwise, the frame is a regular frame
      else {
        // Add the rolls for the current frame to the score
        $score += $rolls[$roll_index] + $rolls[$roll_index+1];
        // Increment the frame index
        $frame_index++;
        // Increment the roll index
        $roll_index += 2;
      }
    }
    // Return the final score
    return $score;
  }
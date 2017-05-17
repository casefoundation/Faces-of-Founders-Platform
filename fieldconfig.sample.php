<?php

$judging_fields = array(
  array(
    // Machine name for backend storage
    'field_name' => 'story_problem_solve',
    // Prompt presented on the submission and judging interfaces
    'field_question' => 'Tell us about your business. What problem is it trying to solve?',
    // Additional guidance text displayed to judges
    'field_judging_guidance' => ''
  ),
  array(
    'field_name' => 'story_biggest_obstacle',
    'field_question' => 'What has been your biggest obstacle in your entrepreneurship journey?',
    'field_judging_guidance' => ''
  ),
  array(
    'field_name' => 'story_entrepeneurship_important',
    'field_question' => 'How do you or your business contribute to advancing inclusive entrepreneurship?',
    'field_judging_guidance' => ''
  )
);

// Final guidance text displayed to judges after reviewing all questions
$judging_final_guidance = '';

<?php
/**
 * Juding field settings
 */
require get_stylesheet_directory() . '/fieldconfig.php';

function inclusive_entrepreneurship_get_judging_fields() {
  global $judging_fields;
  assert(count($judging_fields) == 3,'Judging field config array length must be exactly 3.');
  return $judging_fields;
}

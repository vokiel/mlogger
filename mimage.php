<?php
/* Configuration - list screens with dimensions (width, height) */
$screens = array(
  array(1366, 768),
  array(1680, 1050),
  array(1920, 1080),
);

$width = 0;
$height = 0;
foreach( $screens as $screen ){
  $width += $screen[0];
  $height = max($height, $screen[1]);
}

$im = imagecreatetruecolor($width, $height);

$blue = imagecolorallocatealpha($im, 0x00, 0x00, 0xff, 120);
$red = imagecolorallocatealpha($im, 0xff, 0x00, 0x00, 120);

foreach( $screens as $screen ) {
  $x = 0;
  $y = $height-$screen[1];

  if ( isset($previous_x) ){
    $x = $previous_x;
  }

  $previous_x += $screen[0];

  imagefilledrectangle($im, $x, $y, $previous_x, $height, 0xffffff);
}

$log = file('mouse.log', FILE_SKIP_EMPTY_LINES);
foreach ( $log as $line ) {
    list($code, $x, $y) = explode("\t", $line);

    $code = (int)$code;
    $x = (int)$x;
    $y = (int)$y;

    imagefilledellipse($im, $x, $y, 17, 17, $code === 272 ? $red : $blue);
}

imagestring($im, 2, 10, 10, count($log) . ' clicks', 0xFFBF00);

imagepng($im, 'mouse.png');

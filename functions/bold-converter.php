<?php
// Funktion zum Umwandeln von Sternen in <strong> Tags
function convert_stars_to_strong($text)
  {
    if (empty($text)) {
      return $text;
    }

    // Erst doppelte Sterne durch Platzhalter ersetzen (um sie zu schützen)
    $text = str_replace('**', '###DOUBLE_STAR###', $text);

    // Einzelne Sterne in <strong> Tags umwandeln
    $star_count = 0;
    $result = '';
    $length = strlen($text);

    for ($i = 0; $i < $length; $i++) {
      if ($text[$i] === '*') {
        $star_count++;
        if ($star_count % 2 === 1) {
          $result .= '<strong>';
        } else {
          $result .= '</strong>';
        }
      } else {
        $result .= $text[$i];
      }
    }

    // Doppelte Sterne wieder zurück in einzelne Sterne umwandeln
    $result = str_replace('###DOUBLE_STAR###', '*', $result);

    return $result;
  }
?>
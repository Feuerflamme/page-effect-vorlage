<?php
/**
 * ACF Dynamic Color Variables
 * 
 * Generiert CSS Custom Properties aus ACF-Feldern für dynamische Farbsteuerung
 * 
 * @package theme
 * @since 1.0.0
 */

/**
 * Generiert CSS-Variablen aus ACF-Farbfeldern
 * Wird im <head> ausgegeben für globale Verfügbarkeit
 */
function theme_acf_css_variables() {
    echo '<style id="theme-acf-vars">';
    echo ':root {';
    
    // Basis-Farbe bestimmen: ACF-Wert oder SCSS-Fallback
    $base_color = get_field('akzent-farbe', 'option') ?: '#6faabc';
    
    // Darkening-Wert aus ACF Range-Feld (0-10) - wird auf Basis-Farbe angewendet
    $darkening_value = get_field('darken', 'option');
    $darkening_value = !empty($darkening_value) ? floatval($darkening_value) : 1; // Standard: 1
    
    // Range 0-10 wird zu 0%-100% Abdunklung (volle Kontrolle)
    $darkening_percent = ($darkening_value / 10) * 100; // 0-10 wird zu 0%-100%
    
    // HAUPTFARBE ist die abgedunkelte Version der Basis-Farbe
    $primary_color = theme_darken_color($base_color, $darkening_percent);
    echo '--acf-primary: ' . esc_attr($primary_color) . ';';
    
    // Für Hover/Interaktionen: Noch etwas dunkler (zusätzliche 40% Abdunklung)
    $darkened_color = theme_darken_color($primary_color, 40);
    echo '--acf-primary-dark: ' . esc_attr($darkened_color) . ';';
    echo '--acf-darkening-percent: ' . esc_attr($darkening_percent) . '%;';
    echo '--acf-darkening-value: ' . esc_attr($darkening_value) . ';';
    
    // Debug-Information (nur bei WP_DEBUG)
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $color_source = get_field('akzent-farbe', 'option') ? 'ACF' : 'Fallback';
        theme_debug_color_calculation($base_color, $primary_color, $darkening_percent, "primary color darkening (Source: $color_source)");
    }
    
    echo '}';
    echo '</style>';
}

/**
 * PHP-Funktion um Farben abzudunkeln (simuliert SCSS darken())
 * 
 * @param string $hex_color - Hex-Farbe (z.B. "#007FA3" oder "007FA3")
 * @param int|float $percent - Prozent zum Abdunkeln (0-100)
 * @return string - Abgedunkelte Hex-Farbe
 */
function theme_darken_color($hex_color, $percent) {
    // Validierung der Eingaben
    if (empty($hex_color) || !is_numeric($percent)) {
        return $hex_color; // Fallback: Original-Farbe zurückgeben
    }
    
    // Entferne # falls vorhanden
    $hex_color = ltrim($hex_color, '#');
    
    // Validiere Hex-Format
    if (!ctype_xdigit($hex_color) || strlen($hex_color) !== 6) {
        return '#' . $hex_color; // Fallback: Original zurückgeben
    }
    
    // Begrenze Prozent auf 0-100% (volle Kontrolle)
    $percent = max(0, min(100, $percent)); // 0-100% Abdunklung möglich
    
    // Konvertiere zu RGB
    $r = hexdec(substr($hex_color, 0, 2));
    $g = hexdec(substr($hex_color, 2, 2));
    $b = hexdec(substr($hex_color, 4, 2));
    
    // Realistisches Darkening: Reduziere RGB-Werte proportional
    $factor = (100 - $percent) / 100;
    $r = round($r * $factor);
    $g = round($g * $factor);
    $b = round($b * $factor);
    
    // Stelle sicher, dass Werte zwischen 0-255 bleiben
    $r = max(0, min(255, $r));
    $g = max(0, min(255, $g));
    $b = max(0, min(255, $b));
    
    // Zurück zu Hex
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * PHP-Funktion um Farben aufzuhellen (simuliert SCSS lighten())
 * 
 * @param string $hex_color - Hex-Farbe (z.B. "#007FA3")
 * @param int|float $percent - Prozent zum Aufhellen (0-100)
 * @return string - Aufgehellte Hex-Farbe
 */
function theme_lighten_color($hex_color, $percent) {
    // Validierung der Eingaben
    if (empty($hex_color) || !is_numeric($percent)) {
        return $hex_color;
    }
    
    // Entferne # falls vorhanden
    $hex_color = ltrim($hex_color, '#');
    
    // Validiere Hex-Format
    if (!ctype_xdigit($hex_color) || strlen($hex_color) !== 6) {
        return '#' . $hex_color;
    }
    
    // Begrenze Prozent auf 0-100
    $percent = max(0, min(100, $percent));
    
    // Konvertiere zu RGB
    $r = hexdec(substr($hex_color, 0, 2));
    $g = hexdec(substr($hex_color, 2, 2));
    $b = hexdec(substr($hex_color, 4, 2));
    
    // Helle auf (erhöhe jeden RGB-Wert zur 255)
    $r = round($r + (255 - $r) * ($percent / 100));
    $g = round($g + (255 - $g) * ($percent / 100));
    $b = round($b + (255 - $b) * ($percent / 100));
    
    // Stelle sicher, dass Werte zwischen 0-255 bleiben
    $r = max(0, min(255, $r));
    $g = max(0, min(255, $g));
    $b = max(0, min(255, $b));
    
    // Zurück zu Hex
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * Hilfsfunktion zum Debuggen von Farbberechnungen
 * Nur in Development-Umgebung verwenden
 * 
 * @param string $original_color - Original-Farbe
 * @param string $modified_color - Veränderte Farbe  
 * @param float $percent - Verwendeter Prozentsatz
 * @param string $operation - Art der Operation (darken/lighten)
 */
function theme_debug_color_calculation($original_color, $modified_color, $percent, $operation = 'darken') {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log(sprintf(
            'theme Color Debug: %s(%s, %s%%) = %s',
            $operation,
            $original_color,
            $percent,
            $modified_color
        ));
    }
}

/**
 * Hook die CSS-Variablen in den wp_head
 * Wird automatisch beim Laden der Datei registriert
 */
add_action('wp_head', 'theme_acf_css_variables', 5); // Früh laden für andere Styles
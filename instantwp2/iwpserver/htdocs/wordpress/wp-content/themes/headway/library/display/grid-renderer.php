<?php
class HeadwayGridRenderer {private $x0b;private $x0c = array(); private $x0d = array(); private $x0e = array(); private $x0f = array(); private $x10 = array(); private $x11 = 30; private $x12 = 20; function __construct() {$this->blocks = HeadwayBlocksData::get_blocks_by_layout(HeadwayLayout::get_current_in_use()); } private function step_1_sort_blocks_by_position() { uasort($this->blocks, array(__CLASS__, 'uasort_blocks_by_top_to_left')); }private function uasort_blocks_by_top_to_left($x13, $x14) {if ( is_array($x13) && is_numeric(reset($x13)) )$x13 = reset($x13); if ( is_array($x14) && is_numeric(reset($x14)) )$x14 = reset($x14); if ( is_numeric($x13) )$x13 = $this->blocks[$x13]; if ( is_numeric($x14) )$x14 = $this->blocks[$x14]; $x15 = $x13['position']['top']; $x16 = $x13['position']['left']; $x17 = $x14['position']['top']; $x18 = $x14['position']['left'];if ( $x15 === $x17 && $x16 === $x18 )return 0;if ( $x15 === $x17 ) return ($x16 < $x18) ? -1 : 1;return ($x15 < $x17) ? -1 : 1;}private function step_2_build_rows() {$x19 = null;$x1a = 0; foreach ( $this->blocks as $x1b => $x1c ) {$x1d = is_array($x19) ? $x19['position']['top'] - $this->x12 : null; $x1e = is_array($x19) ? $x19['position']['top'] + $this->x12 : null; if ( $x1d && headway_in_numeric_range($x1c['position']['top'], $x1d, $x1e) ) { $this->rows[$x1a][] = $x1c['id'];} else {$x1a++;$this->rows[$x1a] = array($x1c['id']); } $x19 = $x1c; } } private function step_3_construct_columns() {$x1f = 0;foreach ( $this->blocks as $x1b => $x1c ) { if ( isset($this->blocks_in_sub_columns) && in_array($x1c['id'], $this->blocks_in_sub_columns) )continue; $x1f++; $this->x0e[$x1f] = array($x1b); $x20 = $this->step_3a_extract_sub_columns($x1c['id']); if ( !is_array($x20) )continue;$x21 = 1; $x22 = 0; $x23 = array();foreach ( $x20 as $x24 ) {$x25 = $this->blocks[$x24];$x26 = $x25['dimensions']['width'] == $x1c['dimensions']['width'] ? false : true;$this->step_3b_remove_block_from_columns($x24);if ( isset($x27) && $x27 + $x28 < $x1c['position']['top'] ) if ( $x25['position']['top'] > $x1c['position']['top'] + $x1c['dimensions']['height'] )$x21++;$x29 = 'sub-column-' . $x25['dimensions']['width'] . ':' . $x25['position']['left'];$x2a = $x29 . '-' . $x21;$x2b = (int)$x1c['dimensions']['width'];$x2c = (int)$x1c['position']['left'];if ( $x22 + $x25['dimensions']['width'] - $x2c > $x2b ) { $x22 = 0; if ( !in_array($x29, $x23) )$x21++; }if ( $x26 ) {$x23[] = $x29; if ( !isset($this->x0e[$x1f][$x2a]) )$this->x0e[$x1f][$x2a] = array(); $this->x0e[$x1f][$x2a][] = $x24; } else { $this->x0e[$x1f][] = $x24;}$x22 = (int)$x25['dimensions']['width'] + (int)$x25['position']['left'];$x27 = (int)$x25['position']['top'];$x28 = (int)$x25['dimensions']['height']; } $x2d = false;foreach ( $this->x0e[$x1f] as $x2e )if ( is_array($x2e) ) $x2d = true; if ( $x2d ) {$this->step_3c_modify_rows_for_sub_column_above_origin($x1c, $this->blocks[reset($x20)], $x20); } elseif ( !$x2d && isset($this->blocks_in_sub_columns) ) {foreach ( $this->x0e[$x1f] as $x2e ) headway_remove_from_array($this->blocks_in_sub_columns, $x2e);foreach ( $this->x0e as $x2f => $x30 ) {if ( $x1f == $x2f )continue; foreach ( $x30 as $x31 => $x32 ) {if ( $x32 == $x1c['id'] ) unset($this->x0e[$x2f][$x31]); if ( count($this->x0e[$x2f]) === 0 ) unset($this->x0e[$x2f]); }}} uasort($this->x0e[$x1f], array(__CLASS__, 'uasort_blocks_by_top_to_left')); if ( isset($this->x0e[$x1f]) )$this->x0e[$x1f] = array_values($this->x0e[$x1f]); }$this->x0e = array_values($this->x0e); } private function step_3a_extract_sub_columns($x33) { if ( isset($this->blocks_in_sub_columns) && in_array($x33, $this->blocks_in_sub_columns) )return false; $x34 = array(); $x35 = $this->blocks[$x33]; foreach ( $this->blocks as $x36 ) {if ( $x35['id'] == $x36['id'] ) continue;if ( isset($this->blocks_in_sub_columns) && in_array($x36['id'], $this->blocks_in_sub_columns) ) continue;if ( $x36['position']['left'] > $x35['position']['left'] + $x35['dimensions']['width'] ) continue;if ( $x36['position']['left'] < $x35['position']['left'] ) continue;if ( $x36['position']['left'] + $x36['dimensions']['width'] > $x35['position']['left'] + $x35['dimensions']['width'] ) continue;$x34[] = $x36['id'];} if ( count($x34) === 0 )return null; $x37 = array(); $x38 = array(); foreach ( $x34 as $x39 )$x38[$x39] = $this->get_block_row($x39); foreach ( $x38 as $x3a => $x3b ) {reset($this->rows[$x3b]); while ( $x3c = current($this->rows[$x3b]) ) {if ( $x3c == $x3a ) {$x3d = headway_array_key_neighbors($this->rows[$x3b], key($this->rows[$x3b]));$x3e = (is_numeric($x3d['prev']) && isset($this->blocks[$x3d['prev']])) ? $this->blocks[$x3d['prev']] : null;$x3f = (is_numeric($x3d['next']) && isset($this->blocks[$x3d['next']])) ? $this->blocks[$x3d['next']] : null;$x40 = $x35['position']['left'];$x41 = $x35['dimensions']['width'];if ( $x3e ) {$x42 = $x3e['position']['left']; $x43 = $x3e['dimensions']['width']; }if ( $x3f ) {$x44 = $x3f['position']['left']; $x45 = $x3f['dimensions']['width']; }if ( $x3e && $x42 + $x43 > $x40 && $x42 < $x40 ) $x37[$x3a] = 'left-block-outside-origin';if ( $x3f && $x44 < $x40 + $x41 ) if ( $x44 + $x45 > $x40 + $x41 )$x37[$x3a] = 'right-block-outside-origin'; if ( isset($x37[$x3a]) ) headway_remove_from_array($x34, $x3a); break; }next($this->rows[$x3b]); } } $x37 = array(); $x46 = $x35['id']; foreach ( $x34 as $x39 ) {$x47 = $this->blocks[$x39];$x48 = $this->blocks[$x46];$x49 = $x39 == reset($x34);if ( $x47['position']['top'] < $x35['position']['top'] ) continue;if ( !($x48['position']['top'] === $x47['position']['top']) || $x49 ) { if ( $x47['position']['top'] <= $x48['dimensions']['height'] + $x48['position']['top'] )$x37[$x39] = 'not below previous'; if ( $x47['position']['top'] > $x48['dimensions']['height'] + $x48['position']['top'] + $this->x11 )$x37[$x39] = 'below previous block and tolerance'; } if ( isset($x37[$x39]) ) headway_remove_from_array($x34, $x39); elseif ( $x47['position']['top'] > $x48['position']['top'] ) $x46 = $x39;}$x4a = array_reverse($x34); $x46 = $x35['id']; foreach ( $x4a as $x39 ) {$x47 = $this->blocks[$x39];$x48 = $this->blocks[$x46];$x49 = $x39 == reset($x4a);if ( $x47['position']['top'] > $x35['position']['top'] ) continue;if ( !($x48['position']['top'] === $x47['position']['top']) || $x49 ) { if ( $x47['position']['top'] + $x47['dimensions']['height'] > $x48['position']['top'] )$x37[$x39] = 'not above previous'; if ( $x47['position']['top'] + $x47['dimensions']['height'] < $x48['position']['top'] - $this->x11 )$x37[$x39] = 'above previous block and tolerance'; } if ( isset($x37[$x39]) )headway_remove_from_array($x34, $x39); elseif ( $x47['position']['top'] < $x48['position']['top'] ) $x46 = $x39;} if ( count($x34) === 0 )return null; $x4b = false; $x4c = false; foreach ( $this->blocks as $x1c ) {if ( in_array($x1c['id'], $x34) || $x1c['id'] == $x33 ) continue; $x4d = $x1c['position']['left'];$x4e = $x1c['position']['top'];$x4f = $x1c['dimensions']['width'];$x50 = $x1c['dimensions']['height'];$x2b = $x35['dimensions']['width'];$x2c = $x35['position']['left'];$x51 = $x35['dimensions']['height'];$x52 = $x35['position']['top'];$x53 = $this->blocks[reset($x34)]['position']['top'];$x54 = $this->blocks[reset($x34)]['dimensions']['height']; $x55 = ( $x53 < $x52 ) ? $x54 : $x51;$x56 = ( $x53 < $x52 ) ? $x53 : $x52;if ( $x4d < $x2c || $x4d >= $x2c + $x2b ) $x4b = true;if ( $x4e >= $x56 && $x4e < $x56 + $x55 ) $x4c = true; } if ( !($x4b && $x4c) )return false; $this->blocks_in_sub_columns = isset($this->blocks_in_sub_columns) ? array_merge($this->blocks_in_sub_columns, $x34) : $x34; return count($x34) > 0 ? $x34 : null;} private function step_3b_remove_block_from_columns($x57) { foreach ( $this->x0e as $x1f => $x58 ) {if ( in_array($x57, $x58) ) {headway_remove_from_array($this->x0e[$x1f], $x57); if ( empty($this->x0e[$x1f]) )unset($this->x0e[$x1f]);return true; } }return false; } private function step_3c_modify_rows_for_sub_column_above_origin($x59, $x5a, array $x20) {if ( $x59['position']['top'] < $x5a['position']['top'] )return false; foreach ( $this->rows as $x5b => $x5c ) {foreach ( $x5c as $x5d ) { if ( $x5d === $x59['id'] )$x5e = $x5b; elseif ( $x5d === $x5a['id'] )$x5f = $x5b;} } $x60 = array(); foreach ( $x20 as $x24 ) {$x61 = $this->blocks[$x24];if ( $x61['position']['top'] + $x61['dimensions']['height'] >= $x59['position']['top'] ) continue;$x60[] = $x24; }$x62 = array_search($x5a['id'], $this->rows[$x5f]);headway_array_insert($this->rows[$x5f], array($x59['id']), $x62); foreach ( $x60 as $x1b )$this->step_3d_remove_block_from_rows($x1b); headway_remove_from_array($this->rows[$x5e], $x59['id']);if ( count($this->rows[$x5f]) === 0 )unset($this->rows[$x5f]); if ( count($this->rows[$x5e]) === 0 )unset($this->rows[$x5e]); }private function step_3d_remove_block_from_rows($x57) { foreach ( $this->rows as $x5b => $x5c ) {if ( in_array($x57, $x5c) ) {headway_remove_from_array($this->rows[$x5b], $x57); if ( empty($this->rows[$x5b]) )unset($this->rows[$x5b]);return true; } }return false; } private function step_4_fetch_column_row_positions() {foreach ( $this->x0e as $x63 => $x0b ) { foreach ( $x0b as $x1b ) {foreach ( $this->rows as $x64 => $x0b ) { if ( in_array($x1b, $x0b) ) { $this->x0f[$x63] = $x64;break; }}if ( isset($this->x0f[$x63]) ) break; }} } private function step_5_add_columns_to_rows() { foreach ( $this->x0f as $x63 => $x64 ) {if ( !isset($this->x0e[$x63]) )continue; $this->x0c[$x64][$x63] = $this->x0e[$x63];} foreach ( $this->x0c as $x64 => $x65 ) uasort($this->x0c[$x64], array(__CLASS__, 'uasort_columns_by_left')); ksort($this->x0c, SORT_NUMERIC); }private function uasort_columns_by_left($x13, $x14) {foreach ( $x13 as $x66 )if ( is_numeric($x66) && $x13 = $x66 ) break;foreach ( $x14 as $x67 )if ( is_numeric($x67) && $x14 = $x67 ) break; $x13 = $this->blocks[$x13]; $x14 = $this->blocks[$x14]; $x16 = $x13['position']['left']; $x18 = $x14['position']['left'];if ( $x16 === $x18 )return 0; return ($x16 < $x18) ? -1 : 1;} private function step_6_add_section_classes() { $x1a = 1;foreach ( $this->x0c as $x68 => $x0e ) {$this->section_classes[$x68] = array();$this->section_classes[$x68]['count'] = $x1a;$x1a++;$x69 = 0; $x6a = 1;foreach ( $x0e as $x6b => $x6c ) { $this->section_classes[$x68][$x6b] = array();foreach ( $x6c as $x6d => $x6e ) {if ( is_numeric($x6e) && isset($this->blocks[$x6e]) ) {$x6f = $this->blocks[$x6e];break; } else continue; }$this->section_classes[$x68][$x6b]['count'] = $x6a;$this->section_classes[$x68][$x6b]['left'] = (int)$x6f['position']['left'] - (int)$x69; $this->section_classes[$x68][$x6b]['absolute-left'] = (int)$x6f['position']['left']; $this->section_classes[$x68][$x6b]['width'] = (int)$x6f['dimensions']['width'];$x69 = (int)$x6f['dimensions']['width'] + (int)$x6f['position']['left'];$x6a++;$x70 = 1;$x22 = 0;$this->section_classes[$x68][$x6b]['sub-columns'] = array();foreach ( $x6c as $x71 => $x72 ) { if ( is_numeric($x72) )continue;$x25 = $this->blocks[reset($x72)]; $x73 = $this->section_classes[$x68][$x6b]['absolute-left']; $x74 = $this->section_classes[$x68][$x6b]['width']; if ( $x22 + (int)$x25['dimensions']['width'] - $x73 > $x74 ) { $x70 = 1;$x22 = 0;} $x75 = $x70 === 1 ? $x73 : 0;$this->section_classes[$x68][$x6b]['sub-columns'][$x71] = array('count' => $x70,'width' => $x25['dimensions']['width'],'left' => $x25['position']['left'] - $x22 - $x75 ); $x22 = (int)$x25['dimensions']['width'] + (int)$x25['position']['left']; $x70++; }if ( count($this->section_classes[$x68][$x6b]['sub-columns']) === 0 ) unset($this->section_classes[$x68][$x6b]['sub-columns']);}} } private function get_block_row($x1b) {foreach ( $this->rows as $x5b => $x5c ) foreach ( $x5c as $x5d )if ( $x5d == $x1b ) return $x5b;return null; } private function render_layout() {echo '<div class="grid-container clearfix">' . "\n";do_action('headway_grid_container_open'); foreach ( $this->x0c as $x68 => $x65 ) { $x76 = array('row','row-' . $this->section_classes[$x68]['count'] ); echo '<section class="' . implode(' ', $x76) . '">' . "\n";do_action('headway_block_row_open'); foreach ( $x65 as $x6b => $x77 ) { $x78 = array('column','column-' . $this->section_classes[$x68][$x6b]['count'],'grid-width-' . $this->section_classes[$x68][$x6b]['width'],'grid-left-' . $this->section_classes[$x68][$x6b]['left'] ); echo '<section class="' . implode(' ', $x78) . '">' . "\n"; do_action('headway_block_column_open'); foreach ( $x77 as $x79 => $x7a ) {if ( !is_array($x7a) ) {$x1c = $this->blocks[$x7a]; HeadwayBlocks::display_block($x1c, 'grid-renderer'); } else { $x7b = array('sub-column','sub-column-' . $this->section_classes[$x68][$x6b]['sub-columns'][$x79]['count'],'column','column-' . $this->section_classes[$x68][$x6b]['sub-columns'][$x79]['count'],'grid-width-' . $this->section_classes[$x68][$x6b]['sub-columns'][$x79]['width'],'grid-left-' . $this->section_classes[$x68][$x6b]['sub-columns'][$x79]['left']);echo '<section class="' . implode(' ', $x7b) . '">'; do_action('headway_block_sub_column_open');foreach ( $x7a as $x7c ) {$x1c = $this->blocks[$x7c];HeadwayBlocks::display_block($x1c, 'grid-renderer'); } do_action('headway_block_sub_column_column');echo '</section><!-- .sub-column -->';}} do_action('headway_block_column_close'); echo '</section><!-- .column -->';}do_action('headway_block_row_close'); echo '</section><!-- .row -->' . "\n\n"; }do_action('headway_grid_container_close'); echo '</div><!-- .grid-container -->' . "\n\n"; } private function display_no_grid_message() {echo '<div id="wrapper-1" class="wrapper wrapper-no-blocks">' . "\n\n";echo '<div class="block-type-content">';echo '<div class="entry-content">';echo '<h1 class="entry-title">' . __('No Content to Display', 'headway') . '</h1>'; $x7d = add_query_arg(array('visual-editor' => 'true', 'visual-editor-mode' => 'grid'), home_url()) . '#layout=' . HeadwayLayout::get_current();if ( HeadwayCapabilities::can_user_visually_edit() ) { echo sprintf(__('<p>There are no blocks to display.  Add some by going to the <a href="%s">Headway Grid</a>!</p>', 'headway'), $x7d);} else { echo sprintf(__('<p>There is no content to display.  Please notify the site administrator or <a href="%s">login</a>.</p>', 'headway'), $x7d); } echo '</div><!-- .entry-content -->';echo '</div><!-- .block-type-content -->';echo '</div><!-- .wrapper -->';return false; } public function display() {$this->step_1_sort_blocks_by_position();$this->step_2_build_rows();$this->step_3_construct_columns();$this->step_4_fetch_column_row_positions();$this->step_5_add_columns_to_rows();$this->step_6_add_section_classes();if ( !$this->blocks ) return $this->display_no_grid_message();do_action('headway_before_wrapper');$x7e = array('wrapper');$x7e[] = !HeadwayResponsiveGrid::is_active() ? 'fixed-grid' : 'responsive-grid';echo '<div id="wrapper-1" class="' . implode(' ', $x7e). '">' . "\n\n"; do_action('headway_wrapper_open'); $this->render_layout();do_action('headway_wrapper_close');echo '</div><!-- .wrapper -->';do_action('headway_after_wrapper'); }}?>
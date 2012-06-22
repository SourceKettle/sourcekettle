<?php
/**
 *
 * Test config file for the Geshi helper
 *
 * Place in APP/Config/geshi.php
 */

 // Header type
 // sets the container for the plugin (Can be pre/div/none)
 $config['geshi']['header_type'] = 'pre';

 // Line Number type
 // sets the line numbering style for the plugin (Can be fancy/normal/none)
 $config['geshi']['line_number_type'] = 'normal';

 // Line Number frequency
 // sets the interval of line highlighting (eg. 1 means every line, 2 is every other. -1 disables)
 $config['geshi']['line_numbers'] = 1;

 // Tab width
 // How many spaces is a tab
 $config['geshi']['set_tab_width'] = 4;

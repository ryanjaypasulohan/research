<?php 

/*
 * Copyright (c) 2016 Gabor Gyorvari
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *  http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class MalwareScanner
{
    //Pretty Colors
    private $ANSI_GREEN = "#00bf02";
    private $ANSI_RED = "#bf0000";
    private $ANSI_YELLOW = "#bfbd00";
    private $ANSI_BLUE = "#000180";
    private $ANSI_OFF = "#000";

    private $dir = '';
    private $extension = array('.php');
    private $flagBase64 = false;
    private $flagChecksum = false;
    private $flagComments = false;
    private $flagHideOk = false;
    private $flagHideWhitelist = false;
    private $flagNoStop = false;
    private $flagPattern = false;
    private $flagTime = false;
    private $flagExtraCheck = false;
    private $flagFollowSymlink = false;
    private $flagLineNumber = false;
    private $flagScanEverything = false;
    private $flagCombinedWhitelist = false;
    private $flagDisableStats = false;
    private $outputFormat = '';
    private $whitelist = array();
    private $ignore = array();
    private $stat = array(
        'directories' => 0,
        'files_scanned' => 0,
        'files_infected' => 0,
    );

    //Pattern File Attributes
    private $patterns_raw = array();
    private $patterns_iraw = array();
    private $patterns_re = array();
    private $patterns_b64functions = array();
    private $patterns_b64keywords = array();
    private $combined_whitelist = array();
    private $combined_whitelist_count = 0;

    /**
     * MalwareScanner constructor.
     *
     * @param bool $cli defines its calling from commandline or using as a library, default is true
     */
    public function __construct($cli = true)
    {
        if ($cli === true) {
            //Read Run Options
            $this->parseArgs();

            $dirs = array();
            if (is_array($this->dir)) {
                // allow multiple directory aka. array
                foreach ($this->dir as $path) {
                    $dirs[] = realpath($path);
                }
            } elseif ($bpos = strpos($this->dir, '{')) {
                // Check path has a "brace", expand it to subdirectories
                foreach (glob($this->dir, GLOB_BRACE) as $path) {
                    $dirs[] = realpath($path);
                }
            } else {
                // only one directory specified
                $dirs = array (realpath($this->dir));
            }

            //Make sure a directory was specified.
            if (empty($dirs)) {
                $this->error('No directory specified or directory doesn\'t exist');
                exit(-1);
            }

            //Initiate Scan
            if (!$this->run($dirs)) {
                exit(-1);
            }
        }
    }

    //Allows the -n/--no-color flag to easily remove color characters.
    private function disableColor()
    {
        $this->ANSI_GREEN = '';
        $this->ANSI_RED = '';
        $this->ANSI_YELLOW = '';
        $this->ANSI_BLUE = '';
        $this->ANSI_OFF = '';
    }

    //Prints the passed 'string' in red text, calls showHelp().
    //Exits
    private function error($msg)
    {
        echo $this->ANSI_RED . 'Error: ' . $msg . $this->ANSI_OFF . PHP_EOL;
        $this->showHelp();
        echo PHP_EOL . $this->ANSI_RED . 'Quiting' . $this->ANSI_OFF . PHP_EOL;
    }

    //Handles pattern loading and saving to the class object
    public function initializePatterns()
    {
        $dir = dirname(__FILE__);
        //Loads either the primary scanning patterns or the base64 patterns depending on -b/--base64 flag
        if (!$this->flagBase64) {
            $this->patterns_raw = $this->loadPatterns($dir . '/definitions/patterns_raw.txt');
            $this->patterns_iraw = $this->loadPatterns($dir . '/definitions/patterns_iraw.txt');
            $this->patterns_re = $this->loadPatterns($dir . '/definitions/patterns_re.txt');
        } else {
            $this->patterns_b64functions = $this->loadPatterns($dir . '/base64_patterns/php_functions.txt');
            $this->patterns_b64keywords = $this->loadPatterns($dir . '/base64_patterns/php_keywords.txt');
        }

        //Adds additional checks to patterns_raw
        //This may be something to move into a pattern file rather than leave hardcoded.
        if ($this->flagExtraCheck) {
            $this->patterns_raw['googleBot'] = '# ';
            $this->patterns_raw['htaccess'] = '# ';
        }
    }

    //Check if the md5 checksum exists in the whitelist and returns true if it does.
    private function inWhitelist($hash)
    {
        if ($this->flagCombinedWhitelist) {
            if ($this->binarySearch($hash, $this->combined_whitelist, $this->combined_whitelist_count) > -1) {
                return true;
            }
        }
        return in_array($hash, $this->whitelist);
    }

    //Check if -i/--ignore flag listed this path to be omitted.
    private function isIgnored($pathname)
    {
        foreach ($this->ignore as $pattern) {
            $match = $this->pathMatches($pathname, $pattern);
            if ($match) {
                return true;
            }
        }
        return false;
    }

    //Loads individual pattern files
    //Skips blank linese
    //Stores most recent comment with the pattern in the list[] array
    //Returns an array of patterns:comments in key:value pairs
    private function loadPatterns($file)
    {
        $last_comment = '';
        $list = array();
        if (is_readable($file)) {
            foreach (file($file) as $pattern) {
                //Check if the line is only whitespace and skips.
                if (strlen(trim($pattern)) == 0) {
                    continue;
                }
                //Check if first char in pattern is a '#' which indicates a comment and skips.
                //Stores the comment to be stored with the pattern in the list as key:value pairs.
                //The pattern is the key and the comment is the value.
                if ($pattern[0] === '#') {
                    $last_comment = $pattern;
                    continue;
                }
                $list[trim($pattern)] = trim($last_comment);
            }
        }
        return $list;
    }

    //Loads the whitelist file
    public function loadWhitelist()
    {
        if (!is_file(__DIR__ . '/whitelist.txt')) {
            return;
        }
        $fp = fopen(__DIR__ . '/whitelist.txt', 'r');
        while (!feof($fp)) {
            $line = fgets($fp);
            $this->whitelist[] = substr($line, 0, 32);
        }
    }

    public function addWordpressChecksums($wp_version)
    {
        $apiurl = 'https://api.wordpress.org/core/checksums/1.0/?version=' . $wp_version;
        $json = json_decode(file_get_contents($apiurl));
        $checksums = $json->checksums;

        if ($checksums->$wp_version == false) { #no checksum returned
            $this->error('Cannot load wordpress checksums from: ' . $apiurl);
            exit(-1);
        }

        foreach ($checksums->$wp_version as $file => $checksum) {
            $this->whitelist[] = $checksum;
        }
    }

    //Handles the getopt() function call, sets attributes according to flags.
    //All flag handling stuff should be setup here.
    private function parseArgs()
    {
        $options = getopt(
            'd:e:i:o:abmcxlhkwnsptLj:E',
            array(
                'directory:',
                'extension:',
                'ignore:',
                'all-output',
                'base',
                'checksum',
                'comment',
                'extra-check',
                'follow-link',
                'help',
                'hide-ok',
                'hide-whitelist',
                'no-color',
                'no-stop',
                'pattern',
                'time',
                'line-number',
                'output-format:',
                'wordpress-version:',
                'scan-everything',
                'combined-whitelist',
                'disable-stats'
            )
        );

        //Help Option should be first
        if (isset($options['help']) || isset($options['h'])) {
            $this->showHelp();
            exit;
        }

        //Options that Require Additional Parameters
        if (isset($options['directory']) || isset($options['d'])) {
            $this->dir = isset($options['directory']) ? $options['directory'] : $options['d'];
        }
        if (isset($options['extension']) || isset($options['e'])) {
            $a = isset($options['extension']) ? $options['extension'] : $options['e'];
            if (!is_array($a)) {
                $a = array($a);
            }
            $this->setExtensions($a);
        }
        if (isset($options['ignore']) || isset($options['i'])) {
            $tmp = isset($options['ignore']) ? $options['ignore'] : $options['i'];
            $this->setIgnore(is_array($tmp) ? $tmp : array($tmp));
        }

        //Simple Flag Options
        if (isset($options['all-output']) || isset($options['a'])) {
            $this->setFlagChecksum(true);
            $this->setFlagComments(true);
            $this->setFlagPattern(true);
            $this->setFlagTime(true);
        }
        if (isset($options['base64']) || isset($options['b'])) {
            $this->setFlagBase64(true);
        }
        if (isset($options['checksum']) || isset($options['m'])) {
            $this->setFlagChecksum(true);
        }
        if (isset($options['comment']) || isset($options['c'])) {
            $this->setFlagComments(true);
        }
        if (isset($options['extra-check']) || isset($options['x'])) {
            $this->setFlagExtraCheck(true);
        }
        if (isset($options['follow-symlink']) || isset($options['l'])) {
            $this->setFlagFollowSymlink(true);
        }
        if (isset($options['hide-ok']) || isset($options['k'])) {
            $this->setFlagHideOk(true);
        }
        if (isset($options['hide-whitelist']) || isset($options['w'])) {
            $this->setFlagHideWhitelist(true);
        }
        if (isset($options['no-color']) || isset($options['n'])) {
            $this->disableColor();
        }
        if (isset($options['no-stop']) || isset($options['s'])) {
            $this->setFlagNoStop(true);
        }
        if (isset($options['pattern']) || isset($options['p'])) {
            $this->setFlagPattern(true);
        }
        if (isset($options['time']) || isset($options['t'])) {
            $this->setFlagTime(true);
        }
        if (isset($options['line-number']) || isset($options['L'])) {
            $this->setFlagLineNumber(true);
        }
        if (isset($options['output-format']) || isset($options['o'])) {
            $tmp = isset($options['output-format']) ? $options['output-format'] : $options['o'];
            $this->setOutputFormat(is_array($tmp) ? $tmp : array($tmp));
        }
        if (isset($options['wordpress-version']) || isset($options['j'])) {
            $tmp = isset($options['wordpress-version']) ? $options['wordpress-version'] : $options['j'];
            $this->addWordpressChecksums($tmp);
        }
        if (isset($options['scan-everything']) || isset($options['E'])) {
            $this->setFlagScanEverything(true);
        }
        if (isset($options['combined-whitelist'])) {
            $this->setFlagCombinedWhitelist(true);
        }
        if (isset($options['disable-stats'])) {
            $this->setFlagDisableStats(true);
        }
    }

    public function setExtensions(array $a)
    {
        $this->extension = array();
        foreach ($a as $ext) {
            if ($ext[0] != '.') {
                $ext = '.' . $ext;
            }
            $this->extension[] = strtolower($ext);
        }
    }

    public function setIgnore(array $a)
    {
        $this->ignore = $a;
    }

    public function setFlagChecksum($b)
    {
        $this->flagChecksum = $b;
    }

    public function setFlagComments($b)
    {
        $this->flagComments = $b;
    }

    public function setFlagPattern($b)
    {
        $this->flagPattern = $b;
    }

    public function setFlagTime($b)
    {
        $this->flagTime = $b;
    }

    public function setFlagLineNumber($b)
    {
        $this->flagLineNumber = $b;
    }

    public function setFlagBase64($b)
    {
        $this->flagBase64 = $b;
    }

    public function setFlagExtraCheck($b)
    {
        $this->flagExtraCheck = $b;
    }

    public function setFlagFollowSymlink($b)
    {
        $this->flagFollowSymlink = $b;
    }

    public function setFlagHideOk($b)
    {
        $this->flagHideOk = $b;
    }

    public function setFlagHideWhitelist($b)
    {
        $this->flagHideWhitelist = $b;
    }

    public function setFlagNoStop($b)
    {
        $this->flagNoStop = $b;
    }

    public function setOutputFormat(array $format)
    {
        $this->outputFormat = array_shift($format);
    }

    public function setFlagScanEverything($b)
    {
        $this->flagScanEverything = $b;
    }

    public function setFlagCombinedWhitelist($b)
    {
        $this->flagCombinedWhitelist = $b;
    }

    public function setFlagDisableStats($b)
    {
        $this->flagDisableStats = $b;
    }

    // @see http://stackoverflow.com/a/13914119
    private function pathMatches($path, $pattern, $ignoreCase = false)
    {
        $expr = preg_replace_callback(
            '/[\\\\^$.[\\]|()?*+{}\\-\\/]/',
            function ($matches) {
                switch ($matches[0]) {
                    case '*':
                        return '.*';
                    case '?':
                        return '.';
                    default:
                        return '\\' . $matches[0];
                }
            },
            $pattern
        );

        $expr = '/' . $expr . '/';
        if ($ignoreCase) {
            $expr .= 'i';
        }

        return (bool)preg_match($expr, $path);
    }

    private function displayMalware($state, $state_color, $path, $pattern, $hash) {
        echo "<div class= '$state scanCont' style='position: relative;'>";
        echo "Path: <strong class='filepath'>$path</strong>";
        if($state != "WHITELIST") {
            echo "<span style='display: block'>Code: $pattern</span>";
        }
        echo "<span style='display: block'>Hash: <span class='selectArea'>$hash</span></span>";
        if($state != "WHITELIST") {
            echo "<a class='add_to_whitelist' href='javascript:;'>Add to Whitelist</a>
            <input type='text' value='$hash $path' class='for_whitelist'>";
        }
        echo "</div>";
    }

    /**
     * Formats and prints the scan result output line by line.
     *
     * Depending on specified options, it will print:
     * - Status code
     * - Last Modified Time
     * - MD5 Hash
     * - File Path
     * - Pattern Matched
     * - The last comment to appear in the pattern file before this pattern
     * - Matching line number
     *
     * @param $found
     * @param $path
     * @param $pattern
     * @param $comment
     * @param $hash
     * @param $lineNumber
     * @param bool $inWhitelist
     */
    private function printPath($found, $path, $pattern, $comment, $hash, $lineNumber, $inWhitelist = false)
    {
        $default_format = '%S ';

        if (!$found && !$inWhitelist) {
            if ($this->flagHideOk) {
                return;
            }
            $state = 'OK';
            $hash = '                                ';
            $state_color = $this->ANSI_GREEN;
        } elseif ($inWhitelist) {
            if ($this->flagHideWhitelist) {
                return;
            }
            $state = 'WHITELIST';
            $state_color = $this->ANSI_YELLOW;
        } else {
            $state = 'ERROR';
            $state_color = $this->ANSI_RED;
        }

        //Include cTime
        if ($this->flagTime) {
            $changed_time = filectime($path);
            $ctime = date('H:i d-m-Y', $changed_time);
            $default_format .= '%T';
        } else {
            $ctime = '';
        }

        //Include Checksum/Hash
        if ($this->flagChecksum) {
            $default_format .= '%M ';
        }

        // '#' and {} included to prevent accidental script execution attempts
        // in the event that script output is pasted into a root terminal
        $default_format .= '# {%F} ';

        //'#' added again as code snippets have the potential to be valid shell commands
        if ($found) {
            if ($this->flagPattern) {
                $default_format .= '%P ';
            }
            if ($this->flagComments) {
                $default_format .= '%C ';
            }
            if ($this->flagLineNumber) {
                $default_format .= '# %L';
            }
        }

        if ($this->outputFormat) {
            $map = array(
                '%S' => $state,
                '%T' => $ctime,
                '%M' => $hash,
                '%F' => $path,
                '%P' => $pattern,
                '%C' => $comment,
                '%L' => $lineNumber,
            );
        } else {
            $map = array(
                '%S' => $state_color . '# ' . $state . $this->ANSI_OFF,
                '%T' => $this->ANSI_BLUE . $ctime . $this->ANSI_OFF,
                '%M' => $this->ANSI_BLUE . $hash . $this->ANSI_OFF,
                '%F' => $path,
                '%P' => $state_color . '#' . $pattern . $this->ANSI_OFF,
                '%C' => $this->ANSI_BLUE . $comment . $this->ANSI_OFF,
                '%L' => $lineNumber,
            );
        }

        if ($this->outputFormat) {
            $format = $this->outputFormat;
        } else {
            $format = trim($default_format);
        }      
        
        $this->displayMalware($state, $state_color, $path, $pattern, $hash);

    }    

    //Recursively scales the file system.
    //Calls the scan() function for each file found.
    private function process($dir)
    {
        $dh = opendir($dir);
        if (!$dh) {
            return;
        }
        $this->stat['directories']++;
        while (($file = readdir($dh)) !== false) {
            // echo "File: " . $file . "<br> Dir: " . $dir;
            if ($file == '.' || $file == '..') {
                continue;
            }
            if ($this->isIgnored($dir . $file)) {
                continue;
            }
            if (!$this->flagFollowSymlink && is_link($dir . $file)) {
                continue;
            }
            if (is_dir($dir . $file)) {
                $this->process($dir . $file . '/');
            } elseif (is_file($dir . $file)) {
                $ext = strtolower(substr($file, strrpos($file, '.')));
                if ($this->flagScanEverything || in_array($ext, $this->extension)) {
                    $this->scan($dir . $file);
                }
            }
        }
        closedir($dh);
    }

    //Prints stats on the run.
    private function report($start, $dir)
    {
        $end = time();
        // echo 'Start time: ' . strftime('%Y-%m-%d %H:%M:%S', $start) . PHP_EOL;
        // echo 'End time: ' . strftime('%Y-%m-%d %H:%M:%S', $end) . PHP_EOL;
        // echo 'Total execution time: ' . ($end - $start) . PHP_EOL;
        // echo 'Base directory: ' . $dir . PHP_EOL;
        echo "<div class='scan_summary'>";
        echo '<p>Total directories scanned: <span>' . $this->stat['directories'] . '</span></p>';
        echo '<p>Total files scanned: <span>' . $this->stat['files_scanned'] . '</span></p>';
        echo '<p>Total malware identified: <span>' . $this->stat['files_infected'] . '</span></p>';

        if($this->stat['files_infected'] == 0) {
            echo "<p>No Malware Detected</p>";
        }

        echo "</div";

    }

    /**
     * Validates the input directory
     *
     * - Calls the load pattern and load whitelist functions
     * - Fetch and load combined whitelist
     * - Calls the process and report functions.
     *
     * @param string|array $dir A directory path or a list of paths in array
     * @return bool
     */
    public function run($dir)
    {
        $this->initializePatterns();

        $this->loadWhitelist();

        if ($this->flagCombinedWhitelist && !$this->updateCombinedWhitelist()) {
            return false;
        }

        $start = time();

        if (!is_array($dir)) {
            $dir = array ($dir);
        }

        foreach ($dir as $path) {
            // Make sure the input is a valid directory path.
            $path = rtrim($path, '/');
            if (!is_dir($path)) {
                $this->error('Specified path is not a directory: ' . $path);
                return false;
            }
            $this->process($path . '/');
        }       

        if (!$this->flagDisableStats && !preg_match("/proweaver_scanner/i", $dir[0]) ) {
            $this->report($start, implode(', ', $dir));
        }
        return true;
    }

    //Loads target file contents for scanning
    //Initiates the multiple scan types by calling the scanLoop function
    public function scan($path)
    {
        $this->stat['files_scanned']++;
        $fileContent = file_get_contents($path);
        $found = false;
        $inWhitelist = false;
        $hash = md5($fileContent);
        $toSearch = '';
        $comment = '';
        

        if ($this->inWhitelist($hash)) {
            $inWhitelist = true;
        } elseif (!$this->flagBase64) {
            $this->scanLoop('scanFunc_STR', $fileContent, $this->patterns_raw, $path, $found, $hash);
            $this->scanLoop('scanFunc_STRI', $fileContent, $this->patterns_iraw, $path, $found, $hash);
            $this->scanLoop('scanFunc_RE', $fileContent, $this->patterns_re, $path, $found, $hash);
        } else {
            $this->scanLoop('scanFunc_STR', $fileContent, $this->patterns_b64functions, $path, $found, $hash);
            $this->scanLoop('scanFunc_STR', $fileContent, $this->patterns_b64keywords, $path, $found, $hash);
        }

        if (!$found) {
            $this->printPath($found, $path, $toSearch, $comment, $hash, 0, $inWhitelist);
            return false;
        }

        $this->stat['files_infected']++;
        return true;
    }

    //Performs raw string, case sensitive matching.
    //Returns true if the raw string exists in the file contents.
    private function scanFunc_STR(&$pattern, &$content)
    {
        return strpos($content, $pattern);
    }

    //Performs raw string, case insensitive matching.
    //Returns true if the raw string exists in the file contents, ignoring case.
    private function scanFunc_STRI(&$pattern, &$content)
    {
        return stripos($content, $pattern);
    }

    //Performs regular expression matching.
    //Returns true if the Regular Expression matches something in the file.
    //Patterns will match multiple lines, though you can use ^$ to match the beginning and end of a line.
    private function scanFunc_RE(&$pattern, &$content)
    {
        $ret = preg_match('/' . $pattern . '/im', $content, $match, PREG_OFFSET_CAPTURE);
        if ($ret) {
            return $match[0][1];
        }
        return false;
    }

    //First parameter '$scanFunction' is a defined function name passed as a string.
    //This function should accept a pattern string and a content string.
    //This function will return true if the pattern exists in the content.
    //See 'scanFunc_STR', 'scanFunc_STRI', 'scanFUNC_RE' above as examples.

    //Loops through all patterns in a file using the passed function name to determine a match.
    //Variables passed by reference for performance and modification access.
    private function scanLoop($scanFunction, &$fileContent, &$patterns, &$path, &$found, $hash)
    {
        if (!$found || $this->flagNoStop) {
            foreach ($patterns as $pattern => $comment) {
                //Call the function that is named in $scanFunction
                //This allows multiple search/match functions to be used without duplicating the loop code.
                $position = $this->$scanFunction($pattern, $fileContent);
                if ($position !== false) {
                    $found = true;
                    $lineNumber = 0;
                    if ($this->flagLineNumber) {
                        if ($pos = strrpos(substr($fileContent, 0, $position), "\n")) {
                            $lineNumber = substr_count(substr($fileContent, 0, $pos + 1), "\n") + 1;
                        }
                    }
                    $this->printPath($found, $path, $pattern, $comment, $hash, $lineNumber);
                    if (!$this->flagNoStop) {
                        return;
                    }
                }
            }
        }
    }

    // @see https://www.mkwd.net/binary-search-algorithm-in-php/
    private function binarySearch($needle, array $haystack, $high, $low = 0)
    {
        $key = false;
        // Whilst we have a range. If not, then that match was not found.
        while ($high >= $low) {
            // Find the middle of the range.
            $mid = (int)floor(($high + $low) / 2);
            // Compare the middle of the range with the needle. This should return <0 if it's in the first part of the range,
            // or >0 if it's in the second part of the range. It will return 0 if there is a match.
            $cmp = strcmp($needle, $haystack[$mid]);
            // Adjust the range based on the above logic, so the next loop iteration will use the narrowed range
            if ($cmp < 0) {
                $high = $mid - 1;
            } elseif ($cmp > 0) {
                $low = $mid + 1;
            } else {
                $key = $mid;
                break;
            }
        }

        return $key;
    }

    private function updateCombinedWhitelist($url = 'https://scr34m.github.io/php-malware-scanner')
    {
        $latest_hash = trim(file_get_contents($url . '/database/compressed.sha256'));
        if ($latest_hash === false) {
            $this->error('Unable to download database checksum');
            return false;
        }

        $file = __DIR__ . '/whitelist.dat';
        if (is_readable($file)) {
            $hash = hash_file('sha256', $file);
            if ($hash != $latest_hash) {
                $download = true;
            } else {
                $download = false;
            }
        } else {
            $download = true;
        }

        if ($download) {
            $data = file_get_contents($url . '/database/compressed.dat');
            if ($data === false) {
                $this->error('Unable to download database');
                return false;
            }

            file_put_contents($file, $data);
            $hash = hash_file('sha256', $file);
            if ($hash != $latest_hash) {
                $this->error('Downloaded database hash mismatch');
            }
        }

        $content = gzdecode(file_get_contents($file));
        $this->combined_whitelist = array();
        $this->combined_whitelist_count = 0;
        foreach (explode("\n", $content) as $line) { // faster than strtok, but needs more memory
            if ($line) {
                $this->combined_whitelist[] = $line;
                $this->combined_whitelist_count++;
            }
        }
        $this->combined_whitelist_count -= 1; // -1 because we use indexes in binary search
        echo 'Combined whitelist records count: ' . ($this->combined_whitelist_count + 1) . PHP_EOL;
        return true;
    }

    //Prints out the usage menu options.
    private function showHelp()
    {
        echo 'Usage: php scan.php -d <directory>' . PHP_EOL;
        echo '    -h                   --help               Show this help message' . PHP_EOL;
        echo '    -d <directory>       --directory          Directory for searching' . PHP_EOL;
        echo '    -e <file extension>  --extension          File Extension to Scan, can be used multiple times' . PHP_EOL;
        echo '    -E                   --scan-everything    Scan all files, with or without extensions' . PHP_EOL;
        echo '    -i <directory|file>  --ignore             Directory of file to ignore' . PHP_EOL;
        echo '    -a                   --all-output         Enables --checksum,--comment,--pattern,--time' . PHP_EOL;
        echo '    -b                   --base64             Scan for base64 encoded PHP keywords' . PHP_EOL;
        echo '    -m                   --checksum           Display MD5 Hash/Checksum of file' . PHP_EOL;
        echo '    -c                   --comment            Display comments for matched patterns' . PHP_EOL;
        echo '    -x                   --extra-check        Adds GoogleBot and htaccess to Scan List' . PHP_EOL;
        echo '    -l                   --follow-symlink     Follow symlinked directories' . PHP_EOL;
        echo '    -k                   --hide-ok            Hide results with \'OK\' status' . PHP_EOL;
        echo '    -w                   --hide-whitelist     Hide results with \'WL\' status' . PHP_EOL;
        echo '    -n                   --no-color           Disable color mode' . PHP_EOL;
        echo '    -s                   --no-stop            Continue scanning file after first hit' . PHP_EOL;
        echo '    -p                   --pattern            Show Patterns next to the file name' . PHP_EOL;
        echo '    -t                   --time               Show time of last file change' . PHP_EOL;
        echo '    -L                   --line-number        Display matching pattern line number in file' . PHP_EOL;
        echo '    -o                   --output-format      Custom defined output format' . PHP_EOL;
        echo '    -j                   --wordpress-version  Version of wordpress to get md5 signatures' . PHP_EOL;
        echo '                         --combined-whitelist Combined whitelist' . PHP_EOL;
        echo '                         --disable-stats      Disable statistics output' . PHP_EOL;

    }

}

// script it's self called and not included
if (isset($argv[0]) && realpath($argv[0]) == realpath(__FILE__)) {
    new MalwareScanner();
}

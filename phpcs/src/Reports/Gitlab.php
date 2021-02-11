<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * block_comments comment created event.
 *
 * @package
 * @copyright  2021 SysBind Ltd. <service@sysbind.co.il>
 * @auther     avi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace PHP_CodeSniffer\Reports;

use PHP_CodeSniffer\Files\File;

class Gitlab implements Report {

    /**
     * @inheritDoc
     */
    public function generateFileReport($report, File $phpcsFile, $showSources = false, $width = 80) {
        $filename = str_replace('\\', '\\\\', $report['filename']);
        $filename = str_replace('"', '\"', $filename);
        $filename = str_replace('/', '\/', $filename);
        $messages = '';
        foreach ($report['messages'] as $line => $lineErrors) {
            foreach ($lineErrors as $column => $colErrors) {
                foreach ($colErrors as $error) {
                    $error['message'] = str_replace("\n", '\n', $error['message']);
                    $error['message'] = str_replace("\r", '\r', $error['message']);
                    $error['message'] = str_replace("\t", '\t', $error['message']);

                    $messagesObject = new \stdClass();
                    $messagesObject->description = $error['message'];
                    $messagesObject->fingerprint = $error['source'];
                    $messagesObject->severity = strtolower($error['type']);
                    $messagesObject->location = new \stdClass();
                    $messagesObject->location->path = $filename;
                    $messagesObject->location->lines = new \stdClass();
                    $messagesObject->location->lines->begin = $line;
                    $messages .= json_encode($messagesObject).",";
                }
            }
        }
        echo rtrim($messages, ',');
        return true;
    }

    /**
     * @inheritDoc
     */
    public function generate($cachedData, $totalFiles, $totalErrors, $totalWarnings, $totalFixable, $showSources = false,
        $width = 80, $interactive = false, $toScreen = true) {
        echo '[';
        echo rtrim($cachedData, ',');
        echo "]".PHP_EOL;
    }
}

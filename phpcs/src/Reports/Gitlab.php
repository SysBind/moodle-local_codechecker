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
        $hasOutput = false;

        foreach ($report['messages'] as $line => $lineErrors) {
            foreach ($lineErrors as $column => $colErrors) {
                foreach ($colErrors as $error) {
                    $issue = [
                        'type' => 'issue',
                        'categories' => ['Style'],
                        'check_name' => $error['source'],
                        'fingerprint' => md5($report['filename'] . $error['message'] . $line . $column),
                        'severity' => $error['type'] === 'ERROR' ? 'major' : 'minor',
                        'description' => str_replace(["\n", "\r", "\t"], ['\n', '\r', '\t'], $error['message']),
                        'location' => [
                            'path' => $report['filename'],
                            'lines' => [
                                'begin' => $line,
                                'end' => $line,
                            ]
                        ],
                    ];

                    echo json_encode($issue, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ',';
                    $hasOutput = true;
                }
            }
        }

        return $hasOutput;
    }

    /**
     * @inheritDoc
     */
    public function generate($cachedData, $totalFiles, $totalErrors, $totalWarnings, $totalFixable, $showSources = false,
        $width = 80, $interactive = false, $toScreen = true) {
        echo '[' . rtrim($cachedData, ',') . ']' . PHP_EOL;
    }
}

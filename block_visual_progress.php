<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Block visual_progress is defined here.
 *
 * @package     block_visual_progress
 * @copyright   2026 Renzo Medina <medinast30@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_visual_progress extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_visual_progress');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        } else {
            global $OUTPUT, $COURSE, $USER;
            $completion = new completion_info($COURSE);
            if(!$completion->is_enabled()) {
                $this->content->text = $OUTPUT->render_from_template('block_visual_progress/main', [
                'progress' => false,
                'message'  => get_string('completionnotenabled', 'block_visual_progress'),
            ]);
                return $this->content;
            }
            $modinfo = get_fast_modinfo($COURSE);
            $total = 0;
            $completed = 0;

            foreach ($modinfo->cms as $cm) {
                if ($cm->completion == COMPLETION_TRACKING_NONE) {
                    continue;
                }
                
                $total++;
                
                $details = \core_completion\cm_completion_details::get_instance(
                    $cm,
                    $USER->id,
                    true
                );
                
                if ($details->is_overall_complete()) {
                    $completed++;
                }
            }

            $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
            $valueinfo = '';
            if ($percentage <= 0 ) {
                $valueinfo = get_string("progressvalueinfo", "block_visual_progress");
            }else if ($percentage <= 25) {
                $valueinfo = get_string("progressvalueinfo1", "block_visual_progress");
            }else if ($percentage <= 50) {
                $valueinfo = get_string("progressvalueinfo2", "block_visual_progress");
            }else if ($percentage <= 75) {
                $valueinfo = get_string("progressvalueinfo3", "block_visual_progress");    
            }else {
                $valueinfo = get_string("progressvalueinfo4", "block_visual_progress");
            }
            $context = \context_course::instance($COURSE->id);
            $isteacher = has_capability('moodle/grade:viewall', $context);
            $showteacherview = isset($this->config->viewteacher) ? !empty($this->config->viewteacher) : true;
            $studentslist = [];
            $groupaverage = 0;
            if ($isteacher && $showteacherview) {
                $students = get_enrolled_users($context, 'moodle/course:isincompletionreports');
                $totalstudents = 0;
                $sumpercentages = 0;
                foreach ($students as $student) {
                    $studentcompleted = 0;
                    foreach ($modinfo->cms as $cm) {
                        if ($cm->completion == COMPLETION_TRACKING_NONE) {
                            continue;
                        }
                        
                        $details = \core_completion\cm_completion_details::get_instance(
                            $cm,
                            $student->id,
                            true
                        );
                        
                        if ($details->is_overall_complete()) {
                            $studentcompleted++;
                        }
                    }
                    $studentpercentage = $total > 0 ? round(($studentcompleted / $total) * 100) : 0;
                    $sumpercentages += $studentpercentage;
                    $totalstudents++;
                    $studentslist[] = [
                        'name'     => fullname($student),
                        'progress' => $studentpercentage,
                    ];
                }
                $groupaverage = $totalstudents > 0 ? round($sumpercentages / $totalstudents) : 0;
            }
            $template = [
                'progress' => !($isteacher && $showteacherview),
                'percentage' => $percentage,
                'message' => '',
                'info' => $valueinfo,
                'isteacher'      => $isteacher && $showteacherview,
                'groupaverage'   => $groupaverage,
                'students'       => $studentslist,
            ];
            $this->content->text = $OUTPUT->render_from_template('block_visual_progress/main', $template);
        }

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_visual_progress');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return [
            "course-view" => true,
        ];
    }

    /**
     * Performs a self-test to check if the block is working correctly.
     *
     * @return bool True if the test passed, false otherwise.
     */
    function _self_test() {
        return true;
    }
    
    /**
     * Summary of instance_allow_config
     * @return bool
     */
    public function instance_allow_config() {
        return true;
    }
}

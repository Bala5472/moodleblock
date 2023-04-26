<?php
class block_moodleblock extends block_base {
    public function init() {
        $this->title = get_string('moodleblock', 'block_moodleblock');
    }

     public function applicable_formats() {
        return array('site-index' => true, 'course-view-*' => true);
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        
        global $OUTPUT,$COURSE,$DB,$USER,$CFG;

        if (!is_null($this->content)) {
            return $this->content;
        }


        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text   = '';

        $cmmodules = $DB->get_records('course_modules',array('course' => $COURSE->id));
        if($cmmodules){


	        $table = new html_table();
	        $table->head =  array('Cmid','Activityname','Date of Creation','Completion Status');
	        $data =  array();
	        foreach ($cmmodules as $key => $cmmodule) {
	 			$row = array();
	 			$completionstate = $DB->get_record('course_modules_completion',array('coursemoduleid' =>  $cmmodule->id,'userid' => $USER->id,'completionstate' => 1));
	 			$modulename = $DB->get_field('modules','name',array('id' => $cmmodule->module));
	 			$activityname = $DB->get_field($modulename,'name',array('id' => $cmmodule->instance));
	 			$row[] = $cmmodule->id;
	 			$row[] = "<a href=".$CFG->wwwroot."/mod/".$modulename."/view.php?id=".$cmmodule->id.">".$activityname."</a>";
	 			$row[] = $cmmodule ? date('d-m-Y',$cmmodule->timecreated) : 'NA';
	 			$row[] = $completionstate ? 'Completed' : 'Not Completed';
	 			$data[] =  $row;
	        }
	        $table->data = $data;
	        // print_object($table);exit;
	        $this->content->text = html_writer::table($table);
	    }
        return $this->content;
    }

}
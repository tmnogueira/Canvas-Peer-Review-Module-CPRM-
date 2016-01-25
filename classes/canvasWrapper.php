<?php

class CanvasWrapper
{
	private $canvas;
	
	public function __construct() {
		require_once('classes/canvas.php');
		$canvas = new Canvas();
	}
	
	/*
	 * takes json result and formats it for printing
	 * 
	 */ 
	public function formatCourseData() {
			
		$canvas->getCoursesForUser();
		
		foreach ($canvas->getData() as $data) {
			$this->buttonization($data->id, $data->name);
		}
	}
	
	/*
	 * directly echos button content to page
	 * @param $id an id for the button
	 * @param $title a title between the tags
	 * 
	 */
	private function buttonization($id, $title) {
		echo "<button id='" . $id . "' type='button' class='btn btn-default'>" . $title . "</button>";
	}
}
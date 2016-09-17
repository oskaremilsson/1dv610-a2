<?php
namespace view;

class DateTimeView {

	public function show() {
		//Saturday, the 17th of September 2016, The time is
		$time = getdate();

		$timeString = $time['weekday'] . ", the " . $time['mday'] . "th of ". $time['month'] . " " . $time['year'] .
			", The time is " . $time['hours'] . ":" . $time['minutes'] . ":" . $time['seconds'];

		return '<p>' . $timeString . '</p>';
	}
}

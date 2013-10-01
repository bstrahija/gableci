<?php

if ( ! function_exists('partial'))
{
	function partial($view, $data = array(), $mergeData = array())
	{
		echo View::make($view, $data, $mergeData)->render();
	}
}

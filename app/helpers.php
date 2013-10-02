<?php

if ( ! function_exists('partial'))
{
	function partial($view, $data = array(), $mergeData = array())
	{
		echo View::make($view, $data, $mergeData)->render();
	}
}

if ( ! function_exists('hbs_template'))
{
	function hbs_template($view, $data = array(), $mergeData = array())
	{
		$view = str_replace(".", "/", $view);

		include(app_path() . '/views/' . $view . '.hbs');
	}
}

if ( ! function_exists('array_has'))
{
	/**
	 * Check if an item list has a specific value in the list
	 * @param  array  $array
	 * @param  string $key
	 * @param  mixed  $value
	 * @return mixed
	 */
	function array_has(&$array, $key, $value = null)
	{
		foreach ($array as $i => &$el)
		{
			if (is_array($el))
			{
				if (isset($el[$key]) and $el[$key] == $value) return $i;
			}
			elseif (is_object($el))
			{
				if (isset($el->$key) and $el->$key == $value) return $i;
			}
		}

		return false;
	}
}

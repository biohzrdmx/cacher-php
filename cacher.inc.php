<?php
	/**
	 * Cacher
	 * @author 	biohzrdmx <github.com/biohzrdmx>
	 * @version 1.0
	 * @license MIT
	 * @example Basic usage:
	 *
	 *    // You'll need a folder named 'cache' on the same folder of this file with write permissions
	 *    $stuff = Cacher::getFromCache('stuff', 900);
	 *    if (! $stuff ) {
	 *        $stuff = performTimeConsumingTaskToRetrieveTheStuff();
	 *        Cacher::saveToCache('stuff', $stuff);
	 *    }
	 *
	 */
	class Cacher {

		/**
		 * Simple shim for baseDir (for Hummingbird/Dragonfly you should use the built-in method)
		 * @param  string $path Path
		 * @return string       Fully-qualified path
		 */
		static function baseDir($path) {
			$dir = dirname(__FILE__);
			$ret = "{$dir}{$path}";
			return $ret;
		}

		/**
		 * Get data from cache
		 * @param  string  $name    Name of the cache item
		 * @param  integer $time    How much time should the cache live
		 * @param  boolean $default A default return value if the cached data is too old
		 * @return mixed            The cached data
		 */
		static function getFromCache($name, $time = 900, $default = false) {
			$ret = $default;
			$file = self::baseDir("/cache/{$name}.cache");
			if ( file_exists($file) && time() - filemtime($file) < $time) {
				$ret = file_get_contents($file);
				$decoded = @json_decode($ret);
				$ret = $decoded ? $decoded : $ret;
			}
			return $ret;
		}

		/**
		 * Save data to the cache
		 * @param  string $name Name of the cache item
		 * @param  mixed  $data The data to store in cache
		 * @return boolean      TRUE if the data was stored, FALSE otherwise
		 */
		static function saveToCache($name, $data) {
			$file = self::baseDir("/cache/{$name}.cache");
			if ( is_object($data) || is_array($data) ) {
				$data = json_encode($data);
			}
			$ret = file_put_contents($file, $data);
			return $ret;
		}

	}

?>
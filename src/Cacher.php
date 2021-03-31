<?php
	/**
	 * Cacher
	 * A simple, general-purpose, file-based cache provider
	 * @author 	biohzrdmx <github.com/biohzrdmx>
	 * @version 2.0
	 * @license MIT
	 */

	namespace Cacher;

	class Cacher {

		/**
		 * Cache directory
		 * @var string
		 */
		protected $cache_dir;

		/**
		 * Cache time
		 * @var integer
		 */
		protected $cache_time;

		/**
		 * Serializer function
		 * @var callable
		 */
		protected $serializer;

		/**
		 * Unserializer function
		 * @var callable
		 */
		protected $unserializer;

		/**
		 * Constructor
		 * @param string  $cache_dir  Where to store the cached items
		 * @param integer $cache_time How much time should the cache live
		 */
		function __construct($cache_dir, $cache_time = 900) {
			$this->cache_dir = $cache_dir;
			$this->cache_time = $cache_time;
			$this->serializer = 'serialize';
			$this->unserializer = 'unserialize';
		}

		/**
		 * Set the serializer function
		 * @param callable $serializer The serializer callback to convert from object/array to string
		 * @return $this               The Cacher instance
		 */
		public function setSerializer($serializer) {
			$this->serializer = $serializer;
			return $this;
		}

		/**
		 * Set the unserializer function
		 * @param callable $unserializer The unserializer callback to convert from string to object/array
		 * @return $this                 The Cacher instance
		 */
		public function setUnserializer($unserializer) {
			$this->unserializer = $unserializer;
			return $this;
		}

		/**
		 * Check whether something has been cached and is still fresh or not
		 * @param  string  $name Name of the cache item
		 * @return boolean       TRUE if the item exists and is fresh, FALSE otherwise
		 */
		public function isCached($name) {
			$hash = md5($name);
			$file = sprintf("{$this->cache_dir}/{$hash}");
			return file_exists($file) && time() - filemtime($file) < $this->cache_time;
		}

		/**
		 * Delete something from the cache
		 * @param  string $name Name of the cached item
		 * @return $this        The Cacher instance
		 */
		public function delete($name) {
			$hash = md5($name);
			$file = sprintf("{$this->cache_dir}/{$hash}");
			if ( file_exists($file) ) {
				unlink($file);
			}
			return $this;
		}

		/**
		 * Get data from cache
		 * @param  string  $name    Name of the cache item
		 * @param  boolean $default A default return value if the cached data is too old or doesn't exist
		 * @return mixed            The cached data
		 */
		public function retrieve($name, $default = false) {
			$ret = $default;
			$hash = md5($name);
			$file = sprintf("{$this->cache_dir}/{$hash}");
			if ( $this->isCached($name) ) {
				$ret = file_get_contents($file);
				$decoded = @call_user_func($this->unserializer, $ret);
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
		public function store($name, $data) {
			$ret = false;
			$hash = md5($name);
			$file = sprintf("{$this->cache_dir}/{$hash}");
			if ( is_object($data) || is_array($data) ) {
				$data = call_user_func($this->serializer, $data);
			}
			if ($data) {
				$ret = file_put_contents($file, $data) !== false;
			}
			return $ret;
		}

	}

?>
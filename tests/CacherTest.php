<?php
	namespace Cacher\Tests;

	use PHPUnit\Framework\TestCase;
	use Cacher\Cacher;

	class CacherTest extends TestCase {

		protected $simple = 'foo';
		protected $complex = ['foo' => 'bar', 'bar' => 'baz'];

		public function testStore() {
			$cacher = new Cacher(dirname(__FILE__) . '/cache');
			# Use JSON for this test
			$cacher->setSerializer('json_encode');
			# Cache simple data
			$ret = $cacher->store('simple', $this->simple);
			# Cache complex data
			$ret = $cacher->store('complex', $this->complex);
			$this->assertTrue($ret);
		}

		public function testIsCached() {
			$cacher = new Cacher(dirname(__FILE__) . '/cache');
			# Check for simple data
			$ret = $cacher->isCached('simple');
			# Check for complex data
			$ret = $cacher->isCached('complex');
			$this->assertTrue($ret);
		}

		public function testRetrieve() {
			$cacher = new Cacher(dirname(__FILE__) . '/cache');
			# Use JSON for this test
			$cacher->setUnserializer('json_decode');
			# Retrieve simple data
			$ret = $cacher->retrieve('simple');
			$this->assertEquals($this->simple, $ret);
			# Retrieve complex data
			$ret = $cacher->retrieve('complex');
			$this->assertEquals((object) $this->complex, $ret);
		}

		public function testDelete() {
			$cacher = new Cacher(dirname(__FILE__) . '/cache');
			# Retrieve simple data
			$cacher->delete('simple');
			$this->assertFalse( $cacher->isCached('simple') );
			# Retrieve complex data
			$cacher->delete('complex');
			$this->assertFalse( $cacher->isCached('complex') );
		}
	}

?>
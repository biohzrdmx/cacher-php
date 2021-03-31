cacher-php
=============

A simple, general-purpose, file-based cache provider

### Basic usage

First require `biohzrdmx/cacher-php` with Composer.

Now create a `Cacher` instance passing the cache directory and optionally, the caching time:

```php
$cacher = new Cacher(dirname(__FILE__) . '/cache');
```

Once you've created the instance you may start manipulating the cache items, for example, by adding a new item:

```php
$cacher->store('results', $results);
```

The next time you need to use the `results` data, just recover them from the cache. The first thing to do is check if it exists and if it's still fresh:

```php
$cacher->isCached('results');
```

The `isCached` method will return `true` if the data is in the cache and is fresh enough. Once you've checked it, recover its contents:

```php
$results = $cacher->retrieve('results');
```

And that's it. Easy peasy.

Usually all those operations are executed in the following form:

```php
$cacher = new Cacher(BASE_DIR . '/cache');

if ( $cacher->isCached('results') ) {
	$results = $cacher->retrieve('results');
} else {
	$results = fetchResultsFromSomeSource();
	$cacher->store('results', $results);
}
```

You may also specify a serialize and unserialize callback with the `setSerializer($serializer)` and `setUnserializer($unserializer)` methods.

Finally if you want to delete a cached item you may do so with the `delete($name)` function.

###Licensing

This software is released under the MIT license.

Copyright © 2021 biohzrdmx

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

###Credits

**Lead coder:** biohzrdmx [github.com/biohzrdmx](http://github.com/biohzrdmx)
<?php
/*
Plugin Name: Memcached Redux
Description: The real Memcached (not Memcache) backend for the WP Object Cache.
Version: 0.1.7
Plugin URI: http://wordpress.org/extend/plugins/memcached-redux/
Author: Scott Taylor - uses code from Ryan Boren, Denis de Bernardy, Matt Martz, Mike Schroder, Mika Epstein, Ihor Vorotnov

Install this file to wp-content/object-cache.php
*/

if ( ! defined( 'WP_CACHE_KEY_SALT' ) ) {
	define( 'WP_CACHE_KEY_SALT', '' );
}

if ( class_exists( 'Memcached' ) ) :

	/**
	 * Adds data to the cache if it doesn't already exist.
	 *
	 * @see WP_Object_Cache::add()
	 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
	 *
	 * @param int|string $key    What to call the contents in the cache.
	 * @param mixed      $data   The contents to store in the cache.
	 * @param string     $group  Optional. Where to group the cache contents. Default 'default'.
	 * @param int        $expire Optional. When to expire the cache contents. Default 0 (no expiration).
	 *
	 * @return bool False if cache key and group already exist, true on success
	 */
	function wp_cache_add( $key, $data, $group = '', $expire = 0 ) {
		global $wp_object_cache;

		return $wp_object_cache->add( $key, $data, $group, $expire );
	}

	/**
	 * Increments numeric cache item's value.
	 *
	 * @todo Rename $n to $offset to match WP_Object_Cache method signature.
	 *
	 * @see WP_Object_Cache::incr()
	 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
	 *
	 * @param int|string $key    The cache key to increment
	 * @param int        $n      Optional. The amount by which to increment the item's value. Default 1.
	 * @param string     $group  Optional. The group the key is in. Default 'default'.
	 *
	 * @return false|int False on failure, the item's new value on success.
	 */
	function wp_cache_incr( $key, $n = 1, $group = '' ) {
		global $wp_object_cache;

		return $wp_object_cache->incr( $key, $n, $group );
	}

	/**
	 * Decrements numeric cache item's value.
	 *
	 * @todo Rename $n to $offset to match WP_Object_Cache method signature.
	 *
	 * @see WP_Object_Cache::decr()
	 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
	 *
	 * @param int|string $key    The cache key to decrement.
	 * @param int        $n      Optional. The amount by which to decrement the item's value. Default 1.
	 * @param string     $group  Optional. The group the key is in. Default 'default'.
	 *
	 * @return false|int False on failure, the item's new value on success.
	 */
	function wp_cache_decr( $key, $n = 1, $group = '' ) {
		global $wp_object_cache;

		return $wp_object_cache->decr( $key, $n, $group );
	}

	/**
	 * Closes the cache.
	 *
	 * @todo Just return true, as wp_cache_close() does. WP_Object_Cache::close method is void.
	 *
	 * @see wp_cache_close() for explanation.
	 * @see WP_Object_Cache::close()
	 *
	 * @return bool Always returns true.
	 */
	function wp_cache_close() {
		global $wp_object_cache;

		return $wp_object_cache->close();
	}

	/**
	 * Removes the cache contents matching key and group.
	 *
	 * @todo WP_Object_Cache::delete() sets $group = 'default'. Document it.
	 *
	 * @see WP_Object_Cache::delete()
	 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
	 *
	 * @param int|string $key   What the contents in the cache are called.
	 * @param string     $group Optional. Where the cache contents are grouped. Default empty.
	 *
	 * @return bool True on successful removal, false on failure.
	 */
	function wp_cache_delete( $key, $group = '' ) {
		global $wp_object_cache;

		return $wp_object_cache->delete( $key, $group );
	}

	/**
	 * Clears the object cache of all data.
	 *
	 * @see WP_Object_Cache::flush()
	 * @global WP_Object_Cache $wp_object_cache Object cache global instance
	 *
	 * @return bool Always returns true.
	 */
	function wp_cache_flush() {
		global $wp_object_cache;

		return $wp_object_cache->flush();
	}

	/**
	 * Retrieves the cache contents from the cache by key and group.
	 *
	 * @todo $found is null, not bool (or is it, later?). Document the default value.
	 *
	 * @see WP_Object_Cache::get()
	 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
	 *
	 * @param int|string $key   The key under which the cache contents are stored.
	 * @param string     $group Optional. Where the cache contents are grouped. Default empty.
	 * @param bool       $force Optional. Whether to force an update of the local cache from the persistent cache. Default false.
	 * @param bool       $found Optional. Whether the key was found in the cache (passed by reference). Disambiguates a return of false, a storable value. Default null.
	 *
	 * @return bool|mixed False on failure to retrieve contents or the cache contents on success
	 */
	function wp_cache_get( $key, $group = '', $force = false, &$found = null ) {
		global $wp_object_cache;

		return $wp_object_cache->get( $key, $group, $force, $found );
	}

	/**
	 * Multi-get, available for Memcache**D** only.
	 *
	 * @todo Document it properly.
	 *
	 * $keys_and_groups = array(
	 *      array( 'key', 'group' ),
	 *      array( 'key', '' ),
	 *      array( 'key', 'group' ),
	 *      array( 'key' )
	 * );
	 *
	 * @param array  $key_and_groups
	 * @param string $bucket
	 *
	 * @return array
	 */
	function wp_cache_get_multi( $key_and_groups, $bucket = 'default' ) {
		global $wp_object_cache;

		return $wp_object_cache->get_multi( $key_and_groups, $bucket );
	}

	/**
	 * Multi-set, available for Memcache**D** only.
	 *
	 * @todo Document it properly.
	 * @todo Fix void return type.
	 *
	 * $items = array(
	 *      array( 'key', 'data', 'group' ),
	 *      array( 'key', 'data' )
	 * );
	 *
	 * @param array  $items
	 * @param int    $expire
	 * @param string $group
	 *
	 * @return void
	 */
	function wp_cache_set_multi( $items, $expire = 0, $group = 'default' ) {
		global $wp_object_cache;

		return $wp_object_cache->set_multi( $items, $expire = 0, $group = 'default' );
	}

	/**
	 * Sets up Object Cache Global and assigns it.
	 *
	 * @global WP_Object_Cache $wp_object_cache
	 */
	function wp_cache_init() {
		global $wp_object_cache;

		$wp_object_cache = new WP_Object_Cache();
	}

	/**
	 * Replaces the contents of the cache with new data.
	 *
	 * @todo WP_Object_Cache::delete() sets $group = 'default'. Document it.
	 *
	 * @see WP_Object_Cache::replace()
	 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
	 *
	 * @param int|string $key    The key for the cache data that should be replaced.
	 * @param mixed      $data   The new data to store in the cache.
	 * @param string     $group  Optional. The group for the cache data that should be replaced. Default empty.
	 * @param int        $expire Optional. When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return bool False if original value does not exist, true if contents were replaced
	 */
	function wp_cache_replace( $key, $data, $group = '', $expire = 0 ) {
		global $wp_object_cache;

		return $wp_object_cache->replace( $key, $data, $group, $expire );
	}

	/**
	 * Saves the data to the cache.
	 *
	 * Differs from wp_cache_add() and wp_cache_replace() in that it will always write data.
	 *
	 * @todo WP_Object_Cache::delete() sets $group = 'default'. Document it.
	 *
	 * @see WP_Object_Cache::set()
	 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
	 *
	 * @param int|string $key    The cache key to use for retrieval later.
	 * @param mixed      $data   The contents to store in the cache.
	 * @param string     $group  Optional. Where to group the cache contents. Enables the same key to be used across groups. Default empty.
	 * @param int        $expire Optional. When to expire the cache contents, in seconds. Default 0 (no expiration).
	 *
	 * @return bool False on failure, true on success
	 */
	function wp_cache_set( $key, $data, $group = '', $expire = 0 ) {
		global $wp_object_cache;

		if ( defined( 'WP_INSTALLING' ) === false ) {
			return $wp_object_cache->set( $key, $data, $group, $expire );
		}

		return $wp_object_cache->delete( $key, $group );
	}

	/**
	 * Adds a group or set of groups to the list of global groups.
	 *
	 * @todo Document current global groups.
	 *
	 * @see WP_Object_Cache::add_global_groups()
	 * @global WP_Object_Cache $wp_object_cache Object cache global instance.
	 *
	 * @param string|array $groups A group or an array of groups to add.
	 */
	function wp_cache_add_global_groups( $groups ) {
		global $wp_object_cache;

		$wp_object_cache->add_global_groups( $groups );
	}

	/**
	 * Adds a group or set of groups to the list of non-persistent groups.
	 *
	 * @todo Default cache doesn't persist, default function does nothing.
	 *
	 * @param string|array $groups A group or an array of groups to add.
	 */
	function wp_cache_add_non_persistent_groups( $groups ) {
		global $wp_object_cache;

		$wp_object_cache->add_non_persistent_groups( $groups );
	}

	class WP_Object_Cache
	{

		/**
		 * @todo Document all class properties.
		 * @todo Verify whether public access modifiers are needed here.
		 */
		public $global_groups = array();
		public $no_mc_groups = array();
		public $cache = array();
		public $mc = array();
		public $stats = array();
		public $group_ops = array();
		public $cache_enabled = true;
		public $default_expiration = 0;

		/**
		 * @todo Document it.
		 *
		 * @param        $id
		 * @param        $data
		 * @param string $group
		 * @param int    $expire
		 *
		 * @return bool
		 */
		function add( $id, $data, $group = 'default', $expire = 0 ) {
			$key = $this->key( $id, $group );

			if ( is_object( $data ) ) {
				$data = clone $data;
			}

			if ( in_array( $group, $this->no_mc_groups ) ) {
				$this->cache[$key] = $data;
				return true;
			} elseif ( isset( $this->cache[$key] ) && $this->cache[$key] !== false ) {
				return false;
			}

			$mc =& $this->get_mc( $group );
			$expire = ( $expire === 0) ? $this->default_expiration : $expire;
			$result = $mc->add( $key, $data, $expire );

			if ( false !== $result ) {
				++$this->stats['add'];
				$this->group_ops[$group][] = "add $id";
				$this->cache[$key] = $data;
			}

			return $result;
		}

		/**
		 * @todo Document it.
		 *
		 * @param $groups
		 */
		function add_global_groups( $groups ) {
			if ( ! is_array( $groups ) ) {
				$groups = (array) $groups;
			}

			$this->global_groups = array_merge( $this->global_groups, $groups );
			$this->global_groups = array_unique( $this->global_groups );
		}

		/**
		 * @todo Document it.
		 *
		 * @param $groups
		 */
		function add_non_persistent_groups( $groups ) {
			if ( ! is_array( $groups ) ) {
				$groups = (array) $groups;
			}

			$this->no_mc_groups = array_merge( $this->no_mc_groups, $groups );
			$this->no_mc_groups = array_unique( $this->no_mc_groups );
		}

		/**
		 * @todo Document it.
		 *
		 * @param        $id
		 * @param int    $n
		 * @param string $group
		 *
		 * @return mixed
		 */
		function incr( $id, $n = 1, $group = 'default' ) {
			$key = $this->key( $id, $group );
			$mc =& $this->get_mc( $group );
			$this->cache[ $key ] = $mc->increment( $key, $n );

			return $this->cache[ $key ];
		}

		/**
		 * @todo Document it.
		 *
		 * @param        $id
		 * @param int    $n
		 * @param string $group
		 *
		 * @return mixed
		 */
		function decr( $id, $n = 1, $group = 'default' ) {
			$key = $this->key( $id, $group );
			$mc =& $this->get_mc( $group );
			$this->cache[ $key ] = $mc->decrement( $key, $n );

			return $this->cache[ $key ];
		}

		/**
		 * @todo Document it.
		 */
		function close() {
			// Silence is Golden.
		}

		/**
		 * @todo Document it.
		 *
		 * @param        $id
		 * @param string $group
		 *
		 * @return bool
		 */
		function delete( $id, $group = 'default' ) {
			$key = $this->key( $id, $group );

			if ( in_array( $group, $this->no_mc_groups ) ) {
				unset( $this->cache[$key] );
				return true;
			}

			$mc =& $this->get_mc( $group );

			$result = $mc->delete( $key );

			if ( false !== $result ) {
				++$this->stats['delete'];
				$this->group_ops[$group][] = "delete $id";
				unset( $this->cache[$key] );
			}

			return $result;
		}

		/**
		 * @todo Document it.
		 *
		 * @return bool
		 */
		function flush() {
			// Don't flush if multi-blog.
			if ( function_exists( 'is_site_admin' ) || defined( 'CUSTOM_USER_TABLE' ) && defined( 'CUSTOM_USER_META_TABLE' ) ) {
				return true;
			}

			$ret = true;

			foreach ( array_keys( $this->mc ) as $group ) {
				$ret &= $this->mc[ $group ]->flush();
			}

			return $ret;
		}

		/**
		 * @todo Document it.
		 *
		 * @param        $id
		 * @param string $group
		 * @param bool   $force
		 * @param null   $found
		 *
		 * @return bool|mixed
		 */
		function get( $id, $group = 'default', $force = false, &$found = null ) {
			$key = $this->key( $id, $group );
			$mc =& $this->get_mc( $group );
			$found = false;

			if ( isset( $this->cache[$key] ) && ( ! $force || in_array( $group, $this->no_mc_groups ) ) ) {
				$found = true;

				if ( is_object( $this->cache[$key] ) ) {
					$value = clone $this->cache[ $key ];
				} else {
					$value = $this->cache[ $key ];
				}
			} else if ( in_array( $group, $this->no_mc_groups ) ) {
				$this->cache[$key] = $value = false;
			} else {
				$value = $mc->get( $key );

				if ( empty( $value ) || ( is_int( $value ) && -1 === $value ) ){
					$value = false;
					$found = $mc->getResultCode() !== Memcached::RES_NOTFOUND;
				} else {
					$found = true;
				}

				$this->cache[$key] = $value;
			}

			if ( $found ) {
				++$this->stats['get'];
				$this->group_ops[$group][] = "get $id";
			} else {
				++$this->stats['miss'];
			}

			if ( 'checkthedatabaseplease' === $value ) {
				unset( $this->cache[$key] );
				$value = false;
			}

			return $value;
		}

		/**
		 * @todo Document it.
		 *
		 * @param array  $keys
		 * @param string $group
		 *
		 * @return array
		 */
		function get_multi( $keys, $group = 'default' ) {
			$return = array();
			$gets = array();

			foreach ( $keys as $i => $values ) {
				$mc =& $this->get_mc( $group );
				$values = (array) $values;

				if ( empty( $values[1] ) ) {
					$values[1] = 'default';
				}

				list( $id, $group ) = (array) $values;
				$key = $this->key( $id, $group );

				if ( isset( $this->cache[$key] ) ) {
					if ( is_object( $this->cache[$key] ) ) {
						$return[ $key ] = clone $this->cache[ $key ];
					} else {
						$return[ $key ] = $this->cache[ $key ];
					}
				} else if ( in_array( $group, $this->no_mc_groups ) ) {
					$return[$key] = false;
				} else {
					$gets[$key] = $key;
				}
			}

			if ( ! empty( $gets ) ) {
				// @todo $mc is not defined, $null is undefined. WTF?
				$results = $mc->getMulti( $gets, $null, Memcached::GET_PRESERVE_ORDER );
				$joined = array_combine( array_keys( $gets ), array_values( $results ) );
				$return = array_merge( $return, $joined );
			}

			++$this->stats['get_multi'];
			$this->group_ops[$group][] = "get_multi $id";
			$this->cache = array_merge( $this->cache, $return );

			return array_values( $return );
		}

		/**
		 * @todo Document it.
		 *
		 * @param $key
		 * @param $group
		 *
		 * @return null|string|string[]
		 */
		function key( $key, $group ) {
			if ( empty( $group ) ) {
				$group = 'default';
			}

			if ( false !== array_search( $group, $this->global_groups ) ) {
				$prefix = $this->global_prefix;
			} else {
				$prefix = $this->blog_prefix;
			}

			return preg_replace( '/\s+/', '', WP_CACHE_KEY_SALT . "$prefix$group:$key" );
		}

		/**
		 * @todo Document it.
		 *
		 * @param        $id
		 * @param        $data
		 * @param string $group
		 * @param int    $expire
		 *
		 * @return mixed
		 */
		function replace( $id, $data, $group = 'default', $expire = 0 ) {
			$key = $this->key( $id, $group );
			$expire = ( $expire === 0) ? $this->default_expiration : $expire;
			$mc =& $this->get_mc( $group );

			if ( is_object( $data ) ) {
				$data = clone $data;
			}

			$result = $mc->replace( $key, $data, $expire );

			if ( false !== $result ) {
				$this->cache[ $key ] = $data;
			}

			return $result;
		}

		/**
		 * @todo Document it.
		 *
		 * @param        $id
		 * @param        $data
		 * @param string $group
		 * @param int    $expire
		 *
		 * @return bool
		 */
		function set( $id, $data, $group = 'default', $expire = 0 ) {
			$key = $this->key( $id, $group );
			if ( isset( $this->cache[$key] ) && ( 'checkthedatabaseplease' === $this->cache[$key] ) ) {
				return false;
			}

			if ( is_object( $data) ) {
				$data = clone $data;
			}

			$this->cache[$key] = $data;

			if ( in_array( $group, $this->no_mc_groups ) ) {
				return true;
			}

			$expire = ( $expire === 0 ) ? $this->default_expiration : $expire;
			$mc =& $this->get_mc( $group );
			$result = $mc->set( $key, $data, $expire );

			return $result;
		}

		/**
		 * @todo Document it.
		 *
		 * @param array  $items
		 * @param int    $expire
		 * @param string $group
		 */
		function set_multi( $items, $expire = 0, $group = 'default' ) {
			$sets = array();
			$mc =& $this->get_mc( $group );
			$expire = ( $expire === 0 ) ? $this->default_expiration : $expire;

			foreach ( $items as $i => $item ) {
				if ( empty( $item[2] ) ) {
					$item[2] = 'default';
				}

				list( $id, $data, $group ) = $item;
				$key = $this->key( $id, $group );

				if ( isset( $this->cache[$key] ) && ( 'checkthedatabaseplease' === $this->cache[$key] ) ) {
					continue;
				}

				if ( is_object( $data) ) {
					$data = clone $data;
				}

				$this->cache[$key] = $data;

				if ( in_array( $group, $this->no_mc_groups ) ) {
					continue;
				}

				$sets[$key] = $data;
			}

			if ( ! empty( $sets ) ) {
				$mc->setMulti( $sets, $expire );
			}
		}

		/**
		 * @todo Document it.
		 *
		 * @param $line
		 *
		 * @return string
		 */
		function colorize_debug_line( $line ) {
			$colors = array(
				'get'   => 'green',
				'set'   => 'purple',
				'add'   => 'blue',
				'delete'=> 'red'
			);

			$cmd = substr( $line, 0, strpos( $line, ' ' ) );
			$cmd2 = "<span style='color:{$colors[$cmd]}'>$cmd</span>";

			return $cmd2 . substr( $line, strlen( $cmd ) ) . "\n";
		}

		/**
		 * @todo Document it.
		 */
		function stats() {
			echo "<p>\n";

			foreach ( $this->stats as $stat => $n ) {
				echo "<strong>$stat</strong> $n";
				echo "<br/>\n";
			}

			echo "</p>\n";
			echo "<h3>Memcached:</h3>";

			foreach ( $this->group_ops as $group => $ops ) {
				if ( !isset( $_GET['debug_queries'] ) && 500 < count( $ops ) ) {
					$ops = array_slice( $ops, 0, 500 );
					echo "<big>Too many to show! <a href='" . add_query_arg( 'debug_queries', 'true' ) . "'>Show them anyway</a>.</big>\n";
				}

				echo "<h4>$group commands</h4>";
				echo "<pre>\n";

				$lines = array();

				foreach ( $ops as $op ) {
					$lines[] = $this->colorize_debug_line( $op );
				}

				print_r( $lines );
				echo "</pre>\n";
			}

			if ( ! empty( $this->debug ) && $this->debug ) {
				var_dump( $this->memcache_debug );
			}
		}

		/**
		 * @todo Document it.
		 * @todo Reference (&) used here is deprecated. See https://stackoverflow.com/questions/1676897/what-does-it-mean-to-start-a-php-function-with-an-ampersand
		 *
		 * @param $group
		 *
		 * @return mixed
		 */
		function &get_mc( $group ) {
			if ( isset( $this->mc[$group] ) ) {
				return $this->mc[ $group ];
			}

			return $this->mc['default'];
		}

		/**
		 * WP_Object_Cache constructor.
		 *
		 * @todo Document it.
		 * @todo Move to the beginning of the class.
		 */
		function __construct() {

			$this->stats = array(
				'get'        => 0,
				'get_multi'  => 0,
				'add'        => 0,
				'set'        => 0,
				'delete'     => 0,
				'miss'       => 0,
			);

			global $memcached_servers;

			if ( isset( $memcached_servers ) ) {
				$buckets = $memcached_servers;
			} else {
				$buckets = [ '127.0.0.1' ];
			}

			reset( $buckets );

			if ( is_int( key( $buckets ) ) ) {
				$buckets = [ 'default' => $buckets ];
			}

			foreach ( $buckets as $bucket => $servers ) {
				$this->mc[$bucket] = new Memcached();

				$instances = array();

				foreach ( $servers as $server ) {
					@list( $node, $port ) = explode( ':', $server );

					if ( empty( $port ) ) {
						$port = ini_get( 'memcache.default_port' );
					}

					$port = (int) $port;

					if ( ! $port ) {
						$port = 11211;
					}

					$instances[] = array( $node, $port, 1 );
				}

				$this->mc[$bucket]->addServers( $instances );
			}

			global $blog_id, $table_prefix;

			// @todo Prevent dynamic field declaration.
			$this->global_prefix = '';
			$this->blog_prefix = '';

			if ( function_exists( 'is_multisite' ) ) {
				$this->global_prefix = ( is_multisite() || defined( 'CUSTOM_USER_TABLE' ) && defined( 'CUSTOM_USER_META_TABLE' ) ) ? '' : $table_prefix;
				$this->blog_prefix = ( is_multisite() ? $blog_id : $table_prefix ) . ':';
			}

			$this->cache_hits =& $this->stats['get'];
			$this->cache_misses =& $this->stats['miss'];
		}

	} // End WP_Object_Cache class

else : // No Memcached

	// In 3.7+, we can handle this smoothly
	if ( function_exists( 'wp_using_ext_object_cache' ) ) {
		wp_using_ext_object_cache( false );

	// In earlier versions, there isn't a clean bail-out method.
	} else {
		wp_die( 'Memcached class not available.' );
	}

endif;

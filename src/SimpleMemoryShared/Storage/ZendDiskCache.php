<?php

/*
 * This file is part of the SimpleMemoryShared package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace SimpleMemoryShared\Storage;

use SimpleMemoryShared\Storage\Exception\RuntimeException;

/**
 * Storage adapter using the Zend Data Cache disk store
 */
class ZendDiskCache implements CapacityStorageInterface
{
    /**
     * Construct storage
     */
    public function __construct()
    {
        if (php_sapi_name() === 'cli') {
            throw new RuntimeException('ZendDiskCache is not available from the command line');
        }
        if (!function_exists('zend_disk_cache_store') || !ini_get('zend_datacache.enable')) {
            throw new RuntimeException('Zend Data Cache extension must be loaded and enabled.');
        }
    }

    /**
     * Memory alloc
     */
    public function alloc()
    {
        return;
    }

    /**
     * Test if has datas with $uid key
     * @param mixed $uid
     * @return boolean
     */
    public function has($uid)
    {
        $data = $this->read($uid);
        return false !== $data;
    }

    /**
     * Read datas with $uid key
     * @param mixed $uid
     * @return mixed
     */
    public function read($uid)
    {
        $this->alloc();
        return zend_disk_cache_fetch($uid);
    }

    /**
     * Write datas on $uid key
     * @param mixed $uid
     * @param mixed $mixed
     */
    public function write($uid, $mixed)
    {
        $this->alloc();
        return zend_disk_cache_store($uid, $mixed);
    }

    /**
     * Clear datas with $uid key
     * @param mixed $uid
     * @return void
     */
    public function clear($uid = null)
    {
        $this->alloc();
        if($uid) {
            return zend_disk_cache_delete($uid);
        }
        return zend_disk_cache_clear();
    }

    /**
     * Close storage
     * @param int
     */
    public function close()
    {
        return;
    }

     /**
     * Get max bloc allow
     */
    public function canAllowBlocsMemory($numBloc)
    {
        return true; // no limitation
    }
}

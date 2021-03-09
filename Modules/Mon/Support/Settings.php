<?php
namespace Modules\Mon\Support;

class Settings
{
    protected $saveItemsToDatabase = array();
    protected $deleteItemsFromDatabase = array();
    protected $deleteCategoriesFromDatabase = array();
    protected $cacheNeedsFlush = array();
    protected $cacheRegistry = array();
    protected $initCacheRegistry = array();
    protected $deleteFromCacheRegistry = array();
    protected $items = array();
    protected $loaded = array();
    protected $_cacheId = 'global_website_settings';
    protected $_cacheTime = 0;
    public function __construct()
    {
    }

    /**
     * Settings::set().
     *
     * @param string $category   name of the category
     * @param mixed  $key
     *                           can be either a single item (string) or an array of item=>value pairs
     * @param mixed  $value      value to set for the key, leave this empty if $key is an array
     * @param bool   $toDatabase whether to save the items to the database
     *
     * @return Settings
     */
    public function set($category = 'system', $key = '', $value = '', $toDatabase = true)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($category, $k, $v, $toDatabase);
            }
        } else {
            if ($toDatabase) {
                if (isset($this->saveItemsToDatabase[$category]) && is_array($this->saveItemsToDatabase[$category])) {
                    $this->saveItemsToDatabase[$category] = self::mergeArray($this->saveItemsToDatabase[$category], array($key => $value));
                } else {
                    $this->saveItemsToDatabase[$category] = array($key => $value);
                }
            }
            if (isset($this->items[$category]) && is_array($this->items[$category])) {
                $this->items[$category] = self::mergeArray($this->items[$category], array($key => $value));
            } else {
                $this->items[$category] = array($key => $value);
            }
        }
        return $this;
    }
    /**
     * Settings::get().
     *
     * @param string $category name of the category
     * @param mixed  $key
     *                         can be either :
     *                         empty, returning all items of the selected category
     *                         a string, meaning a single key will be returned
     *                         an array, returning an array of key=>value pairs
     * @param string $default  the default value to be returned
     *
     * @return mixed
     */
    public function get($category = 'system', $key = '', $default = null)
    {
        if (env('APP_INSTALLED') !== true)
        {
            return $default;
        }
        if (!isset($this->loaded[$category])) {
            $this->load($category);
        }
        if (empty($key) && !empty($category)) {
            return isset($this->items[$category]) ? ($this->items[$category] ? $this->items[$category] : (!empty($default) ? $default : null)) : null;
        }
        if (!empty($key) && is_array($key)) {
            $toReturn = array();
            foreach ($key as $k => $v) {
                if (is_numeric($k)) {
                    $toReturn[$v] = $this->get($category, $v);
                } else {
                    $toReturn[$k] = $this->get($category, $k, $v);
                }
            }
            return $toReturn;
        }
        if (isset($this->items[$category][$key])) {
            return $this->items[$category][$key];
        }
        return $default;
    }
    /**
     * delete an item or all items from a category.
     *
     * @param string $category the name of the category
     * @param mixed  $key
     *                         can be either:
     *                         empty, meaning it will delete all items of the selected category
     *                         a single key
     *                         an array of keys
     *
     * @return Settings
     */
    public function delete($category, $key = '')
    {
        if (empty($category)) {
            return $this;
        }
        if (!empty($category) && empty($key)) {
            $this->deleteCategoriesFromDatabase[] = $category;
            $this->deleteCache($category);
            if (isset($this->items[$category])) {
                unset($this->items[$category]);
            }
            return;
        }
        if (is_array($key)) {
            foreach ($key as $k) {
                $this->delete($category, $k);
            }
        } else {
            if (isset($this->items[$category][$key])) {
                unset($this->items[$category][$key]);
                if (empty($this->deleteItemsFromDatabase[$category])) {
                    $this->deleteItemsFromDatabase[$category] = array();
                }
                $this->deleteItemsFromDatabase[$category][] = $key;
            }
        }
        return $this;
    }

    /**
     * load the cache registry.
     *
     * @return array|mixed $cacheRegistry array containing all the cached categories
     */
    protected function loadCacheRegistry()
    {
        if (!empty($this->cacheRegistry)) {
            return $this->cacheRegistry;
        }
        $cacheRegistry = \Cache::get('__cache_registry_'.$this->getCacheId());
        if (empty($cacheRegistry) || !is_array($cacheRegistry)) {
            $cacheRegistry = array();
        }
        $this->cacheRegistry = $cacheRegistry;
        $this->initCacheRegistry = $cacheRegistry;
        return $this->cacheRegistry;
    }
    /**
     * add to cache registry.
     *
     * @param $category - the category to be added to cache
     *
     * @return Settings
     */
    protected function addToCacheRegistry($category)
    {
        $cacheRegistry = $this->loadCacheRegistry();
        if (!in_array($category, $cacheRegistry)) {
            $this->cacheRegistry[] = $category;
        }
        return $this;
    }
    /**
     * delete one/more/all categories from cache.
     *
     * @param mixed $category the name of the category
     *                        if $category is empty will delete all cached categories.
     *                        if $category is an array, will delete all provided categories
     *                        if $category is a string, will delete only that particular category
     *
     * @return Settings
     */
    public function deleteCache($category = '')
    {
        $cacheRegistry = $this->loadCacheRegistry();
        if (empty($category)) {
            $this->deleteFromCacheRegistry = self::mergeArray($this->deleteFromCacheRegistry, $cacheRegistry);
            $cacheRegistry = array();
        } elseif (is_string($category) && in_array($category, $cacheRegistry)) {
            unset($cacheRegistry[array_search($category, $cacheRegistry)]);
            $this->deleteFromCacheRegistry[] = $category;
        } elseif (is_array($category)) {
            foreach ($category as $catName) {
                if (in_array($catName, $cacheRegistry)) {
                    unset($cacheRegistry[array_search($catName, $cacheRegistry)]);
                    $this->deleteFromCacheRegistry[] = $catName;
                }
            }
        }
        $this->cacheRegistry = $cacheRegistry;
        return $this;
    }
    public static function mergeArray($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_integer($k)) {
                    isset($res[$k]) ? $res[] = $v : $res[$k] = $v;
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::mergeArray($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }
        return $res;
    }
    /**
     * load from database the items of the specified category.
     *
     * @param string $category
     *
     * @return array the items of the category
     */
    public function load($category)
    {
        $items = \Cache::get($category.'_'.$this->getCacheId());
        $this->loaded[$category] = true;
        $this->addToCacheRegistry($category);
        if (!$items) {
            $result = \DB::table('settings')->where('category', '=', $category)->get();
            if (empty($result)) {
                return;
            }
            $items = array();
            foreach ($result as $row) {
                $items[$row->key] = @unserialize($row->value);
            }
            \Cache::put($category.'_'.$this->getCacheId(), $items, $this->getCacheTime());
        }
        if (isset($this->items[$category])) {
            $items = self::mergeArray($items, $this->items[$category]);
        }
        $this->set($category, $items, null, false);
        return $items;
    }
    public function toArray()
    {
        return $this->items;
    }
    /**
     * @param int $int the time to cache the keys, defaults to 0
     */
    public function setCacheTime($int)
    {
        $this->_cacheTime = (int) $int > 0 ? $int : 0;
    }
    /**
     * @return int the time to cache the keys, defaults to 0
     */
    public function getCacheTime()
    {
        return $this->_cacheTime;
    }
    /**
     * @param string $str the cache key to prepend to all categories, defaults to 'global_website_settings'
     */
    public function setCacheId($str = '')
    {
        $this->_cacheId = !empty($str) ? $str : $this->_cacheId;
    }
    /**
     * @return string the cache key to prepend to all categories, defaults to 'global_website_settings'
     */
    public function getCacheId()
    {
        return $this->_cacheId;
    }
    protected function addDbItem($category, $key, $value)
    {
        $result = Setting::where('category', '=', $category)->where('key', '=', $key)->first();
        $value = @serialize($value);
        if (!empty($result)) {
            $result->value = $value;
            $result->save();
        } else {
            Setting::create(array(
                'category' => $category,
                'key' => $key,
                'value' => $value,
            ));
        }
    }
    public function whenRequestEnds()
    {
        $this->cacheNeedsFlush = array();
        if (count($this->deleteCategoriesFromDatabase) > 0) {
            foreach ($this->deleteCategoriesFromDatabase as $catName) {
                Setting::where('category', '=', $catName)->delete();
                $this->cacheNeedsFlush[] = $catName;
                if (isset($this->deleteItemsFromDatabase[$catName])) {
                    unset($this->deleteItemsFromDatabase[$catName]);
                }
                if (isset($this->saveItemsToDatabase[$catName])) {
                    unset($this->saveItemsToDatabase[$catName]);
                }
            }
        }
        if (count($this->deleteItemsFromDatabase) > 0) {
            foreach ($this->deleteItemsFromDatabase as $catName => $keys) {
                Setting::where('category', '=', $catName)->whereIn('key', $keys)->delete();
                $this->cacheNeedsFlush[] = $catName;
            }
        }
        if (count($this->saveItemsToDatabase) > 0) {
            foreach ($this->saveItemsToDatabase as $catName => $keyValues) {
                foreach ($keyValues as $k => $v) {
                    $this->addDbItem($catName, $k, $v);
                }
                $this->cacheNeedsFlush[] = $catName;
            }
        }
        if (count($this->cacheRegistry) == 0 && count($this->initCacheRegistry) > 0) {
            \Cache::forget('__cache_registry_'.$this->getCacheId());
        } elseif (count(array_diff($this->initCacheRegistry, $this->cacheRegistry)) > 0 || count(array_diff($this->cacheRegistry, $this->initCacheRegistry)) > 0) {
            \Cache::put('__cache_registry_'.$this->getCacheId(), $this->cacheRegistry, $this->getCacheTime());
        }
        if (count($this->deleteFromCacheRegistry) > 0) {
            $this->cacheNeedsFlush = array_unique(self::mergeArray($this->cacheNeedsFlush, $this->deleteFromCacheRegistry));
        }
        if (count($this->cacheNeedsFlush) > 0) {
            foreach ($this->cacheNeedsFlush as $catName) {
                \Cache::forget($catName.'_'.$this->getCacheId());
            }
        }
    }
}

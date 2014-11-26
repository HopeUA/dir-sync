<?php
namespace AppBundle\Sync\Entity;

/**
 * Simple collection
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
abstract class Collection implements \Iterator, \Countable
{
    /**
     * @var array  Collection Entities
     */
    protected $items = [];
    /**
     * @var string  Current index of the collection
     */
    protected $current;

    /**
     * Init the collection
     */
    public function __construct()
    {
        reset($this->items);
        $this->current = key($this->items);
    }

    /**
     * Rewind
     */
    public function rewind()
    {
        reset($this->items);
        $this->current = key($this->items);
    }

    /**
     * Get the current element
     *
     * @return mixed
     */
    public function current()
    {
        return $this->items[$this->current];
    }

    /**
     * Get the current key
     *
     * @return string
     */
    public function key()
    {
        return $this->current;
    }

    /**
     * Next
     */
    public function next()
    {
        next($this->items);
        $this->current = key($this->items);
    }

    /**
     * Check if current element exists
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->items[$this->current]);
    }

    /**
     * Get total number of collection elements
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Check if element exists
     *
     * @param string $key  Element key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * Add an item to the collection
     *
     * @param mixed  $item  Item
     * @param string $key   Item key
     */
    public function add($item, $key = null)
    {
        if (is_null($key)) {
            $this->items[] = $item;
        } else {
            $this->items[$key] = $item;
        }
    }

    /**
     * Delete item from collection
     *
     * @param string $key  Item key
     */
    public function del($key)
    {
        if ($this->has($key)) {
            unset($this->items[$key]);
        }
    }

    /**
     * Get item from collection
     *
     * @param string $key  Item key
     *
     * @return null|mixed  Collection item
     */
    public function get($key)
    {
        return $this->has($key) ? $this->items[$key] : null;
    }
}

<?php
namespace AppBundle\Sync\Entity;

abstract class Collection implements \Iterator, \Countable
{
    /**
     * @var array Collection Entities
     */
    protected $items = [];
    /**
     * @var string Current index of the collection
     */
    protected $current;

    public function __construct()
    {
        reset($this->items);
        $this->current = key($this->items);
    }

    public function rewind()
    {
        reset($this->items);
        $this->current = key($this->items);
    }

    public function current()
    {
        return $this->items[$this->current];
    }

    public function key()
    {
        return $this->current;
    }

    public function next()
    {
        next($this->items);
        $this->current = key($this->items);
    }

    public function valid()
    {
        return isset($this->items[$this->current]);
    }

    public function count()
    {
        return count($this->items);
    }

    public function has($key)
    {
        return isset($this->items[$key]);
    }

    public function add($item, $key = null)
    {
        if (is_null($key)) {
            $this->items[] = $item;
        } else {
            $this->items[$key] = $item;
        }
    }

    public function del($key)
    {
        if ($this->has($key)) {
            unset($this->items[$key]);
        }
    }

    public function get($key)
    {
        return $this->has($key) ? $this->items[$key] : null;
    }
}

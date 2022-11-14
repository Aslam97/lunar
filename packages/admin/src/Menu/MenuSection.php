<?php

namespace Lunar\Hub\Menu;

use Illuminate\Support\Str;
use Lunar\Hub\LunarHub;

class MenuSection extends MenuSlot
{
    /**
     * The display name of the menu section.
     *
     * @var string
     */
    public $name;

    /**
     * The route name.
     *
     * @var string
     */
    public $route;

    /**
     * Reference to icon or full SVG.
     *
     * @var string
     */
    public $icon;

    /**
     * The positional order of the menu section.
     *
     * @var int
     */
    public $position;

    /**
     * Setter for the name property.
     *
     * @param  string  $name
     * @return static
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Setter for the route property.
     *
     * @param  string  $route
     * @return static
     */
    public function route($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Setter for the icon property.
     *
     * @param  string  $icon
     * @return static
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Setter for the position property.
     *
     * @param  int  $position
     * @return static
     */
    public function position($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Render the HTML for the icon.
     *
     * @param  string  $attrs
     * @return string
     */
    public function renderIcon($attrs = null)
    {
        return LunarHub::icon($this->icon, $attrs);
    }

    /**
     * Determines whether this menu section is considered active.
     *
     * @param  string  $path
     * @return bool
     */
    public function isActive($path)
    {
        return Str::startsWith($path, $this->handle);
    }
}

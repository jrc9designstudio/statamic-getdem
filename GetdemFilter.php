<?php

namespace Statamic\Addons\Getdem;

use Statamic\Extend\Filter;
use Statamic\API\Request;

class GetdemFilter extends Filter
{
    /**
     * Perform filtering on a collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function filter()
    {
        return $this->collection->filter(function($entry) {
          $params = explode('|', $this->get('params', ''));
          $addative = $this->get('and', true);
          $return_arry = [];
          
          foreach ($params as $param)
          {
            $value = Request::get($param, false);
            $taxonomy = $entry->get($param);
            if ($value != '' && $taxonomy != null && !empty($taxonomy))
            {
              $return_arry[] = in_array($value, $taxonomy);
            }
          }
          
          if ($addative && in_array(false, $return_arry))
          {
            return false;
          }
          else if (in_array(true, $return_arry))
          {
            return true;
          }
          
          return true;
        });
    }
}

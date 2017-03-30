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
        return $this->collection->filter( function( $entry ) {

            // get parameters from the tag
            $params = explode( '|', $this->get('params', '' ) );

            // match all the params (`and` mode), or just some (`or` mode)
            // tag usage: {{ ... match="all" }}
            $mode = $this->get('match', 'any'); //default to any (`or` mode)

            // JRC - hazy on what this does
            $return_array = [];

            // if nothing is passed then no need to filter - so return true for all entries
            if( ! count $params ) {
            	return true;
            }
            
            // check for all supplied taxonomies, to see if this entry contains it
            foreach ( $params as $param )
            {
              // get the taxonomy value from the $_GET paramaters
              $value = Request::get( $param, false );

              // JRC - I am hazy on what this is doing, this will try and get i.e. `services` from the entry, what does this return?
              $taxonomy = $entry->get( $param );

              // if something has been set, not sure on the last two - see above...
              if ( $value != '' && $taxonomy != null && ! empty( $taxonomy ) )
              {
                // JRC - not sure what this does?
                $return_array[] = in_array( $value, $taxonomy );
              }
            }
            
            // JRC - also not sure on what is happening here
            if ( $mode === 'all' && in_array( false, $return_array ) )
            {
              return false;
            }
            else if ( in_array( true, $return_array ) )
            {
              return true;
            }
            
            return false;

        });
    }
}

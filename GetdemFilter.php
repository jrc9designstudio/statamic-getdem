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

          // get parameters to check from the tag
          $params_to_check = explode( '|', $this->get('params', '' ) );

          // match all the params (`and` mode), or just some (`or` mode)
          // tag usage: {{ ... match="all" }}
          $mode = $this->get('match', 'any'); //default to any (`or` mode)

          // JRC - hazy on what this does
          // This creates an empty array that I use to store true or false on the match for each param
          $return_array = [];

          // if nothing is passed then no need to filter - so return true for all entries
          // This is pretty pointless because why would you use filter="getdem" if you are not even going to filter
          if( ! count($params_to_check) )
          {
          	return true;
          }
          
          // check for all supplied taxonomies, to see if this entry contains it
          foreach ( $params_to_check as $param )
          {
            // get the taxonomy value from the $_GET paramaters
            $value = Request::get( $param, false );

            // This paramater does not exist in the url params we skip over filtering it
            if ( ! $value )
            {
              $return_array[] = true;
            }
            else
            {
              // JRC - I am hazy on what this is doing, this will try and get i.e. `services` from the entry, what does this return?
              // This gets the array of taxonomies from the entry, may not be nessicary? This is used to do the comparison
              $taxonomy = $entry->get( $param );

              // if something has been set, not sure on the last two - see above...
              // If the taxonomy field we are searching on is not valid or if the entry has no terms for that taxonomy field
              // For example we may get null or we may get an empty array []
              if ( $value != '' && $taxonomy != null && ! empty( $taxonomy ) )
              {
                // JRC - not sure what this does?
                // This checks to see if the $value (text of the passed param) is in the array of taxonmy terms on the entry
                // It adds it's result (true or false) to our return array.
                $return_array[] = in_array( $value, $taxonomy );
              }
          }
          }
          
          // JRC - also not sure on what is happening here
          // So if we want all terms to match our array should now contain [true, true, true] (if we are matching three terms)
          // In other words we are making sure there are no mismatched terms [true, false, true] for example
          if ( $mode === 'all' && in_array( false, $return_array ) )
          {
            return false;
          }
          // Here we are checking to see if any of ther terms evaluated to be true
          // [true, false, true]
          else if ( in_array( true, $return_array ) )
          {
            return true;
          }
          
          return false;

        });
    }
}

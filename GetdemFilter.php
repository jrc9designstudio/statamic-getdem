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

            // This creates an empty array used to store true or false on the match for each parameter
            $return_array = [];

            // check for all supplied taxonomies, to see if this entry contains it
            foreach ( $params_to_check as $param_name )
            {
                // get the taxonomy value from the $_GET paramaters
                $value = Request::get( $param_name, false );

                // This paramater does not exist in the url params we skip over filtering it
                if ( ! $value )
                {
                    $return_array[] = true;
                }
                else
                {
                    // This gets the array of taxonomies from the entry, this is used to do the comparison
                    // It returns an array of taxonomy terms on the entry (slugs) IE: ['coffee', 'harry-potter']
                    $taxonomies_array = $entry->get( $param_name );

                    // if something has been set, not sure on the last two - see above...
                    // If the taxonomy field we are searching on is not valid or if the entry has no terms for that taxonomy field
                    // For example we may get null or we may get an empty array []
                    if ( $value != '' && $taxonomies_array != null && ! empty( $taxonomies_array ) )
                    {
                        // This checks to see if the $value (text of the passed param) is in the array of taxonmy terms on the entry
                        // It adds its result (true or false) to the return array.
                        $return_array[] = in_array( $value, $taxonomies_array );
                    }
                }
            }

            // If we want all terms to match, our array should now *only* contain [true, true, true] (if we are matching three terms)
            // In other words we are making sure there are no mismatched terms [true, false, true] for example
            if ( $mode === 'all' && in_array( false, $return_array ) )
            {
                return false;
            }
            // Here we are checking to see if *any* of the terms evaluated to be true, e.g. [true, false, true]
            else if ( in_array( true, $return_array ) )
            {
                return true;
            }

          return false;

        });
    }
}

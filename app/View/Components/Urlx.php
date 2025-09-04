<?php
 
namespace App\View\Components;
 
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
 
class Urlx

{
    /**
     * Build a modified URL based on the current or given route.
     * @param string|null $route    The route name to use (null = keep current route).
     * @param array       $params   Key-value pairs of query parameters to add, overwrite, or remove (null = remove).
     * @param bool        $reset    If true, clear all existing query parameters before applying new ones.
     * @param string|null $fragment Optional URL fragment (#anchor) to append.
     * @return string
     */

    public function url(
        ?string $route = null,
        array $params = [],
        bool $reset = false,
        ?string $fragment = null
    ): string {
        // 1. Start with existing query parameters (unless reset)
        $query = $reset ? [] : Request::query();
        $queryString = '';
        // 2. Apply incoming parameters
        foreach ($params as $key => $value) {
            if (is_null($value)) {
                unset($query[$key]); // remove param
            } else {
                $query[$key] = $value; // add/overwrite param
            }
        }
 
        // 3. Resolve base URL (route or current)
        $baseUrl = $route ? route($route,$query) : URL::current();
 
        // 4. Build query string
        if(!$route){
            $queryString = $query ? '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986) : '';
        }
        // 5. Attach fragment if provided
        $fragmentPart = $fragment ? '#' . ltrim($fragment, '#') : '';
 
        return $baseUrl . $queryString . $fragmentPart;

    }

}

 
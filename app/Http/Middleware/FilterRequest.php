<?php

namespace App\Http\Middleware;

use Closure;

class FilterRequest
{
    private $tags = array('iframe', 'script');
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = (array)$request->all();
        foreach($input as $key => $post) {
            if($this->contains($post)) {
                //$input[$key] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $post);
                //$input[$key] = preg_replace('/<iframe.*?\/iframe>/i', '', $post);
                
                libxml_use_internal_errors(true);
                $post = mb_convert_encoding($post, 'HTML-ENTITIES', "UTF-8");
                
                $dom = new \DOMDocument();
                $dom->loadHTML(htmlspecialchars_decode($post));
                
                foreach($this->tags as $tag) {
                    $script = $dom->getElementsByTagName($tag);
                    $remove = [];

                    foreach($script as $item) {
                        $remove[] = $item;
                    }

                    foreach ($remove as $item) {
                        $item->parentNode->removeChild($item); 
                    }
                }
                $input[$key] = $dom->saveHTML();
                
                $request->replace($input);
            }
        }

        return $next($request);
    }
    
    public function contains($check)
    {
        if(is_array($check)) {
            foreach($check as $string) {
                $contains = $this->contains($string);
                if($contains) return true;
            }
        } else {
            $array = $this->tags;
            foreach($array as $a) {
                if (stripos($check, $a) !== false) return true;
            }
        }
        
        return false;
    }
}

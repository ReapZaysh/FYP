<?php

namespace App\Http\Middleware;

use Psr\Http\Message\RequestInterface;

class GuzzleForceIpv4Middleware
{
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            // Force IPv4 resolution
            $ipResolveConstant = defined('CURLOPT_IPRESOLVE') ? CURLOPT_IPRESOLVE : 113;
            $ipv4Constant = defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1;
            
            $options['curl'][$ipResolveConstant] = $ipv4Constant;
            
            return $handler($request, $options);
        };
    }
}

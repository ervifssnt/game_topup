<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Remove X-Powered-By header (hide server technology)
        $response->headers->remove('X-Powered-By');
        header_remove('X-Powered-By');

        // Remove Server header (additional privacy)
        $response->headers->remove('Server');

        // ============================================
        // CORE SECURITY HEADERS
        // ============================================

        // X-Frame-Options: Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // X-Content-Type-Options: Prevent MIME-type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // X-XSS-Protection: Enable browser XSS protection (legacy but useful)
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Referrer-Policy: Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions-Policy: Restrict browser features
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), interest-cohort=()');
        
        // ============================================
        // HSTS (HTTP Strict Transport Security)
        // ============================================
        if (config('app.env') === 'production') {
            // Production: Force HTTPS for 1 year, include subdomains
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        } else {
            // Development: Disabled HSTS (localhost doesn't use HTTPS)
            $response->headers->set('Strict-Transport-Security', 'max-age=0');
        }
        
        // ============================================
        // CONTENT SECURITY POLICY (CSP)
        // ============================================
        $csp = [
            // Default: Only allow resources from same origin
            "default-src 'self'",
            
            // Scripts: Allow inline scripts (needed for Laravel/Blade) and CDN
            "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com",
            
            // Styles: Allow inline styles and Google Fonts
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            
            // Fonts: Allow Google Fonts
            "font-src 'self' https://fonts.gstatic.com",
            
            // Images: Allow same origin, data URIs, and HTTPS images
            "img-src 'self' data: https:",
            
            // AJAX/Fetch: Only same origin
            "connect-src 'self'",
            
            // Frames: Prevent embedding in iframes
            "frame-ancestors 'none'",
            
            // Base URI: Prevent base tag injection
            "base-uri 'self'",
            
            // Forms: Only submit to same origin
            "form-action 'self'",
            
            // Object/Embed: Block Flash and other plugins
            "object-src 'none'",
            
            // Media: Allow same origin only
            "media-src 'self'",
            
            // Upgrade insecure requests (only in production)
            config('app.env') === 'production' ? "upgrade-insecure-requests" : "",
        ];
        
        // Remove empty values and join
        $csp = array_filter($csp);
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        
        // ============================================
        // ADDITIONAL SECURITY HEADERS
        // ============================================
        
        // X-Permitted-Cross-Domain-Policies: Prevent Adobe Flash/PDF cross-domain requests
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        
        // Cross-Origin Headers (CORS protection)
        $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

        return $response;
    }
}
<?php

namespace App\Service;

class ExtractYoutubeUrlService
{
    function extractYouTubeID(string $url): string
    {
        $pattern = '/https:\/\/www\.youtube\.com\/watch\?v=/';
        return preg_replace($pattern, '', $url);
    }
}
<?php


namespace App\Helper;


class MtgGoldfishUrl
{
    public static function makeImportUrl($deckUrl)
    {
        $deckId = preg_replace(["'^([^0-9]*)'", "'[^0-9]*$'"], ['', ''], $deckUrl);

        return 'https://www.mtggoldfish.com/deck/arena_download/'.$deckId;
    }
}

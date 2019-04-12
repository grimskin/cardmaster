<?php


namespace App\Helper;


class ScriptParamsParser
{
    public static function unpackParams(string $paramString): array
    {
        $paramString = trim($paramString);

        if (strpos($paramString, ',') === 0) {
            $paramString = trim(substr($paramString, 1));
        }

        if (!$paramString) {
            return [];
        }

        if (strpos($paramString, '[') === 0) {
            $param = substr(
                $paramString,
                strpos($paramString, '[') + 1,
                strpos($paramString, ']') - strpos($paramString, '[') - 1
            );
            $remainingString = substr($paramString, strpos($paramString, ']') + 1);
        } else {
            $tmpArr = explode(',', $paramString, 2);
            $param = trim($tmpArr[0]);
            $remainingString = $tmpArr[1] ?? '';
        }

        return array_merge([$param], ScriptParamsParser::unpackParams($remainingString));
    }
}

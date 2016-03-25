<?php

namespace NordCode\RoboParameters\Reader;

class IniReader implements ParameterReaderInterface
{
    /**
     * @inheritDoc
     */
    public function readFromFile($path)
    {
        return parse_ini_file($path, true, INI_SCANNER_RAW);
    }
}

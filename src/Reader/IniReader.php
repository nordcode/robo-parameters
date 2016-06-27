<?php

namespace NordCode\RoboParameters\Reader;

class IniReader implements ParameterReaderInterface
{
    /**
     * @inheritDoc
     */
    public function readFromFile($path)
    {
        // HHVM <= 3.14 has no stream-wrapper support in parse_ini_file()
        return parse_ini_string(file_get_contents($path), true, INI_SCANNER_RAW);
    }
}

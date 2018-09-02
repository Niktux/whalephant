<?php

namespace Whalephant\Console;

class Application extends \Symfony\Component\Console\Application
{
    private static $logo = '<fg=cyan>

    ▄██████████████▄.▐█▄▄▄▄█▌
    ████████████████▌▀▀██▀▀     <fg=white;bg=blue;options=bold> WHALEPHANT  </>
    ████▄████████████▄▄█▌               <fg=cyan;bg=blue;options=bold> %version% </>
    ▄▄▄▄▄██████████████▀

</>';

    public function getLogo(): string
    {
        $version = str_pad(\Whalephant\Application::VERSION, 10, ' ');
        
        return str_replace('%version%', $version, self::$logo);
    }
    
    public function getHelp(): string
    {
        return $this->getLogo() . parent::getHelp();
    }
}

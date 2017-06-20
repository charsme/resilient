<?php

namespace App\Common;

use Composer\Script\Event;

class ComposerScripts
{
    /**
     * Handle the post-install Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postInstall(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        static::copyEnv($event);
        static::createFolder($event);
    }

    /**
     * Handle the post-update Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        static::copyEnv($event);
        static::createFolder($event);
    }

    /**
     * copyEnv
     *
     * @param Composer\Script\Event $event
     * @return void
     */
    protected static function copyEnv(Event $event)
    {
        $dir = $event->getComposer()->getConfig()->get('vendor-dir') . '/../';

        if (!file_exists($dir . '.env')) {
            echo "copying example .env file please modify it especially on production \n ";
            copy($dir . '.env.example', $dir . '.env');
        }
    }

    /**
     * Create storage folder
     *
     * @return void
     */
    protected static function createFolder(Event $event)
    {
        $dir = $event->getComposer()->getConfig()->get('vendor-dir') . '/../storage/';

        $needed = ['views', 'psr6', 'router', 'upload', 'proxies'];

        foreach ($needed as $d) {
            if (!\file_exists($dir . $d)) {
                echo "creating dir {$d} \n";
                mkdir($dir . $d, true, 0755);
            }
        }
    }
}

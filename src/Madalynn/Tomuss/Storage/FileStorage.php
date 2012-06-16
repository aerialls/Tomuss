<?php

/*
 * This file is part of the Tomuss application.
 *
 * (c) Julien Brochet <mewt@madalynn.eu>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Madalynn\Tomuss\Storage;

use Symfony\Component\Filesystem\Filesystem;

class FileStorage implements StorageInterface
{
    /**
     * The Filesystem
     *
     * @var Symfony\Component\Filesystem\Filesystem
     */
    protected $fs;

    /**
     * The storage folder
     *
     * @var string
     */
    protected $folder;

    /**
     * Constructor
     *
     * @param string $folder The storage folder
     */
    public function __construct($folder)
    {
        $this->fs = new Filesystem();
        $this->folder = $folder;

        $this->fs->mkdir($folder);
    }

    /**
     * {@inheritdoc}
     */
    public function load($username)
    {
        $path = $this->folder.'/'.md5($username);
        if (false === file_exists($path)) {
            return array();
        }

        $s = file_get_contents($path);

        return unserialize($s);
    }

    /**
     * {@inheritdoc}
     */
    public function dump($username, array $notes)
    {
        $path = $this->folder.'/'.md5($username);
        $s = serialize($notes);

        file_put_contents($path, $s);
    }
}
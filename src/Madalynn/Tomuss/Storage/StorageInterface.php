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

/**
 * The StorageInterface
 *
 * @author Julien Brochet <mewt@madalynn.eu>
 */
interface StorageInterface
{
    /**
     * Stores user notes
     *
     * @param array $notes The array of notes
     */
    public function dump($username, array $notes);

    /**
     * Retrieves notes for a username
     *
     * @param string $username The username
     */
    public function load($username);
}
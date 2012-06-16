<?php

/*
 * This file is part of the Tomuss application.
 *
 * (c) Julien Brochet <mewt@madalynn.eu>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Madalynn\Tomuss\Notifier;

use Madalynn\Tomuss\Note;

/**
 * Base class for notifiers
 *
 * @author Julien Brochet <mewt@madalynn.eu>
 */
abstract class Notifier
{
    protected function format($format, Note $note)
    {
        return strtr($format, $this->getPlaceholders($note));
    }

    /**
     * Gets the place holders
     *
     * @param Note $note The note
     *
     * @return array The place holders
     */
    protected function getPlaceholders(Note $note)
    {
        return array(
            '%id%'     => $note->getId(),
            '%name%'   => $note->getName(),
            '%note%'   => $note->getNote(),
            '%author%' => $note->getAuthor(),
            '%date%'   => $note->getDate()->format('d/m/Y at H:i')
        );
    }

    /**
     * Notifies a note
     *
     * @param Note $note A Note instance
     */
    abstract public function notify(Note $note);
}
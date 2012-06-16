<?php

/*
 * This file is part of the Tomuss application.
 *
 * (c) Julien Brochet <mewt@madalynn.eu>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Madalynn\Tomuss\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Madalynn\Tomuss\Tomuss;

/**
 * Tomuss application
 *
 * @author Julien Brochet <mewt@madalynn.eu>
 */
class Application extends BaseApplication
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Tomuss', Tomuss::VERSION);

        $this->add(new SearchCommand());
    }
}
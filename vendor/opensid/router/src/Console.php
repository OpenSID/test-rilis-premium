<?php

namespace OpenSID;

/**
 * This middleware is used in routes that must be restricted to AJAX requests
 *
 * @author Anderson Salas <anderson@ingenia.me>
 */
class Console
{
    /**
     */
    public function handle()
    {
        if(! request()->ajax()) {
            trigger_404();
        }
    }
}
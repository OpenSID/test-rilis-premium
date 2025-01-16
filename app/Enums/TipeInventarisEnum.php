<?php

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class TipeInventarisEnum extends BaseEnum
{
    public const JALAN       = 'jalan';
    public const GEDUNG      = 'gedung';
    public const ASET        = 'aset';
    public const KONSTRUKSI  = 'konstruksi';
    public const PERALATAN   = 'peralatan';
    public const TANAH       = 'tanah';

    public static function all(): array
    {
        return [
            self::JALAN       => 'Jalan',
            self::GEDUNG      => 'Gedung',
            self::ASET        => 'Aset',
            self::KONSTRUKSI  => 'Konstruksi',
            self::PERALATAN   => 'Peralatan',
            self::TANAH       => 'Tanah',
        ];
    }
}


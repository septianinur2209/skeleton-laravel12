<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class MakeEnum extends GeneratorCommand
{
    /**
     * Nama perintah konsol.
     *
     * @var string
     */
    protected $name = 'make:enum';

    /**
     * Deskripsi perintah konsol.
     *
     * @var string
     */
    protected $description = 'Create a new Enum';

    /**
     * Tipe kelas yang dihasilkan.
     *
     * @var string
     */
    protected $type = 'Enum';

    /**
     * Dapatkan file stub untuk generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/enum.stub';
    }

    /**
     * Dapatkan namespace default untuk kelas.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Enums';
    }
}

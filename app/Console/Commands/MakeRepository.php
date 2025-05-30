<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class MakeRepository extends GeneratorCommand
{
    /**
     * Nama perintah konsol.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * Deskripsi perintah konsol.
     *
     * @var string
     */
    protected $description = 'Create a new Repository';

    /**
     * Tipe kelas yang dihasilkan.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Dapatkan file stub untuk generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/repository.stub';
    }

    /**
     * Dapatkan namespace default untuk kelas.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }
}

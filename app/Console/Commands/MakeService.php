<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class MakeService extends GeneratorCommand
{
    /**
     * Nama perintah konsol.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * Deskripsi perintah konsol.
     *
     * @var string
     */
    protected $description = 'Create a new Service';

    /**
     * Tipe kelas yang dihasilkan.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Dapatkan file stub untuk generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/repository.stub';  // Verify this path if 'repository.stub' is intended for service.
    }

    /**
     * Dapatkan namespace default untuk kelas.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services';
    }
}

<?php

namespace App\Console\Commands;

use App\Services\Entity\EntityLoader;
use Illuminate\Console\Command;

class LoadEntityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entity:load {file : Path to entity configuration file}
                            {--sync : Sync all entities from the file}
                            {--force : Force reload even if entity exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load entity definitions from a PHP configuration file';

    /**
     * Execute the console command.
     */
    public function handle(EntityLoader $loader): int
    {
        $file = $this->argument('file');

        // Resolve relative path
        if (! str_starts_with($file, '/')) {
            $file = base_path($file);
        }

        if (! file_exists($file)) {
            $this->error("Entity configuration file not found: {$file}");

            return self::FAILURE;
        }

        $this->info("Loading entities from: {$file}");
        $this->newLine();

        try {
            $entities = $loader->loadEntitiesFromFile($file);

            $this->info('✓ Successfully loaded '.count($entities).' entity/entities');
            $this->newLine();

            // Display loaded entities
            $this->table(
                ['ID', 'Name', 'Display Name', 'Collection', 'Fields', 'Active'],
                collect($entities)->map(fn ($entity) => [
                    $entity->id,
                    $entity->name,
                    $entity->display_name,
                    $entity->collection_name,
                    $entity->fields->count(),
                    $entity->is_active ? '✓' : '✗',
                ])
            );

            $this->newLine();
            $this->info('Entity endpoints:');

            foreach ($entities as $entity) {
                $routes = $loader->generateEntityRoutes($entity);
                $this->newLine();
                $this->line("<fg=cyan>{$entity->display_name}</> ({$entity->name})");
                $this->line("  REST: GET /api/v1/entity/{$entity->name}");
                $this->line('  SOAP: get'.ucfirst($entity->name).'()');
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to load entities: '.$e->getMessage());

            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }

            return self::FAILURE;
        }
    }
}

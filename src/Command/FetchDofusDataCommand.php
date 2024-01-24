<?php

namespace App\Command;

use App\Entity\Resource;
use App\Entity\Monster;
use App\Service\DofusApiService;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchDofusDataCommand extends Command
{
    private DofusApiService $dofusApiService;
    private EntityManagerInterface $entityManager;

    public function __construct(DofusApiService $dofusApiService, EntityManagerInterface $entityManager)
    {
        $this->dofusApiService = $dofusApiService;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:fetch-dofus-data')
            ->setDescription('Fetches items and monsters from Dofus API.')
            ->setHelp('This command allows you to fetch items and monsters from the Dofus API.');
    }

    #[NoReturn] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $this->fetchData($output, 'items', Resource::class);
        $this->fetchData($output, 'monsters', Monster::class);

        $output->writeln('Done fetching data.');
        return Command::SUCCESS;
    }

    private function fetchData(OutputInterface $output, string $type, string $entityClass): void
    {
        $skip = 0;
        $limit = 50;
        $totalFetched = 0;
        $repository = $this->entityManager->getRepository($entityClass);

        do {
            try {
                $response = $this->dofusApiService->fetch($skip, $limit, $type);
                $items = $response["data"] ?? [];
                $totalFetchedInBatch = count($items);

                foreach ($items as $item) {
                    $entity = $this->processItem($item, $entityClass, $repository);
                    $this->entityManager->persist($entity);
                }

                $this->entityManager->flush();
                $this->entityManager->clear();

                $skip += $limit;
                $totalFetched += $totalFetchedInBatch;
                $output->writeln("$totalFetchedInBatch Chargés. $type Total effectué: $totalFetched");
                $output->writeln("Mémoire utilisée :".memory_get_usage());
            } catch (\Exception $e) {
                $output->writeln('Error: ' . $e->getMessage());
            }
        } while (!empty($items));
    }

    private function processItem(array $item, string $entityClass, $repository): object
    {
        $entity = $repository->findOneBy(['ankamaId' => $item['id']]) ?? new $entityClass();

        $entity->setAnkamaId($item["id"]);
        $entity->setName($item["name"]["fr"] ?? 'Unknown');
        $entity->setImgUrl($item["img"]);

        if ($entityClass == Resource::class) {
            $entity->setLevel($item["level"]);
            $entity->setIsImportant(false);
        } elseif ($entityClass == Monster::class) {
            $entity->setLevel($item["grades"][0]["level"]);
        }

        return $entity;
    }
}

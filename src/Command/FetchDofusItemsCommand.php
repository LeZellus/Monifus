<?php

namespace App\Command;

use App\Entity\Resource;
use App\Service\DofusApiService;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchDofusItemsCommand extends Command
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
            ->setName('app:fetch-dofus-items')
            ->setDescription('Fetches items from Dofus API.')
            ->setHelp('This command allows you to fetch items from the Dofus API.');
    }

    #[NoReturn] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        $skip = 0;
        $limit = 50;
        $totalItems = 0;

        $repository = $this->entityManager->getRepository(Resource::class);

        do {
            try {
                //Ici on obtient un tableau de 10 éléments
                $response = $this->dofusApiService->fetchItems($skip, $limit);
                $items = $response["data"] ?? [];
                $totalItemsFetched = count($items);

                foreach($items as $item) {
                    //Get $resource by ankamaId
                    $resource = $repository->findOneBy(['ankamaId' => $item['id']]);

                    if(!$resource) {
                        $resource = new Resource();
                    }

                    //Update or set infos on resource
                    $resource->setAnkamaId($item["id"]);
                    if(isset($item["name"]["fr"])){
                        $resource->setName($item["name"]["fr"]);
                    } else {
                        $output->writeln('Element ' . $item["id"] . ' skippé car aucun nom associé');
                        continue;
                    }

                    $resource->setImgUrl($item["img"]);
                    $resource->setLevel($item["level"]);
                    $resource->setIsImportant(false);
                    $this->entityManager->persist($resource);
                }
                $this->entityManager->flush();
                $this->entityManager->clear();

                $skip += $limit;
                $totalItems += $totalItemsFetched;
                $output->writeln("Processed $totalItemsFetched items. Total processed: $totalItems");
                $output->writeln(memory_get_usage());
            } catch (\Exception $e) {
                $output->writeln('Error: ' . $e->getMessage());
            }

        } while (!empty($items));

        $output->writeln('Done fetching items.');
        return Command::SUCCESS;
    }
}
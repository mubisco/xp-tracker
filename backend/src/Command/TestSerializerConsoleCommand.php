<?php

declare(strict_types=1);

namespace XpTracker\Command;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XpTracker\Encounter\Domain\Party\PartyWasAssigned;

#[AsCommand(name: 'xptrack:test:serialize')]
final class TestSerializerConsoleCommand extends Command
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $event = new PartyWasAssigned('01HWZN8VK8BDA3Y4GWY51GRBNJ', '01HWZN8XHRPN4WX4VKGSSJMDDX', [1, 2]);
        $serialized = $this->serializer->serialize($event, 'json');
        $deserialized = $this->serializer->deserialize($serialized, PartyWasAssigned::class, 'json');
        dd($deserialized);
        return Command::SUCCESS;
    }
}

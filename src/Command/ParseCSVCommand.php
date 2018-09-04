<?php

namespace App\Command;

use App\Entity\Flight;
use App\Rule\CancelledInPeriod;
use App\Rule\DelayedForPeriod;
use App\Rule\DepartedFromEU;
use App\Rule\RuleInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ParseCSVDirect
 *
 * @author Itransition
 */
class ParseCSVCommand extends Command
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator, ?string $name = null)
    {
        parent::__construct($name);
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName('csv:parse')
            ->setDescription('Read a .csv file and apply a decision table')
            ->setHelp('Return information for every flight whether flight is claimable (eligible for compensation) or not')
            ->addArgument('file', InputArgument::OPTIONAL, 'Path to a .csv file', 'example.csv');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $filePath = $input->getArgument('file');
        $reader = \League\Csv\Reader::createFromPath($filePath);
        $rulesMap = [
            'eu' => new DepartedFromEU(),
            'ca' => new CancelledInPeriod(14),
            'de' => new DelayedForPeriod(3),
        ];
        $table = [
            ['rules' => ['eu' => 'Y', 'ca' => 'Y', 'de' => ' '], 'result' => 'Y'],
            ['rules' => ['eu' => 'Y', 'ca' => ' ', 'de' => 'Y'], 'result' => 'Y'],
            ['rules' => ['eu' => 'Y', 'ca' => 'N', 'de' => ' '], 'result' => 'N'],
            ['rules' => ['eu' => 'Y', 'ca' => ' ', 'de' => 'N'], 'result' => 'N'],
            ['rules' => ['eu' => 'N', 'ca' => ' ', 'de' => ' '], 'result' => 'N'],
        ];

        foreach ($reader->getIterator() as $item) {
            $flightDTO = new \App\DTO\Flight(@$item[0], @$item[1], @$item[2]);
            $validationResult = $this->validator->validate($flightDTO);
            if (0 !== $validationResult->count()) {
                $item[] = $validationResult;
            } else {
                $flight = Flight::createFromDTO($flightDTO);
                $claimable = null;
                foreach ($table as $candidate) {
                    $success = true;
                    foreach ($candidate['rules'] as $ruleCode => $value) {
                        /** @var RuleInterface $rule */
                        $rule = $rulesMap[$ruleCode];
                        $flightValue = $rule->check($flight) ? 'Y' : 'N';
                        $success &= $flightValue === $value || ' ' === $value;
                    }
                    if ($success) {
                        $claimable = $candidate['result'];
                        break;
                    }
                }
                // There is no satisfied value set in the table
                if (null === $claimable) {
                    $claimable = 'N';
                }
                $item[] = $claimable;
            }
            $output->writeln(\implode(' ', $item));
        }

        return 0;
    }
}

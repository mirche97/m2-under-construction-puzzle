<?php


namespace Mirche\UCPuzzle\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{

    protected \Mirche\UCPuzzle\Model\PieceFactory $pieceFactory;

    protected array $pieces = [
        'corner' => [
            ['F', 'T', 'B', 'F'],
            ['F', 'F', 'T', 'B'],
            ['T', 'B', 'F', 'F'],
            ['B', 'F', 'F', 'T'],
            ['F', 'B', 'T', 'F'],
            ['F', 'F', 'B', 'T'],
            ['B', 'T', 'F', 'F'],
            ['T', 'F', 'F', 'B'],
        ],
        'side' => [
            ['F', 'T', 'B', 'T'],
            ['F', 'B', 'T', 'B'],
            ['B', 'T', 'F', 'T'],
            ['T', 'B', 'F', 'B'],
            ['T', 'B', 'T', 'F'],
            ['B', 'F', 'B', 'T'],
            ['T', 'F', 'T', 'B'],
            ['B', 'T', 'B', 'F'],
        ],
        'middle' => [
            ['B', 'T', 'B', 'T'],
            ['T', 'B', 'T', 'B'],
        ]
    ];

    protected array $definitions = [
        'B' => 'blank',
        'T' => 'tab',
        'F' => 'flat',
    ];

    protected array $columns = ['top', 'right', 'bottom', 'left'];


    public function __construct(\Mirche\UCPuzzle\Model\PieceFactory $pieceFactory)
    {
        $this->pieceFactory = $pieceFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            foreach ($this->pieces as $type => $defs) {
               foreach ($defs as $def) {
                   $data = $this->mapData($type, $def);

                   $piece = $this->pieceFactory->create();
                   $piece->addData($data)->save();
               }
            }
        }
    }

    protected function mapData(string $type, array $def): array
    {
        $data['type'] = $type;
        $data['name'] = implode('', $def);
        $sides = array_map([$this, 'mapSides'], $def);

        foreach ($sides as $idx => $side) {
            $data[$this->columns[$idx]] = $side;
        }

        return $data;
    }

    function mapSides($x)
    {
        return $this->definitions[$x];
    }
}

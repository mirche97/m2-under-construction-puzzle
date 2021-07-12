<?php

namespace Mirche\UCPuzzle\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Mirche\UCPuzzle\Helper\Draw;
use Mirche\UCPuzzle\Model\ResourceModel\PieceRepository;

class Task extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'mirche_ucpuzzle_task';

    protected $_cacheTag = 'mirche_ucpuzzle_task';

    protected $_eventPrefix = 'mirche_ucpuzzle_task';

    protected $pieceRepository;

    protected \Mirche\UCPuzzle\Model\PuzzleFactory $puzzleFactory;

    private Puzzle $puzzle;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        PieceRepository $pieceRepository,
        \Mirche\UCPuzzle\Model\PuzzleFactory $puzzleFactory,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->pieceRepository = $pieceRepository;
        $this->puzzleFactory = $puzzleFactory;
    }

    protected function _construct()
    {
        $this->_init('Mirche\UCPuzzle\Model\ResourceModel\Task');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    public function getPuzzle(): Puzzle
    {
        if (!isset($this->puzzle)) {
            $this->puzzle = $this->puzzleFactory->create()->load($this->getData('puzzle_id'));
        }

        return $this->puzzle;
    }

    public function getName(): string
    {
        return $this->getData('name');
    }

    public function getRow(): int
    {
        return (int)$this->getData('row');
    }

    public function setRow(int $row): self
    {
        $this->setData('row', $row);

        return $this;
    }

    public function getColumn(): int
    {
        return (int)$this->getData('column');

    }

    public function setColumn(int $column): self
    {
        $this->setData('column', $column);

        return $this;
    }

    public function getPath(): string
    {
        return $this->getData('path');
    }

    public function setPath(string $path): self
    {
        $this->setData('path', $path);

        return $this;
    }

    public function isFinished(): bool
    {
        return (bool)$this->getData('finished');
    }

    public function getX(): int
    {
        $parts = explode(' ', $this->getPath());

        return (int)substr($parts[0], 1);
    }

    public function getY(): int
    {
        $parts = explode(' ', $this->getPath());

        return (int)$parts[1];
    }

    public function getPiece(): Piece
    {
        return $this->pieceRepository->getById($this->getData('piece_id'));
    }

    public function showName(): bool
    {
        return (bool)$this->getData('show_name');
    }
}

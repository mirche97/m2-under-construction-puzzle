<?php

/**
 * Copyright © 2021 mirche's craft corner | All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mirche\UCPuzzle\Block\HomePage;

use Magento\Framework\View\Element\Template;
use Mirche\UCPuzzle\Model\Piece;
use Mirche\UCPuzzle\Model\Puzzle;
use Mirche\UCPuzzle\Helper\Draw as DrawHelper;
use Mirche\UCPuzzle\Model\ResourceModel\Task\Collection as TaskCollection;
use Mirche\UCPuzzle\Model\Task;

class UnderConstruction extends Template
{
    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'Mirche_UCPuzzle::homepage/under-construction.phtml';

    protected \Mirche\UCPuzzle\Model\PuzzleFactory $puzzleFactory;

    protected DrawHelper $drawHelper;

    protected Puzzle $puzzle;

    public function __construct(
        Template\Context $context,
        \Mirche\UCPuzzle\Model\PuzzleFactory $puzzleFactory,
        DrawHelper $drawHelper,
        array $data = []
    ) {
        $this->puzzleFactory = $puzzleFactory;
        $this->drawHelper = $drawHelper;

        parent::__construct($context, $data);
    }

    public function getPuzzle(int $id = 1): Puzzle
    {
        if (!isset($this->puzzle)) {
            $this->puzzle = $this->puzzleFactory->create()->load($id);
        }

        return $this->puzzle;
    }

    public function getWidth(): int
    {
        return $this->getPuzzle()->getData('columns') * (DrawHelper::SIDE_LENGTH + DrawHelper::SPACE_BETWEEN) - DrawHelper::SPACE_BETWEEN;
    }

    public function getHeight(): int
    {
        return $this->getPuzzle()->getData('rows') * (DrawHelper::SIDE_LENGTH + DrawHelper::SPACE_BETWEEN) - DrawHelper::SPACE_BETWEEN;
    }

    public function getTasks(): TaskCollection
    {
        return $this->getPuzzle()->getTasksCollection();
    }

    public function getTextAttributes(Task $task): string
    {
        $offset = DrawHelper::getTextOffset($task->getPiece()->getTextPosition());

        $attributes =
            'x=' . ($task->getX() + round(DrawHelper::SIDE_LENGTH / 2)) .
            ' y=' .  ($task->getY() + $offset['y']) .
            ' textLength=' . DrawHelper::getTextLength($task->getName()).
            ' text-anchor="middle"' .
            ' lengthAdjust="spacingAndGlyphs"
              font-size=' . DrawHelper::TEXT_SIZE . 'px';

        return $attributes;
    }

    public function getAnimateId(Task $task): string
    {
        return 'animate' . $task->getId();
    }

    public function getMotionPath(Task $task): string
    {
        return sprintf('M%d,0 L0,0', $this->getWidth() - $task->getX());
    }

    public function getAnimateDuration(Task $task): float
    {
        return ($this->getPuzzle()->getColumns() - $task->getColumn() + 1) * 0.5;
    }
}

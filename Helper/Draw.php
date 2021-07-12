<?php

namespace Mirche\UCPuzzle\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Mirche\UCPuzzle\Model\Piece;
use Mirche\UCPuzzle\Model\Puzzle;
use Mirche\UCPuzzle\Model\ResourceModel\PieceRepository;
use Mirche\UCPuzzle\Model\Task;
use Psr\Log\LoggerInterface;

class Draw extends Data
{
    const SIDE_LENGTH = 200;
    const TAB_SIDE = 5 / 12 * self::SIDE_LENGTH;
    const TAB_INNER_SIDE = 1 / 12 * self::SIDE_LENGTH;
    const TAB_ARC_SIZE = 1 / 6 * self::SIDE_LENGTH;
    const TAB_ARC_END = 1 / 6 * self::SIDE_LENGTH;
    const BLANK_SIDE = 3 / 8 * self::SIDE_LENGTH;
    const BLANK_INNER_SIDE = 1 / 30 * self::SIDE_LENGTH;
    const BLANK_ARC_SIZE = 1 / 5 * self::SIDE_LENGTH;
    const BLANK_ARC_END = 1 / 4 * self::SIDE_LENGTH;
    const SPACE_BETWEEN = self::BLANK_INNER_SIDE;
    const TEXT_OFFSET_X = self::SIDE_LENGTH / 2;
    const TEXT_OFFSET_Y = ((self::SIDE_LENGTH / 5) * 3);
    const TEXT_SIZE = self::SIDE_LENGTH / 7;

    protected PieceRepository $pieceRepository;

    protected LoggerInterface $logger;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        PieceRepository $pieceRepository,
        LoggerInterface $logger
    )
    {
        $this->pieceRepository = $pieceRepository;
        $this->logger = $logger;
        parent::__construct($context, $storeManager);
    }

    public function drawPath(Task &$task, Puzzle $puzzle): string
    {
        $piece = $this->getPiece($task, $puzzle);
        $position = $this->getPosition($task, $puzzle);
        $task->setData('piece_id', $piece->getId());

        return $this->drawPiece(
            $position['x'],
            $position['y'],
            $piece->getData('top'),
            $piece->getData('right'),
            $piece->getData('bottom'),
            $piece->getData('left'),
        );
    }

    protected function getPosition(Task $task, Puzzle $puzzle): array
    {
        $row = $task->getRow();
        $column = $task->getColumn();

        $rows = $puzzle->getRows();

        $x = ($column - 1) * (self::SIDE_LENGTH + self::SPACE_BETWEEN);
        $y = ($rows - $row) * (self::SIDE_LENGTH + self::SPACE_BETWEEN);

        return ['x' => $x, 'y' => $y];
    }

    protected function getPiece(Task $task, Puzzle $puzzle): Piece
    {
        $row = $task->getRow();
        $column = $task->getColumn();

        $rows = $puzzle->getRows();
        $columns = $puzzle->getColumns();
        $puzzleType = $puzzle->getStartWith();


        if (!in_array($column, [1, $columns]) && !in_array($row, [1, $rows])) {
            return $this->getMiddle($rows, $columns, $row, $column, $puzzleType);
        }

        if (in_array($column, [1, $columns]) && in_array($row, [1, $rows])) {
            return $this->getCorner($rows, $columns, $row, $column, $puzzleType);
        }

        return $this->getSide($rows, $columns, $row, $column, $puzzleType);
    }

    protected function getMiddle(int $rows, int $columns, int $row, int $column, bool $puzzleType): Piece
    {
        // middle
        if ((($this->even($rows) && $this->even($row) || $this->odd($rows) && $this->odd($row))
                && $this->even($column) && $puzzleType
            ) || (
                ($this->even($rows) && $this->odd($row) || $this->odd($rows) && $this->even($row))
                && $this->odd($column) && $puzzleType
            )) {
            return $this->pieceRepository->getMiddle('tab');
        } else {
            return $this->pieceRepository->getMiddle('blank');
        }
    }

    protected function getCorner(int $rows, int $columns, int $row, int $column, bool $puzzleType): Piece
    {
        $horizontalSide = $row === 1 ? 'bottom' : 'top';
        $verticalSide = $column === 1 ? 'left' : 'right';

        $type =
            ($puzzleType &&
                (($row === $rows) && ($column === 1)) || $this->even($rows) && $this->even($columns)
            ) ||  // upper left or all when even x even
            ($puzzleType &&
                $this->odd($rows) && $this->odd($columns) && ($row === 1) && ($column === $columns)
            ) || // bottom right when odd x odd
            ($puzzleType &&
                ($row === $rows) && ($column === $columns) && ($this->odd($rows) && $this->even($columns))
            ) || // upper right when odd x even
            ($puzzleType &&
                ($column === 1) && $this->even($rows)
            ); // bottom left when even rows

        return $this->pieceRepository->getCorner($horizontalSide, $verticalSide, $type);
    }

    protected function getSide(int $rows, int $columns, int $row, int $column, bool $puzzleType): Piece
    {
        if (in_array($row, [1, $rows])) {
            // horizontal sides
            $side = $row === 1 ? 'bottom' : 'top';
            $oppositeIsTab =
                ($puzzleType && $side === 'top' && ($this->even($rows) && $this->even($column) || $this->odd($rows) && $this->odd($column))) ||
                ($puzzleType && $side === 'bottom' && ($this->odd($rows) && $this->even($column) || $this->even($rows) && $this->odd($column)));

            $oppositeSide = $oppositeIsTab ? 'tab' : 'blank';

            return $this->pieceRepository->getSide($side, $oppositeSide);
        }

        // vertical sides
        $side = $column === 1 ? 'left' : 'right';

        $oppositeIsTab =
            ($puzzleType && $side === 'left' && ($this->even($rows) && $this->even($row) || $this->odd($rows) && $this->odd($row)) ||
                ($puzzleType && $side === 'right' && (($this->even($rows) && $this->even($row) || $this->odd($rows) && $this->odd($row))) && $this->odd($columns))) ||
            ($puzzleType && $side === 'right' && (($this->even($rows) && $this->odd($row) || $this->odd($rows) && $this->even($row))) && $this->even($columns));

        $oppositeSide = $oppositeIsTab ? 'tab' : 'blank';

        return $this->pieceRepository->getSide($side, $oppositeSide);
    }


    protected function drawPiece(int $x, int $y, string $top, string $right, string $bottom, string $left): string
    {
        $path = "M$x $y";

        $path .= $this->drawSide('top', $top);
        $path .= $this->drawSide('right', $right);
        $path .= $this->drawSide('bottom', $bottom);
        $path .= $this->drawSide('left', $left);
        $path .= "z";

        return $path;
    }

    protected function drawSide(string $side, string $type): string
    {
        $method = 'draw' . ucfirst($type);

        return $this->$method($side);
    }

    protected function drawFlat(string $side): string
    {
        switch ($side) {
            case 'top':
                return sprintf(' h%f %f', self::SIDE_LENGTH, 0);
            case 'right':
                return sprintf(' v%f %f', 0, self::SIDE_LENGTH);
            case 'bottom':
                return sprintf(' h%f %f', -self::SIDE_LENGTH, 0);
            case 'left':
                return sprintf(' v%f %f', 0, -self::SIDE_LENGTH);
        }
    }

    protected function drawTab(string $side): string
    {
        switch ($side) {
            case 'top':
                return implode(' ', [
                    sprintf(' h%f v%f', self::TAB_SIDE, -self::TAB_INNER_SIDE),
                    $this->tabArc($side),
                    sprintf(' v%f h%f', self::TAB_INNER_SIDE, self::TAB_SIDE)
                ]);
            case 'right':
                return implode(' ', [
                    sprintf(' v%f h%f', self::TAB_SIDE, self::TAB_INNER_SIDE),
                    $this->tabArc($side),
                    sprintf(' h%f v%f', -self::TAB_INNER_SIDE, self::TAB_SIDE)
                ]);
            case 'bottom':
                return implode(' ', [
                    sprintf(' h%f v%f', -self::TAB_SIDE, self::TAB_INNER_SIDE),
                    $this->tabArc($side),
                    sprintf(' v%f h%f', -self::TAB_INNER_SIDE, -self::TAB_SIDE)
                ]);
            case 'left':
                return implode(' ', [
                    sprintf('v%f h%f', -self::TAB_SIDE, -self::TAB_INNER_SIDE),
                    $this->tabArc($side),
                    sprintf(' h%f v%f', self::TAB_INNER_SIDE, -self::TAB_SIDE)
                ]);
        }
    }

    function drawBlank(string $side)
    {
        switch ($side) {
            case 'top':
                return implode(' ', [
                    sprintf(' h%f v%f', self::BLANK_SIDE, self::BLANK_INNER_SIDE),
                    $this->blankArc($side),
                    sprintf(' v%f h%f', -self::BLANK_INNER_SIDE, self::BLANK_SIDE)
                ]);
            case 'right':
                return implode(' ', [
                    sprintf(' v%f h%f', self::BLANK_SIDE, -self::BLANK_INNER_SIDE),
                    $this->blankArc($side),
                    sprintf(' h%f v%f', self::BLANK_INNER_SIDE, self::BLANK_SIDE)
                ]);
            case 'bottom':
                return implode(' ', [
                    sprintf(' h%f v%f', -self::BLANK_SIDE, -self::BLANK_INNER_SIDE),
                    $this->blankArc($side),
                    sprintf(' v%f h%f', self::BLANK_INNER_SIDE, -self::BLANK_SIDE)
                ]);
            case 'left':
                return implode(' ', [
                    sprintf(' v%f h%f', -self::BLANK_SIDE, self::BLANK_INNER_SIDE),
                    $this->blankArc($side),
                    sprintf(' h%f v%f', -self::BLANK_INNER_SIDE, -self::BLANK_SIDE)
                ]);
        }
    }

    protected function tabArc(string $side): string
    {
        switch ($side) {
            case 'top':
                return sprintf(' a%f %f 0 1 1 %f 0', self::TAB_ARC_SIZE, self::TAB_ARC_SIZE, self::TAB_ARC_END);
            case 'right':
                return sprintf(' a%f %f 0 1 1 0 %f', self::TAB_ARC_SIZE, self::TAB_ARC_SIZE, self::TAB_ARC_END);
            case 'bottom':
                return sprintf(' a%f %f 0 1 1 %f 0', self::TAB_ARC_SIZE, self::TAB_ARC_SIZE, -self::TAB_ARC_END);
            case 'left':
                return sprintf(' a%f %f 0 1 1 0 %f', self::TAB_ARC_SIZE, self::TAB_ARC_SIZE, -self::TAB_ARC_END);
        }
    }

    protected function blankArc(string $side): string
    {
        switch ($side) {
            case 'top':
                return sprintf(' a%f %f 0 1 0 %f 0', self::BLANK_ARC_SIZE, self::BLANK_ARC_SIZE, self::BLANK_ARC_END);
            case 'right':
                return sprintf(' a%f %f 0 1 0 0 %f', self::BLANK_ARC_SIZE, self::BLANK_ARC_SIZE, self::BLANK_ARC_END);
            case 'bottom':
                return sprintf(' a%f %f 0 1 0 %f 0', self::BLANK_ARC_SIZE, self::BLANK_ARC_SIZE, -self::BLANK_ARC_END);
            case 'left':
                return sprintf(' a%f %f 0 1 0 0 %f', self::BLANK_ARC_SIZE, self::BLANK_ARC_SIZE, -self::BLANK_ARC_END);
        }
    }

    protected function odd(int $number): bool
    {
        return $number % 2;
    }

    protected function even(int $number): bool
    {
        return !$this->odd($number);
    }

    public static function getTextOffset(string $position): array
    {
        $offset['x'] = self::TEXT_OFFSET_X;
        switch ($position) {
            case Piece::TEXT_POSITION_CENTER:
                $offset['y'] = self::TEXT_OFFSET_Y - round(self::TAB_INNER_SIDE / 2);
                break;
            case Piece::TEXT_POSITION_BOTTOM:
                $offset['y'] = self::SIDE_LENGTH - round(self::TAB_INNER_SIDE);
                break;

        }

        return $offset;
    }

    public static function getTextLength(string $text): int
    {
        $length = strlen($text);

        if ($length >= 8 && $length <= 14) {
            return self::SIDE_LENGTH * 0.8;
        }

        if ($length >= 4 && $length < 8) {
            return self::SIDE_LENGTH * 0.6;
        }

        if ($length < 4) {
            return self::SIDE_LENGTH * 0.5;
        }

        return self::SIDE_LENGTH * 0.95;
    }
}

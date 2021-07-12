<?php

namespace Mirche\UCPuzzle\Model\ResourceModel;

interface PieceRepositoryInterface
{
    const START_WITH_TAB = 1;
    const START_WITH_BLANK = 0;

    public function getCorner(string $horizontalSide, string $verticalSide, int $type = 1);

    public function getSide(string $side, string $sideOpposite);

    public function getMiddle(string $horizontalSides);

    public function getByName(string $name);

    public function getById(int $id);

    public function get(string $top, string $right, string $bottom, string $left);
}

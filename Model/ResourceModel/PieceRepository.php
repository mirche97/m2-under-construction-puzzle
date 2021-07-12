<?php

namespace Mirche\UCPuzzle\Model\ResourceModel;

use Mirche\UCPuzzle\Model\ResourceModel\Piece as Resource;
use Mirche\UCPuzzle\Model\Piece as PieceModel;

class PieceRepository implements PieceRepositoryInterface
{
    protected Resource $resource;

    protected \Mirche\UCPuzzle\Model\PieceFactory $pieceFactory;

    protected \Mirche\UCPuzzle\Model\ResourceModel\Piece\CollectionFactory $pieceCollectionFactory;


    public function __construct(
        Resource $resource,
        \Mirche\UCPuzzle\Model\PieceFactory $pieceFactory,
        \Mirche\UCPuzzle\Model\ResourceModel\Piece\CollectionFactory $pieceCollectionFactory
    )
    {
        $this->resource = $resource;
        $this->pieceFactory = $pieceFactory;
        $this->pieceCollectionFactory = $pieceCollectionFactory;
    }

    public function getCorner(string $horizontalSide, string $verticalSide, int $type = 1): PieceModel
    {
        $collection = $this->pieceCollectionFactory->create();
        $collection
            ->addFieldToFilter('type', 'corner')
            ->addFieldToFilter($horizontalSide, 'flat')
            ->addFieldToFilter($verticalSide, 'flat');

        $corner = sprintf('%s-%s', $horizontalSide, $verticalSide);
        switch ($corner) {
            case 'top-left':
                $tab = $type ? 'right' : 'bottom';
                break;
            case 'top-right':
                $tab = $type ? 'bottom' : 'left';
                break;
            case 'bottom-left':
                $tab = $type ? 'top' : 'right';
                break;
            case 'bottom-right':
                $tab = $type ? 'left' : 'top';
                break;
        }

        $collection
            ->addFieldToFilter($tab, 'tab');

        return $collection->fetchItem();
    }

    public function getSide(string $side, string $sideOpposite): PieceModel
    {
        $collection = $this->pieceCollectionFactory->create();
        $collection
            ->addFieldToFilter('type', 'side')
            ->addFieldToFilter($side, 'flat')
            ->addFieldToFilter(PieceModel::oppositeSide($side), $sideOpposite);

        return $collection->fetchItem();
    }

    public function getMiddle(string $horizontalSides): PieceModel
    {
        $collection = $this->pieceCollectionFactory->create();

        $collection
            ->addFieldToFilter('type', 'middle')
            ->addFieldToFilter('top', $horizontalSides)
            ->addFieldToFilter('bottom', $horizontalSides);

        return $collection->fetchItem();
    }

    public function getByName(string $name): PieceModel
    {
        $instance = $this->pieceFactory->create();
        $this->resource->load($instance, $name, 'name');

        return $instance;
    }

    public function getById(int $id): PieceModel
    {
        $instance = $this->pieceFactory->create();
        $this->resource->load($instance, $id, 'piece_id');

        return $instance;
    }

    public function get(string $top, string $right, string $bottom, string $left): PieceModel
    {
        $collection = $this->pieceCollectionFactory->create();

        return $collection
            ->addFieldToFilter('top', $top)
            ->addFieldToFilter('right', $right)
            ->addFieldToFilter('bottom', $bottom)
            ->addFieldToFilter('left', $left)
            ->fetchItem();
    }
}

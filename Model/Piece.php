<?php

namespace Mirche\UCPuzzle\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Piece extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'mirche_ucpuzzle_piece';

    const TEXT_POSITION_TOP = 'top';
    const TEXT_POSITION_CENTER = 'center';
    const TEXT_POSITION_BOTTOM = 'bottom';

    protected $_cacheTag = 'mirche_ucpuzzle_piece';

    protected $_eventPrefix = 'mirche_ucpuzzle_piece';

    protected function _construct()
    {
        $this->_init('Mirche\UCPuzzle\Model\ResourceModel\Piece');
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

    public function getTop(): string
    {
        return $this->getData('top');
    }

    public function getLeft(): string
    {
        return $this->getData('left');
    }

    public function getBottom(): string
    {
        return $this->getData('bottom');
    }

    public function getRight(): string
    {
        return $this->getData('right');
    }

    public function getTextPosition(): string
    {
        if (in_array('blank', [$this->getLeft(), $this->getRight()])) {
            return self::TEXT_POSITION_BOTTOM;
        }

        return self::TEXT_POSITION_CENTER;
    }
    
    public static function oppositeSide(string $side): string
    {
        switch ($side) {
            case 'top':
                return 'bottom';
            case 'bottom':
                return 'top';
            case 'left':
                return 'right';
            case 'right':
                return 'left';
        }
    }
}

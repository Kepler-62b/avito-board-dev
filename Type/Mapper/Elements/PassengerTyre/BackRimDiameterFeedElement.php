<?php
/*
 *  Copyright 2024.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

declare(strict_types=1);

namespace BaksDev\Avito\Board\Type\Mapper\Elements\PassengerTyre;

use BaksDev\Avito\Board\Type\Mapper\AvitoBoardProductEnum;
use BaksDev\Avito\Board\Type\Mapper\Elements\AvitoFeedElementInterface;
use BaksDev\Avito\Board\Type\Mapper\Products\PassengerTyre\PassengerTyreProductInterface;

/**
 * Диаметр задней оси, дюймы.
 *
 * Применимо, если в поле DifferentWidthTires указано значение 'Да'
 *
 * Одно из значений от Авито
 */
final readonly class BackRimDiameterFeedElement implements AvitoFeedElementInterface
{
    public const string FEED_ELEMENT = 'BackRimDiameter';

    public const string LABEL = 'Диаметр задней оси, дюймы';

    public function __construct(
        private ?PassengerTyreProductInterface $product = null,
    ) {}

    public function isMapping(): bool
    {
        return false;
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function choices(): null
    {
        return null;
    }

    /**
     * Если элемент обязательный, то значение будем брать такое же, как и в элементе
     * @see RimDiameterFeedElement
     */
    public function data(): string
    {
        return '10';
    }

    public function help(): ?string
    {
        return $this->product->help(self::FEED_ELEMENT);
    }

    public function product(): ?AvitoBoardProductEnum
    {
        return $this->product->getProduct();
    }

    public function label(): string
    {
        return self::LABEL;
    }
}

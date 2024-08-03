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

namespace BaksDev\Avito\Board\Type\Mapper\Products\SweatersAndShirts;

use BaksDev\Avito\Board\Type\Mapper\AvitoBoardProductEnum;
use BaksDev\Avito\Board\Type\Mapper\Elements\AvitoBoardElementInterface;
use BaksDev\Avito\Board\Type\Mapper\Elements\SweatersAndShirts\CategoryElement;
use BaksDev\Avito\Board\Type\Mapper\Elements\SweatersAndShirts\GoodsTypeElement;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class SweatersAndShirtsProduct implements SweatersAndShirtsProductInterface
{
    private const string SWEATERS_AND_SHIRTS = 'Кофты и футболки';

    public function __construct(
        #[AutowireIterator('baks.avito.board.elements')] private iterable $elements,
    ) {}

    public function getElements(): array
    {
        $elements = null;

        /** @var AvitoBoardElementInterface $element */
        foreach ($this->elements as $element)
        {

            if ($element->getProduct() === null)
            {
                $elements[] = new $element($this);
            }

            if ($element->getProduct() instanceof self)
            {
                $elements[] = $element;
            }
        }

        if (null === $elements)
        {
            throw new \Exception();
        }

        return $elements;
    }

    public function getElement(string $elementName): ?AvitoBoardElementInterface
    {
        /** @var AvitoBoardElementInterface $element */
        foreach ($this->elements as $element)
        {
            if ($element->element() === $elementName)
            {

                if ($element->getProduct() === null)
                {
                    return new $element($this);
                }

                if ($element->getProduct() instanceof AvitoBoardProductEnum &&
                    $element->getProduct()->value === AvitoBoardProductEnum::SweatersAndShirts->value)
                {
                    return $element;
                }
            }
        }

        throw new \Exception();
    }

    public function getProduct(): string
    {
        return self::SWEATERS_AND_SHIRTS;
    }

    public function isEqualProduct(string $product): bool
    {
        return $product === self::SWEATERS_AND_SHIRTS;
    }

    public function __toString(): string
    {
        $category = (new CategoryElement($this))->getDefault();
        $variation = (new GoodsTypeElement($this))->getDefault();

        return sprintf('%s / %s', $category, $variation);
    }
}

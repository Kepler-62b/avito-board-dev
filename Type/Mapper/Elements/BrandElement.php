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

namespace BaksDev\Avito\Board\Type\Mapper\Elements;

use BaksDev\Avito\Board\Type\Mapper\Products\AvitoProductInterface;

/**
 *  Бренд.
 *  Одно из значений
 *
 * Элемент общий для всех продуктов Авито
 * @TODO ожидает добавление в характеристики продукта поэтому пока isMapping - true| getDefault - string
 */
class BrandElement implements AvitoBoardElementInterface
{
    public const string ELEMENT_ALIAS = 'product_brand';

    private const string ELEMENT = 'Brand';

    private const string ELEMENT_LABEL = 'Бренд';

    public function __construct(
        private readonly ?AvitoProductInterface $product = null,
        protected ?string $data = null,
    ) {}

    public function isMapping(): true
    {
        return true;
    }

    public function isRequired(): true
    {
        return true;
    }

    public function isChoices(): false
    {
        return false;
    }

    public function isInput(): bool
    {
        return true;
    }

    public function getDefault(): string
    {
        return '';
    }

    public function getHelp(): string
    {
        return 'Общее значение для всех продуктов в данной категории';
    }

    public function getProduct(): null
    {
        return $this->product;
    }

    public function setData(string|array $mapper): void
    {
        $this->data = $mapper;
    }

    // @TODO ожидает добавление в характеристики продукта
    public function fetchData(): string
    {
        if (null === $this->data)
        {
            throw new \Exception('Не вызван метод setData');
        }

        return $this->data;

        // @TODO возвращать из свойств продукта, когда бренд будет добавлен
        // return $product[self::ELEMENT_ALIAS];
    }

    public function element(): string
    {
        return self::ELEMENT;
    }

    public function label(): string
    {
        return self::ELEMENT_LABEL;
    }
}

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

namespace BaksDev\Avito\Board\Type\Mapper\Elements\PassengerTire;

use BaksDev\Avito\Board\Type\Mapper\Products\PassengerTire\PassengerTireProduct;

/**
 * Разноширокий комплект шин
 *
 * Одно из значений:
 * — Да
 * — Нет
 *
 *  Список элементов для категории "Легковые шины"
 *  https://www.avito.ru/autoload/documentation/templates/67016?onlyRequiredFields=false&fileFormat=xml
 *
 *   -- не используем, так как не реализуем разноширокие комплекты --
 */
class DifferentWidthTiresElement
{
    private const string ELEMENT = 'DifferentWidthTires';

    private const string LABEL = 'Разноширокий комплект шин';

    public function isMapping(): false
    {
        return false;
    }

    public function isRequired(): false
    {
        return false;
    }

    public function isChoices(): false
    {
        return false;
    }

    public function getDefault(): string
    {
        return 'Нет';
    }

    public function getHelp(): null
    {
        return null;
    }

    public function fetchData(array $data): null
    {
        return null;
    }

    public function element(): string
    {
        return self::ELEMENT;
    }

    public function label(): string
    {
        return self::LABEL;
    }

    public function getProduct(): string
    {
        return PassengerTireProduct::class;
    }
}

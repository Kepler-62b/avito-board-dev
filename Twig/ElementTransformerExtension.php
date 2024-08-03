<?php
/*
 *  Copyright 2023.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Avito\Board\Twig;

use BaksDev\Avito\Board\Type\Mapper\AvitoBoardMapperProvider;
use BaksDev\Avito\Board\Type\Mapper\Elements\AvitoBoardElementInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ElementTransformerExtension extends AbstractExtension
{
    public function __construct(
        private readonly AvitoBoardMapperProvider $mapperProvider,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('element_transform', [$this, 'elementTransform']),
        ];
    }

    public function elementTransform(array $product): ?array
    {
        /** Возвращаем null, если нет маппера для продукта */
        if ($product['avito_board_avito_category'] === null)
        {
            return null;
        }

        /** Преобразуем массив элементов из маппера*/
        //        $mappedElements = $this->mappedElementTransform($product['avito_board_mapper']);
        $mappedElements = $this->mapperTransform($product['avito_board_mapper'], $product['avito_board_avito_category']);

        /** Получаем все элементы по типу продукта, не участвующих в маппинге */
        $unmappedElements = array_filter(
            $this->mapperProvider->filterElements($product['avito_board_avito_category']),
            function (AvitoBoardElementInterface $element) {
                return $element->isMapping() === false;
            }
        );

        $elements = null;
        foreach ($unmappedElements as $element)
        {
            if ($element->getDefault() === null)
            {
                $element->setData($product);

                if ($element->fetchData() === null)
                {
                    // @TODO что делать, если дата окончания у продукта не указана - не отрендерить элемент
                    continue;
                }

                $elements[$element->element()] = $element->fetchData();
            }
            else
            {
                $elements[$element->element()] = $element->getDefault();
            }
        }

        /**
         * Объединяем массивы элементов по принципу:
         * - элемент, описанный в классе имеет приоритет над элементом, лученным из маппера
         *  (элемент класса перезаписывает элемент из маппера)
         */
        $allElements = array_merge($mappedElements, $elements);

        return $allElements;
    }

    private function mapperTransform(string $mapper, string $category): array
    {
        $mapper = json_decode($mapper, false, 512, JSON_THROW_ON_ERROR);

        $instances = $this->instances($mapper, $category);

        return $this->elements($instances);
    }

    /**
     * @return array<class-string, AvitoBoardElementInterface>
     */
    private function instances(array $mapper, string $category): array
    {
        $instances = null;
        foreach ($mapper as $element)
        {
            $instance = $this->mapperProvider->getOneElement($category, $element->element);
            $instance->setData($element->value);
            $instances[$instance::class] = $instance;
        }

        return $instances;
    }

    /**
     * @return array<string, string>
     */
    private function elements(array $instances): array
    {
        foreach ($instances as $className => $instance)
        {
            $baseClass = get_parent_class($className);

            if ($baseClass && isset($instances[$baseClass]))
            {
                $baseData = $instances[$baseClass]->getData();
                $instance->setBaseData($baseData);

                $elements[$instances[$baseClass]->element()] = $instance->getData();
            }

            $elements[$instance->element()] = $instance->getData();
        }

        return $elements;
    }
}

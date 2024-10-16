<?php

namespace BaksDev\Avito\Board\Mapper;

use BaksDev\Avito\Board\Mapper\Elements\AvitoBoardElementInterface;
use BaksDev\Avito\Board\Mapper\Products\AvitoBoardProductInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @see PassengerTireProductInterface
 * @see SweatersAndShirtsProductInterface
 */
final readonly class AvitoBoardMapperProvider
{
    public function __construct(
        #[AutowireIterator('baks.avito.board.mapper.products')] private iterable $products,
    ) {}

    /** @return list<AvitoBoardProductInterface> */
    public function getProducts(): array
    {
        return iterator_to_array($this->products);
    }

    public function getProduct(string $productCategory): AvitoBoardProductInterface
    {
        foreach($this->products as $product)
        {
            if($product->isEqual($productCategory))
            {
                return $product;
            }
        }

        throw new \Exception('Не найдена категория продукта с названием '.$productCategory);
    }

    /**
     * @return list<AvitoBoardElementInterface>
     */
    public function filterElements(string $productCategory): array
    {
        /** @var AvitoBoardProductInterface $product */
        foreach($this->products as $product)
        {
            if($product->isEqual($productCategory))
            {
                return $product->getElements();
            }
        }

        throw new \Exception('Не найдены элементы, относящиеся к категории '.$productCategory);
    }

    public function getElement(string $productCategory, string $elementName): AvitoBoardElementInterface
    {
        /** @var AvitoBoardProductInterface $product */
        foreach($this->products as $product)
        {
            if($product->isEqual($productCategory))
            {
                $allElements = $product->getElements();

                foreach($allElements as $element)
                {
                    if($element->element() === $elementName)
                    {
                        return $element;
                    }
                }
            }
        }

        throw new \Exception('Не найден элемент с названием: '.$elementName);
    }
}

<?php

use BookSellerApiHandler\DTO\BookListRequestDTO;
use BookSellerApiHandler\Exception\QueryNotSupportedBySellerException;
use BookSellerApiHandler\Exception\StrategyNotFoundException;
use BookSellerApiHandler\Strategy\BestBooksStrategy;
use BookSellerApiHandler\Strategy\BookSellerStrategyManager;
use BookSellerApiHandler\Strategy\CheapReadsStrategy;
use BookSellerApiHandler\Strategy\RandomBookStoreStrategy;
use PHPUnit\Framework\TestCase;

/**
 * Class BookSellerStrategyManagerTest
 */
class BookSellerStrategyManagerTest extends TestCase
{
    /**
     * @throws QueryNotSupportedBySellerException
     * @throws StrategyNotFoundException
     */
    public function testGenerateGetBookListUrl(): void
    {
        $bookSellerCode = RandomBookStoreStrategy::BOOK_SELLER_CODE_RANDOM_BOOK_STORE;
        $bookSellerStrategy = new BookSellerStrategyManager($bookSellerCode);

        $bookListRequestDTO = new BookListRequestDTO();
        $bookListRequestDTO->setAuthor('Arthur C. Clarke');

        $url = $bookSellerStrategy->generateGetBookListUrl($bookListRequestDTO);
        $this->assertEquals('https://api.randombookstore.com/athr=Arthur+C.+Clarke&qry_lmt=10&fmt=json', $url);


        $bookSellerCode = CheapReadsStrategy::BOOK_SELLER_CODE_CHEAP_READS;
        $bookSellerStrategy = new BookSellerStrategyManager($bookSellerCode);

        $bookListRequestDTO = new BookListRequestDTO();
        $bookListRequestDTO->setAuthor('Jack Ketchum');
        $bookListRequestDTO->setPublisher('Warner Books');
        $bookListRequestDTO->setYearPublished(1989);
        $bookListRequestDTO->setLimit(123);

        $url = $bookSellerStrategy->generateGetBookListUrl($bookListRequestDTO);
        $this->assertEquals(
            'https://api.cheapreads.com/author-name/Jack+Ketchum/publisher-name/Warner+Books/year/1989/limit/123/data-format/xml',
            $url
        );

        $bookSellerCode = BestBooksStrategy::BOOK_SELLER_CODE_BEST_BOOKS;
        $bookSellerStrategy = new BookSellerStrategyManager($bookSellerCode);

        $bookListRequestDTO = new BookListRequestDTO();
        $bookListRequestDTO->setYearPublished(2007);

        $this->expectException(QueryNotSupportedBySellerException::class);
        $this->expectExceptionMessage('BEST_BOOKS does not support querying by year of publishing');
        $bookSellerStrategy->generateGetBookListUrl($bookListRequestDTO);
    }

    /**
     * @throws StrategyNotFoundException
     */
    public function testResolveFetchedData(): void
    {
        $bookSellerCode = BestBooksStrategy::BOOK_SELLER_CODE_BEST_BOOKS;
        $bookSellerStrategy = new BookSellerStrategyManager($bookSellerCode);

        $data = '[{"book":{"title":"The Invincible","author":"Stanislaw Lem","isbn":"9788025909713"},"stock":{"level":46,"price":13.99}},{"book":{"title":"The Martian","author":"Andy Weir","isbn":"9780140038538"},"stock":{"level":78,"price":14.99}}]';

        $bookListResponseHeaderDTO = $bookSellerStrategy->resolveFetchedData($data);

        $this->assertEquals('Stanislaw Lem', $bookListResponseHeaderDTO->getBookListResponseLineDTOs()[0]->getAuthor());
        $this->assertEquals(14.99, $bookListResponseHeaderDTO->getBookListResponseLineDTOs()[1]->getPrice());


        $bookSellerCode = CheapReadsStrategy::BOOK_SELLER_CODE_CHEAP_READS;
        $bookSellerStrategy = new BookSellerStrategyManager($bookSellerCode);

        $data = '<?xml version="1.0"?><books><book><name>Hyperspace A Scientific Odyssey Through Parallel Universes, Time Warps, and the Tenth Dimension</name><author_name>Michio Kaku</author_name><isbn_number>9788376484983</isbn_number><stock><number>17</number><unit_price>18.99</unit_price></stock></book><book><name>Rendezvous with Rama</name><author_name>Arthur C. Clarke</author_name><isbn_number>9788361516798</isbn_number><stock><number>6</number><unit_price>11.99</unit_price></stock></book></books>';

        $bookListResponseHeaderDTO = $bookSellerStrategy->resolveFetchedData($data);

        $this->assertEquals('Michio Kaku', $bookListResponseHeaderDTO->getBookListResponseLineDTOs()[0]->getAuthor());
        $this->assertEquals(11.99, $bookListResponseHeaderDTO->getBookListResponseLineDTOs()[1]->getPrice());
    }
}

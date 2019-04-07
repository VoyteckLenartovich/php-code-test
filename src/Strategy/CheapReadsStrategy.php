<?php

namespace BookSellerApiHandler\Strategy;

use BookSellerApiHandler\DTO\BookListRequestDTO;
use BookSellerApiHandler\DTO\BookListResponseHeaderDTO;
use BookSellerApiHandler\DTO\BookListResponseLineDTO;
use SimpleXMLElement;

/**
 * Class CheapReadsStrategy
 */
class CheapReadsStrategy implements BookSellerStrategyInterface
{
    const BOOK_SELLER_CODE_CHEAP_READS = 'CHEAP_READS';
    const URL_BASE = 'https://api.cheapreads.com/';
    const FORMAT = 'xml';

    /**
     * @param BookListRequestDTO $bookListRequestDTO
     * @return string
     */
    public function generateGetBookListUrl(BookListRequestDTO $bookListRequestDTO): string
    {
        $url = self::URL_BASE;

        if ($author = $bookListRequestDTO->getAuthorUrlEncoded()) {
            $url .= 'author-name/' . $author . '/';
        }


        if ($publisher = $bookListRequestDTO->getPublisherUrlEncoded()) {
            $url .= 'publisher-name/' . $publisher . '/';
        }

        if ($yearPublished = $bookListRequestDTO->getYearPublished()) {
            $url .= 'year/' . $yearPublished . '/';
        }

        $url .= 'limit/' . $bookListRequestDTO->getLimit() . '/';
        $url .= 'data-format/' . self::FORMAT;

        return $url;
    }

    /**
     * @param string $data
     * @return BookListResponseHeaderDTO
     */
    public function resolveFetchedData(string $data): BookListResponseHeaderDTO
    {
        $xml = new SimpleXMLElement($data);

        $bookListResponseHeaderDTO = new BookListResponseHeaderDTO();

        foreach ($xml as $result) {
            $bookListResponseLineDTO = new BookListResponseLineDTO();
            $bookListResponseLineDTO->setTitle((string)$result->name);
            $bookListResponseLineDTO->setAuthor((string)$result->author_name);
            $bookListResponseLineDTO->setIsbn((string)$result->isbn_number);
            $bookListResponseLineDTO->setQuantity((int)$result->stock->number);
            $bookListResponseLineDTO->setPrice((float)$result->stock->unit_price);

            $bookListResponseHeaderDTO->addBookListResponseLineDTO($bookListResponseLineDTO);
        }

        return $bookListResponseHeaderDTO;
    }
}

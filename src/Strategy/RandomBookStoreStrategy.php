<?php

namespace BookSellerApiHandler\Strategy;

use BookSellerApiHandler\DTO\BookListRequestDTO;
use BookSellerApiHandler\DTO\BookListResponseHeaderDTO;
use BookSellerApiHandler\DTO\BookListResponseLineDTO;

/**
 * Class RandomBookStoreStrategy
 */
class RandomBookStoreStrategy implements BookSellerStrategyInterface
{
    const BOOK_SELLER_CODE_RANDOM_BOOK_STORE = 'RANDOM_BOOK_STORE';
    const URL_BASE = 'https://api.randombookstore.com/';
    const FORMAT = 'json';

    /**
     * @param BookListRequestDTO $bookListRequestDTO
     * @return string
     */
    public function generateGetBookListUrl(BookListRequestDTO $bookListRequestDTO): string
    {
        $url = self::URL_BASE;

        if ($author = $bookListRequestDTO->getAuthorUrlEncoded()) {
            $url .= 'athr=' . $author . '&';
        }

        if ($publisher = $bookListRequestDTO->getPublisherUrlEncoded()) {
            $url .= 'pblshr=' . $publisher . '&';
        }

        if ($yearPublished = $bookListRequestDTO->getYearPublished()) {
            $url .= 'yr_pblsh=' . $yearPublished . '&';
        }

        $url .= 'qry_lmt=' . $bookListRequestDTO->getLimit() . '&';
        $url .= 'fmt=' . self::FORMAT;

        return $url;
    }

    /**
     * @param string $data
     * @return BookListResponseHeaderDTO
     */
    public function resolveFetchedData(string $data): BookListResponseHeaderDTO
    {
        $json = json_decode($data);

        $bookListResponseHeaderDTO = new BookListResponseHeaderDTO();

        foreach ($json as $result) {
            $bookListResponseLineDTO = new BookListResponseLineDTO();
            $bookListResponseLineDTO->setTitle((string)$result->title);
            $bookListResponseLineDTO->setAuthor((string)$result->author);
            $bookListResponseLineDTO->setIsbn((string)$result->isbn);
            $bookListResponseLineDTO->setQuantity((int)$result->quantity);
            $bookListResponseLineDTO->setPrice((float)$result->price);

            $bookListResponseHeaderDTO->addBookListResponseLineDTO($bookListResponseLineDTO);
        }

        return $bookListResponseHeaderDTO;
    }
}

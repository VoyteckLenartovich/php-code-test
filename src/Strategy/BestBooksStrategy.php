<?php

namespace BookSellerApiHandler\Strategy;

use BookSellerApiHandler\DTO\BookListRequestDTO;
use BookSellerApiHandler\DTO\BookListResponseHeaderDTO;
use BookSellerApiHandler\DTO\BookListResponseLineDTO;
use BookSellerApiHandler\Exception\QueryNotSupportedBySellerException;

/**
 * Class BestBooksStrategy
 */
class BestBooksStrategy implements BookSellerStrategyInterface
{
    const BOOK_SELLER_CODE_BEST_BOOKS = 'BEST_BOOKS';
    const URL_BASE = 'https://api.bestbooks.com/';
    const FORMAT = 'json';

    /**
     * @param BookListRequestDTO $bookListRequestDTO
     * @return string
     * @throws QueryNotSupportedBySellerException
     */
    public function generateGetBookListUrl(BookListRequestDTO $bookListRequestDTO): string
    {
        $this->validateBookListRequestDTO($bookListRequestDTO);

        $url = self::URL_BASE;

        if ($author = $bookListRequestDTO->getAuthorUrlEncoded()) {
            $url .= 'author=' . $author . '&';
        }

        if ($publisher = $bookListRequestDTO->getPublisherUrlEncoded()) {
            $url .= 'publisher=' . $publisher . '&';
        }

        $url .= 'limit=' . $bookListRequestDTO->getLimit() . '&';
        $url .= 'format=' . self::FORMAT;

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
            $bookListResponseLineDTO->setTitle((string)$result->book->title);
            $bookListResponseLineDTO->setAuthor((string)$result->book->author);
            $bookListResponseLineDTO->setIsbn((string)$result->book->isbn);
            $bookListResponseLineDTO->setQuantity((int)$result->stock->level);
            $bookListResponseLineDTO->setPrice((float)$result->stock->price);

            $bookListResponseHeaderDTO->addBookListResponseLineDTO($bookListResponseLineDTO);
        }

        return $bookListResponseHeaderDTO;
    }

    /**
     * @param BookListRequestDTO $bookListRequestDTO
     * @throws QueryNotSupportedBySellerException
     */
    private function validateBookListRequestDTO(BookListRequestDTO $bookListRequestDTO): void
    {
        if (null !== $bookListRequestDTO->getYearPublished()) {
            throw new QueryNotSupportedBySellerException(
                self::BOOK_SELLER_CODE_BEST_BOOKS . ' does not support querying by year of publishing'
            );
        }
    }
}

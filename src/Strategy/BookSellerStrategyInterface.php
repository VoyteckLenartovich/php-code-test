<?php

namespace BookSellerApiHandler\Strategy;

use BookSellerApiHandler\DTO\BookListRequestDTO;
use BookSellerApiHandler\DTO\BookListResponseHeaderDTO;
use BookSellerApiHandler\Exception\QueryNotSupportedBySellerException;

/**
 * Interface BookSellerStrategyInterface
 */
interface BookSellerStrategyInterface
{
    /**
     * @param BookListRequestDTO $bookListRequestDTO
     * @return string
     * @throws QueryNotSupportedBySellerException
     */
    public function generateGetBookListUrl(BookListRequestDTO $bookListRequestDTO): string;

    /**
     * @param string $data
     * @return BookListResponseHeaderDTO
     */
    public function resolveFetchedData(string $data): BookListResponseHeaderDTO;
}

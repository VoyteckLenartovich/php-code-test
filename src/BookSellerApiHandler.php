<?php

namespace BookSellerApiHandler;

use BookSellerApiHandler\DTO\BookListRequestDTO;
use BookSellerApiHandler\DTO\BookListResponseHeaderDTO;
use BookSellerApiHandler\Exception\DataFetchingFailedException;
use BookSellerApiHandler\Exception\QueryNotSupportedBySellerException;
use BookSellerApiHandler\Exception\StrategyNotFoundException;
use BookSellerApiHandler\Strategy\BookSellerStrategyManager;

/**
 * Class BookSellerApiHandler
 */
class BookSellerApiHandler
{
    /**
     * @param string $bookSellerCode
     * @param BookListRequestDTO $bookListRequestDTO
     * @return BookListResponseHeaderDTO
     * @throws DataFetchingFailedException
     * @throws QueryNotSupportedBySellerException
     * @throws StrategyNotFoundException
     */
    public function getBookList(
        string $bookSellerCode,
        BookListRequestDTO $bookListRequestDTO
    ): BookListResponseHeaderDTO {
        $bookSellerStrategy = new BookSellerStrategyManager($bookSellerCode);

        $url = $bookSellerStrategy->generateGetBookListUrl($bookListRequestDTO);
        $data = $this->fetchData($url);
        $bookListResponseHeaderDTO = $bookSellerStrategy->resolveFetchedData($data);

        return $bookListResponseHeaderDTO;
    }

    /**
     * @param string $url
     * @return string
     * @throws DataFetchingFailedException
     */
    private function fetchData(string $url): string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, $url);
        $data = curl_exec($curl);
        curl_close($curl);

        if (false === $data) {
            throw new DataFetchingFailedException();
        }

        return $data;
    }
}

<?php

namespace BookSellerApiHandler\Strategy;

use BookSellerApiHandler\DTO\BookListRequestDTO;
use BookSellerApiHandler\DTO\BookListResponseHeaderDTO;
use BookSellerApiHandler\Exception\QueryNotSupportedBySellerException;
use BookSellerApiHandler\Exception\StrategyNotFoundException;

/**
 * Class BookSellerStrategyManager
 */
class BookSellerStrategyManager implements BookSellerStrategyInterface
{
    /**
     * @var BookSellerStrategyInterface
     */
    private $strategy;

    /**
     * BookSellerStrategyManager constructor.
     * @param string $bookSellerCode
     * @throws StrategyNotFoundException
     */
    public function __construct(string $bookSellerCode)
    {
        switch ($bookSellerCode) {
            case BestBooksStrategy::BOOK_SELLER_CODE_BEST_BOOKS:
                $this->strategy = new BestBooksStrategy();
                break;
            case RandomBookStoreStrategy::BOOK_SELLER_CODE_RANDOM_BOOK_STORE:
                $this->strategy = new RandomBookStoreStrategy();
                break;
            case CheapReadsStrategy::BOOK_SELLER_CODE_CHEAP_READS:
                $this->strategy = new CheapReadsStrategy();
                break;
            default:
                throw new StrategyNotFoundException('BookSellerStrategy not found for code ' . $bookSellerCode);
        }
    }

    /**
     * @param BookListRequestDTO $bookListRequestDTO
     * @return string
     * @throws QueryNotSupportedBySellerException
     */
    public function generateGetBookListUrl(BookListRequestDTO $bookListRequestDTO): string
    {
        return $this->strategy->generateGetBookListUrl($bookListRequestDTO);
    }

    /**
     * @param string $data
     * @return BookListResponseHeaderDTO
     */
    public function resolveFetchedData(string $data): BookListResponseHeaderDTO
    {
        return $this->strategy->resolveFetchedData($data);
    }
}

<?php

namespace BookSellerApiHandler\DTO;

/**
 * Class BookListResponseHeaderDTO
 */
class BookListResponseHeaderDTO
{
    /**
     * @var BookListResponseLineDTO[]
     */
    private $bookListResponseLineDTOs;

    /**
     * @return BookListResponseLineDTO[]
     */
    public function getBookListResponseLineDTOs(): array
    {
        return $this->bookListResponseLineDTOs;
    }

    /**
     * @param BookListResponseLineDTO $bookListResponseLineDTO
     */
    public function addBookListResponseLineDTO(BookListResponseLineDTO $bookListResponseLineDTO): void
    {
        $this->bookListResponseLineDTOs[] = $bookListResponseLineDTO;
    }
}

<?php

namespace BookSellerApiHandler\DTO;

/**
 * Class BookListRequestDTO
 */
class BookListRequestDTO
{
    /**
     * @var string|null
     */
    private $author;

    /**
     * @var string|null
     */
    private $publisher;

    /**
     * @var int|null
     */
    private $yearPublished;

    /**
     * @var int
     */
    private $limit = 10;

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @return string|null
     */
    public function getAuthorUrlEncoded(): ?string
    {
        return urlencode($this->author);
    }

    /**
     * @param string|null $author
     */
    public function setAuthor(?string $author)
    {
        $this->author = $author;
    }

    /**
     * @return string|null
     */
    public function getPublisher(): ?string
    {
        return $this->publisher;
    }
    /**
     * @return string|null
     */
    public function getPublisherUrlEncoded(): ?string
    {
        return urlencode($this->publisher);
    }

    /**
     * @param string|null $publisher
     */
    public function setPublisher(?string $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @return int|null
     */
    public function getYearPublished(): ?int
    {
        return $this->yearPublished;
    }

    /**
     * @param int|null $yearPublished
     */
    public function setYearPublished(?int $yearPublished)
    {
        $this->yearPublished = $yearPublished;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit = null)
    {
        $this->limit = $limit;
    }
}

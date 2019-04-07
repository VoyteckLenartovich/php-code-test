# ReadMe

## Usage

* Run `composer-install`
* Run `vendor\bin\phpunit --bootstrap vendor\autoload.php tests\BookSellerStrategyManagerTest.php` to run the test cases

## Comments

* I have decided to define constant format (json/xml) inside every strategy because I don't see any reason why you should retrieve data from the same book seller one time in JSON and other times in XML format. It should be defined once for a seller. 
* I could have moved some of the repeating code from strategies to an abstract strategy, but I have decided not to. I prefer to avoid creating unnecessary abstract classes, especially if they can lower the overall readability and simpleness of the code.
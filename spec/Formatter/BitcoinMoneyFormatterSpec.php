<?php

namespace spec\Money\Formatter;

use Money\Currency;
use Money\Money;
use Money\MoneyFormatter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BitcoinMoneyFormatterSpec extends ObjectBehavior
{
    function let(MoneyFormatter $moneyFormatter)
    {
        $this->beConstructedWith($moneyFormatter, 2);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Money\Formatter\BitcoinMoneyFormatter');
    }

    function it_is_a_money_formatter()
    {
        $this->shouldImplement(MoneyFormatter::class);
    }

    /**
     * @dataProvider bitcoinExamples
     */
    function it_formats_money($value, $formatted, $fractionDigits, MoneyFormatter $moneyFormatter)
    {
        $this->beConstructedWith($moneyFormatter, $fractionDigits);

        $moneyFormatter->format(Argument::type(Money::class))->shouldNotBeCalled();

        $money = new Money($value, new Currency('XBT'));

        $this->format($money)->shouldReturn($formatted);
    }

    public function bitcoinExamples()
    {
        return [
            [100000, "\0xC9\0x831000.00", 2],
            [41, "\0xC9\0x830.41", 2],
            [5, "\0xC9\0x830.05", 2],
            [5, "\0xC9\0x835", 0],
            [5, "\0xC9\0x830.0005", 4],
        ];
    }

    function it_formats_a_different_currency(MoneyFormatter $moneyFormatter)
    {
        $money = new Money(5, new Currency('USD'));
        $moneyFormatter->format($money)->willReturn('$0.05');

        $this->format($money)->shouldReturn('$0.05');
    }
}

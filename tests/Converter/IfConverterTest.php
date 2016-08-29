<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\Converter;

use toTwig\Converter\IfConverter;
use toTwig\FrameworkTestCase;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class IfConverterTest extends FrameworkTestCase
{
    /**
     * @var IfConverter
     */
    protected $converter;

    public function setUp()
    {
        $this->converter = new IfConverter();
    }

    /**
     * @covers       \toTwig\Converter\IfConverter::convert
     * @dataProvider provider
     */
    public function testThatIfIsConverted($smarty, $twig)
    {

        // Test the above cases
        $this->assertSame(
            $twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );

    }

    public function provider()
    {
        return array(
            array(
                // Test an if statement (no else or elseif)
                "{if !foo or foo.bar or foo}\nfoo\n{/if}",
                "{% if not foo or foo.bar or foo %}\nfoo\n{% endif %}",
            ),
            array(
                // Test an if with an else and a single logical operation.
                "{if foo}\nbar\n{else}\nfoo{/if}",
                "{% if foo %}\nbar\n{% else %}\nfoo{% endif %}",
            ),
            array(
                // Test an if with an else and an elseif and two logical operations
                '{if foo and awesome.string}\nbar\n{elseif awesome.sauce[1] and blue and \'hello\'}\nfoo{/if}',
                '{% if foo and awesome.string %}\nbar\n{% elseif awesome.sauce[1] and blue and \'hello\' %}\nfoo{% endif %}',
            ),
            array(
                // Test an if with an elseif and else clause.
                '{if foo || !foo[3]}\nbar\n{elseif awesome.sauce[1] && blue && \'hello\'}\nfoo\n{else}bar{/if}',
                '{% if foo or not foo[3] %}\nbar\n{% elseif awesome.sauce[1] and blue and \'hello\' %}\nfoo\n{% else %}bar{% endif %}',
            ),
            array(
                // Test an if statement with parenthesis.
                '{if (foo and bar) or foo and (bar or (foo and bar))}\nbar\n{else}\nfoo{/if}',
                '{% if (foo and bar) or foo and (bar or (foo and bar)) %}\nbar\n{% else %}\nfoo{% endif %}',
            ),
            array(
                // Test an elseif statement with parenthesis.
                "{if foo}\nbar\n{elseif (foo and bar) or foo and (bar or (foo and bar))}\nfoo{/if}",
                "{% if foo %}\nbar\n{% elseif (foo and bar) or foo and (bar or (foo and bar)) %}\nfoo{% endif %}",
            ),
        );
    }

}

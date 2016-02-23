<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Test for MO files parsing.
 *
 * @package PhpMyAdmin-test
 */

class MoFilesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideMoFiles
     */
    public function testMoFileTranslate($filename)
    {
        $parser = new MoTranslator\MoTranslator($filename);
        $this->assertEquals(
            'Pole',
            $parser->gettext('Column')
        );
        // Non existing string
        $this->assertEquals(
            'Column parser',
            $parser->gettext('Column parser')
        );
    }

    /**
     * @dataProvider provideMoFiles
     */
    public function testMoFilePlurals($filename)
    {
        $parser = new MoTranslator\MoTranslator($filename);
        if (strpos($filename, 'plurals.mo') !== false) {
            $expected = '%d sekundy';
        } else {
            $expected = '%d sekund';
        }
        $this->assertEquals(
            $expected,
            $parser->ngettext(
                '%d second',
                '%d seconds',
                0
            )
        );
        $this->assertEquals(
            '%d sekunda',
            $parser->ngettext(
                '%d second',
                '%d seconds',
                1
            )
        );
        $this->assertEquals(
            '%d sekundy',
            $parser->ngettext(
                '%d second',
                '%d seconds',
                2
            )
        );
        $this->assertEquals(
            $expected,
            $parser->ngettext(
                '%d second',
                '%d seconds',
                5
            )
        );
        $this->assertEquals(
            $expected,
            $parser->ngettext(
                '%d second',
                '%d seconds',
                10
            )
        );
        // Non existing string
        $this->assertEquals(
            '"%d" seconds',
            $parser->ngettext(
                '"%d" second',
                '"%d" seconds',
                10
            )
        );
    }

    /**
     * @dataProvider provideMoFiles
     */
    public function testMoFileContext($filename)
    {
        $parser = new MoTranslator\MoTranslator($filename);
        $this->assertEquals(
            'Tabulka',
            $parser->pgettext(
                'Display format',
                'Table'
            )
        );
    }

    public function provideMoFiles()
    {
        $result = array();
        foreach (glob('./tests/data/*.mo') as $file) {
            $result[] = array($file);
        }
        return $result;
    }

    public function testEmptyMoFile()
    {
        $parser = new MoTranslator\MoTranslator('./tests/data/mo.empty');
        $this->assertEquals(
            'Table',
            $parser->pgettext(
                'Display format',
                'Table'
            )
        );
        $this->assertEquals(
            '"%d" seconds',
            $parser->ngettext(
                '"%d" second',
                '"%d" seconds',
                10
            )
        );
    }
}

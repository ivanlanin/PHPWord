<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Style\TextBox as TextBoxStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\TextBox as TextBoxStyleWriter;

/**
 * TextBox element writer
 *
 * @since 0.11.0
 */
class TextBox extends AbstractElement
{
    /**
     * Write element
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        $style = $element->getStyle();

        if ($style instanceof TextBoxStyle) {
            $styleWriter = new TextBoxStyleWriter($xmlWriter, $style);
            $styleWriter->write();
        }

        if (!$this->withoutP) {
            $xmlWriter->startElement('w:p');
            if (!is_null($style->getAlign())) {
                $xmlWriter->startElement('w:pPr');
                $xmlWriter->startElement('w:jc');
                $xmlWriter->writeAttribute('w:val', $style->getAlign());
                $xmlWriter->endElement(); // w:jc
                $xmlWriter->endElement(); // w:pPr
            }
        }

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:pict');
        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('type', '#_x0000_t0202');
        $styleWriter->write();

        $xmlWriter->startElement('v:textbox');
        $margins = implode(', ', $style->getInnerMargin());
        $xmlWriter->writeAttribute('inset', $margins);

        $xmlWriter->startElement('w:txbxContent');
        $xmlWriter->startElement('w:p');
        $containerWriter = new Container($xmlWriter, $element);
        $containerWriter->write();
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // w:txbxContent

        $xmlWriter->endElement(); // v: textbox

        $styleWriter->writeW10Wrap();
        $xmlWriter->endElement(); // v:shape
        $xmlWriter->endElement(); // w:pict
        $xmlWriter->endElement(); // w:r

        if (!$this->withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }
}
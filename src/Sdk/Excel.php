<?php

namespace App\Sdk;

use App\Sdk\ServiceTrait;

use Ticketpark\HtmlPhpExcel\HtmlPhpExcel;

class Excel
{
    use ServiceTrait;

    /**
     * @var HtmlPhpExcel
     */
    protected $htmlPhpExcel;

    /**
     * Initiate Cache Cleaner Service via using Predis\Client.
     *
     * @param Client $client
     */
    public function renderExcel($view, $data, $fileName)
    {
        $renderedTemplate = $this->container->get('templating')->renderResponse($view, $data)->getContent();
        $html = preg_replace(['/>\s+/', '/\s+</', '/\.([0-9]{3}\b)[.]*/'], ['>', '<', '\\1'], $renderedTemplate);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");

        libxml_use_internal_errors(true);

        $htmlPhpExcel = new HtmlPhpExcel($html);
        $htmlPhpExcel->process()->output($fileName . '-' . date('Ymd-Hi') . '.xlsx');

        libxml_use_internal_errors(false);

        exit;
    }
}

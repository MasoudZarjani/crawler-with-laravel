<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlController extends Controller
{
    public function search(request $request)
    {
        $pageUrl = $request->input('url');
        if (!$pageUrl) {
            return response()->json("ERROR", "Product page url not found.");
        }
        $errors = array();

        $result = $this->makeWebRequest($pageUrl, $errors);
        if (empty($errors)) {
            $response['content'] = $this->parseQuickProductsJson(
                $result,
                $request->input('tagBox'),
                $request->input('tagTitle'),
                $request->input('tagBody')
            );
            $response['Status'] = 'SUCCESS';
            $response['Message'] = 'Products downloaded successfully';
        } else {
            $response['Errors'] = $errors;
            $response['Status'] = 'ERROR';
            $response['Message'] = "Error in fetching products from the url. Errors: " . implode('|', $errors);
        }
        return response()->json($response);
    }
    private function parseQuickProductsJson($result, $tagBox, $tagTitle, $tagBody)
    {
        $response = '';
        try {
            $crawler = new Crawler($result);
            $filter = $crawler->filter($tagBox);

            foreach ($filter as $i => $domElement) {
                $_crawler = new Crawler($domElement);

                $arr[$i] = array(
                    'title' => $_crawler->filter($tagTitle)->text(),
                    'body' => $_crawler->filter($tagBody)->text(),
                );
            }
        } catch (Exception $ex) {
        }
        return $arr;
    }

    /**
     * Make web request to affiliate server url
     * @param String $url
     */
    private function makeWebRequest($url, &$errors)
    {
        $client = new Client();
        $response = $client->get($url);

        if ($response->getStatusCode() == 200) {
            return (string)$response->getBody();
        } else {
            array_push($errors, $response->getReasonPhrase());
            return;
        }
    }
}

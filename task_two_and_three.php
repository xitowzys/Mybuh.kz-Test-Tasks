<?php

/**
 * Основная функция парсинга
 * @param Closure $processArticle
 * @param int $offset
 * @param int $lenght
 * @link https://www.opennet.ru
 */
function parserOpennetDecorator(Closure $processArticle, int $offset = 0, int $lenght = 20): array
{
    $url = 'https://www.opennet.ru';

    $params = [
        'skip' => $offset,
        'news' => 'mainnews',
        'lines' => $lenght,
        'mid_lines' => 0,
    ];

    $urlNews = $url . '/opennews/main.shtml?' . http_build_query($params);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $urlNews);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('Ошибка cURL: ' . curl_error($ch));
    }

    curl_close($ch);

    libxml_use_internal_errors(true);

    $doc = new DOMDocument();
    if (!$doc->loadHTML($html)) {
        throw new Exception('Ошибка загрузки HTML');
    }

    $xpath = new DOMXPath($doc);
    $news = [];

    $articles = $xpath->query('//td[@class="tnews"]//a');

    if ($articles->length > 0) {
        foreach ($articles as $item) {
            $newsItem = $processArticle($item, $url);
            if ($newsItem !== null) {
                $news[] = $newsItem;
            }
        }
    }

    return $news;
}

/**
 * Функция парсинга без кэша
 * @link https://www.opennet.ru
 */
$parserOpennetWithoutCache = function ($item, $baseUrl) {
    $title = trim($item->nodeValue);
    $link = $baseUrl . $item->getAttribute('href');
    $newsItem = ['title' => $title, 'link' => $link];

    return $newsItem;
};

/**
 * Функция парсинга с кэшом
 * @link https://www.opennet.ru
 */
$parserOpennetWithCache = function ($item, $baseUrl) {
    static $cache = [];

    $title = trim($item->nodeValue);
    $link = $baseUrl . $item->getAttribute('href');

    if (!array_key_exists($title, $cache)) {
        echo "Сохранено в кэш: $title\n";
        $newsItem = ['title' => $title, 'link' => $link];
        $cache[$title] = $newsItem;
        return $newsItem;
    }

    return $cache[$title];
};

print_r(parserOpennetDecorator($parserOpennetWithCache, 0, 5));
print_r(parserOpennetDecorator($parserOpennetWithCache, 0, 2));
print_r(parserOpennetDecorator($parserOpennetWithCache, 3, 1));
print_r(parserOpennetDecorator($parserOpennetWithCache, 3, 4));
